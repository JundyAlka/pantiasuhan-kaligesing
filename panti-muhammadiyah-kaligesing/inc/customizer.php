<?php
/**
 * Pengaturan Customizer & Helper Identitas Panti Asuhan Muhammadiyah Kaligesing
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ambil nomor WhatsApp resmi tunggal (hanya digit, awalan 62).
 */
function pmk_get_wa_number() {
	$raw    = get_theme_mod( 'pmk_wa_number', '6285326980493' );
	$digits = preg_replace( '/\D+/', '', (string) $raw );
	if ( strpos( $digits, '0' ) === 0 ) {
		$digits = '62' . substr( $digits, 1 );
	}
	return $digits ?: '6285326980493';
}

/**
 * Format tampilan nomor WhatsApp resmi yang konsisten di seluruh halaman (awalan 08).
 */
function pmk_get_wa_display() {
	$digits = pmk_get_wa_number();
	if ( strpos( $digits, '62' ) === 0 ) {
		$local = substr( $digits, 2 );
		if ( strlen( $local ) >= 10 ) {
			return '0' . substr( $local, 0, 3 ) . '-' . substr( $local, 3, 4 ) . '-' . substr( $local, 7 );
		}
		return '0' . $local;
	}
	return $digits;
}

/**
 * Bangun tautan WhatsApp resmi dengan pesan opsional.
 */
function pmk_get_wa_url( $message = '' ) {
	$number = pmk_get_wa_number();
	$url    = 'https://wa.me/' . rawurlencode( $number );
	if ( ! empty( $message ) ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * Alamat lengkap resmi sesuai Laporan KP & Google Maps.
 */
function pmk_get_address() {
	return get_theme_mod(
		'pmk_address',
		'Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, RT 02/RW 02, Desa Kaliharjo, Kaligesing, Purworejo, Jawa Tengah 54175'
	);
}

/**
 * Informasi Rekening Donasi Resmi (Bab III Poin C.5 & data resmi Bank BRI panti).
 */
function pmk_get_donation_info() {
	return array(
		'bank_name'   => get_theme_mod( 'pmk_bank_name', 'Bank BRI' ),
		'account_no'  => get_theme_mod( 'pmk_bank_account', '684301006299530' ),
		'holder_name' => get_theme_mod( 'pmk_bank_holder', 'PAY DAN DM KALIGESING' ),
		'note'        => get_theme_mod(
			'pmk_bank_note',
			'Setelah melakukan transfer donasi, zakat, infaq, atau sedekah, silakan konfirmasi melalui WhatsApp resmi agar tercatat dalam laporan penyaluran amanah panti.'
		),
	);
}

/**
 * Ambil URL logo resmi panti.
 */
function pmk_get_logo_url() {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$uploaded       = wp_get_attachment_image_url( $custom_logo_id, 'medium' );
		if ( $uploaded ) {
			return esc_url( $uploaded );
		}
	}
	return esc_url( get_template_directory_uri() . '/assets/images/photos/logo-panti.png' );
}

/**
 * Ambil URL gambar dari Customizer atau paket foto asli bawaan tema.
 */
function pmk_get_image_url( $mod_key, $fallback_file, $search_keywords = array() ) {
	$custom = get_theme_mod( $mod_key, '' );
	if ( ! empty( $custom ) ) {
		return esc_url( $custom );
	}

	$clean = ltrim( $fallback_file, '/' );
	$photo_path = get_template_directory() . '/assets/images/photos/' . $clean;
	if ( file_exists( $photo_path ) ) {
		return esc_url( get_template_directory_uri() . '/assets/images/photos/' . $clean );
	}

	if ( function_exists( 'get_posts' ) && ! empty( $search_keywords ) ) {
		static $media_cache = null;
		if ( null === $media_cache ) {
			$media_cache = get_posts(
				array(
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'post_status'    => 'inherit',
					'posts_per_page' => 60,
				)
			);
		}
		if ( is_array( $media_cache ) ) {
			foreach ( $media_cache as $att ) {
				$haystack = strtolower( $att->post_title . ' ' . $att->post_name );
				foreach ( $search_keywords as $kw ) {
					if ( false !== strpos( $haystack, strtolower( $kw ) ) ) {
						$src = wp_get_attachment_image_url( $att->ID, 'large' );
						if ( $src ) {
							return esc_url( $src );
						}
					}
				}
			}
		}
	}

	return esc_url( get_template_directory_uri() . '/assets/images/' . $clean );
}

/**
 * Daftarkan kontrol Customizer untuk identitas, kontak WA tunggal, rekening donasi, dan foto.
 */
function pmk_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'pmk_panti_settings',
		array(
			'title'       => __( 'Pengaturan Panti & Sinkronisasi KP', 'panti-muhammadiyah-kaligesing' ),
			'priority'    => 25,
			'description' => __( 'Atur Nomor WhatsApp tunggal, Rekening Donasi resmi (Bab III C.5), Alamat, dan Foto Fasilitas.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	$wp_customize->add_setting(
		'pmk_wa_number',
		array(
			'default'           => '6285326980493',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pmk_wa_number',
		array(
			'label'       => __( 'Nomor WhatsApp Resmi Tunggal (Format 628xxx)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Gunakan 1 nomor aktif di seluruh website (mis. 6285326980493 atau 6281225472032).', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_panti_settings',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'pmk_address',
		array(
			'default'           => 'Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, RT 02/RW 02, Desa Kaliharjo, Kaligesing, Purworejo, Jawa Tengah 54175',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'pmk_address',
		array(
			'label'   => __( 'Alamat Lengkap Panti', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'pmk_bank_name',
		array(
			'default'           => 'Bank BRI',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pmk_bank_name',
		array(
			'label'   => __( 'Nama Bank Rekening Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'pmk_bank_account',
		array(
			'default'           => '684301006299530',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pmk_bank_account',
		array(
			'label'   => __( 'Nomor Rekening Donasi Resmi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'pmk_bank_holder',
		array(
			'default'           => 'PAY DAN DM KALIGESING',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pmk_bank_holder',
		array(
			'label'   => __( 'Atas Nama Rekening Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'pmk_wpforms_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'pmk_wpforms_id',
		array(
			'label'       => __( 'ID Shortcode WPForms (Opsional)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Kosongkan untuk memakai formulir kontak bawaan tema yang terhubung ke database WordPress & gaya WPForms.', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_panti_settings',
			'type'        => 'number',
		)
	);

	$image_fields = array(
		'pmk_img_hero'             => 'Foto Utama Hero Beranda',
		'pmk_img_kepala'           => 'Foto Kepala Panti (H. Johan Martono)',
		'pmk_img_fac_depan'        => 'Foto Fasilitas 1: Depan Panti',
		'pmk_img_fac_tamu'         => 'Foto Fasilitas 2: Ruang Tamu',
		'pmk_img_fac_administrasi' => 'Foto Fasilitas 3: Ruang Kerja Administrasi',
		'pmk_img_fac_mushola'      => 'Foto Fasilitas 4: Mushola Panti',
		'pmk_img_fac_halaman'      => 'Foto Fasilitas 5: Halaman Utama',
		'pmk_img_fac_asrama'       => 'Foto Fasilitas 6: Bangunan Asrama / Ruang Belajar',
	);

	foreach ( $image_fields as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'   => $label,
					'section' => 'pmk_panti_settings',
				)
			)
		);
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'customize_register', 'pmk_customize_register' );
}
