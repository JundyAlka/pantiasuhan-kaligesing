"""Verify all KP checklist criteria, commercial Customizer options, dynamic photo/layout edits, Elementor/Shortcode compatibility, and ZIP archive."""
import json
import os
import urllib.parse
import urllib.request
import zipfile

BASE_URL = "http://127.0.0.1:8088"
ZIP_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "panti-muhammadiyah-kaligesing.zip")
DB_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "local-preview", "messages.json")

if os.path.exists(DB_PATH):
    os.remove(DB_PATH)

forbidden_strings = [
    "Assisted Living",
    "Pharmacy Support",
    "Resident Care",
    "24/7 Special Services",
    "Yoga for Relaxation",
    "Caring Staff",
    "Powered by Elderly Home",
    "My Blog",
]

php_error_markers = [
    "Fatal error:",
    "Parse error:",
    "Warning:",
    "Notice:",
    "Deprecated:",
    "Uncaught Error",
]

def assert_no_php_errors(html: str, context: str) -> None:
    for marker in php_error_markers:
        assert marker not in html, f"PHP error marker '{marker}' found during {context}!"

# 1. Default KP Routes Check
for route in ["/", "/about/", "/facilities/", "/contact/"]:
    with urllib.request.urlopen(BASE_URL + route, timeout=5) as resp:
        assert resp.status == 200, f"{route} returned {resp.status}"
        html = resp.read().decode("utf-8")
        assert_no_php_errors(html, f"GET {route}")
        assert "Panti Asuhan Muhammadiyah Kaligesing" in html
        for nav_item in ["Beranda", "Tentang Kami", "Fasilitas", "Kontak"]:
            assert nav_item in html, f"Missing Indonesian nav item {nav_item} on {route}"
        for bad in forbidden_strings:
            assert bad not in html, f"Found leftover template text '{bad}' on {route}"
        assert "0853-2698-0493" in html
        assert "0812-2547-2032" not in html
        assert "logo-panti.png" in html
print("[PASS] 1. All 4 routes return HTTP 200, Indonesian navbar, real logo, unified WA, and 0 leftovers.")

# 2. Verify Home hero slider, 3 orbiting collage cards, unified panorama, counters, slideshow, and Bank BRI
with urllib.request.urlopen(BASE_URL + "/", timeout=5) as resp:
    home_html = resp.read().decode("utf-8")
    assert 'id="pmk-hero-bg-slider"' in home_html
    assert home_html.count("pmk-hero__bg-slide") >= 4
    assert 'id="pmk-orbit-stage"' in home_html
    assert home_html.count("pmk-orbit-card") >= 3
    assert 'data-slot="main"' in home_html
    assert 'data-slot="top"' in home_html
    assert 'data-slot="bottom"' in home_html
    assert "pmk-unified-showcase" in home_html
    for count in ['data-counter-target="24"', 'data-counter-target="15"', 'data-counter-target="18"', 'data-counter-target="5"']:
        assert count in home_html, f"Missing counter {count} on Home"
    assert 'id="pmk-activity-slideshow"' in home_html
    assert "Plang" not in home_html, "Informal word 'Plang' should be replaced"
    assert "684301006299530" in home_html
    assert "PAY DAN DM KALIGESING" in home_html
print("[PASS] 2. Hero bg slider, 3 orbiting collage cards, unified section bg, counters, and Bank BRI verified.")

# 2b. Verify About page hierarchical org chart & Kepala Panti photo
with urllib.request.urlopen(BASE_URL + "/about/", timeout=5) as resp:
    about_html = resp.read().decode("utf-8")
    assert "pmk-org-chart" in about_html
    assert "kepala-panti.jpg" in about_html
    assert "H. Johan Martono" in about_html
    assert "Iwan Kusni Surahman" in about_html
    assert "Harsiwi Wismayanti" in about_html
    assert "Hj. Arifatul Aini" in about_html
    assert "Sanjaya Ancas Mahardika" in about_html
print("[PASS] 2b. About hierarchical Bagan Struktur Organisasi & Kepala Panti portrait verified.")

