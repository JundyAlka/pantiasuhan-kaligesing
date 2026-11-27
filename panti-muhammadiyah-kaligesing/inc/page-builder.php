<?php
/**
 * Integrasi Elementor, Gutenberg Block Editor, Shortcodes & Widget Areas
 *
 * Memastikan:
 * 1. "Edit with Elementor" berfungsi di seluruh halaman (Beranda, Tentang Kami, Fasilitas, Kontak, Halaman Baru)
 *    tanpa pernah memunculkan error "The content area was not found in your page".
 * 2. Mendukung 3 mode fleksibel di Customizer (Hybrid, Builder Only, Theme Only).
 * 3. Menyediakan 5 Shortcode Komponen Resmi Panti yang dapat dipasang di Elementor / Gutenberg:
 *    - [pmk_rekening_donasi]
 *    - [pmk_form_kontak]
 *    - [pmk_struktur_organisasi]
 *    - [pmk_galeri_fasilitas]
 *    - [pmk_info_kontak]
 * 4. Mendaftarkan Sidebar / Widget Area WordPress & Block Pattern Gutenberg.
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftar teks placeholder bawaan sinkronisasi KP (agar tidak tercetak ganda di mode hybrid).
 *
 * @return array<int, string>
 */
function pmk_get_default_seed_contents() {
	return array(
		'Halaman Beranda Resmi Panti Asuhan Muhammadiyah Kaligesing.',
		'Profil, Sambutan Kepala Panti, Sejarah, Visi Misi, Program, dan Struktur Organisasi.',
		'Fasilitas dan Galeri Sarana Panti Asuhan Muhammadiyah Kaligesing.',
		'Formulir Kontak, Rekening Donasi, Alamat Lengkap, WhatsApp, dan Peta Lokasi.',
	);
}

/**
 * Deteksi apakah halaman sedang dibuka di dalam mode editor atau pratinjau Elementor.
 *
 * @return bool
 */
function pmk_is_elementor_editor_or_preview() {
	if ( isset( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) ) {
		return true;
	}
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->preview ) ) {
		if ( method_exists( \Elementor\Plugin::$instance->preview, 'is_preview_mode' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return true;
		}
	}
	return false;
}

/**
 * Deteksi apakah halaman saat ini diaktifkan mode builder Elementor.
 *
 * @param int $post_id ID post/halaman.
 * @return bool
 */
function pmk_is_elementor_built_page( $post_id = 0 ) {
	if ( ! $post_id && function_exists( 'get_the_ID' ) ) {
		$post_id = (int) get_the_ID();
	}
	if ( $post_id > 0 && function_exists( 'get_post_meta' ) ) {
		return ( 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true ) );
	}
	return false;
}

/**
 * Cek apakah halaman memiliki konten kustom di luar teks seed bawaan KP.
 *
 * @return bool
 */
function pmk_has_custom_page_content() {
	if ( ! function_exists( 'get_post' ) ) {
		return false;
	}
	$post = get_post();
	if ( ! is_object( $post ) || empty( $post->post_content ) ) {
		return false;
	}
	$raw_content = trim( wp_strip_all_tags( (string) $post->post_content ) );
	if ( '' === $raw_content ) {
		// Mungkin berisi blok HTML/shortcode non-teks
		return ( '' !== trim( (string) $post->post_content ) );
	}
	return ! in_array( $raw_content, pmk_get_default_seed_contents(), true );
}

/**
 * Cek apakah seksi bawaan tema harus disembunyikan (ketika mode = builder_only dan halaman berisi konten Elementor/Gutenberg).
 *
 * @return bool
 */
function pmk_should_hide_theme_sections() {
	$mode = pmk_get_mod( 'pmk_builder_mode', 'hybrid' );
	if ( 'builder_only' !== $mode ) {
		return false;
	}
	return ( pmk_is_elementor_editor_or_preview() || pmk_is_elementor_built_page() || pmk_has_custom_page_content() );
}

