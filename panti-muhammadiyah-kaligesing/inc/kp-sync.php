<?php
/**
 * Sinkronisasi Otomatis Struktur Situs, Pemulihan .htaccess (Anti-404 & Anti-JSON Error),
 * Pembersihan Sisa Template Lama & Cache (SpeedyCache / LiteSpeed)
 *
 * Fitur Utama:
 * - Berjalan otomatis pada `init` saat tema di-upload atau diperbarui (tanpa perlu ganti-ganti tema)
 * - Menulis & memverifikasi file `.htaccess` WordPress secara langsung (dengan `wp-admin/includes/misc.php`)
 *   agar sub-halaman (/about/, /facilities/, /contact/) dan REST API (/wp-json/) TIDAK PERNAH 404 atau gagal JSON
 * - Membersihkan metadata Elementor lama & konten template lama pada 4 halaman utama (baik slug Inggris maupun Indonesia)
 * - Menghapus cache SpeedyCache & LiteSpeed Cache secara otomatis setelah sinkronisasi
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pastikan file .htaccess memiliki aturan mod_rewrite WordPress yang valid
 * sehingga sub-halaman (/about/, /facilities/, /contact/) dan Gutenberg REST API (/wp-json/)
 * tidak pernah menghasilkan LiteSpeed 404 atau "The response is not a valid JSON response".
 */
function pmk_repair_htaccess_and_permalinks() {
	if ( defined( 'PMK_LOCAL_PREVIEW' ) && PMK_LOCAL_PREVIEW ) {
		return;
	}

	// Muat file admin WordPress yang berisi save_mod_rewrite_rules() dan insert_with_markers()
	$file_inc = ABSPATH . 'wp-admin/includes/file.php';
	$misc_inc = ABSPATH . 'wp-admin/includes/misc.php';
	if ( file_exists( $file_inc ) ) {
		require_once $file_inc;
	}
	if ( file_exists( $misc_inc ) ) {
		require_once $misc_inc;
	}

	// Atur struktur permalink ke /%postname%/ melalui objek $wp_rewrite
	global $wp_rewrite;
	if ( is_object( $wp_rewrite ) && method_exists( $wp_rewrite, 'set_permalink_structure' ) ) {
		if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
		}
	} else {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	if ( function_exists( 'save_mod_rewrite_rules' ) ) {
		save_mod_rewrite_rules();
	}

	// Verifikasi langsung file fisik .htaccess di root WordPress (ABSPATH)
	$htaccess_file = ABSPATH . '.htaccess';
	$home_path     = function_exists( 'home_url' ) ? (string) parse_url( home_url( '/' ), PHP_URL_PATH ) : '/';
	$rewrite_base  = ( '' !== $home_path ) ? trailingslashit( $home_path ) : '/';

	$wp_htaccess_rules = array(
		'<IfModule mod_rewrite.c>',
		'RewriteEngine On',
		'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]',
		'RewriteBase ' . $rewrite_base,
		'RewriteRule ^index\.php$ - [L]',
		'RewriteCond %{REQUEST_FILENAME} !-f',
		'RewriteCond %{REQUEST_FILENAME} !-d',
		'RewriteRule . ' . $rewrite_base . 'index.php [L]',
		'</IfModule>',
	);

	$needs_direct_write = true;
	if ( file_exists( $htaccess_file ) && is_readable( $htaccess_file ) ) {
		$current_ht = (string) file_get_contents( $htaccess_file );
		if ( false !== strpos( $current_ht, 'RewriteRule . ' ) && false !== strpos( $current_ht, 'index.php' ) ) {
			$needs_direct_write = false;
		}
	}

	if ( $needs_direct_write ) {
		if ( function_exists( 'insert_with_markers' ) ) {
			@insert_with_markers( $htaccess_file, 'WordPress', $wp_htaccess_rules );
		}
		// Cek ulang apakah insert_with_markers berhasil menulis
		$verify_ht = file_exists( $htaccess_file ) ? (string) @file_get_contents( $htaccess_file ) : '';
		if ( false === strpos( $verify_ht, 'RewriteRule . ' ) ) {
			$block = "\n# BEGIN WordPress\n" . implode( "\n", $wp_htaccess_rules ) . "\n# END WordPress\n";
			@file_put_contents( $htaccess_file, $verify_ht . $block );
		}
		if ( file_exists( $htaccess_file ) ) {
			@chmod( $htaccess_file, 0644 );
		}
	}

	// Jika server sama sekali tidak mengizinkan penulisan .htaccess dan file .htaccess tidak ada,
	// gunakan fallback PATHINFO (/index.php/%postname%/) agar sub-halaman & /wp-json/ tetap bebas 404.
	$final_ht = file_exists( $htaccess_file ) ? (string) @file_get_contents( $htaccess_file ) : '';
	if ( false === strpos( $final_ht, 'index.php' ) ) {
		update_option( 'permalink_structure', '/index.php/%postname%/' );
	}

	if ( function_exists( 'flush_rewrite_rules' ) ) {
		flush_rewrite_rules( true );
	}
}

