<?php
/**
 * Pengaturan Customizer Komersial & Helper Identitas Panti Asuhan Muhammadiyah Kaligesing
 *
 * Menyediakan:
 * - Panel Customizer Komersial (7 Seksi Terstruktur: Identitas, Warna & Layout, Beranda, Tentang Kami, Fasilitas, Donasi, Kontak)
 * - Selective Refresh (Ikon Pensil Edit Cepat di Live Preview WordPress Customizer)
 * - Resolusi Gambar Defensif (Mendukung Attachment ID, Array Media, URL Penuh, Nama File Lokal, & Fallback Anti-Error)
 * - Injeksi CSS Variabel Dinamis (:root) untuk kustomisasi warna, lebar kontainer, dan sudut kartu
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ambil nilai string dari Customizer dengan fallback aman bila dikosongkan.
 *
 * @param string $key     Kunci theme_mod.
 * @param string $default Nilai default bila kosong.
 * @return string
 */
function pmk_get_mod( $key, $default = '' ) {
	$val = get_theme_mod( $key, $default );
	if ( is_string( $val ) || is_numeric( $val ) ) {
		$trimmed = trim( (string) $val );
		if ( '' !== $trimmed ) {
			return $trimmed;
		}
	}
	return (string) $default;
}

/**
 * Ambil nilai boolean/toggle dari Customizer dengan evaluasi aman.
 *
 * @param string $key     Kunci theme_mod.
 * @param bool   $default Nilai default.
 * @return bool
 */
function pmk_get_bool_mod( $key, $default = true ) {
	$val = get_theme_mod( $key, $default ? '1' : '0' );
	if ( is_bool( $val ) ) {
		return $val;
	}
	$str = strtolower( trim( (string) $val ) );
	if ( in_array( $str, array( '0', 'false', 'off', 'no' ), true ) ) {
		return false;
	}
	if ( in_array( $str, array( '1', 'true', 'on', 'yes' ), true ) ) {
		return true;
	}
	return (bool) $default;
}

/**
 * Ambil nilai integer dari Customizer dengan batas minimum & maksimum aman.
 *
 * @param string $key     Kunci theme_mod.
 * @param int    $default Nilai default.
 * @param int    $min     Batas bawah.
 * @param int    $max     Batas atas.
 * @return int
 */
function pmk_get_int_mod( $key, $default = 0, $min = 0, $max = 999999 ) {
	$raw = get_theme_mod( $key, $default );
	if ( '' === $raw || null === $raw || ! is_numeric( $raw ) ) {
		return (int) $default;
	}
	$num = (int) $raw;
	if ( $num < $min ) {
		return (int) $min;
	}
	if ( $num > $max ) {
		return (int) $max;
	}
	return $num;
}

/**
 * Sanitizer checkbox Customizer.
 *
 * @param mixed $checked Nilai input.
 * @return string '1' atau '0'.
 */
function pmk_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && ( true === $checked || '1' === (string) $checked || 'true' === (string) $checked ) ) ? '1' : '0';
}

/**
 * Sanitizer warna hex dengan fallback aman ke nilai default setting.
 *
 * @param string $color   Warna hex.
 * @param object $setting Objek WP_Customize_Setting.
 * @return string
 */
function pmk_sanitize_hex_or_default( $color, $setting = null ) {
	$clean = trim( (string) $color );
	if ( preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $clean ) ) {
		return $clean;
	}
	if ( is_object( $setting ) && isset( $setting->default ) ) {
		return (string) $setting->default;
	}
	return '#0F5132';
}

/**
 * Sanitizer nilai gambar Customizer (mendukung URL maupun Attachment ID).
 *
 * @param mixed $value Nilai input dari kontrol gambar/media.
 * @return string
 */
function pmk_sanitize_image_mod( $value ) {
	if ( is_numeric( $value ) ) {
		return (string) absint( $value );
	}
	if ( is_array( $value ) ) {
		if ( ! empty( $value['url'] ) ) {
			return esc_url_raw( (string) $value['url'] );
		}
		if ( ! empty( $value['id'] ) ) {
			return (string) absint( $value['id'] );
		}
		return '';
	}
	$str = trim( (string) $value );
	if ( '' === $str ) {
		return '';
	}
	if ( preg_match( '/^[a-zA-Z0-9._-]+\.(jpg|jpeg|png|webp|svg|gif)$/i', $str ) ) {
		return sanitize_text_field( $str );
	}
	return esc_url_raw( $str );
}

/**
 * Ubah daftar teks dipisahkan koma atau baris baru menjadi array bersih.
 *
 * @param string $raw           Teks daftar.
 * @param array  $default_items Array default bila hasil kosong.
 * @return array<int, string>
 */
function pmk_parse_list_items( $raw, $default_items = array() ) {
	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return $default_items;
	}
	$parts  = preg_split( '/[\r\n,]+/', $raw );
	$result = array();
	if ( is_array( $parts ) ) {
		foreach ( $parts as $part ) {
			$clean = trim( (string) $part );
			if ( '' !== $clean ) {
				$result[] = $clean;
			}
		}
	}
	return ! empty( $result ) ? $result : $default_items;
}

/**
 * Ambil nomor WhatsApp resmi tunggal (hanya digit, awalan 62).
 */
function pmk_get_wa_number() {
	$raw    = pmk_get_mod( 'pmk_wa_number', '6285326980493' );
	$digits = preg_replace( '/\D+/', '', (string) $raw );
	if ( strpos( $digits, '0' ) === 0 ) {
		$digits = '62' . substr( $digits, 1 );
	} elseif ( strpos( $digits, '8' ) === 0 ) {
		$digits = '62' . $digits;
	}
	return ( strlen( $digits ) >= 9 ) ? $digits : '6285326980493';
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
	return pmk_get_mod(
		'pmk_address',
		'Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, RT 02/RW 02, Desa Kaliharjo, Kaligesing, Purworejo, Jawa Tengah 54175'
	);
}

/**
 * Informasi Rekening Donasi Resmi (Bab III Poin C.5 & data resmi Bank BRI panti).
 */
function pmk_get_donation_info() {
	return array(
		'bank_name'   => pmk_get_mod( 'pmk_bank_name', 'Bank BRI' ),
		'account_no'  => pmk_get_mod( 'pmk_bank_account', '684301006299530' ),
		'holder_name' => pmk_get_mod( 'pmk_bank_holder', 'PAY DAN DM KALIGESING' ),
		'note'        => pmk_get_mod(
			'pmk_bank_note',
			'Setelah melakukan transfer donasi, zakat, infaq, atau sedekah, silakan konfirmasi melalui WhatsApp resmi agar tercatat dalam laporan penyaluran amanah panti.'
		),
		'title'       => pmk_get_mod(
			'pmk_donation_title',
			'Mari Berbagi Kebaikan untuk Masa Depan Anak Yatim & Dhuafa'
		),
		'desc'        => pmk_get_mod(
			'pmk_donation_desc',
			'Dukungan zakat, infaq, sedekah, maupun wakaf dari Bapak/Ibu sangat berarti bagi biaya pendidikan, konsumsi bergizi, dan kebutuhan asrama anak-anak asuh kami.'
		),
	);
}

/**
 * Ambil URL logo resmi panti (mendukung Custom Logo WP, Customizer Logo Override, atau fallback bawaan).
 */
