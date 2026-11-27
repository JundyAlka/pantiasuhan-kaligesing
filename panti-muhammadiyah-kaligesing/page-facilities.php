<?php
/**
 * Template Name: Halaman Facilities (Fasilitas)
 * Memuat:
 * - Hero Panorama Landscape Panti (dapat dikustomisasi di Customizer)
 * - Fasilitas 01–03 dengan palet lembut dan checklist sarana
 * - Galeri Grid 6 Fasilitas Utama Bab II + Dokumentasi Tambahan menggunakan foto asli (dapat diganti foto & teksnya lewat Customizer)
 * - Kompatibel penuh dengan Elementor & Gutenberg Block Editor
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

get_header();

$hero_fac       = pmk_get_image_url( 'pmk_img_hero_facilities', 'photo-9.png' );
$fac_hero_title = pmk_get_mod( 'pmk_fac_hero_title', 'Fasilitas & Sarana Panti Asuhan' );
$fac_hero_desc  = pmk_get_mod(
	'pmk_fac_hero_desc',
	'Sarana dan prasarana yang bersih, nyaman, serta representatif untuk menunjang tempat tinggal, ibadah, pembelajaran, dan pelayanan tamu maupun donatur.'
);

$show_highlights = pmk_get_bool_mod( 'pmk_show_fac_highlights', true );
$show_main_fac   = pmk_get_bool_mod( 'pmk_show_fac_main', true );
$show_extra_docs = pmk_get_bool_mod( 'pmk_show_fac_extra', true );

$facilities = pmk_get_facilities_data();

$extra_docs = array(
	array(
		'title'    => 'Kepala Panti Asuhan Muhammadiyah Kaligesing',
		'img'      => pmk_get_image_url( 'pmk_img_kepala', 'kepala-panti.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'kepala-panti.jpg' ),
	),
	array(
		'title'    => 'Penyerahan Bantuan Bakti Sosial bersama Mitra',
		'img'      => pmk_get_image_url( '', 'bakti-sosial-kepala.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'bakti-sosial-kepala.jpg' ),
	),
	array(
		'title'    => 'Kebersamaan Anak Asuh & Pembina di Aula',
		'img'      => pmk_get_image_url( '', 'anak-kebersamaan-1.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-kebersamaan-1.jpg' ),
	),
	array(
		'title'    => 'Kegiatan Edukasi & Motivasi di Mushola Panti',
		'img'      => pmk_get_image_url( '', 'anak-edukasi-mushola.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-edukasi-mushola.jpg' ),
	),
	array(
		'title'    => 'Senyum Ceria Anak Asuh Panti Muhammadiyah',
		'img'      => pmk_get_image_url( '', 'anak-ceria-landscape.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-ceria-landscape.jpg' ),
	),
	array(
		'title'    => 'Keakraban Santri Putri & Pendamping',
		'img'      => pmk_get_image_url( '', 'anak-ceria-potret.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-ceria-potret.jpg' ),
	),
	array(
		'title'    => 'Sesi Interaktif & Kuis Keislaman Santri',
		'img'      => pmk_get_image_url( '', 'anak-kuis-mushola.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-kuis-mushola.jpg' ),
	),
	array(
		'title'    => 'Keluarga Besar Santri & Kakak Pembina',
		'img'      => pmk_get_image_url( '', 'anak-kebersamaan-2.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-kebersamaan-2.jpg' ),
	),
	array(
		'title'    => 'Suasana Pembinaan Karakter di Serambi Mushola',
		'img'      => pmk_get_image_url( '', 'anak-pembinaan-aula.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'anak-pembinaan-aula.jpg' ),
	),
	array(
		'title'    => 'Dokumentasi Kegiatan Bersama Santri',
		'img'      => pmk_get_image_url( '', 'photo-3.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'photo-3.jpg' ),
	),
	array(
		'title'    => 'Kebersamaan Santri & Pengurus Panti',
		'img'      => pmk_get_image_url( '', 'panti-3.png' ),
		'fallback' => pmk_get_fallback_photo_url( 'panti-3.png' ),
	),
	array(
		'title'    => 'Bakti Sosial & Kunjungan Mitra',
		'img'      => pmk_get_image_url( '', 'photo-1.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'photo-1.jpg' ),
	),
	array(
		'title'    => 'Aktivitas Kemandirian & Lingkungan Santri',
		'img'      => pmk_get_image_url( '', 'wa-kegiatan-1.jpg' ),
		'fallback' => pmk_get_fallback_photo_url( 'wa-kegiatan-1.jpg' ),
	),
	array(
		'title'    => 'Latihan Ekstrakurikuler Tapak Suci',
		'img'      => pmk_get_image_url( '', 'photo-10.png' ),
		'fallback' => pmk_get_fallback_photo_url( 'photo-10.png' ),
	),
);

if ( ! pmk_should_hide_theme_sections() ) :
?>

<section class="pmk-page-hero pmk-page-hero--photo" style="background-image: url('<?php echo esc_url( $hero_fac ); ?>');">
	<div class="pmk-page-hero__scrim" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<nav class="pmk-breadcrumb" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
			<span aria-hidden="true">/</span>
			<span>Fasilitas</span>
		</nav>
		<h1 class="pmk-page-hero__title"><?php echo esc_html( $fac_hero_title ); ?></h1>
		<p class="pmk-page-hero__desc">
			<?php echo esc_html( $fac_hero_desc ); ?>
		</p>
	</div>
</section>

<?php if ( $show_highlights ) : ?>
<!-- FASILITAS UNGGULAN 01–03 -->
<section class="pmk-section pmk-section--tint">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-reveal">
			<span class="pmk-eyebrow">Ringkasan Sarana Utama</span>
			<h2 class="pmk-section-title">Kenyamanan, Ibadah &amp; Pelayanan Terpadu</h2>
			<p class="pmk-section-desc">Tiga kelompok fasilitas inti yang memastikan anak asuh bertumbuh dalam lingkungan yang aman dan bermartabat.</p>
		</div>

		<div class="pmk-facility-highlights">
			<article class="pmk-facility-highlight pmk-reveal">
				<span class="pmk-facility-highlight__num">Fasilitas / 01</span>
				<h3 class="pmk-pillar-card__title" style="margin-bottom:10px;">Mendukung Tumbuh Kembang Anak Asuh</h3>
				<p class="pmk-pillar-card__desc" style="margin-bottom:14px;">Fasilitas utama yang bersih, nyaman, dan representatif untuk aktivitas harian serta pembentukan karakter islami anak asuh secara optimal.</p>
				<ul class="pmk-check-list">
					<li>Asrama Putra &amp; Putri Terpisah</li>
					<li>Mushola &amp; Sarana Ibadah</li>
					<li>Ruang Belajar Mandiri</li>
				</ul>
			</article>

			<article class="pmk-facility-highlight pmk-reveal" style="--delay:90ms;">
				<span class="pmk-facility-highlight__num">Fasilitas / 02</span>
				<h3 class="pmk-pillar-card__title" style="margin-bottom:10px;">Sarana Pendukung Kreativitas &amp; Gizi</h3>
				<p class="pmk-pillar-card__desc" style="margin-bottom:14px;">Sarana penunjang untuk mengasah minat, bakat, serta menjaga kesehatan dan asupan gizi terbaik bagi seluruh penghuni panti.</p>
				<ul class="pmk-check-list">
					<li>Ruang Aula / Kegiatan Bersama</li>
					<li>Dapur Sehat &amp; Ruang Makan</li>
					<li>Sarana Olahraga &amp; Kreativitas</li>
				</ul>
			</article>

			<article class="pmk-facility-highlight pmk-reveal" style="--delay:180ms;">
				<span class="pmk-facility-highlight__num">Fasilitas / 03</span>
				<h3 class="pmk-pillar-card__title" style="margin-bottom:10px;">Lingkungan Asri, Aman &amp; Islami</h3>
				<p class="pmk-pillar-card__desc" style="margin-bottom:14px;">Area panti asuhan senantiasa terjaga kebersihan dan keamanannya agar anak-anak dapat beribadah, belajar, dan berinteraksi dengan tenteram.</p>
				<ul class="pmk-check-list">
					<li>Sistem Keamanan &amp; Pengawasan</li>
					<li>Kamar Mandi Bersih</li>
					<li>Area Terbuka Hijau</li>
				</ul>
			</article>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $show_main_fac ) : ?>
<!-- GALERI 6 FASILITAS UTAMA BAB II -->
<section class="pmk-section">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow">Dokumentasi Fasilitas Panti</span>
			<h2 class="pmk-section-title">6 Fasilitas Utama Panti Asuhan Muhammadiyah Kaligesing</h2>
			<p class="pmk-section-desc">Klik foto mana saja untuk memperbesar pratinjau. Seluruh dokumentasi ditata dalam grid responsif yang rapi.</p>
		</div>

		<div class="pmk-gallery-grid">
			<?php foreach ( $facilities as $idx => $fac ) : ?>
				<article class="pmk-gallery-card pmk-reveal" style="--delay: <?php echo esc_attr( $idx * 60 ); ?>ms;">
					<div class="pmk-gallery-card__img-wrap">
						<img src="<?php echo esc_url( $fac['img'] ); ?>" data-fallback-src="<?php echo esc_url( $fac['fallback'] ); ?>" alt="<?php echo esc_attr( $fac['title'] ); ?>" loading="lazy">
						<span class="pmk-gallery-card__badge"><?php echo esc_html( $fac['badge'] ); ?></span>
					</div>
					<div class="pmk-gallery-card__body">
						<h3 class="pmk-gallery-card__title"><?php echo esc_html( $fac['title'] ); ?></h3>
						<p class="pmk-gallery-card__desc"><?php echo esc_html( $fac['desc'] ); ?></p>
						<button type="button" class="pmk-gallery-card__zoom" data-lightbox-src="<?php echo esc_url( $fac['img'] ); ?>" data-lightbox-title="<?php echo esc_attr( $fac['badge'] . ' — ' . $fac['title'] ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
							<span>Perbesar Foto</span>
						</button>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( $show_extra_docs ) : ?>
<!-- DOKUMENTASI GALERI KEGIATAN LAINNYA -->
<section class="pmk-section pmk-section--tint">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow">Galeri Dokumentasi</span>
			<h2 class="pmk-section-title">Potret Aktivitas &amp; Lingkungan Panti</h2>
		</div>

		<div class="pmk-gallery-grid">
			<?php foreach ( $extra_docs as $idx => $doc ) : ?>
				<article class="pmk-gallery-card pmk-reveal" style="--delay: <?php echo esc_attr( $idx * 50 ); ?>ms;">
					<div class="pmk-gallery-card__img-wrap">
						<img src="<?php echo esc_url( $doc['img'] ); ?>" data-fallback-src="<?php echo esc_url( $doc['fallback'] ); ?>" alt="<?php echo esc_attr( $doc['title'] ); ?>" loading="lazy">
					</div>
					<div class="pmk-gallery-card__body" style="padding:16px 20px;">
						<h3 class="pmk-gallery-card__title" style="font-size:1rem;"><?php echo esc_html( $doc['title'] ); ?></h3>
						<button type="button" class="pmk-gallery-card__zoom" data-lightbox-src="<?php echo esc_url( $doc['img'] ); ?>" data-lightbox-title="<?php echo esc_attr( $doc['title'] ); ?>">
							<span>Lihat Ukuran Penuh</span>
						</button>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
endif; // End ! pmk_should_hide_theme_sections()

pmk_render_builder_content( 'facilities' );
?>

<!-- LIGHTBOX MODAL -->
<div id="pmk-lightbox" class="pmk-lightbox" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Pratinjau Foto Fasilitas">
	<div class="pmk-lightbox__dialog">
		<img id="pmk-lightbox-img" class="pmk-lightbox__img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="">
		<div class="pmk-lightbox__bar">
			<strong id="pmk-lightbox-title" style="color:var(--pmk-text);font-size:1rem;"></strong>
			<button type="button" id="pmk-lightbox-close" class="pmk-btn pmk-btn--primary" style="min-height:40px;padding:8px 16px;">Tutup</button>
		</div>
	</div>
</div>

<?php
get_footer();
