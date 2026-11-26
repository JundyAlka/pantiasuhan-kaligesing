<?php
/**
 * Front Page Template (Halaman Beranda) — Panti Asuhan Muhammadiyah Kaligesing
 * Fitur:
 * - Hero Banner lebih tinggi & lebar dengan Background Slideshow (Plang Panti, Gedung, Anak Asuh, Kegiatan)
 * - 3 Kotak Foto Kolase dengan animasi bergulir halus (rolling reel) berurutan
 * - Bento Grid Informasi Kontak WA Tunggal, Alamat, Akreditasi & Hadis (tidak terpotong)
 * - Satu Section Panorama Terpadu: Tiga Pilar Layanan + Mengenal Panti Lebih Dekat + Animasi Hitung (24+, 15+, 18+, 5+)
 * - Ringkasan Kegiatan & Dokumentasi dengan Slideshow Foto Interaktif + Kartu Aktivitas di atas latar bersih
 * - Penyaluran Donasi Resmi dengan background foto kebersamaan anak asuh & Rekening BRI panti
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

get_header();

$bank       = pmk_get_donation_info();
$wa_display = pmk_get_wa_display();
$wa_url     = pmk_get_wa_url( 'Assalamualaikum, saya ingin mendapatkan informasi dan konfirmasi donasi untuk Panti Asuhan Muhammadiyah Kaligesing.' );
$logo_url   = pmk_get_logo_url();

$hero_bg     = pmk_get_image_url( 'pmk_img_hero', 'panti-1.jpg' );
$panti_3     = pmk_get_image_url( '', 'panti-3.png' );
$panti_4     = pmk_get_image_url( '', 'panti-4.jpg' );
$photo_1     = pmk_get_image_url( '', 'photo-1.jpg' );
$photo_3     = pmk_get_image_url( '', 'photo-3.jpg' );
$photo_5     = pmk_get_image_url( '', 'photo-5.jpg' );
$photo_6     = pmk_get_image_url( '', 'photo-6.jpg' );
$photo_8     = pmk_get_image_url( '', 'photo-8.jpg' );
$photo_10    = pmk_get_image_url( '', 'photo-10.png' );
$wa_foto_1   = pmk_get_image_url( '', 'wa-kegiatan-1.jpg' );
$wa_land_2   = pmk_get_image_url( '', 'wa-landscape-2.jpg' );
$kaligrafi   = pmk_get_image_url( '', 'kaligrafi-muhammadiyah.jpg' );

$anak_kebersamaan_1   = pmk_get_image_url( '', 'anak-kebersamaan-1.jpg' );
$anak_kebersamaan_2   = pmk_get_image_url( '', 'anak-kebersamaan-2.jpg' );
$anak_ceria_landscape = pmk_get_image_url( '', 'anak-ceria-landscape.jpg' );
$anak_ceria_potret    = pmk_get_image_url( '', 'anak-ceria-potret.jpg' );
$anak_edukasi_mushola = pmk_get_image_url( '', 'anak-edukasi-mushola.jpg' );
$anak_kuis_mushola    = pmk_get_image_url( '', 'anak-kuis-mushola.jpg' );
$anak_pembinaan_aula  = pmk_get_image_url( '', 'anak-pembinaan-aula.jpg' );
$bakti_sosial_kepala  = pmk_get_image_url( '', 'bakti-sosial-kepala.jpg' );

$hero_bg_slides = array(
	array(
		'img'   => $anak_ceria_landscape,
		'label' => '01 Kebersamaan Anak Asuh',
		'pos'   => 'center 42%',
	),
	array(
		'img'   => $anak_edukasi_mushola,
		'label' => '02 Edukasi & Mushola',
		'pos'   => 'center 45%',
	),
	array(
		'img'   => $anak_kebersamaan_1,
		'label' => '03 Keluarga Besar Santri',
		'pos'   => 'center 42%',
	),
	array(
		'img'   => $hero_bg,
		'label' => '04 Profil & Lokasi',
		'pos'   => '62% 48%',
	),
	array(
		'img'   => $wa_land_2,
		'label' => '05 Gedung Panti',
		'pos'   => 'center 50%',
	),
);

$slides = array(
	array(
		'img'     => $anak_kebersamaan_1,
		'tag'     => 'Kebersamaan Santri',
		'title'   => 'Kebersamaan Ceria Anak Asuh & Pembina Panti',
		'caption' => 'Suasana penuh kehangatan dan keakraban antara anak asuh, pengurus, serta kakak pembina di aula panti.',
	),
	array(
		'img'     => $anak_edukasi_mushola,
		'tag'     => 'Pembinaan & Motivasi',
		'title'   => 'Kegiatan Edukasi & Motivasi di Mushola Panti',
		'caption' => 'Pembinaan karakter Islami, penguatan mental positif, dan kebersamaan santri putra maupun putri.',
	),
	array(
		'img'     => $panti_4,
		'tag'     => 'Ruang Belajar',
		'title'   => 'Pendampingan IT & Ruang Belajar Anak Asuh',
		'caption' => 'Pembinaan keterampilan teknologi dan tugas sekolah di lingkungan asrama.',
	),
	array(
		'img'     => $anak_ceria_landscape,
		'tag'     => 'Keceriaan Anak Asuh',
		'title'   => 'Senyum Bahagia & Semangat Belajar Santri',
		'caption' => 'Menumbuhkan rasa percaya diri, kegembiraan, dan ukhuwah Islamiyah dalam lingkungan asuh yang penuh kasih.',
	),
	array(
		'img'     => $photo_1,
		'tag'     => 'Bakti Sosial',
		'title'   => 'Kegiatan Bakti Sosial & Silaturahmi Masyarakat',
		'caption' => 'Kolaborasi positif bersama lembaga, mitra sosial, dan masyarakat Purworejo.',
	),
	array(
		'img'     => $anak_kuis_mushola,
		'tag'     => 'Interaksi Keislaman',
		'title'   => 'Sesi Interaktif & Kuis Keislaman Anak Asuh',
		'caption' => 'Melatih keberanian berbicara di depan umum serta memperdalam wawasan keagamaan santri.',
	),
	array(
		'img'     => $wa_land_2,
		'tag'     => 'Gedung & Mushola',
		'title'   => 'Bangunan Utama & Mushola Panti Asuhan',
		'caption' => 'Sarana ibadah dan halaman terbuka yang nyaman bagi kegiatan santri setiap hari.',
	),
	array(
		'img'     => $hero_bg,
		'tag'     => 'Identitas Resmi Lembaga',
		'title'   => 'Papan Nama Resmi Panti Asuhan Muhammadiyah Kaligesing',
		'caption' => 'Berlokasi strategis di Jl. Raya Purworejo–Kaligesing KM 8,5 Dusun Krajan, Desa Kaliharjo.',
	),
);
$orbit_pool = array(
	array(
		'img' => $anak_kebersamaan_1,
		'tag' => 'Kebersamaan Anak Asuh & Pembina',
	),
	array(
		'img' => $anak_edukasi_mushola,
		'tag' => 'Edukasi & Motivasi di Mushola',
	),
	array(
		'img' => $anak_ceria_potret,
		'tag' => 'Keceriaan Santri Putri',
	),
	array(
		'img' => $anak_ceria_landscape,
		'tag' => 'Senyum Bahagia Anak Asuh',
	),
	array(
		'img' => $anak_kuis_mushola,
		'tag' => 'Interaksi & Kuis Keislaman',
	),
	array(
		'img' => $anak_kebersamaan_2,
		'tag' => 'Keluarga Besar Santri Panti',
	),
	array(
		'img' => $anak_pembinaan_aula,
		'tag' => 'Suasana Pembinaan di Aula',
	),
	array(
		'img' => $wa_land_2,
		'tag' => 'Gedung & Mushola Panti',
	),
	array(
		'img' => $hero_bg,
		'tag' => 'Papan Nama Resmi LKSA',
	),
	array(
		'img' => $photo_10,
		'tag' => 'Tapak Suci Santri',
	),
	array(
		'img' => $photo_8,
		'tag' => 'Semarak Ramadan',
	),
	array(
		'img' => $panti_4,
		'tag' => 'Ruang Belajar & IT',
	),
	array(
		'img' => $photo_1,
		'tag' => 'Bakti Sosial Panti',
	),
	array(
		'img' => $bakti_sosial_kepala,
		'tag' => 'Penyerahan Amanah Bakti Sosial',
	),
);
?>

<!-- 1. HERO BANNER LEBIH LEBAR & TURUN + BACKGROUND SLIDESHOW + 3 KOTAK BERPUTAR -->
<section class="pmk-hero">
	<div class="pmk-hero__bg-slider" id="pmk-hero-bg-slider" aria-hidden="true">
		<?php foreach ( $hero_bg_slides as $idx => $bg_slide ) : ?>
			<div
				class="pmk-hero__bg-slide <?php echo 0 === $idx ? 'is-active' : ''; ?>"
				data-hero-bg-index="<?php echo esc_attr( $idx ); ?>"
				style="background-image: url('<?php echo esc_url( $bg_slide['img'] ); ?>'); background-position: <?php echo esc_attr( $bg_slide['pos'] ); ?>;"
			></div>
		<?php endforeach; ?>
	</div>
	<div class="pmk-hero__scrim" aria-hidden="true"></div>

	<div class="pmk-container pmk-container--wide pmk-hero__grid">
		<div class="pmk-hero__head pmk-reveal is-visible">
			<span class="pmk-hero__badge">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="" width="24" height="24" aria-hidden="true">
				<span>Terakreditasi Resmi &bull; Pimpinan Cabang Muhammadiyah Kaligesing</span>
			</span>
			<h1 class="pmk-hero__title">
				Selamat Datang di <span>Panti Asuhan Muhammadiyah Kaligesing</span>
			</h1>
			<p class="pmk-hero__lead">
				Panti Asuhan Muhammadiyah Kaligesing berkomitmen memberikan pengasuhan terbaik, pembentukan karakter Islami, dan menjamin pendidikan anak-anak asuh demi masa depan yang lebih cerah.
			</p>
		</div>

		<!-- 3 KOTAK ELEMEN BERPUTAR POSISI & UKURAN (KOTAK BAWAH BERGANTI FOTO) -->
		<div class="pmk-hero__orbit-wrap pmk-reveal is-visible" style="--delay: 120ms;">
			<div class="pmk-orbit-side-cue" aria-hidden="true">
				<span class="pmk-orbit-side-cue__line"></span>
				<span class="pmk-orbit-side-cue__dot"></span>
			</div>
			<div class="pmk-hero__collage" id="pmk-orbit-stage">
				<button type="button" class="pmk-orbit-card" data-slot="main" data-photo-index="0" aria-label="Foto utama panti">
					<img class="pmk-orbit-card__img is-current" src="<?php echo esc_url( $orbit_pool[0]['img'] ); ?>" alt="<?php echo esc_attr( $orbit_pool[0]['tag'] ); ?>">
					<img class="pmk-orbit-card__img is-next" src="<?php echo esc_url( $orbit_pool[0]['img'] ); ?>" alt="">
					<span class="pmk-orbit-card__tag"><?php echo esc_html( $orbit_pool[0]['tag'] ); ?></span>
					<span class="pmk-orbit-card__live" aria-hidden="true"></span>
				</button>

				<button type="button" class="pmk-orbit-card" data-slot="top" data-photo-index="1" aria-label="Foto kegiatan atas">
					<img class="pmk-orbit-card__img is-current" src="<?php echo esc_url( $orbit_pool[1]['img'] ); ?>" alt="<?php echo esc_attr( $orbit_pool[1]['tag'] ); ?>">
					<img class="pmk-orbit-card__img is-next" src="<?php echo esc_url( $orbit_pool[1]['img'] ); ?>" alt="">
					<span class="pmk-orbit-card__tag"><?php echo esc_html( $orbit_pool[1]['tag'] ); ?></span>
					<span class="pmk-orbit-card__live" aria-hidden="true"></span>
				</button>

				<button type="button" class="pmk-orbit-card" data-slot="bottom" data-photo-index="2" aria-label="Foto kegiatan slideshow bawah">
					<img class="pmk-orbit-card__img is-current" src="<?php echo esc_url( $orbit_pool[2]['img'] ); ?>" alt="<?php echo esc_attr( $orbit_pool[2]['tag'] ); ?>">
					<img class="pmk-orbit-card__img is-next" src="<?php echo esc_url( $orbit_pool[2]['img'] ); ?>" alt="">
					<span class="pmk-orbit-card__tag"><?php echo esc_html( $orbit_pool[2]['tag'] ); ?></span>
					<span class="pmk-orbit-card__live" aria-hidden="true"></span>
				</button>
			</div>
			<script id="pmk-orbit-pool-data" type="application/json"><?php echo json_encode( $orbit_pool, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>
		</div>

		<div class="pmk-hero__body pmk-reveal is-visible" style="--delay: 80ms;">
			<div class="pmk-hero__actions">
				<a class="pmk-btn pmk-btn--gold pmk-btn--sheen pmk-btn--glow" href="#donasi-home">
					<svg class="pmk-icon-pulse" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
					<span>Salurkan Donasi Sekarang</span>
				</a>
				<a class="pmk-btn pmk-btn--ghost-light" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
					<span>Mengenal Panti Lebih Dekat</span>
				</a>
			</div>
			<div class="pmk-hero__meta">
				<span class="pmk-hero__meta-item">
					<svg class="pmk-icon-float" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EAB308" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
					Pengasuhan Amanah &amp; Transparan
				</span>
				<span class="pmk-hero__meta-item">
					<svg class="pmk-icon-float" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EAB308" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
					Desa Kaliharjo, Kaligesing, Purworejo
				</span>
			</div>

			<!-- Kontrol Slide Background Hero -->
			<div class="pmk-hero__bg-pills" aria-label="Pilih foto latar banner">
				<?php foreach ( $hero_bg_slides as $idx => $bg_slide ) : ?>
					<button
						type="button"
						class="pmk-hero__bg-pill <?php echo 0 === $idx ? 'is-active' : ''; ?>"
						data-hero-bg-btn="<?php echo esc_attr( $idx ); ?>"
					>
						<?php echo esc_html( $bg_slide['label'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="pmk-hero__floating-card pmk-reveal is-visible" style="--delay: 180ms;">
			<div class="pmk-hero__floating-info">
				<div class="pmk-hero__floating-label">Layanan &amp; Konsultasi Donasi</div>
				<div class="pmk-hero__floating-val"><?php echo esc_html( $wa_display ); ?></div>
				<div class="pmk-hero__floating-sub">Konfirmasi zakat, infaq &amp; sedekah langsung bersama pengurus</div>
			</div>
			<a class="pmk-btn pmk-btn--wa pmk-btn--sheen" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
				<svg class="pmk-icon-pulse" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
				<span>Chat WhatsApp</span>
			</a>
		</div>
	</div>
</section>

<!-- 2. BENTO GRID: KONTAK WA, ALAMAT, AKREDITASI & HADIS -->
<section class="pmk-section pmk-bento">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-bento__grid">
			<article class="pmk-bento__card pmk-bento__card--wa pmk-reveal">
				<div>
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
					</div>
					<p class="pmk-bento__label" style="margin-top:14px;">Kontak Resmi Aktif</p>
					<h2 class="pmk-bento__title"><?php echo esc_html( $wa_display ); ?></h2>
					<p class="pmk-bento__text">Terhubung langsung dengan pengurus Panti Asuhan Muhammadiyah Kaligesing untuk silaturahmi maupun konfirmasi zakat, infaq, dan sedekah.</p>
				</div>
				<a class="pmk-btn pmk-btn--gold pmk-btn--sheen" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
					Chat WhatsApp Resmi
				</a>
			</article>

			<article class="pmk-bento__card pmk-bento__card--addr pmk-reveal" style="--delay: 80ms;">
				<div>
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
					</div>
					<p class="pmk-bento__label" style="margin-top:14px;">Alamat Lengkap</p>
					<h2 class="pmk-bento__title">Desa Kaliharjo, Kaligesing</h2>
					<p class="pmk-bento__text"><?php echo esc_html( pmk_get_address() ); ?></p>
				</div>
				<a class="pmk-btn pmk-btn--outline" href="<?php echo esc_url( home_url( '/contact/#peta' ) ); ?>">
					Lihat Peta Lokasi
				</a>
			</article>

			<article class="pmk-bento__card pmk-bento__card--accred pmk-reveal" style="--delay: 160ms;">
				<div>
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M12 2l3 6 6.6.9-4.8 4.6 1.1 6.5L12 17l-5.9 3 1.1-6.5L2.4 8.9 9 8z"/></svg>
					</div>
					<p class="pmk-bento__label" style="margin-top:14px;">Terakreditasi Resmi</p>
					<h2 class="pmk-bento__title">Naungan PCM Kaligesing</h2>
					<p class="pmk-bento__text">Berada di bawah naungan Pimpinan Cabang Muhammadiyah Kaligesing dan bergerak aktif dalam pelayanan sosial serta pengasuhan anak yatim secara amanah.</p>
				</div>
				<a class="pmk-btn pmk-btn--outline" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
					Pelajari Profil Lengkap
				</a>
			</article>

			<article class="pmk-bento__card pmk-bento__card--hadith pmk-reveal" style="--delay: 220ms; background-image: linear-gradient(125deg, rgba(7, 41, 26, 0.92), rgba(15, 81, 50, 0.88)), url('<?php echo esc_url( $kaligrafi ); ?>'); background-size: cover; background-position: center;">
				<div class="pmk-hadith__layout">
					<div class="pmk-hadith__emblem">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="Lambang Muhammadiyah" width="64" height="64">
					</div>
					<div>
						<blockquote class="pmk-hadith__quote">
							&ldquo;Sebaik-baik rumah di kalangan orang Islam adalah rumah yang di dalamnya terdapat anak yatim yang diperlakukan dengan baik. Dan seburuk-buruk rumah di kalangan orang Islam adalah rumah yang di dalamnya terdapat anak yatim yang diperlakukan dengan buruk.&rdquo;
						</blockquote>
						<p class="pmk-hadith__source">HR. Ibnu Majah &bull; Landasan Pengasuhan Penuh Kasih Sayang</p>
					</div>
				</div>
			</article>
		</div>
	</div>
</section>

<!-- 3. SATU SECTION PANORAMA TERPADU: TIGA PILAR LAYANAN + MENGENAL PANTI LEBIH DEKAT + ANIMASI HITUNG -->
<section class="pmk-unified-showcase" style="background-image: url('<?php echo esc_url( $wa_land_2 ); ?>');">
	<div class="pmk-unified-showcase__scrim" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow pmk-eyebrow--light">Khidmat Sosial &amp; Komitmen Pengasuhan</span>
			<h2 class="pmk-section-title" style="color:#FFFFFF;">Tiga Pilar Layanan &amp; Amanah Panti</h2>
			<p class="pmk-section-desc" style="color:rgba(255,255,255,0.9);">
				Setiap program dirancang terstruktur untuk memastikan tumbuh kembang spiritual, akademik, dan kemandirian anak asuh.
			</p>
		</div>

		<!-- Strip 4 Khidmat Sosial (Ikon Terpusat Presisi + Marquee Horizontal Halus di Mobile) -->
		<div class="pmk-khidmat-marquee pmk-reveal">
			<div class="pmk-khidmat-strip">
				<div class="pmk-khidmat-pill">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pengasuh</strong>
						<span>Hunian layak &amp; kasih sayang</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pendidikan</strong>
						<span>Sekolah formal &amp; bimbingan</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 3H20v18H6.5A2.5 2.5 0 0 1 4 18.5v-13A2.5 2.5 0 0 1 6.5 3z"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Agama</strong>
						<span>Shalat berjamaah &amp; tahfidz</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"/><line x1="8" y1="20" x2="16" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pelatihan</strong>
						<span>Kreativitas, IT &amp; wirausaha</span>
					</div>
				</div>

				<!-- Clone set untuk animasi horizontal infinite yang mulus khusus mobile -->
				<div class="pmk-khidmat-pill pmk-khidmat-pill--clone" aria-hidden="true">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pengasuh</strong>
						<span>Hunian layak &amp; kasih sayang</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill pmk-khidmat-pill--clone" aria-hidden="true">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pendidikan</strong>
						<span>Sekolah formal &amp; bimbingan</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill pmk-khidmat-pill--clone" aria-hidden="true">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 3H20v18H6.5A2.5 2.5 0 0 1 4 18.5v-13A2.5 2.5 0 0 1 6.5 3z"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Agama</strong>
						<span>Shalat berjamaah &amp; tahfidz</span>
					</div>
				</div>
				<div class="pmk-khidmat-pill pmk-khidmat-pill--clone" aria-hidden="true">
					<span class="pmk-khidmat-pill__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"/><line x1="8" y1="20" x2="16" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/></svg>
					</span>
					<div class="pmk-khidmat-pill__text">
						<strong>Pelatihan</strong>
						<span>Kreativitas, IT &amp; wirausaha</span>
					</div>
				</div>
			</div>
		</div>

		<div class="pmk-cards-3">
			<article class="pmk-pillar-card pmk-pillar-card--photo pmk-reveal">
				<div class="pmk-pillar-card__media">
					<img src="<?php echo esc_url( $anak_edukasi_mushola ); ?>" alt="Membentuk Generasi Islami yang Mandiri">
					<span class="pmk-pillar-card__num">01 &bull; Fasilitas &amp; Karakter</span>
				</div>
				<div class="pmk-pillar-card__body">
					<div class="pmk-pillar-card__top">
						<h3 class="pmk-pillar-card__title">Membentuk Generasi Islami</h3>
						<span class="pmk-badge-icon pmk-icon-anim">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 3H20v18H6.5A2.5 2.5 0 0 1 4 18.5v-13A2.5 2.5 0 0 1 6.5 3z"/></svg>
						</span>
					</div>
					<p class="pmk-pillar-card__desc">Memadukan pendidikan formal, ilmu keagamaan, serta pelatihan keterampilan teknologi informasi dalam lingkungan islami yang aman.</p>
					<ul class="pmk-check-list">
						<li>Asrama yang Nyaman</li>
						<li>Lingkungan Islami &amp; Aman</li>
						<li>Bimbingan Akademik</li>
					</ul>
				</div>
			</article>

			<article class="pmk-pillar-card pmk-pillar-card--photo pmk-reveal" style="--delay: 100ms;">
				<div class="pmk-pillar-card__media">
					<img src="<?php echo esc_url( $anak_ceria_landscape ); ?>" alt="Menjamin Masa Depan Anak Asuh">
					<span class="pmk-pillar-card__num">02 &bull; Misi &amp; Fokus Kami</span>
				</div>
				<div class="pmk-pillar-card__body">
					<div class="pmk-pillar-card__top">
						<h3 class="pmk-pillar-card__title">Menjamin Masa Depan</h3>
						<span class="pmk-badge-icon pmk-icon-anim">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/></svg>
						</span>
					</div>
					<p class="pmk-pillar-card__desc">Penyaluran bantuan komprehensif mulai dari pemenuhan gizi seimbang, fasilitas belajar memadai, hingga pembentukan mental berakhlak mulia.</p>
					<ul class="pmk-check-list">
						<li>Pemenuhan Gizi &amp; Kesehatan</li>
						<li>Beasiswa Pendidikan Formal</li>
						<li>Pembentukan Mental Islami</li>
					</ul>
				</div>
			</article>

			<article class="pmk-pillar-card pmk-pillar-card--photo pmk-reveal" style="--delay: 200ms;">
				<div class="pmk-pillar-card__media">
					<img src="<?php echo esc_url( $photo_1 ); ?>" alt="Menyalurkan Amanah Bersama Donatur">
					<span class="pmk-pillar-card__num">03 &bull; Transparansi &amp; Donasi</span>
				</div>
				<div class="pmk-pillar-card__body">
					<div class="pmk-pillar-card__top">
						<h3 class="pmk-pillar-card__title">Menyalurkan Amanah</h3>
						<span class="pmk-badge-icon pmk-icon-anim">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
						</span>
					</div>
					<p class="pmk-pillar-card__desc">Seluruh dana zakat, infaq, dan sedekah dikelola secara terbuka dan disalurkan sepenuhnya untuk operasional serta pendidikan anak asuh.</p>
					<ul class="pmk-check-list">
						<li>Donasi Operasional Bulanan</li>
						<li>Sembako &amp; Kebutuhan Pokok</li>
						<li>Beasiswa Orang Tua Asuh</li>
					</ul>
				</div>
			</article>
		</div>

		<!-- MENGENAL PANTI LEBIH DEKAT + ANIMASI HITUNG DI DALAM SATU BACKGROUND TERPADU -->
		<div class="pmk-unified-stats">
			<div class="pmk-section-head pmk-section-head--center pmk-reveal" style="margin-bottom:32px;">
				<span class="pmk-eyebrow pmk-eyebrow--light">Mengenal Panti Lebih Dekat</span>
				<h2 class="pmk-section-title" style="color:#FFFFFF;">Mentransformasi Pelayanan Publik secara Digital demi Mencetak Generasi Islami yang Mandiri</h2>
				<p class="pmk-section-desc" style="color:rgba(255,255,255,0.88);">
					Rekam jejak pengabdian dan amanah pengasuhan Panti Asuhan Muhammadiyah Kaligesing bersama para donatur.
				</p>
			</div>

			<div class="pmk-counter-grid pmk-counter-grid--unified pmk-reveal">
				<article class="pmk-counter-card">
					<div class="pmk-counter-card__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
					</div>
					<div class="pmk-counter-card__val" data-counter-target="24" data-counter-suffix="+">0+</div>
					<div class="pmk-counter-card__bar"></div>
					<div class="pmk-counter-card__label">Anak Asuh</div>
					<p class="pmk-counter-card__sub">Tinggal &amp; dibina penuh di asrama panti</p>
				</article>

				<article class="pmk-counter-card">
					<div class="pmk-counter-card__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
					</div>
					<div class="pmk-counter-card__val" data-counter-target="15" data-counter-suffix="+">0+</div>
					<div class="pmk-counter-card__bar"></div>
					<div class="pmk-counter-card__label">Anak Binaan</div>
					<p class="pmk-counter-card__sub">Pendampingan santri luar asrama</p>
				</article>

				<article class="pmk-counter-card">
					<div class="pmk-counter-card__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2.5 9 2.5 12 0v-5"/></svg>
					</div>
					<div class="pmk-counter-card__val" data-counter-target="18" data-counter-suffix="+">0+</div>
					<div class="pmk-counter-card__bar"></div>
					<div class="pmk-counter-card__label">Alumni Terbina</div>
					<p class="pmk-counter-card__sub">Telah mandiri &amp; berkarya di masyarakat</p>
				</article>

				<article class="pmk-counter-card">
					<div class="pmk-counter-card__icon pmk-icon-anim">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
					</div>
					<div class="pmk-counter-card__val" data-counter-target="5" data-counter-suffix="+">0+</div>
					<div class="pmk-counter-card__bar"></div>
					<div class="pmk-counter-card__label">Tahun Mengabdi</div>
					<p class="pmk-counter-card__sub">Konsisten melayani umat &amp; anak yatim</p>
				</article>
			</div>
		</div>
	</div>
</section>

<!-- 4. RINGKASAN KEGIATAN & DOKUMENTASI (LATAR BERSIH + SLIDESHOW INTERAKTIF + KARTU AKTIVITAS) -->
<section class="pmk-section pmk-section--tint">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow">Aktivitas &amp; Dokumentasi Nyata</span>
			<h2 class="pmk-section-title">Ringkasan Kegiatan &amp; Dokumentasi Panti</h2>
			<p class="pmk-section-desc">
				Galeri aktivitas pembinaan harian, bakti sosial, dan kehidupan asrama anak-anak asuh di Panti Asuhan Muhammadiyah Kaligesing.
			</p>
		</div>

		<div class="pmk-showcase-split">
			<!-- SLIDESHOW INTERAKTIF -->
			<div class="pmk-slideshow pmk-reveal" id="pmk-activity-slideshow" aria-roledescription="carousel" aria-label="Slideshow Dokumentasi Kegiatan Panti">
				<div class="pmk-slideshow__viewport">
					<?php foreach ( $slides as $idx => $slide ) : ?>
						<figure class="pmk-slide <?php echo 0 === $idx ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $idx ); ?>">
							<img src="<?php echo esc_url( $slide['img'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>">
							<figcaption class="pmk-slide__caption">
								<span class="pmk-slide__tag"><?php echo esc_html( $slide['tag'] ); ?></span>
								<h3 class="pmk-slide__title"><?php echo esc_html( $slide['title'] ); ?></h3>
								<p class="pmk-slide__desc"><?php echo esc_html( $slide['caption'] ); ?></p>
							</figcaption>
						</figure>
					<?php endforeach; ?>

					<div class="pmk-slideshow__counter" id="pmk-slide-counter">01 / 0<?php echo count( $slides ); ?></div>

					<button type="button" class="pmk-slideshow__nav pmk-slideshow__nav--prev" id="pmk-slide-prev" aria-label="Foto sebelumnya">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
					</button>
					<button type="button" class="pmk-slideshow__nav pmk-slideshow__nav--next" id="pmk-slide-next" aria-label="Foto berikutnya">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
					</button>
				</div>

				<div class="pmk-slideshow__dots" role="tablist" aria-label="Pilih slide dokumentasi">
					<?php foreach ( $slides as $idx => $slide ) : ?>
						<button type="button" class="pmk-slideshow__dot <?php echo 0 === $idx ? 'is-active' : ''; ?>" data-slide-dot="<?php echo esc_attr( $idx ); ?>" aria-label="Tampilkan slide <?php echo esc_attr( $idx + 1 ); ?>"></button>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- 4 KARTU AKTIVITAS INTERAKTIF -->
			<div class="pmk-activity-stack pmk-reveal" style="--delay: 120ms;">
				<article class="pmk-activity-card pmk-activity-card--interactive" data-slide-jump="0">
					<div class="pmk-activity-card__media">
						<img src="<?php echo esc_url( $anak_kebersamaan_1 ); ?>" alt="Kebersamaan Ceria Anak Asuh Panti">
					</div>
					<div class="pmk-activity-card__body">
						<div class="pmk-activity-card__meta">
							<span class="pmk-activity-card__tag">Kebersamaan</span>
							<span class="pmk-activity-card__pill">Slide 01</span>
						</div>
						<h3 class="pmk-activity-card__title">Kebersamaan Anak Asuh &amp; Pembina</h3>
						<p class="pmk-activity-card__desc">Suasana hangat penuh keceriaan bersama santri dan pembina di aula panti.</p>
					</div>
				</article>

				<article class="pmk-activity-card pmk-activity-card--interactive" data-slide-jump="1">
					<div class="pmk-activity-card__media">
						<img src="<?php echo esc_url( $anak_edukasi_mushola ); ?>" alt="Pembinaan dan Motivasi di Mushola Panti">
					</div>
					<div class="pmk-activity-card__body">
						<div class="pmk-activity-card__meta">
							<span class="pmk-activity-card__tag">Pembinaan</span>
							<span class="pmk-activity-card__pill">Slide 02</span>
						</div>
						<h3 class="pmk-activity-card__title">Edukasi &amp; Motivasi di Mushola</h3>
						<p class="pmk-activity-card__desc">Bimbingan karakter Islami, penguatan mental, dan motivasi belajar rutin.</p>
					</div>
				</article>

				<article class="pmk-activity-card pmk-activity-card--interactive" data-slide-jump="2">
					<div class="pmk-activity-card__media">
						<img src="<?php echo esc_url( $panti_4 ); ?>" alt="Pendampingan Belajar dan IT Santri">
					</div>
					<div class="pmk-activity-card__body">
						<div class="pmk-activity-card__meta">
							<span class="pmk-activity-card__tag">Pendidikan</span>
							<span class="pmk-activity-card__pill">Slide 03</span>
						</div>
						<h3 class="pmk-activity-card__title">Pendampingan Sekolah &amp; Belajar IT</h3>
						<p class="pmk-activity-card__desc">Jam belajar terstruktur bersama pembina untuk tugas sekolah dan literasi komputer.</p>
					</div>
				</article>

				<article class="pmk-activity-card pmk-activity-card--interactive" data-slide-jump="4">
					<div class="pmk-activity-card__media">
						<img src="<?php echo esc_url( $photo_1 ); ?>" alt="Bakti Sosial dan Kunjungan Tamu">
					</div>
					<div class="pmk-activity-card__body">
						<div class="pmk-activity-card__meta">
							<span class="pmk-activity-card__tag">Sosial &amp; Tamu</span>
							<span class="pmk-activity-card__pill">Slide 05</span>
						</div>
						<h3 class="pmk-activity-card__title">Silaturahmi &amp; Bakti Sosial</h3>
						<p class="pmk-activity-card__desc">Penerimaan kunjungan donatur, santunan berkala, serta kegiatan sosial masyarakat.</p>
					</div>
				</article>
			</div>
		</div>

		<div style="margin-top:32px;text-align:center;">
			<a class="pmk-btn pmk-btn--primary pmk-btn--sheen" href="<?php echo esc_url( home_url( '/facilities/' ) ); ?>">
				<span>Lihat Seluruh Fasilitas &amp; Galeri Panti</span>
			</a>
		</div>
	</div>
</section>

<!-- 5. SECTION INFO REKENING DONASI DENGAN BACKGROUND FOTO BERSAMA ANAK ASUH -->
<section id="donasi-home" class="pmk-section pmk-photo-band pmk-photo-band--donation" style="background-image: url('<?php echo esc_url( $anak_kebersamaan_1 ); ?>'); background-position: center 55%;">
	<div class="pmk-photo-band__scrim pmk-photo-band__scrim--warm" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<div class="pmk-donation-box pmk-reveal">
			<div>
				<span class="pmk-eyebrow pmk-eyebrow--light">Penyaluran Donasi Resmi</span>
				<h2 class="pmk-donation-box__title">Mari Berbagi Kebaikan untuk Masa Depan Anak Yatim &amp; Dhuafa</h2>
				<p class="pmk-donation-box__desc">
					Dukungan zakat, infaq, sedekah, maupun wakaf dari Bapak/Ibu sangat berarti bagi biaya pendidikan, konsumsi bergizi, dan kebutuhan asrama anak-anak asuh kami.
				</p>
				<div class="pmk-donation-box__actions">
					<a class="pmk-btn pmk-btn--gold pmk-btn--sheen pmk-btn--glow" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
						Konfirmasi Donasi via WhatsApp
					</a>
					<a class="pmk-btn pmk-btn--ghost-light" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
						Buka Halaman Kontak
					</a>
				</div>
			</div>

			<div class="pmk-bank-card">
				<div class="pmk-bank-card__head">
					<span class="pmk-bank-card__bank"><?php echo esc_html( $bank['bank_name'] ); ?></span>
					<span class="pmk-bank-card__badge">Rekening Resmi Panti</span>
				</div>
				<div class="pmk-bank-card__num-row">
					<span class="pmk-bank-card__number"><?php echo esc_html( $bank['account_no'] ); ?></span>
					<button type="button" class="pmk-copy-btn" data-copy-target="<?php echo esc_attr( $bank['account_no'] ); ?>">
						Salin Rekening
					</button>
				</div>
				<p class="pmk-bank-card__holder">a.n. <strong><?php echo esc_html( $bank['holder_name'] ); ?></strong></p>
				<p class="pmk-bank-card__note"><?php echo esc_html( $bank['note'] ); ?></p>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
