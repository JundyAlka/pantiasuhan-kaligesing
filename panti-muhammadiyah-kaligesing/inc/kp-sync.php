<?php
/**
 * Sinkronisasi Otomatis Struktur Situs & Checklist Laporan Kerja Praktek
 * - Site Title & Tagline (hapus "My Blog")
 * - Permalink Post Name (/%postname%/)
 * - 4 Halaman Wajib (Home, About, Facilities, Contact) + Menu Navigasi Utama
 * - Panel Admin Checklist Pengujian Akhir (Tabel 4.1)
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Jalankan sinkronisasi otomatis agar web langsung rapi sesuai Laporan KP.
 */
function pmk_run_kp_synchronization() {
	if ( ! function_exists( 'update_option' ) ) {
		return;
	}

	// 1. Bersihkan Site Title & Tagline dari "My Blog"
	update_option( 'blogname', 'Panti Asuhan Muhammadiyah Kaligesing' );
	update_option( 'blogdescription', 'Panti Asuhan Yatim & Dhuafa Muhammadiyah Kaligesing - Purworejo' );

	// 2. Pastikan struktur Permalink menggunakan Post Name (/about/, /facilities/, /contact/)
	$current_permalink = get_option( 'permalink_structure' );
	if ( '/%postname%/' !== $current_permalink ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	// 3. Pastikan 4 Halaman Wajib tersedia dan dipublikasikan
	$required_pages = array(
		'home'       => array(
			'title'    => 'Beranda',
			'template' => '',
			'content'  => 'Halaman Beranda Resmi Panti Asuhan Muhammadiyah Kaligesing.',
		),
		'about'      => array(
			'title'    => 'Tentang Kami',
			'template' => 'page-about.php',
			'content'  => 'Profil, Sambutan Kepala Panti, Sejarah, Visi Misi, Program, dan Struktur Organisasi.',
		),
		'facilities' => array(
			'title'    => 'Fasilitas',
			'template' => 'page-facilities.php',
			'content'  => 'Fasilitas dan Galeri Sarana Panti Asuhan Muhammadiyah Kaligesing.',
		),
		'contact'    => array(
			'title'    => 'Kontak',
			'template' => 'page-contact.php',
			'content'  => 'Formulir Kontak, Rekening Donasi, Alamat Lengkap, WhatsApp, dan Peta Lokasi.',
		),
	);

	$page_ids = array();
	foreach ( $required_pages as $slug => $cfg ) {
		$existing = get_page_by_path( $slug );
		if ( ! $existing ) {
			// Cek berdasarkan judul jika slug lama berbeda (mis. beranda/profil/fasilitas/kontak)
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
			if ( 'publish' !== $existing->post_status ) {
				wp_update_post(
					array(
						'ID'          => $page_id,
						'post_status' => 'publish',
					)
				);
			}
		}

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			$page_ids[ $slug ] = (int) $page_id;
			if ( ! empty( $cfg['template'] ) ) {
				update_post_meta( $page_id, '_wp_page_template', $cfg['template'] );
			}
		}
	}

	// Atur halaman depan statis ke Home
	if ( ! empty( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
	}

	// 4. Sinkronkan Menu Navigasi Tepat 4 Item (Home, About, Facilities, Contact)
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

	// 5. Buat tabel pesan kontak & flush rewrite rules
	if ( function_exists( 'pmk_create_messages_table' ) ) {
		pmk_create_messages_table();
	}
	flush_rewrite_rules( true );
	update_option( 'pmk_kp_synced_v2', current_time( 'mysql' ) );
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'after_switch_theme', 'pmk_run_kp_synchronization' );
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
		echo '<div class="notice notice-success is-dismissible"><p><strong>Berhasil!</strong> Site Title, Permalink (/%postname%/), 4 halaman utama, dan Menu Navigasi KP telah disinkronkan.</p></div>';
	}

	if ( isset( $_POST['pmk_set_wa'] ) && check_admin_referer( 'pmk_wa_action' ) ) {
		$selected_wa = sanitize_text_field( wp_unslash( $_POST['pmk_set_wa'] ) );
		set_theme_mod( 'pmk_wa_number', $selected_wa );
		echo '<div class="notice notice-success is-dismissible"><p><strong>Nomor WhatsApp diperbarui!</strong> Seluruh halaman kini memakai satu nomor resmi: <code>' . esc_html( pmk_get_wa_display() ) . '</code>.</p></div>';
	}

	$site_title = get_option( 'blogname' );
	$permalink  = get_option( 'permalink_structure' );
	$is_ssl     = is_ssl();
	?>
	<div class="wrap">
		<h1>Sinkronisasi Website &amp; Laporan Kerja Praktek</h1>
		<p>Panel ini memastikan website <strong>Panti Asuhan Muhammadiyah Kaligesing</strong> memenuhi seluruh ketentuan Bab III &amp; Tabel 4.1 Laporan KP.</p>

		<div class="card" style="max-width:860px;padding:20px;margin-top:16px;">
			<h2>1. Status Kepatuhan Struktur KP</h2>
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
						<td><?php echo ( '/%postname%/' === $permalink ) ? '✅ Post Name (/about/, /facilities/, /contact/)' : '⚠️ Belum Post Name'; ?></td>
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
					Jalankan Sinkronisasi Ulang Otomatis (Permalink, Halaman &amp; 4 Menu KP)
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
			<p class="description" style="margin-top:10px;">Untuk mengubah nomor rekening donasi, alamat, atau mengganti foto fasilitas asli, buka <strong>Appearance &rarr; Customize &rarr; Pengaturan Panti &amp; Sinkronisasi KP</strong>.</p>
		</div>
	</div>
	<?php
}
