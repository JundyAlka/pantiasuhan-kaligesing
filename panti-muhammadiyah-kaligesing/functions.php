<?php
/**
 * Functions & Definitions — Panti Asuhan Muhammadiyah Kaligesing
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PMK_THEME_VERSION', '2.1.0' );

require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/contact-handler.php';
require_once get_template_directory() . '/inc/kp-sync.php';

/**
 * Setup fitur inti tema WordPress, Elementor compatibility, dan lokasi menu.
 */
function pmk_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
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
 * Daftar 4 menu wajib KP dalam Bahasa Indonesia dengan slug URL konsisten (/ , /about/ , /facilities/ , /contact/).
 */
function pmk_get_nav_items() {
	$current_uri = isset( $_SERVER['REQUEST_URI'] ) ? trim( (string) strtok( $_SERVER['REQUEST_URI'], '?' ), '/' ) : '';
	$items       = array(
		array(
			'label' => 'Beranda',
			'slug'  => '',
			'url'   => home_url( '/' ),
		),
		array(
			'label' => 'Tentang Kami',
			'slug'  => 'about',
			'url'   => home_url( '/about/' ),
		),
		array(
			'label' => 'Fasilitas',
			'slug'  => 'facilities',
			'url'   => home_url( '/facilities/' ),
		),
		array(
			'label' => 'Kontak',
			'slug'  => 'contact',
			'url'   => home_url( '/contact/' ),
		),
	);

	foreach ( $items as &$item ) {
		$active = false;
		if ( function_exists( 'is_front_page' ) && '' === $item['slug'] ) {
			$active = is_front_page();
		} elseif ( function_exists( 'is_page' ) && '' !== $item['slug'] ) {
			$active = is_page( $item['slug'] );
		} else {
			$active = ( $current_uri === $item['slug'] );
		}
		$item['active'] = $active;
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