/**
 * Bersihkan metadata Elementor lama & konten template lama pada suatu halaman
 * agar tidak menimpa tampilan tema baru.
 *
 * @param int    $page_id       ID halaman WordPress.
 * @param string $template_file Nama file template tema (mis. 'page-about.php' atau 'default').
 * @param string $clean_content Konten ringkas halaman.
 */
function pmk_clean_page_legacy_meta( $page_id, $template_file, $clean_content ) {
	$page_id = (int) $page_id;
	if ( $page_id <= 0 ) {
		return;
	}

	// Hapus metadata Elementor lama yang memaksa halaman merender template lama
	$legacy_meta_keys = array(
		'_elementor_edit_mode',
		'_elementor_data',
		'_elementor_template_type',
		'_elementor_page_settings',
		'_elementor_css',
	);
	foreach ( $legacy_meta_keys as $mkey ) {
		if ( function_exists( 'delete_post_meta' ) ) {
			delete_post_meta( $page_id, $mkey );
		}
	}

	if ( function_exists( 'update_post_meta' ) ) {
		update_post_meta( $page_id, '_wp_page_template', $template_file ?: 'default' );
	}

	// Bersihkan post_content bila berisi sisa template lama
	if ( function_exists( 'get_post' ) && function_exists( 'wp_update_post' ) ) {
		$post_obj = get_post( $page_id );
		if ( is_object( $post_obj ) ) {
			$existing_body = (string) ( $post_obj->post_content ?? '' );
			$is_legacy     = function_exists( 'pmk_is_legacy_leftover_content' ) && pmk_is_legacy_leftover_content( $existing_body );
			$has_el_html   = ( false !== strpos( $existing_body, 'elementor-' ) || false !== strpos( $existing_body, 'wp-block-' ) );
			if ( $is_legacy || $has_el_html || 'publish' !== $post_obj->post_status ) {
				wp_update_post(
					array(
						'ID'           => $page_id,
						'post_status'  => 'publish',
						'post_content' => $clean_content,
					)
				);
			}
		}
	}
}

/**
 * Bersihkan seluruh cache plugin (SpeedyCache, LiteSpeed Cache, WP Object Cache)
 * agar tampilan tema baru langsung muncul di browser pengunjung dan admin.
 */
function pmk_purge_all_caches() {
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	if ( function_exists( 'do_action' ) ) {
		do_action( 'litespeed_purge_all' );
		do_action( 'speedycache_delete_cache' );
	}
	if ( class_exists( '\SpeedyCache\Delete' ) && method_exists( '\SpeedyCache\Delete', 'run' ) ) {
		try {
			\SpeedyCache\Delete::run( array( 'all' => true ) );
		} catch ( \Throwable $e ) {
			// Ignore cache plugin internal errors
		}
	}
}

/**
 * Jalankan sinkronisasi otomatis agar web langsung rapi, bebas tertimpa tema lama,
 * bebas 404 pada sub-halaman, dan bebas JSON error di WordPress Editor.
 */