/**
 * Render area konten WordPress (`the_content()`) secara cerdas agar Elementor & Gutenberg
 * selalu kompatibel 100% di seluruh template halaman.
 *
 * @param string $context Nama konteks template (home, about, facilities, contact).
 */
function pmk_render_builder_content( $context = 'page' ) {
	$is_preview  = pmk_is_elementor_editor_or_preview();
	$is_builder  = pmk_is_elementor_built_page();
	$has_custom  = pmk_has_custom_page_content();
	$mode        = pmk_get_mod( 'pmk_builder_mode', 'hybrid' );

	// Selalu panggil the_content() saat di Elementor Preview/Editor agar Elementor tidak pernah error
	if ( ! $is_preview && 'theme_only' === $mode ) {
		return;
	}

	if ( ! $is_preview && ! $is_builder && ! $has_custom ) {
		return;
	}

	if ( function_exists( 'have_posts' ) && function_exists( 'the_post' ) && function_exists( 'the_content' ) ) {
		if ( have_posts() ) {
			echo '<section class="pmk-section pmk-builder-region" data-pmk-builder-context="' . esc_attr( $context ) . '">';
			echo '<div class="pmk-container pmk-container--wide pmk-builder-region__inner">';
			while ( have_posts() ) {
				the_post();
				the_content();
			}
			echo '</div>';
			echo '</section>';
		} elseif ( $is_preview ) {
			echo '<section class="pmk-section pmk-builder-region" data-pmk-builder-context="' . esc_attr( $context ) . '">';
			echo '<div class="pmk-container pmk-container--wide pmk-builder-region__inner">';
			the_content();
			echo '</div>';
			echo '</section>';
		}
	}
}

/**
 * Shortcode 1: [pmk_rekening_donasi]
 * Menampilkan kartu Rekening Donasi Resmi dengan tombol Salin Rekening.
 */
