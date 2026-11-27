<?php
/**
 * Default Page Template (Kompatibel dengan Gutenberg & Elementor + Auto-Router 4 Halaman Inti)
 *
 * Jika WordPress atau plugin mencoba merender page.php pada salah satu dari 4 halaman utama
 * (Beranda/Home, Tentang Kami/About, Fasilitas/Facilities, Kontak/Contact), template ini otomatis
 * mengalihkan rendering ke template resmi tema agar halaman tidak pernah tertimpa konten lama.
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'pmk_resolve_core_template_type' ) && function_exists( 'pmk_get_core_template_file' ) ) {
	$core_type = pmk_resolve_core_template_type();
	if ( '' !== $core_type ) {
		$core_file = pmk_get_core_template_file( $core_type );
		if ( '' !== $core_file && file_exists( $core_file ) ) {
			include $core_file;
			return;
		}
	}
}

get_header();
?>
<section class="pmk-page-hero">
	<div class="pmk-container">
		<h1 class="pmk-page-hero__title"><?php the_title(); ?></h1>
	</div>
</section>

<section class="pmk-section">
	<div class="pmk-container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</section>
<?php
get_footer();
