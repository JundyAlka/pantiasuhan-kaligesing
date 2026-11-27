<?php
/**
 * Template Name: Elementor Full Width (Header & Footer Panti)
 * Template Post Type: page, post
 *
 * Template khusus bagi pengguna yang ingin mendesain halaman secara bebas 100%
 * menggunakan drag-and-drop Elementor atau Gutenberg Block Editor dengan tetap
 * mempertahankan Header, Navigasi Mobile, Tombol WhatsApp, dan Footer resmi Panti.
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="pmk-elementor-fullwidth">
	<?php
	if ( function_exists( 'have_posts' ) && have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	elseif ( function_exists( 'the_content' ) ) :
		the_content();
	endif;
	?>
</div>

<?php
get_footer();