function pmk_shortcode_rekening_donasi() {
	$bank = pmk_get_donation_info();
	ob_start();
	?>
	<div class="pmk-bank-card pmk-shortcode-bank">
		<div class="pmk-bank-card__head">
			<span class="pmk-bank-card__bank"><?php echo esc_html( $bank['bank_name'] ); ?></span>
			<span class="pmk-bank-card__badge">Rekening Resmi Panti</span>
		</div>
		<div class="pmk-bank-card__num-row">
			<span class="pmk-bank-card__number"><?php echo esc_html( $bank['account_no'] ); ?></span>
			<button type="button" class="pmk-copy-btn" data-copy-target="<?php echo esc_attr( $bank['account_no'] ); ?>">
				Salin Rekening
			</button>
		</div>
		<p class="pmk-bank-card__holder">a.n. <strong><?php echo esc_html( $bank['holder_name'] ); ?></strong></p>
		<p class="pmk-bank-card__note"><?php echo esc_html( $bank['note'] ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode 2: [pmk_struktur_organisasi]
 * Menampilkan Bagan Struktur Organisasi Hierarkis Panti.
 */
function pmk_shortcode_struktur_organisasi() {
	$org = pmk_get_org_structure_data();
	ob_start();
	?>
	<div class="pmk-org-chart pmk-reveal is-visible">
		<div class="pmk-org-tier pmk-org-tier--root">
			<article class="pmk-org-node pmk-org-node--root">
				<span class="pmk-org-node__badge">Pelindung &amp; Penyelenggara</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['pelindung'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['pelindung_sub'] ); ?></p>
			</article>
		</div>

		<div class="pmk-org-stem" aria-hidden="true"></div>

		<div class="pmk-org-tier pmk-org-tier--leaders">
			<article class="pmk-org-node pmk-org-node--chief">
				<span class="pmk-org-node__badge">Kepala Panti</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['kepala'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['kepala_sub'] ); ?></p>
			</article>

			<div class="pmk-org-bridge" aria-hidden="true">
				<span class="pmk-org-bridge__dot"></span>
			</div>

			<article class="pmk-org-node pmk-org-node--deputy">
				<span class="pmk-org-node__badge">Wakil Kepala</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['wakil'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['wakil_sub'] ); ?></p>
			</article>
		</div>

		<div class="pmk-org-stem" aria-hidden="true"></div>

		<div class="pmk-org-branch-3">
			<article class="pmk-org-node pmk-org-node--core">
				<span class="pmk-org-node__badge">Sekretaris</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['sekretaris'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['sekretaris_sub'] ); ?></p>
			</article>

			<article class="pmk-org-node pmk-org-node--core pmk-org-node--core-highlight">
				<span class="pmk-org-node__badge">Kepala Asrama</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['kepala_asrama'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['kepala_asrama_sub'] ); ?></p>
			</article>

			<article class="pmk-org-node pmk-org-node--core">
				<span class="pmk-org-node__badge">Bendahara</span>
				<h3 class="pmk-org-node__name"><?php echo esc_html( $org['bendahara'] ); ?></h3>
				<p class="pmk-org-node__sub"><?php echo esc_html( $org['bendahara_sub'] ); ?></p>
			</article>
		</div>

		<div class="pmk-org-stem pmk-org-stem--with-label">
			<span class="pmk-org-spine-pill">Garis Koordinasi 7 Bidang Pengurus</span>
		</div>

		<div class="pmk-org-dept-grid">
			<?php foreach ( $org['departments'] as $dept ) : ?>
				<article class="pmk-org-dept <?php echo ! empty( $dept['wide'] ) ? 'pmk-org-dept--wide' : ''; ?>">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code"><?php echo esc_html( $dept['code'] ); ?></span>
						<h4 class="pmk-org-dept__title"><?php echo esc_html( $dept['title'] ); ?></h4>
					</div>
					<div class="pmk-org-dept__members">
						<?php foreach ( $dept['members'] as $member_name ) : ?>
							<span class="pmk-member-chip"><?php echo esc_html( $member_name ); ?></span>
						<?php endforeach; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode 3: [pmk_galeri_fasilitas]
 * Menampilkan Grid 6 Fasilitas Utama Panti dengan fitur Lightbox.
 */
function pmk_shortcode_galeri_fasilitas() {
	$facilities = pmk_get_facilities_data();
	ob_start();
	?>
	<div class="pmk-gallery-grid">
		<?php foreach ( $facilities as $idx => $fac ) : ?>
			<article class="pmk-gallery-card pmk-reveal is-visible" style="--delay: <?php echo esc_attr( $idx * 60 ); ?>ms;">
				<div class="pmk-gallery-card__img-wrap">
					<img src="<?php echo esc_url( $fac['img'] ); ?>" data-fallback-src="<?php echo esc_url( $fac['fallback'] ); ?>" alt="<?php echo esc_attr( $fac['title'] ); ?>" loading="lazy">
					<span class="pmk-gallery-card__badge"><?php echo esc_html( $fac['badge'] ); ?></span>
				</div>
				<div class="pmk-gallery-card__body">
					<h3 class="pmk-gallery-card__title"><?php echo esc_html( $fac['title'] ); ?></h3>
					<p class="pmk-gallery-card__desc"><?php echo esc_html( $fac['desc'] ); ?></p>
					<button type="button" class="pmk-gallery-card__zoom" data-lightbox-src="<?php echo esc_url( $fac['img'] ); ?>" data-lightbox-title="<?php echo esc_attr( $fac['badge'] . ' — ' . $fac['title'] ); ?>">
						<span>Perbesar Foto</span>
					</button>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode 4: [pmk_info_kontak]
 * Menampilkan Kartu Kontak Langsung, Nomor WhatsApp Tunggal, dan Alamat Panti.
 */
function pmk_shortcode_info_kontak() {
	$address    = pmk_get_address();
	$wa_display = pmk_get_wa_display();
	$wa_url     = pmk_get_wa_url( 'Assalamualaikum, saya ingin menghubungi Panti Asuhan Muhammadiyah Kaligesing.' );
	$maps_link  = pmk_get_mod( 'pmk_maps_link', 'https://maps.google.com/?q=Panti+Asuhan+Muhammadiyah+Kaligesing+Purworejo' );
	ob_start();
	?>
	<div class="pmk-info-card">
		<h2 style="margin:0 0 12px;font-size:1.2rem;font-weight:800;">Kontak Langsung &amp; Alamat Panti</h2>
		<p style="margin:0 0 14px;color:var(--pmk-text-muted);font-size:0.94rem;">
			<strong>Alamat Lengkap:</strong><br>
			<?php echo esc_html( $address ); ?>
		</p>
		<p style="margin:0 0 16px;color:var(--pmk-text-muted);font-size:0.94rem;">
			<strong>WhatsApp Resmi:</strong> <?php echo esc_html( $wa_display ); ?>
		</p>
		<div style="display:flex;flex-wrap:wrap;gap:10px;">
			<a class="pmk-btn pmk-btn--wa" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
				Chat WhatsApp Sekarang
			</a>
			<a class="pmk-btn pmk-btn--outline" href="<?php echo esc_url( $maps_link ); ?>" target="_blank" rel="noopener noreferrer">
				Buka Google Maps
			</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode 5: [pmk_form_kontak]
 * Menampilkan Formulir Kontak Resmi Panti (atau WPForms/CF7 bila diatur di Customizer).
 */
function pmk_shortcode_form_kontak() {
	$custom_sc  = pmk_get_mod( 'pmk_custom_form_shortcode', '' );
	$wpforms_id = absint( get_theme_mod( 'pmk_wpforms_id', 0 ) );

	if ( '' !== $custom_sc && function_exists( 'do_shortcode' ) && false === strpos( $custom_sc, '[pmk_form_kontak' ) ) {
		return '<div class="wpforms-container">' . do_shortcode( $custom_sc ) . '</div>';
	}
	if ( $wpforms_id > 0 && function_exists( 'shortcode_exists' ) && shortcode_exists( 'wpforms' ) ) {
		return '<div class="wpforms-container">' . do_shortcode( '[wpforms id="' . $wpforms_id . '"]' ) . '</div>';
	}

	$submission = pmk_process_contact_submission();
	ob_start();
	?>
	<div class="pmk-shortcode-form-wrap">
		<?php if ( 'success' === $submission['status'] ) : ?>
			<div class="pmk-form-alert pmk-form-alert--success" role="status">
				<?php echo esc_html( $submission['notice'] ); ?>
			</div>
		<?php elseif ( 'error' === $submission['status'] ) : ?>
			<div class="pmk-form-alert pmk-form-alert--error" role="alert">
				<?php echo esc_html( $submission['notice'] ); ?>
			</div>
		<?php endif; ?>

		<form class="wpforms-container" method="post" action="<?php echo esc_url( home_url( '/contact/' ) ); ?>" novalidate>
			<input type="hidden" name="pmk_contact_action" value="1">
			<?php
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( 'pmk_submit_contact', 'pmk_contact_nonce' );
			}
			?>
			<div class="pmk-form-field">
				<label class="pmk-form-label" for="pmk_sc_name">Nama Lengkap <span class="pmk-form-req">*</span></label>
				<input type="text" id="pmk_sc_name" name="pmk_name" class="pmk-form-input" value="<?php echo esc_attr( $submission['values']['name'] ); ?>" required>
			</div>
			<div class="pmk-form-field">
				<label class="pmk-form-label" for="pmk_sc_email">Alamat Email <span class="pmk-form-req">*</span></label>
				<input type="email" id="pmk_sc_email" name="pmk_email" class="pmk-form-input" value="<?php echo esc_attr( $submission['values']['email'] ); ?>" required>
			</div>
			<div class="pmk-form-field">
				<label class="pmk-form-label" for="pmk_sc_subject">Keperluan / Subjek</label>
				<input type="text" id="pmk_sc_subject" name="pmk_subject" class="pmk-form-input" value="<?php echo esc_attr( $submission['values']['subject'] ); ?>">
			</div>
			<div class="pmk-form-field">
				<label class="pmk-form-label" for="pmk_sc_message">Pesan <span class="pmk-form-req">*</span></label>
				<textarea id="pmk_sc_message" name="pmk_message" rows="4" class="pmk-form-textarea" required><?php echo esc_textarea( $submission['values']['message'] ); ?></textarea>
			</div>
			<button type="submit" class="pmk-btn pmk-btn--primary" style="width:100%;">Kirim Pesan</button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Daftarkan kelima shortcode komponen tema.
 */
function pmk_register_shortcodes() {
	if ( ! function_exists( 'add_shortcode' ) ) {
		return;
	}
	add_shortcode( 'pmk_rekening_donasi', 'pmk_shortcode_rekening_donasi' );
	add_shortcode( 'pmk_struktur_organisasi', 'pmk_shortcode_struktur_organisasi' );
	add_shortcode( 'pmk_galeri_fasilitas', 'pmk_shortcode_galeri_fasilitas' );
	add_shortcode( 'pmk_info_kontak', 'pmk_shortcode_info_kontak' );
	add_shortcode( 'pmk_form_kontak', 'pmk_shortcode_form_kontak' );
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'init', 'pmk_register_shortcodes' );
}

/**
 * Daftarkan Area Widget WordPress (Appearance -> Widgets) seperti tema komersial.
 */
function pmk_register_widget_areas() {
	if ( ! function_exists( 'register_sidebar' ) ) {
		return;
	}
	register_sidebar(
		array(
			'name'          => __( 'Area Widget Footer Tambahan', 'panti-muhammadiyah-kaligesing' ),
			'id'            => 'pmk-footer-widgets',
			'description'   => __( 'Tambahkan widget tambahan di bagian bawah footer panti bila diperlukan.', 'panti-muhammadiyah-kaligesing' ),
			'before_widget' => '<div id="%1$s" class="pmk-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="pmk-footer__title">',
			'after_title'   => '</h3>',
		)
	);
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'widgets_init', 'pmk_register_widget_areas' );
}

/**
 * Daftarkan Block Pattern Category & Block Patterns untuk Gutenberg Editor.
 */
function pmk_register_block_patterns() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'pmk-panti',
			array(
				'label' => __( 'Panti Muhammadiyah Kaligesing', 'panti-muhammadiyah-kaligesing' ),
			)
		);
	}
	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern(
			'panti-muhammadiyah-kaligesing/donation-card',
			array(
				'title'       => __( 'Kartu Rekening Donasi Resmi Panti', 'panti-muhammadiyah-kaligesing' ),
				'description' => __( 'Menampilkan kartu rekening donasi resmi Bank BRI beserta tombol salin otomatis.', 'panti-muhammadiyah-kaligesing' ),
				'categories'  => array( 'pmk-panti' ),
				'content'     => '<!-- wp:shortcode -->[pmk_rekening_donasi]<!-- /wp:shortcode -->',
			)
		);
		register_block_pattern(
			'panti-muhammadiyah-kaligesing/org-chart',
			array(
				'title'       => __( 'Bagan Struktur Organisasi Panti', 'panti-muhammadiyah-kaligesing' ),
				'description' => __( 'Menampilkan bagan hierarki pengurus Panti Asuhan Muhammadiyah Kaligesing.', 'panti-muhammadiyah-kaligesing' ),
				'categories'  => array( 'pmk-panti' ),
				'content'     => '<!-- wp:shortcode -->[pmk_struktur_organisasi]<!-- /wp:shortcode -->',
			)
		);
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'init', 'pmk_register_block_patterns' );
}