function pmk_get_logo_url() {
	$default_logo = get_template_directory_uri() . '/assets/images/photos/logo-panti.png';

	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id && function_exists( 'wp_get_attachment_image_url' ) ) {
			$uploaded = wp_get_attachment_image_url( $custom_logo_id, 'medium' );
			if ( $uploaded ) {
				return esc_url( $uploaded );
			}
		}
	}

	$custom_logo_mod = pmk_get_image_url( 'pmk_img_logo', 'logo-panti.png' );
	if ( ! empty( $custom_logo_mod ) ) {
		return $custom_logo_mod;
	}

	return esc_url( $default_logo );
}

/**
 * Ambil URL gambar fallback bawaan tema yang dijamin ada.
 *
 * @param string $fallback_file Nama file fallback di assets/images/photos/.
 * @return string
 */
function pmk_get_fallback_photo_url( $fallback_file = 'panti-1.jpg' ) {
	$clean      = ltrim( (string) $fallback_file, '/' );
	$photo_path = get_template_directory() . '/assets/images/photos/' . $clean;
	if ( '' !== $clean && file_exists( $photo_path ) ) {
		return esc_url( get_template_directory_uri() . '/assets/images/photos/' . $clean );
	}
	$svg_path = get_template_directory() . '/assets/images/' . $clean;
	if ( '' !== $clean && file_exists( $svg_path ) ) {
		return esc_url( get_template_directory_uri() . '/assets/images/' . $clean );
	}
	return esc_url( get_template_directory_uri() . '/assets/images/photos/panti-1.jpg' );
}

/**
 * Ambil URL gambar dari Customizer atau paket foto asli bawaan tema secara defensif (anti-error).
 * Mendukung:
 * 1. Attachment ID integer (mis. dari WP_Customize_Media_Control)
 * 2. Array attachment (['url' => ..., 'id' => ...])
 * 3. Nama file lokal di dalam assets/images/photos/
 * 4. URL penuh (http/https) atau path relatif (/wp-content/...)
 * 5. Fallback otomatis ke foto bawaan tema bila nilai kosong, rusak, atau gambar dihapus
 *
 * @param string $mod_key         Kunci theme_mod di Customizer.
 * @param string $fallback_file   Nama file fallback bawaan di assets/images/photos/.
 * @param array  $search_keywords Kata kunci pencarian opsional di Media Library.
 * @return string URL gambar yang aman.
 */
function pmk_get_image_url( $mod_key, $fallback_file, $search_keywords = array() ) {
	$custom = '';
	if ( ! empty( $mod_key ) ) {
		$custom = get_theme_mod( $mod_key, '' );
	}

	// 1. Jika berbentuk array dari kontrol media kustom
	if ( is_array( $custom ) ) {
		if ( ! empty( $custom['url'] ) && is_string( $custom['url'] ) ) {
			$custom = $custom['url'];
		} elseif ( ! empty( $custom['id'] ) && is_numeric( $custom['id'] ) ) {
			$custom = (int) $custom['id'];
		} else {
			$custom = '';
		}
	}

	// 2. Jika berbentuk Attachment ID numerik
	if ( is_numeric( $custom ) && (int) $custom > 0 ) {
		if ( function_exists( 'wp_get_attachment_image_url' ) ) {
			$att_url = wp_get_attachment_image_url( (int) $custom, 'full' );
			if ( ! empty( $att_url ) && is_string( $att_url ) ) {
				return esc_url( $att_url );
			}
		}
		$custom = '';
	}

	// 3. Jika berbentuk string non-kosong
	if ( is_string( $custom ) ) {
		$custom = trim( $custom );
		if ( '' !== $custom ) {
			// Nama file lokal di folder photos (mis. "photo-5.jpg")
			if ( preg_match( '/^[a-zA-Z0-9._-]+\.(jpg|jpeg|png|webp|svg|gif)$/i', $custom ) ) {
				$local_candidate = get_template_directory() . '/assets/images/photos/' . $custom;
				if ( file_exists( $local_candidate ) ) {
					return esc_url( get_template_directory_uri() . '/assets/images/photos/' . $custom );
				}
			}

			// URL absolut http/https atau path root-relative yang valid
			$is_valid_http = (bool) preg_match( '#^https?://[^\s/$.?#].[^\s]*$#i', $custom );
			$is_valid_rel  = ( 0 === strpos( $custom, '/' ) && strlen( $custom ) > 2 && false === strpos( $custom, ' ' ) );
			if ( $is_valid_http || $is_valid_rel ) {
				return esc_url( $custom );
			}
		}
	}

	// 4. Fallback ke foto bawaan di folder tema
	$clean      = ltrim( (string) $fallback_file, '/' );
	$photo_path = get_template_directory() . '/assets/images/photos/' . $clean;
	if ( '' !== $clean && file_exists( $photo_path ) ) {
		return esc_url( get_template_directory_uri() . '/assets/images/photos/' . $clean );
	}

	// 5. Pencarian Media Library bila diminta
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
						$src = function_exists( 'wp_get_attachment_image_url' ) ? wp_get_attachment_image_url( $att->ID, 'large' ) : '';
						if ( $src ) {
							return esc_url( $src );
						}
					}
				}
			}
		}
	}

	return pmk_get_fallback_photo_url( $clean );
}

/**
 * Ambil data 6 Fasilitas Utama Panti (bisa dikustomisasi teks dan fotonya dari Customizer).
 *
 * @return array<int, array{badge: string, title: string, desc: string, img: string, fallback: string}>
 */
function pmk_get_facilities_data() {
	$defaults = array(
		1 => array(
			'badge'   => 'Fasilitas 01',
			'title'   => 'Depan Panti',
			'desc'    => 'Tampak depan bangunan utama beserta papan nama resmi Panti Asuhan Muhammadiyah Kaligesing di jalur utama Jl. Raya Purworejo–Kaligesing KM 8,5.',
			'mod_img' => 'pmk_img_fac_depan',
			'file'    => 'panti-1.jpg',
		),
		2 => array(
			'badge'   => 'Fasilitas 02',
			'title'   => 'Ruang Tamu',
			'desc'    => 'Area penerimaan tamu, wali anak asuh, dan para donatur yang tertata bersih serta nyaman untuk kegiatan silaturahmi dan penyerahan amanah donasi.',
			'mod_img' => 'pmk_img_fac_tamu',
			'file'    => 'photo-6.jpg',
		),
		3 => array(
			'badge'   => 'Fasilitas 03',
			'title'   => 'Ruang Kerja Administrasi',
			'desc'    => 'Ruang sekretariat dan tata usaha untuk pelayanan administrasi, pencatatan data anak asuh, pengarsipan dokumen resmi, serta pembukuan keuangan panti.',
			'mod_img' => 'pmk_img_fac_administrasi',
			'file'    => 'photo-8.jpg',
		),
		4 => array(
			'badge'   => 'Fasilitas 04',
			'title'   => 'Mushola Panti',
			'desc'    => 'Pusat kegiatan ibadah shalat lima waktu berjamaah, tadarus dan tahfidz Al-Qur’an, serta pembinaan akhlak Islami seluruh anak asuh setiap hari.',
			'mod_img' => 'pmk_img_fac_mushola',
			'file'    => 'photo-5.jpg',
		),
		5 => array(
			'badge'   => 'Fasilitas 05',
			'title'   => 'Halaman Utama',
			'desc'    => 'Area terbuka yang hijau, asri, dan aman di lingkungan panti untuk kegiatan olahraga pagi, apel kebersamaan, serta aktivitas luar ruang anak asuh.',
			'mod_img' => 'pmk_img_fac_halaman',
			'file'    => 'wa-landscape-2.jpg',
		),
		6 => array(
			'badge'   => 'Fasilitas 06',
			'title'   => 'Bangunan Asrama / Ruang Belajar',
			'desc'    => 'Fasilitas hunian kamar anak asuh yang sehat dan terawat, dilengkapi ruang belajar bersama yang tenang untuk mendukung prestasi akademik.',
			'mod_img' => 'pmk_img_fac_asrama',
			'file'    => 'panti-4.jpg',
		),
	);

	$items = array();
	foreach ( $defaults as $idx => $cfg ) {
		$items[] = array(
			'badge'    => $cfg['badge'],
			'title'    => pmk_get_mod( "pmk_fac_{$idx}_title", $cfg['title'] ),
			'desc'     => pmk_get_mod( "pmk_fac_{$idx}_desc", $cfg['desc'] ),
			'img'      => pmk_get_image_url( $cfg['mod_img'], $cfg['file'] ),
			'fallback' => pmk_get_fallback_photo_url( $cfg['file'] ),
		);
	}
	return $items;
}

