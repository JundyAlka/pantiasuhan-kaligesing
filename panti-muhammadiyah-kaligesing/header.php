<?php
/**
 * Header Template — Panti Asuhan Muhammadiyah Kaligesing
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_url         = pmk_get_logo_url();
$default_logo_url = pmk_get_fallback_photo_url( 'logo-panti.png' );
$brand_kicker     = pmk_get_mod( 'pmk_brand_kicker', 'LKSA / MCC • PCM Kaligesing' );
$brand_name       = pmk_get_mod( 'pmk_brand_name', 'Panti Asuhan Muhammadiyah' );
$brand_sub        = pmk_get_mod( 'pmk_brand_sub', 'Kaligesing • Kabupaten Purworejo' );
$header_cta_text  = pmk_get_mod( 'pmk_header_cta_text', 'Chat WhatsApp' );
$enable_preloader = pmk_get_bool_mod( 'pmk_enable_preloader', true );
$theme_dark_color = pmk_sanitize_hex_or_default( pmk_get_mod( 'pmk_color_dark', '#093622' ) );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="<?php echo esc_attr( $theme_dark_color ); ?>">
	<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $logo_url ); ?>">
		<link rel="icon" type="image/png" sizes="192x192" href="<?php echo esc_url( $logo_url ); ?>">
		<link rel="shortcut icon" href="<?php echo esc_url( $logo_url ); ?>">
		<link rel="apple-touch-icon" href="<?php echo esc_url( $logo_url ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-default-photo="<?php echo esc_url( pmk_get_fallback_photo_url( 'panti-1.jpg' ) ); ?>">
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<?php if ( $enable_preloader ) : ?>
<div id="pmk-page-loader" class="pmk-page-loader" aria-hidden="true">
	<div class="pmk-page-loader__core">
		<span class="pmk-page-loader__ring"></span>
		<img src="<?php echo esc_url( $logo_url ); ?>" data-fallback-src="<?php echo esc_url( $default_logo_url ); ?>" alt="" width="54" height="54">
	</div>
	<span class="pmk-page-loader__brand"><?php echo esc_html( $brand_name ); ?> Kaligesing</span>
	<span class="pmk-page-loader__bar"><span></span></span>
</div>
<?php endif; ?>
<a class="pmk-skip-link" href="#pmk-main-content">Langsung ke konten utama</a>

<header class="pmk-header" role="banner">
	<div class="pmk-container pmk-container--wide pmk-header__inner">
		<a class="pmk-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="pmk-brand__logo">
				<img src="<?php echo esc_url( $logo_url ); ?>" data-fallback-src="<?php echo esc_url( $default_logo_url ); ?>" alt="Logo Panti Asuhan Muhammadiyah Kaligesing" width="52" height="52">
			</span>
			<span class="pmk-brand__text">
				<span class="pmk-brand__kicker"><?php echo esc_html( $brand_kicker ); ?></span>
				<span class="pmk-brand__name"><?php echo esc_html( $brand_name ); ?></span>
				<span class="pmk-brand__sub"><?php echo esc_html( $brand_sub ); ?></span>
			</span>
		</a>

		<nav class="pmk-nav" aria-label="Navigasi Utama">
			<?php
			if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary' ) && ! defined( 'PMK_LOCAL_PREVIEW' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'pmk-nav__list',
						'depth'          => 1,
						'fallback_cb'    => function () {
							pmk_render_fallback_nav( 'pmk-nav__list' );
						},
					)
				);
			} else {
				pmk_render_fallback_nav( 'pmk-nav__list' );
			}
			?>
		</nav>

		<div class="pmk-header__actions">
			<a class="pmk-btn pmk-btn--primary pmk-header__cta" href="<?php echo esc_url( pmk_get_wa_url( 'Assalamualaikum, saya ingin bertanya mengenai donasi / informasi Panti Asuhan Muhammadiyah Kaligesing.' ) ); ?>" target="_blank" rel="noopener noreferrer">
				<svg class="pmk-icon-pulse" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
				<span><?php echo esc_html( $header_cta_text ); ?></span>
			</a>

			<button type="button" id="pmk-nav-toggle" class="pmk-nav-toggle" aria-expanded="false" aria-controls="pmk-mobile-nav" aria-label="Buka menu navigasi">
				<span class="pmk-burger" aria-hidden="true">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
		</div>
	</div>
</header>

<div id="pmk-mobile-nav" class="pmk-mobile-drawer" role="dialog" aria-modal="true" aria-label="Menu Navigasi Seluler">
	<div class="pmk-mobile-drawer__panel">
		<?php pmk_render_fallback_nav( 'pmk-mobile-nav__list' ); ?>
		<div class="pmk-mobile-drawer__cta">
			<a class="pmk-btn pmk-btn--wa pmk-mobile-drawer__wa" href="<?php echo esc_url( pmk_get_wa_url( 'Assalamualaikum, saya ingin menghubungi Panti Asuhan Muhammadiyah Kaligesing.' ) ); ?>" target="_blank" rel="noopener noreferrer">
				<span class="pmk-mobile-drawer__wa-icon" aria-hidden="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
				</span>
				<span class="pmk-mobile-drawer__wa-text">
					<strong>Chat WhatsApp Resmi</strong>
					<small><?php echo esc_html( pmk_get_wa_display() ); ?></small>
				</span>
			</a>
			<a class="pmk-btn pmk-btn--outline pmk-mobile-drawer__donate" href="<?php echo esc_url( home_url( '/contact/#donasi' ) ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FACC15" stroke-width="2.2" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
				<span>Info Rekening Donasi</span>
			</a>
		</div>
	</div>
</div>

<main id="pmk-main-content">
