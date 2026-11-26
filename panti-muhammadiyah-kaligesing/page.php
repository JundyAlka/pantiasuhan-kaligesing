<?php
/**
 * Default Page Template (Kompatibel dengan Gutenberg & Elementor)
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

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