# 3. Verify real photos are served with HTTP 200
for asset in [
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/logo-panti.png",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/kepala-panti.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/bakti-sosial-kepala.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/panti-1.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/photo-3.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/photo-8.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/wa-landscape-2.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/anak-kebersamaan-1.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/anak-ceria-landscape.jpg",
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/anak-edukasi-mushola.jpg",
]:
    with urllib.request.urlopen(BASE_URL + asset, timeout=5) as resp:
        assert resp.status == 200
print("[PASS] 3. Downloaded & newly added real photos served with HTTP 200.")

# 4. Verify empty required fields rejected
empty_data = urllib.parse.urlencode({
    "pmk_contact_action": "1",
    "pmk_name": "",
    "pmk_email": "",
    "pmk_subject": "Informasi Umum Panti",
    "pmk_message": "",
}).encode("utf-8")
req_empty = urllib.request.Request(BASE_URL + "/contact/", data=empty_data, method="POST")
with urllib.request.urlopen(req_empty, timeout=5) as resp:
    html_empty = resp.read().decode("utf-8")
    assert_no_php_errors(html_empty, "POST /contact/ empty")
    assert "Peringatan: Mohon lengkapi seluruh kolom wajib" in html_empty
    assert not os.path.exists(DB_PATH)
print("[PASS] 4. Empty required form submission rejected with explicit warning and not saved.")

# 5. Verify valid contact submission saved
valid_data = urllib.parse.urlencode({
    "pmk_contact_action": "1",
    "pmk_name": "Budi Santoso",
    "pmk_email": "budi@example.com",
    "pmk_subject": "Konfirmasi Penyaluran Donasi",
    "pmk_message": "Assalamualaikum, mohon informasi jadwal kunjungan ke panti minggu ini.",
}).encode("utf-8")
req_valid = urllib.request.Request(BASE_URL + "/contact/", data=valid_data, method="POST")
with urllib.request.urlopen(req_valid, timeout=5) as resp:
    html_valid = resp.read().decode("utf-8")
    assert_no_php_errors(html_valid, "POST /contact/ valid")
    assert "berhasil dikirim dan tercatat di database" in html_valid

with urllib.request.urlopen(BASE_URL + "/admin-messages", timeout=5) as resp:
    records = json.loads(resp.read().decode("utf-8"))
    assert len(records) == 1
    assert records[0]["full_name"] == "Budi Santoso"
print("[PASS] 5. Valid contact submission shows success alert and persists to database.")

# 6. Verify Customizer Panel, 7 Sections, 80+ Controls & Selective Refresh Registration
with urllib.request.urlopen(BASE_URL + "/customizer-audit", timeout=5) as resp:
    audit = json.loads(resp.read().decode("utf-8"))
    assert audit["panels_count"] == 1 and "pmk_theme_panel" in audit["panels"]
    assert audit["sections_count"] == 7, f"Expected 7 Customizer sections, got {audit['sections_count']}"
    assert audit["settings_count"] >= 80, f"Expected >= 80 Customizer settings, got {audit['settings_count']}"
    assert audit["controls_count"] >= 80, f"Expected >= 80 Customizer controls, got {audit['controls_count']}"
    assert audit["partials_count"] >= 8, f"Expected >= 8 Selective Refresh partials, got {audit['partials_count']}"
print(f"[PASS] 6. Commercial Customizer audited ({audit['panels_count']} panel, {audit['sections_count']} sections, {audit['settings_count']} settings/controls, {audit['partials_count']} selective refresh partials).")