/**
 * Ambil data Struktur Organisasi (bisa dikustomisasi nama & jabatannya di Customizer).
 *
 * @return array<string, mixed>
 */
function pmk_get_org_structure_data() {
	$bidang_defaults = array(
		1 => array(
			'code'    => 'Bidang 01',
			'title'   => 'Humas & Usaha',
			'members' => 'Sanjaya Ancas Mahardika, Agung Mulyanto, Supardiyono',
			'wide'    => false,
		),
		2 => array(
			'code'    => 'Bidang 02',
			'title'   => 'Pendidikan & Pelatihan',
			'members' => 'Sudiyono, Edy Prasetyo',
			'wide'    => false,
		),
		3 => array(
			'code'    => 'Bidang 03',
			'title'   => 'Kerohanian',
			'members' => 'Bambang Edy, Triman',
			'wide'    => false,
		),
		4 => array(
			'code'    => 'Bidang 04',
			'title'   => 'Sarana Prasarana',
			'members' => 'Teguh, Sutarko Hantoro',
			'wide'    => false,
		),
		5 => array(
			'code'    => 'Bidang 05',
			'title'   => 'Pengasuhan & Rumah Tangga',
			'members' => 'Istiqomah, Dwi Yanto',
			'wide'    => false,
		),
		6 => array(
			'code'    => 'Bidang 06',
			'title'   => 'Sosial & Kesehatan',
			'members' => 'Hj. Mugiati',
			'wide'    => false,
		),
		7 => array(
			'code'    => 'Bidang 07',
			'title'   => 'Identifikasi Klien',
			'members' => 'Hj. Arifatul Aini, Harsiwi Wismayanti',
			'wide'    => true,
		),
	);

	$departments = array();
	foreach ( $bidang_defaults as $num => $bcfg ) {
		$raw_members   = pmk_get_mod( "pmk_org_bidang_{$num}_members", $bcfg['members'] );
		$default_list  = pmk_parse_list_items( $bcfg['members'] );
		$departments[] = array(
			'code'    => $bcfg['code'],
			'title'   => pmk_get_mod( "pmk_org_bidang_{$num}_title", $bcfg['title'] ),
			'members' => pmk_parse_list_items( $raw_members, $default_list ),
			'wide'    => $bcfg['wide'],
		);
	}

	return array(
		'pelindung'         => pmk_get_mod( 'pmk_org_pelindung', 'Pimpinan Cabang Muhammadiyah (PCM) Kaligesing' ),
		'pelindung_sub'     => pmk_get_mod( 'pmk_org_pelindung_sub', 'Majelis Pelayanan Kesejahteraan Sosial (MPKS)' ),
		'kepala'            => pmk_get_mod( 'pmk_org_kepala', 'H. Johan Martono' ),
		'kepala_sub'        => pmk_get_mod( 'pmk_org_kepala_sub', 'Pimpinan & Penanggung Jawab Operasional' ),
		'wakil'             => pmk_get_mod( 'pmk_org_wakil', 'Iwan Kusni Surahman' ),
		'wakil_sub'         => pmk_get_mod( 'pmk_org_wakil_sub', 'Koordinator Harian & Pendampingan Program' ),
		'sekretaris'        => pmk_get_mod( 'pmk_org_sekretaris', 'Harsiwi Wismayanti' ),
		'sekretaris_sub'    => pmk_get_mod( 'pmk_org_sekretaris_sub', 'Kesekretariatan & Administrasi Data' ),
		'kepala_asrama'     => pmk_get_mod( 'pmk_org_kepala_asrama', 'Hj. Arifatul Aini' ),
		'kepala_asrama_sub' => pmk_get_mod( 'pmk_org_kepala_asrama_sub', 'Pengawasan Harian Asrama & Santri' ),
		'bendahara'         => pmk_get_mod( 'pmk_org_bendahara', '1. Sumardi • 2. Sunaryo' ),
		'bendahara_sub'     => pmk_get_mod( 'pmk_org_bendahara_sub', 'Keuangan, Akuntansi & Pelaporan Donasi' ),
		'departments'       => $departments,
	);
}

/**
 * Output variabel CSS dinamis (:root) hasil pengaturan Appearance -> Customize.
 */
function pmk_output_customizer_css() {
	$primary_color   = pmk_sanitize_hex_or_default( pmk_get_mod( 'pmk_color_primary', '#0F5132' ) );
	$dark_color      = pmk_sanitize_hex_or_default( pmk_get_mod( 'pmk_color_dark', '#093622' ) );
	$gold_color      = pmk_sanitize_hex_or_default( pmk_get_mod( 'pmk_color_gold', '#EAB308' ) );
	$bg_color        = pmk_sanitize_hex_or_default( pmk_get_mod( 'pmk_color_bg', '#FAFBF9' ) );
	$container_width = pmk_get_int_mod( 'pmk_container_width', 1340, 1080, 1680 );
	$card_radius     = pmk_get_int_mod( 'pmk_card_radius', 20, 6, 36 );
	$wide_width      = $container_width + 60;

	echo '<style id="pmk-customizer-live-css">' . "\n";
	echo ':root {' . "\n";
	echo '  --pmk-emerald-800: ' . esc_html( $primary_color ) . ';' . "\n";
	echo '  --pmk-emerald-900: ' . esc_html( $dark_color ) . ';' . "\n";
	echo '  --pmk-gold-400: ' . esc_html( $gold_color ) . ';' . "\n";
	echo '  --pmk-bg: ' . esc_html( $bg_color ) . ';' . "\n";
	echo '  --pmk-container: ' . (int) $container_width . 'px;' . "\n";
	echo '  --pmk-container-wide: ' . (int) $wide_width . 'px;' . "\n";
	echo '  --pmk-radius-lg: ' . (int) $card_radius . 'px;' . "\n";
	echo '}' . "\n";
	echo '</style>' . "\n";
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'wp_head', 'pmk_output_customizer_css', 30 );
}

/**
 * Helper internal untuk menambahkan kontrol gambar ke Customizer secara kompatibel.
 */
function pmk_add_customizer_image_control( $wp_customize, $setting_id, $label, $section, $description = '' ) {
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => '',
			'sanitize_callback' => 'pmk_sanitize_image_mod',
		)
	);

	if ( class_exists( 'WP_Customize_Image_Control' ) ) {
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$setting_id,
				array(
					'label'       => $label,
					'description' => $description,
					'section'     => $section,
				)
			)
		);
	} else {
		$wp_customize->add_control(
			$setting_id,
			array(
				'label'       => $label,
				'description' => $description,
				'section'     => $section,
				'type'        => 'text',
			)
		);
	}
}