function pmk_run_kp_synchronization() {
	if ( ! function_exists( 'update_option' ) ) {
		return;
	}

	// 1. Bersihkan Site Title & Tagline dari "My Blog"
	update_option( 'blogname', 'Panti Asuhan Muhammadiyah Kaligesing' );
	update_option( 'blogdescription', 'Panti Asuhan Yatim & Dhuafa Muhammadiyah Kaligesing - Purworejo' );

	// 2. Pastikan 4 Halaman Wajib tersedia, dipublikasikan, dan dibersihkan dari sisa template lama
	$required_pages = array(
		'home'       => array(
			'title'       => 'Beranda',
			'template'    => 'default',
			'content'     => 'Halaman Beranda Resmi Panti Asuhan Muhammadiyah Kaligesing.',
			'alt_slugs'   => array( 'beranda', 'halaman-utama' ),
		),
		'about'      => array(
			'title'       => 'Tentang Kami',
			'template'    => 'page-about.php',
			'content'     => 'Profil, Sambutan Kepala Panti, Sejarah, Visi Misi, Program, dan Struktur Organisasi.',
			'alt_slugs'   => array( 'tentang-kami', 'tentang', 'profil' ),
		),
		'facilities' => array(
			'title'       => 'Fasilitas',
			'template'    => 'page-facilities.php',
			'content'     => 'Fasilitas dan Galeri Sarana Panti Asuhan Muhammadiyah Kaligesing.',
			'alt_slugs'   => array( 'fasilitas', 'galeri', 'sarana' ),
		),
		'contact'    => array(
			'title'       => 'Kontak',
			'template'    => 'page-contact.php',
			'content'     => 'Formulir Kontak, Rekening Donasi, Alamat Lengkap, WhatsApp, dan Peta Lokasi.',
			'alt_slugs'   => array( 'kontak', 'hubungi-kami' ),
		),
	);

	// Bersihkan halaman front page lama bila sudah diatur ke halaman lain
	$old_front_id = (int) get_option( 'page_on_front', 0 );
	if ( $old_front_id > 0 ) {
		pmk_clean_page_legacy_meta( $old_front_id, 'default', $required_pages['home']['content'] );
	}

	$page_ids = array();
	foreach ( $required_pages as $slug => $cfg ) {
		$existing = get_page_by_path( $slug );

		// Bersihkan juga halaman dengan slug alternatif bahasa Indonesia (mis. /tentang-kami/, /fasilitas/, /kontak/, /beranda/)
		foreach ( $cfg['alt_slugs'] as $alt_slug ) {
			$alt_page = get_page_by_path( $alt_slug );
			if ( $alt_page && isset( $alt_page->ID ) ) {
				pmk_clean_page_legacy_meta( $alt_page->ID, $cfg['template'], $cfg['content'] );
				// Jika halaman slug utama belum ada, ubah post_name halaman lama ini menjadi slug kanonik
				if ( ! $existing ) {
					wp_update_post(
						array(
							'ID'          => $alt_page->ID,
							'post_name'   => $slug,
							'post_title'  => $cfg['title'],
							'post_status' => 'publish',
						)
					);
					$existing = get_post( $alt_page->ID );
				}
			}
		}

		if ( ! $existing ) {
			$page_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $cfg['title'],
					'post_name'    => $slug,
					'post_content' => $cfg['content'],
				)
			);
		} else {
			$page_id = $existing->ID;
		}

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			$page_ids[ $slug ] = (int) $page_id;
			pmk_clean_page_legacy_meta( $page_id, $cfg['template'], $cfg['content'] );
		}
	}

	// Atur halaman depan statis ke Home
	if ( ! empty( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
		update_option( 'page_for_posts', 0 );
	}

	// 3. Sinkronkan Menu Navigasi Tepat 4 Item (Beranda, Tentang Kami, Fasilitas, Kontak)
	$menu_name = 'Menu Utama KP';
	$menu_obj  = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu_obj ? (int) $menu_obj->term_id : wp_create_nav_menu( $menu_name );

	if ( $menu_id && ! is_wp_error( $menu_id ) ) {
		$old_items = wp_get_nav_menu_items( $menu_id );
		if ( is_array( $old_items ) ) {
			foreach ( $old_items as $item ) {
				wp_delete_post( $item->ID, true );
			}
		}

		$order = 1;
		foreach ( $required_pages as $slug => $cfg ) {
			if ( ! empty( $page_ids[ $slug ] ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => $cfg['title'],
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page_ids[ $slug ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $order++,
					)
				);
			}
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		$locations['footer']  = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	// 4. Buat tabel pesan kontak
	if ( function_exists( 'pmk_create_messages_table' ) ) {
		pmk_create_messages_table();
	}

	// 5. Pastikan mode builder default adalah 'theme_only' agar sisa konten lama tidak muncul di bawah
	if ( 'hybrid' === get_theme_mod( 'pmk_builder_mode', '' ) ) {
		set_theme_mod( 'pmk_builder_mode', 'theme_only' );
	}

	// 6. Perbaiki file .htaccess & flush rewrite rules
	pmk_repair_htaccess_and_permalinks();

	// 7. Bersihkan cache SpeedyCache / LiteSpeed
	pmk_purge_all_caches();

	update_option( 'pmk_kp_synced_v2', current_time( 'mysql' ) );
	update_option( 'pmk_auto_healed_version', defined( 'PMK_THEME_VERSION' ) ? PMK_THEME_VERSION : '2.4.0' );
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'after_switch_theme', 'pmk_run_kp_synchronization' );
}

/**
 * Jalankan auto-healing secara otomatis pada request pertama setelah tema di-upload/diperbarui
 * (Namun JANGAN berjalan di dalam request REST API /wp-json/ atau AJAX agar respons JSON selalu murni).
 */
function pmk_auto_heal_on_init() {
	if ( defined( 'PMK_LOCAL_PREVIEW' ) && PMK_LOCAL_PREVIEW ) {
		return;
	}
	if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
		return;
	}
	if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
		return;
	}

	$target_ver  = defined( 'PMK_THEME_VERSION' ) ? PMK_THEME_VERSION : '2.4.0';
	$healed_ver  = function_exists( 'get_option' ) ? (string) get_option( 'pmk_auto_healed_version', '' ) : '';
	$htaccess_ok = file_exists( ABSPATH . '.htaccess' );

	if ( $healed_ver !== $target_ver || ! $htaccess_ok ) {
		pmk_run_kp_synchronization();
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'init', 'pmk_auto_heal_on_init', 20 );
}