# 7. Multi-Scenario Customizer Dynamic Stress Test (Colors, Photos, Edge-Case Image Inputs, Section Toggles, Org Chart, Facilities)
stress_home_query = urllib.parse.urlencode({
    "_mod[pmk_color_primary]": "#14532D",
    "_mod[pmk_color_gold]": "#F59E0B",
    "_mod[pmk_container_width]": "1420",
    "_mod[pmk_card_radius]": "24",
    "_mod[pmk_wa_number]": "0812-2547-2032",
    "_mod[pmk_hero_title_highlight]": "LKSA Muhammadiyah Kaligesing Unggul",
    "_mod[pmk_stat_1_val]": "42",
    "_mod[pmk_bank_name]": "Bank Syariah Indonesia (BSI)",
    "_mod[pmk_bank_account]": "7788990011",
    "_mod[pmk_img_hero]": "99999",  # Simulated attachment ID that doesn't exist -> must gracefully fallback to panti-1.jpg!
    "_mod[pmk_img_orbit_1]": "bakti-sosial-kepala.jpg",  # Direct filename override -> must resolve to assets/images/photos/bakti-sosial-kepala.jpg
    "_mod[pmk_img_orbit_2]": "   ",  # Empty whitespace -> must fallback cleanly without broken src
    "_mod[pmk_img_orbit_3]": "not a valid url",  # Malformed URL -> must fallback cleanly to anak-ceria-potret.jpg
})
with urllib.request.urlopen(BASE_URL + "/?" + stress_home_query, timeout=5) as resp:
    sh_html = resp.read().decode("utf-8")
    assert_no_php_errors(sh_html, "Home Customizer Stress Test")
    assert "--pmk-emerald-800: #14532D;" in sh_html
    assert "--pmk-gold-400: #F59E0B;" in sh_html
    assert "--pmk-container: 1420px;" in sh_html
    assert "--pmk-radius-lg: 24px;" in sh_html
    assert "0812-2547-2032" in sh_html
    assert "wa.me/6281225472032" in sh_html
    assert "LKSA Muhammadiyah Kaligesing Unggul" in sh_html
    assert 'data-counter-target="42"' in sh_html
    assert "Bank Syariah Indonesia (BSI)" in sh_html
    assert "7788990011" in sh_html
    assert "http://99999" not in sh_html and 'src="99999"' not in sh_html
    assert "not a valid url" not in sh_html
    assert "panti-1.jpg" in sh_html

# Stress test About page (Custom Kepala Panti, Custom Org Chart members, Section toggle hide history)
stress_about_query = urllib.parse.urlencode({
    "_mod[pmk_kepala_name]": "Drs. H. Ahmad Dahlan",
    "_mod[pmk_org_wakil]": "H. Fachruddin",
    "_mod[pmk_org_bidang_1_title]": "Bidang Humas, Digital & Kemitraan",
    "_mod[pmk_org_bidang_1_members]": "Anggota Satu, Anggota Dua, Anggota Tiga, Anggota Empat",
    "_mod[pmk_show_about_history]": "0",
    "_mod[pmk_img_kepala]": "bakti-sosial-kepala.jpg",
})
with urllib.request.urlopen(BASE_URL + "/about/?" + stress_about_query, timeout=5) as resp:
    sa_html = resp.read().decode("utf-8")
    assert_no_php_errors(sa_html, "About Customizer Stress Test")
    assert "Drs. H. Ahmad Dahlan" in sa_html
    assert "H. Fachruddin" in sa_html
    assert "Bidang Humas, Digital &amp; Kemitraan" in sa_html
    assert "Anggota Empat" in sa_html
    assert "pmk-history-grid" not in sa_html, "History section should be hidden when toggled off"
    assert "bakti-sosial-kepala.jpg" in sa_html

# Stress test Facilities page (Custom facility title, description, photo override & broken image guard)
stress_fac_query = urllib.parse.urlencode({
    "_mod[pmk_fac_1_title]": "Gedung Utama & Pusat Layanan Terpadu",
    "_mod[pmk_img_fac_depan]": "kepala-panti.jpg",
    "_mod[pmk_img_fac_tamu]": "invalid_missing_file.jpg",
})
with urllib.request.urlopen(BASE_URL + "/facilities/?" + stress_fac_query, timeout=5) as resp:
    sf_html = resp.read().decode("utf-8")
    assert_no_php_errors(sf_html, "Facilities Customizer Stress Test")
    assert "Gedung Utama &amp; Pusat Layanan Terpadu" in sf_html
    assert "kepala-panti.jpg" in sf_html
    assert "invalid_missing_file.jpg" not in sf_html, "Non-existent local filename must fallback to photo-6.jpg"
    assert "photo-6.jpg" in sf_html
print("[PASS] 7. Multi-scenario Customizer dynamic edits & defensive photo fallback stress-tested across pages with 0 errors.")