/**
 * Helper internal untuk menambahkan kontrol teks/textarea/number/checkbox ke Customizer.
 */
function pmk_add_customizer_field( $wp_customize, $setting_id, $args ) {
	$default  = $args['default'] ?? '';
	$type     = $args['type'] ?? 'text';
	$sanitize = $args['sanitize_callback'] ?? 'sanitize_text_field';

	if ( 'textarea' === $type && ! isset( $args['sanitize_callback'] ) ) {
		$sanitize = 'sanitize_textarea_field';
	} elseif ( 'checkbox' === $type && ! isset( $args['sanitize_callback'] ) ) {
		$sanitize = 'pmk_sanitize_checkbox';
	} elseif ( 'number' === $type && ! isset( $args['sanitize_callback'] ) ) {
		$sanitize = 'absint';
	}

	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
		)
	);

	$control_args = array(
		'label'       => $args['label'] ?? $setting_id,
		'section'     => $args['section'] ?? 'pmk_panti_settings',
		'type'        => $type,
		'description' => $args['description'] ?? '',
	);
	if ( ! empty( $args['choices'] ) ) {
		$control_args['choices'] = $args['choices'];
	}
	if ( ! empty( $args['input_attrs'] ) ) {
		$control_args['input_attrs'] = $args['input_attrs'];
	}

	$wp_customize->add_control( $setting_id, $control_args );
}

/**
 * Daftarkan Panel & 7 Seksi Customizer Komersial untuk Panti Asuhan Muhammadiyah Kaligesing.
 */
