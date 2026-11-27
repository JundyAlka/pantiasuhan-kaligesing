<?php
/**
 * Functions & Definitions — Panti Asuhan Muhammadiyah Kaligesing
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PMK_THEME_VERSION', '2.4.0' );

require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/contact-handler.php';
require_once get_template_directory() . '/inc/page-builder.php';
require_once get_template_directory() . '/inc/kp-sync.php';

/**
 * Setup fitur inti tema WordPress komersial, Elementor compatibility, dan lokasi menu.
 */
function pmk_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 150,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu Utama Header (4 Menu Wajib KP)', 'panti-muhammadiyah-kaligesing' ),
			'footer'  => __( 'Menu Navigasi Footer', 'panti-muhammadiyah-kaligesing' ),
		)
	);
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'after_setup_theme', 'pmk_theme_setup' );
}

/**
 * Enqueue Google Fonts, CSS Utama, dan JS Interaktif.
 */
function pmk_enqueue_assets() {
	wp_enqueue_style(
		'pmk-google-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'pmk-main-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'pmk-google-fonts' ),
		PMK_THEME_VERSION
	);

	wp_enqueue_style(
		'pmk-theme-root',
		get_stylesheet_uri(),
		array( 'pmk-main-style' ),
		PMK_THEME_VERSION
	);

	wp_enqueue_script(
		'pmk-main-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		PMK_THEME_VERSION,
		true
	);
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'wp_enqueue_scripts', 'pmk_enqueue_assets' );
}

/**
 * Daftarkan lokasi Elementor Theme Builder bila plugin Elementor aktif.
 */
function pmk_register_elementor_locations( $elementor_theme_manager ) {
	if ( is_object( $elementor_theme_manager ) && method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
		$elementor_theme_manager->register_all_core_location();
	}
}
if ( function_exists( 'add_action' ) ) {
	add_action( 'elementor/theme/register_locations', 'pmk_register_elementor_locations' );
}

/**
 * Ambil URL halaman inti ('', 'about', 'facilities', 'contact') secara cerdas
 * dengan menyesuaikan struktur permalink server (.htaccess / PATHINFO / page_id) agar tidak pernah 404.
 *
 * @param string $slug Slug halaman ('', 'about', 'facilities', 'contact').
 * @param string $hash Anchor opsional (mis. '#donasi' atau '#peta').
 * @return string
 */
function pmk_get_page_url( $slug = '', $hash = '' ) {
	$clean_slug = trim( (string) $slug, '/' );
	if ( '' === $clean_slug || 'home' === $clean_slug ) {
		return home_url( '/' ) . $hash;
	}

	if ( defined( 'PMK_LOCAL_PREVIEW' ) && PMK_LOCAL_PREVIEW ) {
		return home_url( '/' . $clean_slug . '/' ) . $hash;
	}

	$alt_map = array(
		'about'      => array( 'about', 'tentang-kami', 'profil' ),
		'facilities' => array( 'facilities', 'fasilitas', 'galeri' ),
		'contact'    => array( 'contact', 'kontak', 'hubungi-kami' ),
	);

	$candidates = $alt_map[ $clean_slug ] ?? array( $clean_slug );
	if ( function_exists( 'get_page_by_path' ) && function_exists( 'get_permalink' ) ) {
		foreach ( $candidates as $cand ) {
			$page_obj = get_page_by_path( $cand );
			if ( is_object( $page_obj ) && ! empty( $page_obj->ID ) ) {
				$perm = get_permalink( $page_obj->ID );
				if ( ! empty( $perm ) && is_string( $perm ) ) {
					return $perm . $hash;
				}
			}
		}
	}

	if ( function_exists( 'get_option' ) ) {
		$perm_struct = (string) get_option( 'permalink_structure', '' );
		if ( 0 === strpos( $perm_struct, '/index.php' ) ) {
			return home_url( '/index.php/' . $clean_slug . '/' ) . $hash;
		}
	}

	return home_url( '/' . $clean_slug . '/' ) . $hash;
}

/**
 * Daftar 4 menu wajib KP dalam Bahasa Indonesia dengan resolusi URL anti-404.
 */
function pmk_get_nav_items() {
	$active_type = function_exists( 'pmk_resolve_core_template_type' ) ? pmk_resolve_core_template_type() : '';
	$current_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) strtok( $_SERVER['REQUEST_URI'], '?' ), '/' ) : '';

	$items = array(
		array(
			'label' => 'Beranda',
			'slug'  => '',
			'type'  => 'home',
			'url'   => pmk_get_page_url( '' ),
		),
		array(
			'label' => 'Tentang Kami',
			'slug'  => 'about',
			'type'  => 'about',
			'url'   => pmk_get_page_url( 'about' ),
		),
		array(
			'label' => 'Fasilitas',
			'slug'  => 'facilities',
			'type'  => 'facilities',
			'url'   => pmk_get_page_url( 'facilities' ),
		),
		array(
			'label' => 'Kontak',
			'slug'  => 'contact',
			'type'  => 'contact',
			'url'   => pmk_get_page_url( 'contact' ),
		),
	);

	foreach ( $items as &$item ) {
		if ( '' !== $active_type ) {
			$item['active'] = ( $active_type === $item['type'] );
		} else {
			$item['active'] = ( $current_uri === $item['slug'] );
		}
	}
	unset( $item );

	return $items;
}

/**
 * Render menu 4 item wajib KP berbahasa Indonesia di header & footer.
 */
function pmk_render_fallback_nav( $list_class = 'pmk-nav__list' ) {
	echo '<ul class="' . esc_attr( $list_class ) . '">';
	foreach ( pmk_get_nav_items() as $item ) {
		$aria  = $item['active'] ? ' aria-current="page"' : '';
		$class = $item['active'] ? ' class="is-active"' : '';
		echo '<li><a href="' . esc_url( $item['url'] ) . '"' . $class . $aria . '>' . esc_html( $item['label'] ) . '</a></li>';
	}
	echo '</ul>';
}
