<?php
/**
 * Footer Template — Panti Asuhan Muhammadiyah Kaligesing
 *
 * @package Panti_Muhammadiyah_Kaligesing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$logo_url = pmk_get_logo_url();
?>
</main>

<footer class="pmk-footer" role="contentinfo">
	<div class="pmk-container pmk-container--wide">
		<div class="pmk-footer__grid">
			<div>
				<div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
					<span class="pmk-footer__logo-glass">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo Panti Asuhan Muhammadiyah Kaligesing" width="50" height="50">
					</span>
					<div>
						<h2 class="pmk-footer__title" style="margin:0;font-size:1.15rem;">Panti Asuhan Muhammadiyah Kaligesing</h2>
						<span style="font-size:0.84rem;color:#FACC15;font-weight:700;">Terakreditasi Resmi &bull; LKSA / MCC PCM Kaligesing</span>
					</div>
				</div>
				<p class="pmk-footer__desc">
					Lembaga kesejahteraan sosial anak yatim dan dhuafa di bawah naungan Pimpinan Cabang Muhammadiyah Kaligesing yang berkomitmen membentuk generasi Islami, berakhlak mulia, dan mandiri.
				</p>
			</div>

			<div>
				<h2 class="pmk-footer__title">Navigasi Halaman</h2>
				<?php pmk_render_fallback_nav( 'pmk-footer__menu' ); ?>
			</div>

			<div>
				<h2 class="pmk-footer__title">Kontak &amp; Alamat Resmi</h2>
				<ul class="pmk-footer__contact-list">
					<li>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EAB308" stroke-width="2" style="flex-shrink:0;margin-top:3px;" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						<span><?php echo esc_html( pmk_get_address() ); ?></span>
					</li>
					<li>
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EAB308" stroke-width="2" style="flex-shrink:0;margin-top:3px;" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<a href="<?php echo esc_url( pmk_get_wa_url( 'Assalamualaikum, saya ingin menghubungi Panti Asuhan Muhammadiyah Kaligesing.' ) ); ?>" target="_blank" rel="noopener noreferrer" style="color:#FFFFFF;font-weight:700;">
							<?php echo esc_html( pmk_get_wa_display() ); ?> (WhatsApp Resmi)
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="pmk-footer__bottom">
			<span>Copyright &copy; 2026 Panti Asuhan Muhammadiyah Kaligesing. Hak Cipta Dilindungi.</span>
			<span>Desa Kaliharjo, Kec. Kaligesing, Kab. Purworejo, Jawa Tengah</span>
		</div>
	</div>
</footer>

<a class="pmk-float-wa" href="<?php echo esc_url( pmk_get_wa_url( 'Assalamualaikum, saya ingin informasi mengenai Panti Asuhan Muhammadiyah Kaligesing.' ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat WhatsApp Panti Asuhan Muhammadiyah Kaligesing">
	<svg class="pmk-icon-pulse" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
	<span>Chat WhatsApp</span>
</a>

<?php wp_footer(); ?>
</body>
</html>
