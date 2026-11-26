<?php
/**
 * Main Index Fallback Template
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

get_header();
?>
<section class="pmk-page-hero">
	<div class="pmk-container">
		<h1 class="pmk-page-hero__title"><?php bloginfo( 'name' ); ?></h1>
		<p class="pmk-page-hero__desc"><?php bloginfo( 'description' ); ?></p>
	</div>
</section>

<section class="pmk-section">
	<div class="pmk-container">
		<?php if ( have_posts() ) : ?>
			<div class="pmk-cards-3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="pmk-pillar-card">
						<h2 class="pmk-pillar-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="pmk-pillar-card__desc"><?php the_excerpt(); ?></div>
						<a class="pmk-btn pmk-btn--outline" href="<?php the_permalink(); ?>">Baca Selengkapnya</a>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="pmk-info-card">
				<h2 style="margin-top:0;">Halaman Tidak Ditemukan</h2>
				<p>Silakan kembali ke beranda untuk melihat informasi resmi Panti Asuhan Muhammadiyah Kaligesing.</p>
				<a class="pmk-btn pmk-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Kembali ke Home</a>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
