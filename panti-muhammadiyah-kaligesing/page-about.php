<?php
/**
 * Template Name: Halaman About (Profil Panti)
 * Memuat: Sambutan Kepala Panti (H. Johan Martono), Animasi Hitung, Sejarah Singkat,
 * Visi & Misi (1 Visi + 4 Misi), Semboyan, 4 Pilar Program, dan Struktur Organisasi Lengkap.
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

get_header();

$hero_about   = pmk_get_image_url( '', 'anak-kebersamaan-1.jpg' );
$kepala_photo = pmk_get_image_url( 'pmk_img_kepala', 'photo-8.jpg' );
$visi_bg      = pmk_get_image_url( '', 'anak-edukasi-mushola.jpg' );
?>

<section class="pmk-page-hero pmk-page-hero--photo" style="background-image: url('<?php echo esc_url( $hero_about ); ?>');">
	<div class="pmk-page-hero__scrim" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<nav class="pmk-breadcrumb" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
			<span aria-hidden="true">/</span>
			<span>Tentang Kami</span>
		</nav>
		<h1 class="pmk-page-hero__title">Profil Panti Asuhan Muhammadiyah Kaligesing</h1>
		<p class="pmk-page-hero__desc">
			Mengenal lebih dekat amanah pengasuhan, sejarah pendirian, visi dan misi, empat pilar pembinaan, serta jajaran pengurus resmi LKSA / MCC Pimpinan Cabang Muhammadiyah Kaligesing.
		</p>
	</div>
</section>

<!-- 1. SAMBUTAN KEPALA PANTI -->
<section class="pmk-section">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-sambutan pmk-reveal">
			<div class="pmk-sambutan__photo">
				<img src="<?php echo esc_url( $kepala_photo ); ?>" alt="H. Johan Martono - Kepala Panti Asuhan Muhammadiyah Kaligesing">
			</div>
			<div>
				<span class="pmk-eyebrow">Sambutan Kepala Panti</span>
				<h2 class="pmk-section-title" style="margin-bottom:16px;">Mengasuh dengan Hati, Membekali dengan Ilmu</h2>
				<blockquote class="pmk-sambutan__quote">
					&ldquo;Assalamu&rsquo;alaikum warahmatullahi wabarakatuh. Panti Asuhan Muhammadiyah Kaligesing berkomitmen penuh dalam menyediakan lingkungan pengasuhan yang aman, islami, dan edukatif bagi anak-anak yatim serta dhuafa. Melalui integrasi teknologi dan keterbukaan informasi, kami berharap dapat terus menyebarkan syiar kebaikan serta mencetak generasi yang mandiri dan berakhlak mulia di wilayah Purworejo.&rdquo;
				</blockquote>
				<p style="margin:0;color:var(--pmk-text-muted);">
					Kami mengucapkan terima kasih kepada seluruh donatur, dermawan, serta warga persyarikatan Muhammadiyah yang senantiasa menyalurkan amanah dan dukungannya demi masa depan anak-anak asuh kami.
				</p>
				<div class="pmk-sambutan__author">
					<p class="pmk-sambutan__name">H. Johan Martono</p>
					<p class="pmk-sambutan__role">Kepala Panti Asuhan Muhammadiyah Kaligesing</p>
				</div>
			</div>
		</div>

		<!-- ANIMASI HITUNG PROFIL PANTI -->
		<div class="pmk-counter-grid pmk-counter-grid--about pmk-reveal" style="margin-top:34px;">
			<article class="pmk-counter-card">
				<div class="pmk-counter-card__val" data-counter-target="3" data-counter-suffix="+">0+</div>
				<div class="pmk-counter-card__bar"></div>
				<div class="pmk-counter-card__label">Gedung Asrama Utama</div>
			</article>
			<article class="pmk-counter-card">
				<div class="pmk-counter-card__val" data-counter-target="17" data-counter-suffix="+">0+</div>
				<div class="pmk-counter-card__bar"></div>
				<div class="pmk-counter-card__label">Pengurus &amp; Pengasuh</div>
			</article>
			<article class="pmk-counter-card">
				<div class="pmk-counter-card__val" data-counter-target="35" data-counter-suffix="+">0+</div>
				<div class="pmk-counter-card__bar"></div>
				<div class="pmk-counter-card__label">Anak Asuh &amp; Binaan</div>
			</article>
			<article class="pmk-counter-card">
				<div class="pmk-counter-card__val" data-counter-target="10" data-counter-suffix="+">0+</div>
				<div class="pmk-counter-card__bar"></div>
				<div class="pmk-counter-card__label">Tahun Mengabdi</div>
			</article>
		</div>
	</div>
</section>

<!-- 2. SEJARAH SINGKAT & SEMBOYAN -->
<section class="pmk-section pmk-section--tint">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-history-grid">
			<article class="pmk-history-card pmk-reveal">
				<span class="pmk-eyebrow">Sejarah Singkat</span>
				<h2 class="pmk-section-title">Lahir dari Semangat Dakwah &amp; Teologi Al-Ma&rsquo;un</h2>
				<p style="color:var(--pmk-text-muted);margin-bottom:14px;">
					Panti Asuhan Yatim &amp; Dhu&rsquo;afa Muhammadiyah Kaligesing berdiri di bawah naungan Pimpinan Cabang Muhammadiyah (PCM) Kaligesing sebagai wujud kepedulian nyata terhadap pemenuhan hak hidup, pengasuhan, dan pendidikan anak-anak yatim, piatu, serta keluarga kurang mampu di Kabupaten Purworejo.
				</p>
				<p style="color:var(--pmk-text-muted);margin:0;">
					Berlokasi di Jl. Raya Purworejo&ndash;Kaligesing KM 8,5 Dusun Krajan, Desa Kaliharjo, lembaga kesejahteraan sosial anak (LKSA / MCC) ini memadukan pendidikan formal di sekolah dengan pembinaan karakter Islami di lingkungan asrama.
				</p>
			</article>

			<aside class="pmk-semboyan-card pmk-reveal" style="--delay:100ms;">
				<span class="pmk-eyebrow pmk-eyebrow--light">Semboyan Pengasuhan</span>
				<blockquote class="pmk-semboyan-card__quote">
					&ldquo;Mendidik dengan Hati, Mengasuh dengan Nyata, Menjemput Masa Depan yang Mulia.&rdquo;
				</blockquote>
				<p style="margin:0;color:rgba(255,255,255,0.84);font-size:0.94rem;">
					Menjadikan setiap ruang asrama sebagai rumah kedua yang hangat, berdisiplin, dan menumbuhkan harapan masa depan bagi seluruh santri.
				</p>
			</aside>
		</div>
	</div>
</section>

<!-- 3. VISI & MISI (DENGAN FOTO LATAR + DARK OVERLAY KONSISTEN) -->
<section class="pmk-section pmk-photo-band" style="background-image: url('<?php echo esc_url( $visi_bg ); ?>');">
	<div class="pmk-photo-band__scrim" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow pmk-eyebrow--light">Arah &amp; Tujuan Lembaga</span>
			<h2 class="pmk-section-title" style="color:#FFFFFF;">Visi &amp; Misi Panti Asuhan</h2>
			<p class="pmk-section-desc" style="color:rgba(255,255,255,0.86);">Berlandaskan nilai keislaman dan persyarikatan Muhammadiyah untuk mencetak generasi unggul.</p>
		</div>

		<div class="pmk-visi-misi-grid">
			<div class="pmk-solid-dark-card pmk-reveal">
				<span class="pmk-eyebrow pmk-eyebrow--light">Visi Resmi</span>
				<h3 style="margin:0 0 14px;font-size:1.35rem;color:#FACC15;">1 Visi Utama</h3>
				<p style="margin:0;font-family:var(--pmk-font-serif);font-size:1.25rem;line-height:1.65;color:#FFFFFF;">
					&ldquo;Menjadi Lembaga Kesejahteraan Sosial Anak (LKSA) yang unggul dalam membentuk karakter santri yang religius, berintegritas, mandiri, dan responsif terhadap perkembangan zaman digital.&rdquo;
				</p>
			</div>

			<div class="pmk-solid-dark-card pmk-reveal" style="--delay:100ms;">
				<span class="pmk-eyebrow pmk-eyebrow--light">Misi Resmi</span>
				<h3 style="margin:0 0 16px;font-size:1.35rem;color:#FACC15;">4 Misi Strategis</h3>
				<ol class="pmk-misi-list">
					<li>
						<span class="pmk-misi-list__num">1</span>
						<span>Menyelenggarakan pengasuhan dan pembinaan berbasis nilai-nilai Islami berlandaskan Al-Qur&rsquo;an dan As-Sunnah.</span>
					</li>
					<li>
						<span class="pmk-misi-list__num">2</span>
						<span>Menyediakan sarana pendidikan formal maupun non-formal yang layak bagi anak yatim dan dhuafa.</span>
					</li>
					<li>
						<span class="pmk-misi-list__num">3</span>
						<span>Membekali santri dengan keterampilan digital, kreativitas, dan kemandirian hidup sebagai bekal masa depan.</span>
					</li>
					<li>
						<span class="pmk-misi-list__num">4</span>
						<span>Mewujudkan tata kelola panti asuhan yang amanah, transparan, akuntabel, dan bersinergi bersama masyarakat.</span>
					</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<!-- 4. EMPAT PILAR PROGRAM PANTI -->
<section class="pmk-section">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-reveal">
			<span class="pmk-eyebrow">Pembinaan Terpadu</span>
			<h2 class="pmk-section-title">4 Pilar Program Panti Asuhan</h2>
			<p class="pmk-section-desc">Setiap anak asuh dibimbing melalui empat pilar program utama yang berjalan berkesinambungan.</p>
		</div>

		<div class="pmk-programs-4">
			<article class="pmk-program-card pmk-reveal">
				<div class="pmk-program-card__head">
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2L15 8l6 1-4.5 4.2L17.6 20 12 17l-5.6 3 1.1-6.8L3 9l6-1z"/></svg>
					</div>
					<div>
						<span style="font-size:0.78rem;font-weight:800;color:var(--pmk-emerald-800);text-transform:uppercase;">Pilar 01</span>
						<h3 style="margin:0;font-size:1.2rem;">Keagamaan</h3>
					</div>
				</div>
				<p style="margin:0 0 12px;color:var(--pmk-text-muted);">Pembentukan karakter islami melalui salat berjamaah, tahfidz Al-Qur&rsquo;an, dan kajian rutin santri.</p>
				<ul class="pmk-program-card__list">
					<li>Shalat fardhu berjamaah lima waktu di Mushola Panti</li>
					<li>Tahsin, tadarus rutin, dan program Tahfidz Al-Qur&rsquo;an</li>
					<li>Kajian akidah, akhlak, dan Kemuhammadiyahan</li>
				</ul>
			</article>

			<article class="pmk-program-card pmk-reveal" style="--delay:80ms;">
				<div class="pmk-program-card__head">
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
					</div>
					<div>
						<span style="font-size:0.78rem;font-weight:800;color:var(--pmk-emerald-800);text-transform:uppercase;">Pilar 02</span>
						<h3 style="margin:0;font-size:1.2rem;">Pendidikan</h3>
					</div>
				</div>
				<p style="margin:0 0 12px;color:var(--pmk-text-muted);">Menjamin kelangsungan edukasi formal santri dari tingkat dasar hingga jenjang pendidikan tinggi.</p>
				<ul class="pmk-program-card__list">
					<li>Beasiswa penuh sekolah formal SD, SMP, hingga SMA/SMK</li>
					<li>Pendampingan belajar malam dan bimbingan PR</li>
					<li>Penguatan literasi dan keterampilan komputer</li>
				</ul>
			</article>

			<article class="pmk-program-card pmk-reveal" style="--delay:160ms;">
				<div class="pmk-program-card__head">
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
					</div>
					<div>
						<span style="font-size:0.78rem;font-weight:800;color:var(--pmk-emerald-800);text-transform:uppercase;">Pilar 03</span>
						<h3 style="margin:0;font-size:1.2rem;">Kemandirian</h3>
					</div>
				</div>
				<p style="margin:0 0 12px;color:var(--pmk-text-muted);">Pemberian pelatihan keterampilan hidup (<em>life skills</em>) dan kewirausahaan untuk bekal masa depan.</p>
				<ul class="pmk-program-card__list">
					<li>Pembiasaan kemandirian harian, kedisiplinan, dan tanggung jawab</li>
					<li>Pelatihan keterampilan praktis dan kreativitas santri</li>
					<li>Pengelolaan kebersihan lingkungan asrama</li>
				</ul>
			</article>

			<article class="pmk-program-card pmk-reveal" style="--delay:240ms;">
				<div class="pmk-program-card__head">
					<div class="pmk-bento__icon pmk-icon-anim">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
					</div>
					<div>
						<span style="font-size:0.78rem;font-weight:800;color:var(--pmk-emerald-800);text-transform:uppercase;">Pilar 04</span>
						<h3 style="margin:0;font-size:1.2rem;">Sosial &amp; Kesehatan</h3>
					</div>
				</div>
				<p style="margin:0 0 12px;color:var(--pmk-text-muted);">Pelayanan jaminan pemeliharaan kesehatan fisik, mental, serta ruang hidup yang layak bagi seluruh santri.</p>
				<ul class="pmk-program-card__list">
					<li>Pemenuhan konsumsi harian yang sehat dan bergizi seimbang</li>
					<li>Pemeriksaan kesehatan santri secara berkala</li>
					<li>Layanan santunan yatim dan bakti sosial masyarakat</li>
				</ul>
			</article>
		</div>
	</div>
</section>

<!-- 5. BAGAN STRUKTUR ORGANISASI HIERARKIS (SESUAI DATA RESMI PENGURUS LKSA / MCC) -->
<section id="struktur-organisasi" class="pmk-section pmk-section--tint">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-section-head pmk-section-head--center pmk-reveal">
			<span class="pmk-eyebrow">Manajemen Lembaga</span>
			<h2 class="pmk-section-title">Bagan Struktur Organisasi Pengurus</h2>
			<p class="pmk-section-desc">Susunan hierarki pengurus resmi LKSA / MCC Panti Asuhan Yatim &amp; Dhu&rsquo;afa Muhammadiyah Kaligesing.</p>
		</div>

		<div class="pmk-org-chart pmk-reveal">
			<!-- LEVEL 0: PENAUNG -->
			<div class="pmk-org-tier pmk-org-tier--root">
				<article class="pmk-org-node pmk-org-node--root">
					<span class="pmk-org-node__badge">Pelindung &amp; Penyelenggara</span>
					<h3 class="pmk-org-node__name">Pimpinan Cabang Muhammadiyah (PCM) Kaligesing</h3>
					<p class="pmk-org-node__sub">Majelis Pelayanan Kesejahteraan Sosial (MPKS)</p>
				</article>
			</div>

			<div class="pmk-org-stem" aria-hidden="true"></div>

			<!-- LEVEL 1: PIMPINAN UTAMA -->
			<div class="pmk-org-tier pmk-org-tier--leaders">
				<article class="pmk-org-node pmk-org-node--chief">
					<span class="pmk-org-node__badge">Kepala Panti</span>
					<h3 class="pmk-org-node__name">H. Johan Martono</h3>
					<p class="pmk-org-node__sub">Pimpinan &amp; Penanggung Jawab Operasional</p>
				</article>

				<div class="pmk-org-bridge" aria-hidden="true">
					<span class="pmk-org-bridge__dot"></span>
				</div>

				<article class="pmk-org-node pmk-org-node--deputy">
					<span class="pmk-org-node__badge">Wakil Kepala</span>
					<h3 class="pmk-org-node__name">Iwan Kusni Surahman</h3>
					<p class="pmk-org-node__sub">Koordinator Harian &amp; Pendampingan Program</p>
				</article>
			</div>

			<div class="pmk-org-stem" aria-hidden="true"></div>

			<!-- LEVEL 2: PENGELOLA INTI (3 CABANG) -->
			<div class="pmk-org-branch-3">
				<article class="pmk-org-node pmk-org-node--core">
					<span class="pmk-org-node__badge">Sekretaris</span>
					<h3 class="pmk-org-node__name">Harsiwi Wismayanti</h3>
					<p class="pmk-org-node__sub">Kesekretariatan &amp; Administrasi Data</p>
				</article>

				<article class="pmk-org-node pmk-org-node--core pmk-org-node--core-highlight">
					<span class="pmk-org-node__badge">Kepala Asrama</span>
					<h3 class="pmk-org-node__name">Hj. Arifatul Aini</h3>
					<p class="pmk-org-node__sub">Pengawasan Harian Asrama &amp; Santri</p>
				</article>

				<article class="pmk-org-node pmk-org-node--core">
					<span class="pmk-org-node__badge">Bendahara</span>
					<h3 class="pmk-org-node__name">1. Sumardi &bull; 2. Sunaryo</h3>
					<p class="pmk-org-node__sub">Keuangan, Akuntansi &amp; Pelaporan Donasi</p>
				</article>
			</div>

			<div class="pmk-org-stem pmk-org-stem--with-label">
				<span class="pmk-org-spine-pill">Garis Koordinasi 7 Bidang Pengurus</span>
			</div>

			<!-- LEVEL 3: 7 BIDANG PELAKSANA -->
			<div class="pmk-org-dept-grid">
				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 01</span>
						<h4 class="pmk-org-dept__title">Humas &amp; Usaha</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Sanjaya Ancas Mahardika</span>
						<span class="pmk-member-chip">Agung Mulyanto</span>
						<span class="pmk-member-chip">Supardiyono</span>
					</div>
				</article>

				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 02</span>
						<h4 class="pmk-org-dept__title">Pendidikan &amp; Pelatihan</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Sudiyono</span>
						<span class="pmk-member-chip">Edy Prasetyo</span>
					</div>
				</article>

				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 03</span>
						<h4 class="pmk-org-dept__title">Kerohanian</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Bambang Edy</span>
						<span class="pmk-member-chip">Triman</span>
					</div>
				</article>

				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 04</span>
						<h4 class="pmk-org-dept__title">Sarana Prasarana</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Teguh</span>
						<span class="pmk-member-chip">Sutarko Hantoro</span>
					</div>
				</article>

				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 05</span>
						<h4 class="pmk-org-dept__title">Pengasuhan &amp; Rumah Tangga</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Istiqomah</span>
						<span class="pmk-member-chip">Dwi Yanto</span>
					</div>
				</article>

				<article class="pmk-org-dept">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 06</span>
						<h4 class="pmk-org-dept__title">Sosial &amp; Kesehatan</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Hj. Mugiati</span>
					</div>
				</article>

				<article class="pmk-org-dept pmk-org-dept--wide">
					<div class="pmk-org-dept__head">
						<span class="pmk-org-dept__code">Bidang 07</span>
						<h4 class="pmk-org-dept__title">Identifikasi Klien</h4>
					</div>
					<div class="pmk-org-dept__members">
						<span class="pmk-member-chip">Hj. Arifatul Aini</span>
						<span class="pmk-member-chip">Harsiwi Wismayanti</span>
					</div>
				</article>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