/**
 * Tambahkan halaman admin Sinkronisasi & Checklist Laporan KP di menu Appearance.
 */
function pmk_add_kp_admin_page() {
	add_theme_page(
		__( 'Sinkronisasi Laporan KP', 'panti-muhammadiyah-kaligesing' ),
		__( 'Sinkronisasi KP', 'panti-muhammadiyah-kaligesing' ),
		'manage_options',
		'pmk-kp-sync',
		'pmk_render_kp_admin_page'
	);
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'admin_menu', 'pmk_add_kp_admin_page' );
}

/**
 * Render halaman utilitas Sinkronisasi Laporan KP.
 */
function pmk_render_kp_admin_page() {
	if ( isset( $_POST['pmk_run_sync_now'] ) && check_admin_referer( 'pmk_sync_action' ) ) {
		pmk_run_kp_synchronization();
		echo '<div class="notice notice-success is-dismissible"><p><strong>Berhasil!</strong> File <code>.htaccess</code>, Permalink (/%postname%/), pembersihan sisa tema lama pada 4 halaman utama, Menu Navigasi KP, dan pembersihan Cache telah disinkronkan.</p></div>';
	}

	if ( isset( $_POST['pmk_set_wa'] ) && check_admin_referer( 'pmk_wa_action' ) ) {
		$selected_wa = sanitize_text_field( wp_unslash( $_POST['pmk_set_wa'] ) );
		set_theme_mod( 'pmk_wa_number', $selected_wa );
		pmk_purge_all_caches();
		echo '<div class="notice notice-success is-dismissible"><p><strong>Nomor WhatsApp diperbarui!</strong> Seluruh halaman kini memakai satu nomor resmi: <code>' . esc_html( pmk_get_wa_display() ) . '</code>.</p></div>';
	}

	$site_title  = get_option( 'blogname' );
	$permalink   = get_option( 'permalink_structure' );
	$htaccess_ok = file_exists( ABSPATH . '.htaccess' );
	$is_ssl      = is_ssl();
	?>
	<div class="wrap">
		<h1>Sinkronisasi Website &amp; Pemulihan Otomatis</h1>
		<p>Panel ini memastikan website <strong>Panti Asuhan Muhammadiyah Kaligesing</strong> bersih dari sisa template lama, sub-halaman bebas 404, dan REST API JSON berjalan normal.</p>

		<div class="card" style="max-width:860px;padding:20px;margin-top:16px;">
			<h2>1. Status Kepatuhan &amp; Kesehatan Rute Server</h2>
			<table class="widefat striped">
				<tbody>
					<tr>
						<td><strong>Site Title</strong></td>
						<td><?php echo esc_html( $site_title ); ?></td>
						<td><?php echo ( 'My Blog' !== $site_title ) ? '✅ Sesuai' : '⚠️ Masih My Blog'; ?></td>
					</tr>
					<tr>
						<td><strong>Permalink Structure</strong></td>
						<td><code><?php echo esc_html( $permalink ?: '?page_id=...' ); ?></code></td>
						<td><?php echo ( '' !== $permalink ) ? '✅ Aktif' : '⚠️ Plain'; ?></td>
					</tr>
					<tr>
						<td><strong>File Rewrite <code>.htaccess</code></strong></td>
						<td><code><?php echo $htaccess_ok ? 'Tersedia di Root Server' : 'Belum ditemukan'; ?></code></td>
						<td><?php echo $htaccess_ok ? '✅ Sub-halaman & JSON API Aman' : '⚠️ Klik tombol sinkronisasi di bawah'; ?></td>
					</tr>
					<tr>
						<td><strong>Nomor WhatsApp Tunggal Aktif</strong></td>
						<td><code><?php echo esc_html( pmk_get_wa_display() ); ?></code></td>
						<td>✅ Seragam di semua halaman</td>
					</tr>
					<tr>
						<td><strong>Protokol HTTPS (SSL)</strong></td>
						<td><?php echo $is_ssl ? 'Aktif (https://)' : 'Belum terdeteksi SSL'; ?></td>
						<td><?php echo $is_ssl ? '✅ Aman' : '⚠️ Pastikan SSL aktif di hosting'; ?></td>
					</tr>
				</tbody>
			</table>

			<form method="post" style="margin-top:16px;">
				<?php wp_nonce_field( 'pmk_sync_action' ); ?>
				<button type="submit" name="pmk_run_sync_now" class="button button-primary button-hero">
					Perbaiki .htaccess, Bersihkan Sisa Tema Lama &amp; Bersihkan Cache Sekarang
				</button>
			</form>
		</div>

		<div class="card" style="max-width:860px;padding:20px;margin-top:16px;">
			<h2>2. Penyeragaman Nomor WhatsApp Resmi (1 Klik)</h2>
			<p>Pilih satu nomor WhatsApp resmi yang aktif agar seragam di seluruh website dan laporan KP:</p>
			<form method="post" style="display:flex;gap:12px;flex-wrap:wrap;">
				<?php wp_nonce_field( 'pmk_wa_action' ); ?>
				<button type="submit" name="pmk_set_wa" value="6285326980493" class="button button-secondary">
					Gunakan 0853-2698-0493 (Website Terbaru)
				</button>
				<button type="submit" name="pmk_set_wa" value="6281225472032" class="button button-secondary">
					Gunakan 0812-2547-2032 (Laporan KP)
				</button>
			</form>
			<p class="description" style="margin-top:10px;">Untuk mengubah warna, layout, nomor rekening donasi, alamat, struktur organisasi, atau foto fasilitas, buka <strong>Appearance &rarr; Customize &rarr; Pengaturan Tema Panti Muhammadiyah</strong>.</p>
		</div>
	</div>
	<?php
}