function pmk_customize_register( $wp_customize ) {
	// Panel Utama Tema Komersial
	if ( method_exists( $wp_customize, 'add_panel' ) ) {
		$wp_customize->add_panel(
			'pmk_theme_panel',
			array(
				'title'       => __( 'Pengaturan Tema Panti Muhammadiyah', 'panti-muhammadiyah-kaligesing' ),
				'description' => __( 'Pusat pengaturan tampilan komersial: Identitas, Warna & Layout, Beranda, Tentang Kami, Struktur Organisasi, Fasilitas & Galeri, Rekening Donasi, serta Kontak Resmi.', 'panti-muhammadiyah-kaligesing' ),
				'priority'    => 25,
			)
		);
	}

	// =========================================================================
	// SEKSI 1: IDENTITAS HEADER, FOOTER & FITUR GLOBAL
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_identity',
		array(
			'title'       => __( '1. Identitas Header, Footer & Tombol Global', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 10,
			'description' => __( 'Atur logo, teks nama panti di header/footer, animasi preloader, dan tombol WhatsApp melayang.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_logo',
		__( 'Logo Resmi Panti (Header, Footer & Favicon)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_identity',
		__( 'Kosongkan untuk memakai logo Muhammadiyah bawaan tema.', 'panti-muhammadiyah-kaligesing' )
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_brand_kicker',
		array(
			'label'   => __( 'Label Kecil Atas Nama Panti (Header)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'LKSA / MCC • PCM Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_brand_name',
		array(
			'label'   => __( 'Nama Utama Brand Header', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Panti Asuhan Muhammadiyah',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_brand_sub',
		array(
			'label'   => __( 'Sub-Label Wilayah (Header)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Kaligesing • Kabupaten Purworejo',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_header_cta_text',
		array(
			'label'   => __( 'Teks Tombol CTA Kanan Header', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Chat WhatsApp',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_enable_preloader',
		array(
			'label'   => __( 'Tampilkan Animasi Loading Saat Buka Halaman', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'type'    => 'checkbox',
			'default' => '1',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_enable_float_wa',
		array(
			'label'   => __( 'Tampilkan Tombol Chat WhatsApp Melayang (Kanan Bawah)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'type'    => 'checkbox',
			'default' => '1',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_footer_title',
		array(
			'label'   => __( 'Judul Lembaga di Footer', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Panti Asuhan Muhammadiyah Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_footer_tagline',
		array(
			'label'   => __( 'Tagline Akreditasi di Footer', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Terakreditasi Resmi • LKSA / MCC PCM Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_footer_desc',
		array(
			'label'   => __( 'Deskripsi Singkat Panti di Footer', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'type'    => 'textarea',
			'default' => 'Lembaga kesejahteraan sosial anak yatim dan dhuafa di bawah naungan Pimpinan Cabang Muhammadiyah Kaligesing yang berkomitmen membentuk generasi Islami, berakhlak mulia, dan mandiri.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_footer_copyright',
		array(
			'label'   => __( 'Teks Hak Cipta (Copyright) Footer', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_identity',
			'default' => 'Copyright © 2026 Panti Asuhan Muhammadiyah Kaligesing. Hak Cipta Dilindungi.',
		)
	);

	// =========================================================================
	// SEKSI 2: WARNA TEMA, TATA LETAK & MODE ELEMENTOR (APPEARANCE)
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_colors',
		array(
			'title'       => __( '2. Warna Tema, Tata Letak & Mode Elementor', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 20,
			'description' => __( 'Sesuaikan palet warna utama, lebar kontainer halaman, kelengkungan kartu, serta mode integrasi Elementor / Gutenberg.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	$color_settings = array(
		'pmk_color_primary' => array(
			'label'   => __( 'Warna Hijau Utama Muhammadiyah', 'panti-muhammadiyah-kaligesing' ),
			'default' => '#0F5132',
		),
		'pmk_color_dark'    => array(
			'label'   => __( 'Warna Hijau Tua (Header & Footer)', 'panti-muhammadiyah-kaligesing' ),
			'default' => '#093622',
		),
		'pmk_color_gold'    => array(
			'label'   => __( 'Warna Aksen Emas Matahari', 'panti-muhammadiyah-kaligesing' ),
			'default' => '#EAB308',
		),
		'pmk_color_bg'      => array(
			'label'   => __( 'Warna Latar Belakang Halaman', 'panti-muhammadiyah-kaligesing' ),
			'default' => '#FAFBF9',
		),
	);

	foreach ( $color_settings as $cid => $ccfg ) {
		$wp_customize->add_setting(
			$cid,
			array(
				'default'           => $ccfg['default'],
				'sanitize_callback' => 'pmk_sanitize_hex_or_default',
			)
		);
		if ( class_exists( 'WP_Customize_Color_Control' ) ) {
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$cid,
					array(
						'label'   => $ccfg['label'],
						'section' => 'pmk_sec_colors',
					)
				)
			);
		} else {
			$wp_customize->add_control(
				$cid,
				array(
					'label'   => $ccfg['label'],
					'section' => 'pmk_sec_colors',
					'type'    => 'text',
				)
			);
		}
	}

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_container_width',
		array(
			'label'       => __( 'Lebar Kontainer Utama (px)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Rentang aman: 1140 hingga 1600 (Default: 1340).', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_sec_colors',
			'type'        => 'number',
			'default'     => 1340,
			'input_attrs' => array(
				'min'  => 1140,
				'max'  => 1600,
				'step' => 10,
			),
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_card_radius',
		array(
			'label'       => __( 'Radius Sudut Kartu / Box (px)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Rentang: 8 hingga 32 (Default: 20).', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_sec_colors',
			'type'        => 'number',
			'default'     => 20,
			'input_attrs' => array(
				'min'  => 8,
				'max'  => 32,
				'step' => 2,
			),
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_builder_mode',
		array(
			'label'       => __( 'Mode Integrasi Elementor & Block Editor', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Pilih bagaimana halaman utama bekerja saat diedit menggunakan Elementor atau Gutenberg.', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_sec_colors',
			'type'        => 'select',
			'default'     => 'hybrid',
			'choices'     => array(
				'hybrid'       => __( 'Hybrid (Tampilkan Desain Tema + Blok Tambahan Elementor/Gutenberg)', 'panti-muhammadiyah-kaligesing' ),
				'builder_only' => __( 'Full Page Builder (Ganti Isi Halaman dengan Konten Elementor bila Diedit)', 'panti-muhammadiyah-kaligesing' ),
				'theme_only'   => __( 'Desain Bawaan Tema (Hanya Aktifkan Dropzone Saat Mode Edit Elementor)', 'panti-muhammadiyah-kaligesing' ),
			),
		)
	);

	// =========================================================================
	// SEKSI 3: HALAMAN BERANDA (HERO, KOLASE, STATISTIK & SEKSI)
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_home',
		array(
			'title'       => __( '3. Halaman Beranda (Hero, Statistik & Foto)', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 30,
			'description' => __( 'Kustomisasi teks Hero, foto latar slideshow, 3 kartu kolase, angka statistik, serta tampil/sembunyikan seksi di Beranda.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	// Toggle visibilitas seksi Beranda
	$home_toggles = array(
		'pmk_show_home_bento'      => __( 'Tampilkan Seksi Bento Grid (WA, Alamat, Akreditasi & Hadis)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_home_pillars'    => __( 'Tampilkan Seksi Tiga Pilar Layanan & Khidmat Sosial', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_home_stats'      => __( 'Tampilkan Seksi Statistik Angka Pencapaian Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_home_activities' => __( 'Tampilkan Seksi Slideshow Kegiatan & Dokumentasi', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_home_donation'   => __( 'Tampilkan Seksi Penyaluran Donasi Resmi di Beranda', 'panti-muhammadiyah-kaligesing' ),
	);
	foreach ( $home_toggles as $tid => $tlabel ) {
		pmk_add_customizer_field(
			$wp_customize,
			$tid,
			array(
				'label'   => $tlabel,
				'section' => 'pmk_sec_home',
				'type'    => 'checkbox',
				'default' => '1',
			)
		);
	}

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_badge',
		array(
			'label'   => __( 'Teks Badge Akreditasi di Hero Beranda', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'Terakreditasi Resmi • Pimpinan Cabang Muhammadiyah Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_title_prefix',
		array(
			'label'   => __( 'Judul Hero (Baris Awal Putih)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'Selamat Datang di',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_title_highlight',
		array(
			'label'   => __( 'Judul Hero (Sorotan Emas)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'Panti Asuhan Muhammadiyah Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_lead',
		array(
			'label'   => __( 'Deskripsi Paragraf Hero Beranda', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'type'    => 'textarea',
			'default' => 'Panti Asuhan Muhammadiyah Kaligesing berkomitmen memberikan pengasuhan terbaik, pembentukan karakter Islami, dan menjamin pendidikan anak-anak asuh demi masa depan yang lebih cerah.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_btn_primary',
		array(
			'label'   => __( 'Teks Tombol Utama Hero', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'Salurkan Donasi Sekarang',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hero_btn_secondary',
		array(
			'label'   => __( 'Teks Tombol Kedua Hero', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'Mengenal Panti Lebih Dekat',
		)
	);

	// Foto-foto Hero Beranda & Kolase
	$home_images = array(
		'pmk_img_hero'         => __( 'Foto Utama Hero Beranda / Papan Nama (Slide 4)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_hero_slide_1' => __( 'Foto Latar Hero Slide 1: Kebersamaan Anak Asuh', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_hero_slide_2' => __( 'Foto Latar Hero Slide 2: Edukasi & Mushola', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_hero_slide_3' => __( 'Foto Latar Hero Slide 3: Keluarga Besar Santri', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_hero_slide_5' => __( 'Foto Latar Hero Slide 5: Gedung Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_orbit_1'      => __( 'Foto Kartu Kolase Hero 1 (Utama)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_orbit_2'      => __( 'Foto Kartu Kolase Hero 2 (Kanan Atas)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_orbit_3'      => __( 'Foto Kartu Kolase Hero 3 (Kanan Bawah)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_img_unified_bg'   => __( 'Foto Latar Panorama Seksi Tiga Pilar & Statistik', 'panti-muhammadiyah-kaligesing' ),
	);
	foreach ( $home_images as $img_id => $img_label ) {
		pmk_add_customizer_image_control( $wp_customize, $img_id, $img_label, 'pmk_sec_home' );
	}

	// Statistik Beranda (4 Kartu Counter)
	$home_stats_defaults = array(
		1 => array( 'val' => 24, 'label' => 'Anak Asuh', 'sub' => 'Tinggal & dibina penuh di asrama panti' ),
		2 => array( 'val' => 15, 'label' => 'Anak Binaan', 'sub' => 'Pendampingan santri luar asrama' ),
		3 => array( 'val' => 18, 'label' => 'Alumni Terbina', 'sub' => 'Telah mandiri & berkarya di masyarakat' ),
		4 => array( 'val' => 5,  'label' => 'Tahun Mengabdi', 'sub' => 'Konsisten melayani umat & anak yatim' ),
	);
	foreach ( $home_stats_defaults as $sidx => $scfg ) {
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_stat_{$sidx}_val",
			array(
				'label'   => sprintf( __( 'Angka Statistik Beranda #%d', 'panti-muhammadiyah-kaligesing' ), $sidx ),
				'section' => 'pmk_sec_home',
				'type'    => 'number',
				'default' => $scfg['val'],
			)
		);
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_stat_{$sidx}_label",
			array(
				'label'   => sprintf( __( 'Judul Statistik Beranda #%d', 'panti-muhammadiyah-kaligesing' ), $sidx ),
				'section' => 'pmk_sec_home',
				'default' => $scfg['label'],
			)
		);
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_stat_{$sidx}_sub",
			array(
				'label'   => sprintf( __( 'Keterangan Statistik Beranda #%d', 'panti-muhammadiyah-kaligesing' ), $sidx ),
				'section' => 'pmk_sec_home',
				'default' => $scfg['sub'],
			)
		);
	}

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hadith_quote',
		array(
			'label'   => __( 'Teks Kutipan Hadis di Bento Grid', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'type'    => 'textarea',
			'default' => 'Sebaik-baik rumah di kalangan orang Islam adalah rumah yang di dalamnya terdapat anak yatim yang diperlakukan dengan baik. Dan seburuk-buruk rumah di kalangan orang Islam adalah rumah yang di dalamnya terdapat anak yatim yang diperlakukan dengan buruk.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_hadith_source',
		array(
			'label'   => __( 'Sumber Riwayat Hadis', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_home',
			'default' => 'HR. Ibnu Majah • Landasan Pengasuhan Penuh Kasih Sayang',
		)
	);

	// =========================================================================
	// SEKSI 4: HALAMAN TENTANG KAMI & STRUKTUR ORGANISASI
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_about',
		array(
			'title'       => __( '4. Halaman Tentang Kami & Struktur Organisasi', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 40,
			'description' => __( 'Atur foto & teks Sambutan Kepala Panti, Sejarah, Visi Misi, Statistik Profil, dan nama-nama pengurus di Bagan Struktur Organisasi.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	$about_toggles = array(
		'pmk_show_about_sambutan' => __( 'Tampilkan Seksi Sambutan Kepala Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_about_stats'    => __( 'Tampilkan Seksi 4 Angka Statistik Profil', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_about_history'  => __( 'Tampilkan Seksi Sejarah Singkat & Semboyan', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_about_visimisi' => __( 'Tampilkan Seksi Visi & Misi Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_about_programs' => __( 'Tampilkan Seksi 4 Pilar Program Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_about_org'      => __( 'Tampilkan Bagan Struktur Organisasi Pengurus', 'panti-muhammadiyah-kaligesing' ),
	);
	foreach ( $about_toggles as $atid => $atlabel ) {
		pmk_add_customizer_field(
			$wp_customize,
			$atid,
			array(
				'label'   => $atlabel,
				'section' => 'pmk_sec_about',
				'type'    => 'checkbox',
				'default' => '1',
			)
		);
	}

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_hero_about',
		__( 'Foto Latar Banner Atas Halaman Tentang Kami', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_about'
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_about_hero_title',
		array(
			'label'   => __( 'Judul Banner Halaman Tentang Kami', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'Profil Panti Asuhan Muhammadiyah Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_about_hero_desc',
		array(
			'label'   => __( 'Deskripsi Banner Halaman Tentang Kami', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Mengenal lebih dekat amanah pengasuhan, sejarah pendirian, visi dan misi, empat pilar pembinaan, serta jajaran pengurus resmi LKSA / MCC Pimpinan Cabang Muhammadiyah Kaligesing.',
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_kepala',
		__( 'Foto Kepala Panti / Direktur (Sambutan)', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_about'
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_kepala_name',
		array(
			'label'   => __( 'Nama Kepala Panti (Sambutan)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'H. Johan Martono',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_kepala_role',
		array(
			'label'   => __( 'Jabatan Kepala Panti (Sambutan)', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'Kepala Panti Asuhan Muhammadiyah Kaligesing',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sambutan_title',
		array(
			'label'   => __( 'Judul Sambutan Kepala Panti', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'Mengasuh dengan Hati, Membekali dengan Ilmu',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sambutan_quote',
		array(
			'label'   => __( 'Isi Kutipan Sambutan Kepala Panti', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Assalamu’alaikum warahmatullahi wabarakatuh. Panti Asuhan Muhammadiyah Kaligesing berkomitmen penuh dalam menyediakan lingkungan pengasuhan yang aman, islami, dan edukatif bagi anak-anak yatim serta dhuafa. Melalui integrasi teknologi dan keterbukaan informasi, kami berharap dapat terus menyebarkan syiar kebaikan serta mencetak generasi yang mandiri dan berakhlak mulia di wilayah Purworejo.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sambutan_closing',
		array(
			'label'   => __( 'Paragraf Penutup Sambutan', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Kami mengucapkan terima kasih kepada seluruh donatur, dermawan, serta warga persyarikatan Muhammadiyah yang senantiasa menyalurkan amanah dan dukungannya demi masa depan anak-anak asuh kami.',
		)
	);

	// Statistik Halaman Tentang Kami
	$about_stats_defaults = array(
		1 => array( 'val' => 3,  'label' => 'Gedung Asrama Utama' ),
		2 => array( 'val' => 17, 'label' => 'Pengurus & Pengasuh' ),
		3 => array( 'val' => 35, 'label' => 'Anak Asuh & Binaan' ),
		4 => array( 'val' => 10, 'label' => 'Tahun Mengabdi' ),
	);
	foreach ( $about_stats_defaults as $as_idx => $as_cfg ) {
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_about_stat_{$as_idx}_val",
			array(
				'label'   => sprintf( __( 'Angka Statistik Profil #%d', 'panti-muhammadiyah-kaligesing' ), $as_idx ),
				'section' => 'pmk_sec_about',
				'type'    => 'number',
				'default' => $as_cfg['val'],
			)
		);
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_about_stat_{$as_idx}_label",
			array(
				'label'   => sprintf( __( 'Label Statistik Profil #%d', 'panti-muhammadiyah-kaligesing' ), $as_idx ),
				'section' => 'pmk_sec_about',
				'default' => $as_cfg['label'],
			)
		);
	}

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sejarah_title',
		array(
			'label'   => __( 'Judul Sejarah Singkat', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'Lahir dari Semangat Dakwah & Teologi Al-Ma’un',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sejarah_p1',
		array(
			'label'   => __( 'Paragraf 1 Sejarah Singkat', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Panti Asuhan Yatim & Dhu’afa Muhammadiyah Kaligesing berdiri di bawah naungan Pimpinan Cabang Muhammadiyah (PCM) Kaligesing sebagai wujud kepedulian nyata terhadap pemenuhan hak hidup, pengasuhan, dan pendidikan anak-anak yatim, piatu, serta keluarga kurang mampu di Kabupaten Purworejo.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_sejarah_p2',
		array(
			'label'   => __( 'Paragraf 2 Sejarah Singkat', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Berlokasi di Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, Desa Kaliharjo, lembaga kesejahteraan sosial anak (LKSA / MCC) ini memadukan pendidikan formal di sekolah dengan pembinaan karakter Islami di lingkungan asrama.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_semboyan_quote',
		array(
			'label'   => __( 'Kutipan Semboyan Pengasuhan', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'default' => 'Mendidik dengan Hati, Mengasuh dengan Nyata, Menjemput Masa Depan yang Mulia.',
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_visi_bg',
		__( 'Foto Latar Seksi Visi & Misi', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_about'
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_visi_text',
		array(
			'label'   => __( 'Teks Visi Utama Panti', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_about',
			'type'    => 'textarea',
			'default' => 'Menjadi Lembaga Kesejahteraan Sosial Anak (LKSA) yang unggul dalam membentuk karakter santri yang religius, berintegritas, mandiri, dan responsif terhadap perkembangan zaman digital.',
		)
	);

	$misi_defaults = array(
		1 => 'Menyelenggarakan pengasuhan dan pembinaan berbasis nilai-nilai Islami berlandaskan Al-Qur’an dan As-Sunnah.',
		2 => 'Menyediakan sarana pendidikan formal maupun non-formal yang layak bagi anak yatim dan dhuafa.',
		3 => 'Membekali santri dengan keterampilan digital, kreativitas, dan kemandirian hidup sebagai bekal masa depan.',
		4 => 'Mewujudkan tata kelola panti asuhan yang amanah, transparan, akuntabel, dan bersinergi bersama masyarakat.',
	);
	foreach ( $misi_defaults as $m_idx => $m_text ) {
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_misi_{$m_idx}",
			array(
				'label'   => sprintf( __( 'Butir Misi #%d', 'panti-muhammadiyah-kaligesing' ), $m_idx ),
				'section' => 'pmk_sec_about',
				'type'    => 'textarea',
				'default' => $m_text,
			)
		);
	}

	// Bagan Struktur Organisasi
	$org_fields = array(
		'pmk_org_pelindung'              => array( 'Pelindung & Penyelenggara', 'Pimpinan Cabang Muhammadiyah (PCM) Kaligesing' ),
		'pmk_org_pelindung_sub'          => array( 'Sub-Jabatan Pelindung', 'Majelis Pelayanan Kesejahteraan Sosial (MPKS)' ),
		'pmk_org_kepala'                 => array( 'Nama Kepala Panti (Bagan Struktur)', 'H. Johan Martono' ),
		'pmk_org_wakil'                  => array( 'Nama Wakil Kepala Panti', 'Iwan Kusni Surahman' ),
		'pmk_org_sekretaris'             => array( 'Nama Sekretaris', 'Harsiwi Wismayanti' ),
		'pmk_org_kepala_asrama'          => array( 'Nama Kepala Asrama', 'Hj. Arifatul Aini' ),
		'pmk_org_bendahara'              => array( 'Nama Bendahara', '1. Sumardi • 2. Sunaryo' ),
		'pmk_org_bidang_1_title'         => array( 'Nama Bidang 01', 'Humas & Usaha' ),
		'pmk_org_bidang_1_members'       => array( 'Anggota Bidang 01 (Pisahkan dengan koma)', 'Sanjaya Ancas Mahardika, Agung Mulyanto, Supardiyono' ),
		'pmk_org_bidang_2_title'         => array( 'Nama Bidang 02', 'Pendidikan & Pelatihan' ),
		'pmk_org_bidang_2_members'       => array( 'Anggota Bidang 02 (Pisahkan dengan koma)', 'Sudiyono, Edy Prasetyo' ),
		'pmk_org_bidang_3_title'         => array( 'Nama Bidang 03', 'Kerohanian' ),
		'pmk_org_bidang_3_members'       => array( 'Anggota Bidang 03 (Pisahkan dengan koma)', 'Bambang Edy, Triman' ),
		'pmk_org_bidang_4_title'         => array( 'Nama Bidang 04', 'Sarana Prasarana' ),
		'pmk_org_bidang_4_members'       => array( 'Anggota Bidang 04 (Pisahkan dengan koma)', 'Teguh, Sutarko Hantoro' ),
		'pmk_org_bidang_5_title'         => array( 'Nama Bidang 05', 'Pengasuhan & Rumah Tangga' ),
		'pmk_org_bidang_5_members'       => array( 'Anggota Bidang 05 (Pisahkan dengan koma)', 'Istiqomah, Dwi Yanto' ),
		'pmk_org_bidang_6_title'         => array( 'Nama Bidang 06', 'Sosial & Kesehatan' ),
		'pmk_org_bidang_6_members'       => array( 'Anggota Bidang 06 (Pisahkan dengan koma)', 'Hj. Mugiati' ),
		'pmk_org_bidang_7_title'         => array( 'Nama Bidang 07', 'Identifikasi Klien' ),
		'pmk_org_bidang_7_members'       => array( 'Anggota Bidang 07 (Pisahkan dengan koma)', 'Hj. Arifatul Aini, Harsiwi Wismayanti' ),
	);
	foreach ( $org_fields as $oid => $oinfo ) {
		pmk_add_customizer_field(
			$wp_customize,
			$oid,
			array(
				'label'   => $oinfo[0],
				'section' => 'pmk_sec_about',
				'default' => $oinfo[1],
			)
		);
	}

	// =========================================================================
	// SEKSI 5: HALAMAN FASILITAS & GALERI FOTO
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_facilities',
		array(
			'title'       => __( '5. Halaman Fasilitas & Galeri Foto', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 50,
			'description' => __( 'Ganti foto, judul, dan deskripsi 6 Fasilitas Utama maupun galeri dokumentasi tambahan tanpa merusak tata letak grid.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_hero_facilities',
		__( 'Foto Banner Atas Halaman Fasilitas', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_facilities'
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_fac_hero_title',
		array(
			'label'   => __( 'Judul Banner Halaman Fasilitas', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_facilities',
			'default' => 'Fasilitas & Sarana Panti Asuhan',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_fac_hero_desc',
		array(
			'label'   => __( 'Deskripsi Banner Halaman Fasilitas', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_facilities',
			'type'    => 'textarea',
			'default' => 'Sarana dan prasarana yang bersih, nyaman, serta representatif untuk menunjang tempat tinggal, ibadah, pembelajaran, dan pelayanan tamu maupun donatur.',
		)
	);

	$fac_toggles = array(
		'pmk_show_fac_highlights' => __( 'Tampilkan Ringkasan 3 Kelompok Sarana Utama', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_fac_main'       => __( 'Tampilkan Grid 6 Fasilitas Utama Panti', 'panti-muhammadiyah-kaligesing' ),
		'pmk_show_fac_extra'      => __( 'Tampilkan Galeri Dokumentasi Aktivitas & Lingkungan', 'panti-muhammadiyah-kaligesing' ),
	);
	foreach ( $fac_toggles as $ftid => $ftlabel ) {
		pmk_add_customizer_field(
			$wp_customize,
			$ftid,
			array(
				'label'   => $ftlabel,
				'section' => 'pmk_sec_facilities',
				'type'    => 'checkbox',
				'default' => '1',
			)
		);
	}

	$fac_controls = array(
		1 => array(
			'img_key' => 'pmk_img_fac_depan',
			'title'   => 'Depan Panti',
			'desc'    => 'Tampak depan bangunan utama beserta papan nama resmi Panti Asuhan Muhammadiyah Kaligesing di jalur utama Jl. Raya Purworejo–Kaligesing KM 8,5.',
		),
		2 => array(
			'img_key' => 'pmk_img_fac_tamu',
			'title'   => 'Ruang Tamu',
			'desc'    => 'Area penerimaan tamu, wali anak asuh, dan para donatur yang tertata bersih serta nyaman untuk kegiatan silaturahmi dan penyerahan amanah donasi.',
		),
		3 => array(
			'img_key' => 'pmk_img_fac_administrasi',
			'title'   => 'Ruang Kerja Administrasi',
			'desc'    => 'Ruang sekretariat dan tata usaha untuk pelayanan administrasi, pencatatan data anak asuh, pengarsipan dokumen resmi, serta pembukuan keuangan panti.',
		),
		4 => array(
			'img_key' => 'pmk_img_fac_mushola',
			'title'   => 'Mushola Panti',
			'desc'    => 'Pusat kegiatan ibadah shalat lima waktu berjamaah, tadarus dan tahfidz Al-Qur’an, serta pembinaan akhlak Islami seluruh anak asuh setiap hari.',
		),
		5 => array(
			'img_key' => 'pmk_img_fac_halaman',
			'title'   => 'Halaman Utama',
			'desc'    => 'Area terbuka yang hijau, asri, dan aman di lingkungan panti untuk kegiatan olahraga pagi, apel kebersamaan, serta aktivitas luar ruang anak asuh.',
		),
		6 => array(
			'img_key' => 'pmk_img_fac_asrama',
			'title'   => 'Bangunan Asrama / Ruang Belajar',
			'desc'    => 'Fasilitas hunian kamar anak asuh yang sehat dan terawat, dilengkapi ruang belajar bersama yang tenang untuk mendukung prestasi akademik.',
		),
	);

	foreach ( $fac_controls as $fnum => $fcfg ) {
		pmk_add_customizer_image_control(
			$wp_customize,
			$fcfg['img_key'],
			sprintf( __( 'Foto Fasilitas %d: %s', 'panti-muhammadiyah-kaligesing' ), $fnum, $fcfg['title'] ),
			'pmk_sec_facilities'
		);
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_fac_{$fnum}_title",
			array(
				'label'   => sprintf( __( 'Judul Fasilitas 0%d', 'panti-muhammadiyah-kaligesing' ), $fnum ),
				'section' => 'pmk_sec_facilities',
				'default' => $fcfg['title'],
			)
		);
		pmk_add_customizer_field(
			$wp_customize,
			"pmk_fac_{$fnum}_desc",
			array(
				'label'   => sprintf( __( 'Deskripsi Fasilitas 0%d', 'panti-muhammadiyah-kaligesing' ), $fnum ),
				'section' => 'pmk_sec_facilities',
				'type'    => 'textarea',
				'default' => $fcfg['desc'],
			)
		);
	}

	// =========================================================================
	// SEKSI 6: REKENING DONASI RESMI & AJAKAN BERBAGI
	// =========================================================================
	$wp_customize->add_section(
		'pmk_sec_donation',
		array(
			'title'       => __( '6. Rekening Donasi Resmi & Ajakan Berbagi', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 60,
			'description' => __( 'Atur Nama Bank, Nomor Rekening, Atas Nama, Catatan Konfirmasi, serta foto latar seksi donasi.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_bank_name',
		array(
			'label'   => __( 'Nama Bank Rekening Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'default' => 'Bank BRI',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_bank_account',
		array(
			'label'   => __( 'Nomor Rekening Donasi Resmi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'default' => '684301006299530',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_bank_holder',
		array(
			'label'   => __( 'Atas Nama Rekening Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'default' => 'PAY DAN DM KALIGESING',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_bank_note',
		array(
			'label'   => __( 'Catatan Konfirmasi Transfer Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'type'    => 'textarea',
			'default' => 'Setelah melakukan transfer donasi, zakat, infaq, atau sedekah, silakan konfirmasi melalui WhatsApp resmi agar tercatat dalam laporan penyaluran amanah panti.',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_donation_title',
		array(
			'label'   => __( 'Judul Ajakan Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'default' => 'Mari Berbagi Kebaikan untuk Masa Depan Anak Yatim & Dhuafa',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_donation_desc',
		array(
			'label'   => __( 'Deskripsi Ajakan Donasi', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_sec_donation',
			'type'    => 'textarea',
			'default' => 'Dukungan zakat, infaq, sedekah, maupun wakaf dari Bapak/Ibu sangat berarti bagi biaya pendidikan, konsumsi bergizi, dan kebutuhan asrama anak-anak asuh kami.',
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_donation_bg',
		__( 'Foto Latar Seksi Donasi Beranda', 'panti-muhammadiyah-kaligesing' ),
		'pmk_sec_donation'
	);

	// =========================================================================
	// SEKSI 7: KONTAK RESMI, WHATSAPP, FORMULIR & PETA LOKASI
	// =========================================================================
	$wp_customize->add_section(
		'pmk_panti_settings',
		array(
			'title'       => __( '7. Kontak Resmi, WhatsApp, Formulir & Peta', 'panti-muhammadiyah-kaligesing' ),
			'panel'       => 'pmk_theme_panel',
			'priority'    => 70,
			'description' => __( 'Atur Nomor WhatsApp tunggal, Alamat Lengkap, Peta Google Maps, dan integrasi shortcode WPForms / Contact Form 7.', 'panti-muhammadiyah-kaligesing' ),
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_wa_number',
		array(
			'label'       => __( 'Nomor WhatsApp Resmi Tunggal (Format 08xxx atau 628xxx)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Otomatis diformat menjadi 08xx-xxxx-xxxx di tampilan halaman dan 628xxx pada tautan wa.me.', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_panti_settings',
			'default'     => '6285326980493',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_address',
		array(
			'label'   => __( 'Alamat Lengkap Panti', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'textarea',
			'default' => 'Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, RT 02/RW 02, Desa Kaliharjo, Kaligesing, Purworejo, Jawa Tengah 54175',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_maps_link',
		array(
			'label'           => __( 'Tautan Tombol Buka Google Maps', 'panti-muhammadiyah-kaligesing' ),
			'section'         => 'pmk_panti_settings',
			'default'         => 'https://maps.google.com/?q=Panti+Asuhan+Muhammadiyah+Kaligesing+Purworejo',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_maps_embed',
		array(
			'label'             => __( 'URL Embed Iframe Google Maps', 'panti-muhammadiyah-kaligesing' ),
			'section'           => 'pmk_panti_settings',
			'default'           => 'https://maps.google.com/maps?q=Panti%20Asuhan%20Muhammadiyah%20Kaligesing%20Kaliharjo%20Purworejo&t=&z=15&ie=UTF8&iwloc=&output=embed',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_wpforms_id',
		array(
			'label'       => __( 'ID Shortcode WPForms (Opsional)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Kosongkan (atau 0) untuk memakai formulir kontak bawaan tema yang langsung menyimpan pesan ke database WordPress.', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_panti_settings',
			'type'        => 'number',
			'default'     => '',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_custom_form_shortcode',
		array(
			'label'       => __( 'Shortcode Formulir Kustom (WPForms / CF7 / FluentForms)', 'panti-muhammadiyah-kaligesing' ),
			'description' => __( 'Contoh: [wpforms id="123"] atau [contact-form-7 id="45"]. Kosongkan untuk memakai formulir kontak bawaan tema.', 'panti-muhammadiyah-kaligesing' ),
			'section'     => 'pmk_panti_settings',
			'default'     => '',
		)
	);

	pmk_add_customizer_image_control(
		$wp_customize,
		'pmk_img_hero_contact',
		__( 'Foto Banner Atas Halaman Kontak', 'panti-muhammadiyah-kaligesing' ),
		'pmk_panti_settings'
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_contact_hero_title',
		array(
			'label'   => __( 'Judul Banner Halaman Kontak', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'default' => 'Kontak & Saluran Donasi Resmi',
		)
	);

	pmk_add_customizer_field(
		$wp_customize,
		'pmk_contact_hero_desc',
		array(
			'label'   => __( 'Deskripsi Banner Halaman Kontak', 'panti-muhammadiyah-kaligesing' ),
			'section' => 'pmk_panti_settings',
			'type'    => 'textarea',
			'default' => 'Panti Asuhan Muhammadiyah Kaligesing siap menerima silaturahmi, kunjungan, maupun penyaluran donasi dari Anda demi masa depan santri yang lebih baik.',
		)
	);

	// =========================================================================
	// SELECTIVE REFRESH PARTIALS (IKON PENSIL EDIT CEPAT DI CUSTOMIZER PREVIEW)
	// =========================================================================
	if ( isset( $wp_customize->selective_refresh ) && method_exists( $wp_customize->selective_refresh, 'add_partial' ) ) {
		$partials = array(
			'pmk_brand_name'           => '.pmk-brand__name',
			'pmk_hero_title_highlight' => '.pmk-hero__title',
			'pmk_hero_lead'            => '.pmk-hero__lead',
			'pmk_wa_number'            => '.pmk-hero__floating-val',
			'pmk_bank_account'         => '.pmk-bank-card__number',
			'pmk_kepala_name'          => '.pmk-sambutan__name',
			'pmk_sambutan_title'       => '.pmk-sambutan',
			'pmk_org_kepala'           => '.pmk-org-chart',
			'pmk_fac_1_title'          => '.pmk-gallery-grid',
			'pmk_address'              => '.pmk-footer__contact-list',
		);
		foreach ( $partials as $pid => $selector ) {
			$wp_customize->selective_refresh->add_partial(
				$pid,
				array(
					'selector'        => $selector,
					'settings'        => array( $pid ),
					'fallback_refresh' => true,
				)
			);
		}
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'customize_register', 'pmk_customize_register' );
}