# 8. Verify Elementor Editor/Preview Mode, Full-Width Template, Legacy Content Blocker, Indonesian Slug Auto-Routing & All 5 Theme Shortcodes
for route in ["/", "/about/", "/facilities/", "/contact/"]:
    with urllib.request.urlopen(BASE_URL + route + "?elementor-preview=1", timeout=5) as resp:
        el_html = resp.read().decode("utf-8")
        assert_no_php_errors(el_html, f"Elementor Preview on {route}")
        assert "pmk-builder-region" in el_html, f"Missing Elementor dropzone region on {route}"
        assert "elementor-section-wrap" in el_html, f"the_content() not rendered in Elementor preview on {route}"

# Verify that even if DB post_content has old template leftovers AND page.php is loaded on /beranda, /tentang-kami, /fasilitas, /kontak,
# it routes to the proper theme template and NEVER prints the old leftover template content at the bottom!
legacy_payload = urllib.parse.urlencode({
    "_page_content": "Selamat Datang!! Jalan Raya, Sawah,Ladang, Kaliharjo Mewujudkan Pengasuhan Islami demi Masa Depan 23+ 14+ 17+ 4+"
})
for id_route, expected_marker in [
    ("/beranda/", 'id="pmk-orbit-stage"'),
    ("/tentang-kami/", "pmk-org-chart"),
    ("/fasilitas/", "pmk-gallery-grid"),
    ("/kontak/", 'id="pmk-contact-form"'),
]:
    with urllib.request.urlopen(BASE_URL + id_route + "?" + legacy_payload, timeout=5) as resp:
        id_html = resp.read().decode("utf-8")
        assert_no_php_errors(id_html, f"Legacy route & content blocker test on {id_route}")
        assert expected_marker in id_html, f"Route {id_route} via page.php did not auto-route to expected template ({expected_marker})!"
        assert "Jalan Raya, Sawah,Ladang" not in id_html, f"Legacy leftover content leaked onto {id_route}!"
        assert "Selamat Datang!!" not in id_html, f"Legacy leftover content leaked onto {id_route}!"

shortcode_test_query = urllib.parse.urlencode({
    "_page_content": "[pmk_rekening_donasi] [pmk_struktur_organisasi] [pmk_galeri_fasilitas] [pmk_info_kontak] [pmk_form_kontak]"
})
with urllib.request.urlopen(BASE_URL + "/elementor-page?" + shortcode_test_query, timeout=5) as resp:
    sc_html = resp.read().decode("utf-8")
    assert_no_php_errors(sc_html, "Elementor Full Width + All 5 Shortcodes")
    assert "pmk-elementor-fullwidth" in sc_html
    assert "pmk-shortcode-bank" in sc_html
    assert "pmk-org-chart" in sc_html
    assert "pmk-gallery-grid" in sc_html
    assert "pmk-shortcode-form-wrap" in sc_html
print("[PASS] 8. Legacy template override blocker, Indonesian slug auto-router (/beranda, /tentang-kami, /fasilitas, /kontak), Elementor Preview, and all 5 shortcodes verified.")

# 9. Verify ZIP archive
assert os.path.isfile(ZIP_PATH)
with zipfile.ZipFile(ZIP_PATH, "r") as zf:
    names = zf.namelist()
    for required_entry in [
        "panti-muhammadiyah-kaligesing/style.css",
        "panti-muhammadiyah-kaligesing/screenshot.png",
        "panti-muhammadiyah-kaligesing/inc/customizer.php",
        "panti-muhammadiyah-kaligesing/inc/page-builder.php",
        "panti-muhammadiyah-kaligesing/template-elementor-fullwidth.php",
        "panti-muhammadiyah-kaligesing/assets/images/photos/logo-panti.png",
        "panti-muhammadiyah-kaligesing/assets/images/photos/kepala-panti.jpg",
        "panti-muhammadiyah-kaligesing/assets/images/photos/bakti-sosial-kepala.jpg",
    ]:
        assert required_entry in names, f"Missing {required_entry} in theme ZIP!"
    assert all("\\" not in n for n in names)
print(f"[PASS] 9. ZIP archive verified ({len(names)} files, commercial Customizer & Elementor template bundled, forward slashes).")
