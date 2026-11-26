"""Verify all KP checklist criteria, hero slider, rolling collage, unified background, and ZIP."""
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

for route in ["/", "/about/", "/facilities/", "/contact/"]:
    with urllib.request.urlopen(BASE_URL + route, timeout=5) as resp:
        assert resp.status == 200, f"{route} returned {resp.status}"
        html = resp.read().decode("utf-8")
        assert "Panti Asuhan Muhammadiyah Kaligesing" in html
        for nav_item in ["Beranda", "Tentang Kami", "Fasilitas", "Kontak"]:
            assert nav_item in html, f"Missing Indonesian nav item {nav_item} on {route}"
        for bad in forbidden_strings:
            assert bad not in html, f"Found leftover template text '{bad}' on {route}"
        assert "0853-2698-0493" in html
        assert "0812-2547-2032" not in html
        assert "logo-panti.png" in html
print("[PASS] 1. All 4 routes return HTTP 200, Indonesian navbar, real logo, unified WA, and 0 leftovers.")

# Verify Home hero slider, 3 orbiting collage cards, unified panorama, counters, slideshow, and Bank BRI
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

# Verify About page hierarchical org chart
with urllib.request.urlopen(BASE_URL + "/about/", timeout=5) as resp:
    about_html = resp.read().decode("utf-8")
    assert "pmk-org-chart" in about_html
    assert "H. Johan Martono" in about_html
    assert "Iwan Kusni Surahman" in about_html
    assert "Harsiwi Wismayanti" in about_html
    assert "Hj. Arifatul Aini" in about_html
    assert "Sanjaya Ancas Mahardika" in about_html
print("[PASS] 2b. About hierarchical Bagan Struktur Organisasi verified.")

# Verify real photos are served with HTTP 200
for asset in [
    "/wp-content/themes/panti-muhammadiyah-kaligesing/assets/images/photos/logo-panti.png",
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
print("[PASS] 3. Downloaded real photos served with HTTP 200.")

# Verify empty required fields rejected
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
    assert "Peringatan: Mohon lengkapi seluruh kolom wajib" in html_empty
    assert not os.path.exists(DB_PATH)
print("[PASS] 4. Empty required form submission rejected with explicit warning and not saved.")

# Verify valid contact submission saved
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
    assert "berhasil dikirim dan tercatat di database" in html_valid

with urllib.request.urlopen(BASE_URL + "/admin-messages", timeout=5) as resp:
    records = json.loads(resp.read().decode("utf-8"))
    assert len(records) == 1
    assert records[0]["full_name"] == "Budi Santoso"
print("[PASS] 5. Valid contact submission shows success alert and persists to database.")

# Verify ZIP archive
assert os.path.isfile(ZIP_PATH)
with zipfile.ZipFile(ZIP_PATH, "r") as zf:
    names = zf.namelist()
    assert "panti-muhammadiyah-kaligesing/style.css" in names
    assert "panti-muhammadiyah-kaligesing/assets/images/photos/logo-panti.png" in names
    assert all("\\" not in n for n in names)
print(f"[PASS] 6. ZIP archive verified ({len(names)} files, bundled real photos, forward slashes).")
