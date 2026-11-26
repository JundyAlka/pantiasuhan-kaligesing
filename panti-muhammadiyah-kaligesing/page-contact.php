<?php
/**
 * Template Name: Halaman Contact (Kontak & Donasi)
 * Memuat:
 * - Hero Banner dengan foto landscape asli
 * - Formulir Kontak Standar (Nama, Email, Subjek, Pesan, Submit) sesuai Flowchart Gambar 3.2 & Tabel 4.1
 * - Validasi kolom wajib + penyimpanan ke database WordPress
 * - Tombol Chat WhatsApp (Nomor Tunggal)
 * - Alamat Lengkap & Embed Google Maps
 * - Info Rekening Donasi Resmi (Bank BRI 684301006299530 a.n. PAY DAN DM KALIGESING)
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

$submission = pmk_process_contact_submission();

get_header();

$hero_contact = pmk_get_image_url( '', 'photo-10.png' );
$bank         = pmk_get_donation_info();
$wa_display   = pmk_get_wa_display();
$wa_url       = pmk_get_wa_url( 'Assalamualaikum, saya ingin menghubungi Panti Asuhan Muhammadiyah Kaligesing.' );
$address      = pmk_get_address();
$wpforms_id   = absint( get_theme_mod( 'pmk_wpforms_id', 0 ) );
$use_wpforms  = ( $wpforms_id > 0 && function_exists( 'shortcode_exists' ) && shortcode_exists( 'wpforms' ) );
?>

<section class="pmk-page-hero pmk-page-hero--photo" style="background-image: url('<?php echo esc_url( $hero_contact ); ?>');">
	<div class="pmk-page-hero__scrim" aria-hidden="true"></div>
	<div class="pmk-container pmk-container--wide" style="position:relative;z-index:2;">
		<nav class="pmk-breadcrumb" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
			<span aria-hidden="true">/</span>
			<span>Kontak</span>
		</nav>
		<h1 class="pmk-page-hero__title">Kontak &amp; Saluran Donasi Resmi</h1>
		<p class="pmk-page-hero__desc">
			Panti Asuhan Muhammadiyah Kaligesing siap menerima silaturahmi, kunjungan, maupun penyaluran donasi dari Anda demi masa depan santri yang lebih baik.
		</p>
	</div>
</section>

<section class="pmk-section">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-contact-grid">
			<!-- KOLOM KIRI: FORMULIR KONTAK -->
			<div class="pmk-contact-card pmk-reveal">
				<span class="pmk-eyebrow">Formulir Kontak Resmi</span>
				<h2 class="pmk-section-title" style="font-size:1.65rem;">Kirim Pesan ke Pengurus Panti</h2>
				<p style="margin:0 0 22px;color:var(--pmk-text-muted);font-size:0.95rem;">
					Kolom bertanda bintang (<span class="pmk-form-req">*</span>) wajib diisi. Setiap pesan yang valid akan langsung tersimpan aman ke dalam database WordPress.
				</p>

				<?php if ( $use_wpforms ) : ?>
					<div class="wpforms-container">
						<?php echo do_shortcode( '[wpforms id="' . $wpforms_id . '"]' ); ?>
					</div>
				<?php else : ?>
					<?php if ( 'success' === $submission['status'] ) : ?>
						<div class="pmk-form-alert pmk-form-alert--success" role="status">
							<?php echo esc_html( $submission['notice'] ); ?>
						</div>
					<?php elseif ( 'error' === $submission['status'] ) : ?>
						<div class="pmk-form-alert pmk-form-alert--error" role="alert">
							<?php echo esc_html( $submission['notice'] ); ?>
						</div>
					<?php endif; ?>

					<div id="pmk-client-alert" class="pmk-form-alert pmk-form-alert--error" role="alert" hidden></div>

					<form id="pmk-contact-form" class="wpforms-container" method="post" action="<?php echo esc_url( home_url( '/contact/' ) ); ?>" novalidate>
						<input type="hidden" name="pmk_contact_action" value="1">
						<?php
						if ( function_exists( 'wp_nonce_field' ) ) {
							wp_nonce_field( 'pmk_submit_contact', 'pmk_contact_nonce' );
						}
						?>

						<div class="pmk-form-field">
							<label class="pmk-form-label" for="pmk_name">
								Nama Lengkap <span class="pmk-form-req">*</span>
							</label>
							<input
								type="text"
								id="pmk_name"
								name="pmk_name"
								class="pmk-form-input <?php echo isset( $submission['errors']['name'] ) ? 'is-invalid' : ''; ?>"
								value="<?php echo esc_attr( $submission['values']['name'] ); ?>"
								placeholder="Contoh: Ahmad Fauzi"
								required
							>
							<p id="pmk_name_error" class="pmk-field-error"><?php echo isset( $submission['errors']['name'] ) ? esc_html( $submission['errors']['name'] ) : ''; ?></p>
						</div>

						<div class="pmk-form-field">
							<label class="pmk-form-label" for="pmk_email">
								Alamat Email <span class="pmk-form-req">*</span>
							</label>
							<input
								type="email"
								id="pmk_email"
								name="pmk_email"
								class="pmk-form-input <?php echo isset( $submission['errors']['email'] ) ? 'is-invalid' : ''; ?>"
								value="<?php echo esc_attr( $submission['values']['email'] ); ?>"
								placeholder="nama@email.com"
								required
							>
							<p id="pmk_email_error" class="pmk-field-error"><?php echo isset( $submission['errors']['email'] ) ? esc_html( $submission['errors']['email'] ) : ''; ?></p>
						</div>

						<div class="pmk-form-field">
							<label class="pmk-form-label" for="pmk_subject">Keperluan / Subjek</label>
							<select id="pmk_subject" name="pmk_subject" class="pmk-form-select">
								<?php
								$subjects = array(
									'Informasi Umum Panti',
									'Konfirmasi Penyaluran Donasi',
									'Rencana Kunjungan / Silaturahmi',
									'Kerja Sama & Bakti Sosial',
								);
								foreach ( $subjects as $subj ) :
									$selected = ( $submission['values']['subject'] === $subj ) ? 'selected' : '';
									?>
									<option value="<?php echo esc_attr( $subj ); ?>" <?php echo $selected; ?>><?php echo esc_html( $subj ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="pmk-form-field">
							<label class="pmk-form-label" for="pmk_message">
								Pesan <span class="pmk-form-req">*</span>
							</label>
							<textarea
								id="pmk_message"
								name="pmk_message"
								rows="5"
								class="pmk-form-textarea <?php echo isset( $submission['errors']['message'] ) ? 'is-invalid' : ''; ?>"
								placeholder="Tuliskan pesan, pertanyaan, atau informasi kunjungan Anda di sini..."
								required
							><?php echo esc_textarea( $submission['values']['message'] ); ?></textarea>
							<p id="pmk_message_error" class="pmk-field-error"><?php echo isset( $submission['errors']['message'] ) ? esc_html( $submission['errors']['message'] ) : ''; ?></p>
						</div>

						<button type="submit" class="pmk-btn pmk-btn--primary" style="width:100%;">
							Kirim Pesan
						</button>
					</form>
				<?php endif; ?>
			</div>

			<!-- KOLOM KANAN: REKENING DONASI, WHATSAPP, ALAMAT & GOOGLE MAPS -->
			<div class="pmk-contact-stack pmk-reveal" style="--delay:100ms;">
				<div id="donasi" class="pmk-bank-card">
					<div class="pmk-bank-card__head">
						<span class="pmk-bank-card__bank"><?php echo esc_html( $bank['bank_name'] ); ?></span>
						<span class="pmk-bank-card__badge">Info Rekening Donasi</span>
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

				<div class="pmk-info-card">
					<h2 style="margin:0 0 12px;font-size:1.2rem;font-weight:800;">Kontak Langsung &amp; Alamat Panti</h2>
					<p style="margin:0 0 14px;color:var(--pmk-text-muted);font-size:0.94rem;">
						<strong>Alamat Lengkap:</strong><br>
						<?php echo esc_html( $address ); ?>
					</p>
					<p style="margin:0 0 16px;color:var(--pmk-text-muted);font-size:0.94rem;">
						<strong>WhatsApp Resmi:</strong> <?php echo esc_html( $wa_display ); ?>
					</p>
					<div style="display:flex;flex-wrap:wrap;gap:10px;">
						<a class="pmk-btn pmk-btn--wa" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer">
							Chat WhatsApp Sekarang
						</a>
						<a class="pmk-btn pmk-btn--outline" href="https://maps.google.com/?q=Panti+Asuhan+Muhammadiyah+Kaligesing+Purworejo" target="_blank" rel="noopener noreferrer">
							Buka Google Maps
						</a>
					</div>
				</div>

				<div id="peta" class="pmk-map-frame">
					<iframe
						title="Peta Lokasi Panti Asuhan Muhammadiyah Kaligesing"
						src="https://maps.google.com/maps?q=Panti%20Asuhan%20Muhammadiyah%20Kaligesing%20Kaliharjo%20Purworejo&t=&z=15&ie=UTF8&iwloc=&output=embed"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
					></iframe>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
