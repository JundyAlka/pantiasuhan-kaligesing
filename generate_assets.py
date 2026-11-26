"""Generate bundled vector assets and theme screenshot.png for Panti Muhammadiyah Kaligesing."""
import os
import struct
import zlib

THEME_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "panti-muhammadiyah-kaligesing")
IMG_DIR = os.path.join(THEME_DIR, "assets", "images")
os.makedirs(IMG_DIR, exist_ok=True)


LOGO_SVG = """<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" fill="none">
  <circle cx="60" cy="60" r="54" fill="#0F5132" stroke="#EAB308" stroke-width="4"/>
  <g stroke="#EAB308" stroke-width="3.5" stroke-linecap="round">
    <line x1="60" y1="12" x2="60" y2="24"/>
    <line x1="60" y1="96" x2="60" y2="108"/>
    <line x1="12" y1="60" x2="24" y2="60"/>
    <line x1="96" y1="60" x2="108" y2="60"/>
    <line x1="26" y1="26" x2="34.5" y2="34.5"/>
    <line x1="85.5" y1="85.5" x2="94" y2="94"/>
    <line x1="94" y1="26" x2="85.5" y2="34.5"/>
    <line x1="34.5" y1="85.5" x2="26" y2="94"/>
  </g>
  <circle cx="60" cy="60" r="24" fill="#FFFFFF" stroke="#EAB308" stroke-width="3"/>
  <path d="M60 43 C51 43 45 50 45 59 C45 69 52 76 61 76 C66 76 70 73 73 69 C66 71 58 67 58 59 C58 51 64 46 71 45 C67 43.7 63.6 43 60 43Z" fill="#0F5132"/>
  <polygon points="71,52 73,57 78,57 74,60 75.5,65 71,62 66.5,65 68,60 64,57 69,57" fill="#D4A017"/>
</svg>"""


def make_scene_svg(title: str, subtitle: str, tag: str, accent: str, motif: str) -> str:
    motifs = {
        "building": """
          <rect x="140" y="190" width="520" height="210" rx="10" fill="#FFFFFF" fill-opacity="0.95"/>
          <polygon points="115,196 400,96 685,196" fill="#07291A"/>
          <polygon points="145,192 400,112 655,192" fill="#0F5132"/>
          <rect x="348" y="255" width="104" height="145" rx="8" fill="#093622"/>
          <rect x="180" y="238" width="96" height="72" rx="8" fill="#DCFCE7" stroke="#0F5132" stroke-width="4"/>
          <rect x="524" y="238" width="96" height="72" rx="8" fill="#DCFCE7" stroke="#0F5132" stroke-width="4"/>
          <rect x="275" y="160" width="250" height="34" rx="6" fill="#EAB308"/>
        """,
        "mushola": """
          <rect x="180" y="210" width="440" height="190" rx="12" fill="#FFFFFF" fill-opacity="0.95"/>
          <path d="M270 210 C270 120 530 120 530 210 Z" fill="#0F5132"/>
          <path d="M320 210 C320 145 480 145 480 210 Z" fill="#166534"/>
          <circle cx="400" cy="108" r="14" fill="#EAB308"/>
          <path d="M360 400 V290 C360 258 440 258 440 290 V400 Z" fill="#093622"/>
          <path d="M225 320 V265 C225 245 275 245 275 265 V320 Z" fill="#DCFCE7"/>
          <path d="M525 320 V265 C525 245 575 245 575 265 V320 Z" fill="#DCFCE7"/>
        """,
        "interior": """
          <rect x="120" y="135" width="560" height="270" rx="16" fill="#FFFFFF" fill-opacity="0.94"/>
          <rect x="160" y="175" width="210" height="125" rx="10" fill="#DCFCE7" stroke="#0F5132" stroke-width="4"/>
          <rect x="420" y="205" width="215" height="95" rx="10" fill="#F0F7F3" stroke="#D5E2DA" stroke-width="3"/>
          <rect x="210" y="330" width="380" height="28" rx="8" fill="#0F5132"/>
          <circle cx="525" cy="175" r="24" fill="#EAB308"/>
        """,
        "garden": """
          <path d="M0 330 Q220 280 440 325 T800 300 V500 H0 Z" fill="#166534"/>
          <path d="M0 370 Q280 340 520 375 T800 355 V500 H0 Z" fill="#0F5132"/>
          <circle cx="170" cy="245" r="56" fill="#22C55E" fill-opacity="0.85"/>
          <rect x="162" y="285" width="16" height="65" rx="4" fill="#78350F"/>
          <circle cx="620" cy="235" r="64" fill="#16A34A" fill-opacity="0.9"/>
          <rect x="612" y="285" width="16" height="72" rx="4" fill="#78350F"/>
          <rect x="290" y="200" width="220" height="145" rx="10" fill="#FFFFFF" fill-opacity="0.92"/>
          <polygon points="270,206 400,145 530,206" fill="#07291A"/>
        """,
        "portrait": """
          <circle cx="400" cy="188" r="68" fill="#FFFFFF" fill-opacity="0.95"/>
          <path d="M270 390 C270 305 530 305 530 390 Z" fill="#FFFFFF" fill-opacity="0.95"/>
          <rect x="332" y="130" width="136" height="36" rx="14" fill="#07291A"/>
          <circle cx="400" cy="200" r="44" fill="#E2E8F0"/>
          <rect x="358" y="320" width="84" height="56" rx="8" fill="#0F5132"/>
        """,
    }
    shape = motifs.get(motif, motifs["building"])
    return f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 500" fill="none">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="800" y2="500" gradientUnits="userSpaceOnUse">
      <stop offset="0%" stop-color="#07291A"/>
      <stop offset="55%" stop-color="#0F5132"/>
      <stop offset="100%" stop-color="{accent}"/>
    </linearGradient>
  </defs>
  <rect width="800" height="500" fill="url(#bg)"/>
  <circle cx="690" cy="95" r="140" fill="#EAB308" fill-opacity="0.14"/>
  <circle cx="110" cy="430" r="160" fill="#22C55E" fill-opacity="0.12"/>
  {shape}
  <rect x="32" y="32" width="230" height="34" rx="17" fill="#07291A" fill-opacity="0.78" stroke="#EAB308" stroke-opacity="0.5"/>
  <text x="48" y="54" fill="#FACC15" font-family="Segoe UI, Arial, sans-serif" font-size="14" font-weight="700">{tag}</text>
  <rect x="0" y="408" width="800" height="92" fill="#07291A" fill-opacity="0.88"/>
  <text x="36" y="446" fill="#FFFFFF" font-family="Segoe UI, Arial, sans-serif" font-size="22" font-weight="700">{title}</text>
  <text x="36" y="474" fill="#D1FAE5" font-family="Segoe UI, Arial, sans-serif" font-size="14" font-weight="500">{subtitle}</text>
</svg>"""


ASSETS = {
    "logo-muhammadiyah.svg": LOGO_SVG,
    "hero-panti.svg": make_scene_svg(
        "Panti Asuhan Muhammadiyah Kaligesing",
        "Lingkungan Asri, Amanah & Berkarakter Islami — Purworejo",
        "PANTI ASUHAN MUHAMMADIYAH",
        "#166534",
        "building",
    ),
    "kepala-panti.svg": make_scene_svg(
        "H. Johan Martono",
        "Kepala Panti Asuhan Muhammadiyah Kaligesing",
        "SAMBUTAN PIMPINAN",
        "#1E7A45",
        "portrait",
    ),
    "kegiatan-tahfidz.svg": make_scene_svg(
        "Pembinaan Ruhani & Tahfidz Al-Qur'an",
        "Shalat Berjamaah, Tadarus & Penguatan Akhlak Karimah",
        "PROGRAM KEAGAMAAN",
        "#166534",
        "mushola",
    ),
    "kegiatan-belajar.svg": make_scene_svg(
        "Pendidikan Formal & Bimbingan Belajar",
        "Pendampingan Sekolah SD, SMP hingga SMA/SMK",
        "PROGRAM PENDIDIKAN",
        "#1E7A45",
        "interior",
    ),
    "kegiatan-kemandirian.svg": make_scene_svg(
        "Pelatihan Keterampilan & Kemandirian",
        "Bekal Life Skills dan Kewirausahaan Anak Asuh",
        "PROGRAM KEMANDIRIAN",
        "#166534",
        "garden",
    ),
    "kegiatan-sosial.svg": make_scene_svg(
        "Pengasuhan, Gizi & Kesehatan Terpadu",
        "Pelayanan Sosial dan Pemeriksaan Kesehatan Berkala",
        "SOSIAL & KESEHATAN",
        "#1E7A45",
        "building",
    ),
    "fasilitas-depan.svg": make_scene_svg(
        "Depan Panti Asuhan Muhammadiyah",
        "Jl. Raya Purworejo-Kaligesing KM 8,5, Desa Kaliharjo",
        "FASILITAS 01 • DEPAN PANTI",
        "#166534",
        "building",
    ),
    "fasilitas-tamu.svg": make_scene_svg(
        "Ruang Tamu & Penerimaan Donatur",
        "Area Silaturahmi yang Nyaman untuk Wali & Masyarakat",
        "FASILITAS 02 • RUANG TAMU",
        "#1E7A45",
        "interior",
    ),
    "fasilitas-administrasi.svg": make_scene_svg(
        "Ruang Kerja Administrasi",
        "Tata Usaha, Pengarsipan & Layanan Informasi Terpadu",
        "FASILITAS 03 • ADMINISTRASI",
        "#166534",
        "interior",
    ),
    "fasilitas-mushola.svg": make_scene_svg(
        "Mushola Panti Asuhan",
        "Pusat Ibadah Berjamaah, Tahfidz & Kajian Islami",
        "FASILITAS 04 • MUSHOLA PANTI",
        "#1E7A45",
        "mushola",
    ),
    "fasilitas-halaman.svg": make_scene_svg(
        "Halaman Utama Lingkungan Panti",
        "Area Terbuka Hijau untuk Apel, Olahraga & Kebersamaan",
        "FASILITAS 05 • HALAMAN UTAMA",
        "#166534",
        "garden",
    ),
    "fasilitas-asrama.svg": make_scene_svg(
        "Bangunan Asrama & Ruang Belajar",
        "Hunian Nyaman serta Ruang Belajar Bersama Anak Asuh",
        "FASILITAS 06 • ASRAMA & BELAJAR",
        "#1E7A45",
        "building",
    ),
}

for filename, content in ASSETS.items():
    with open(os.path.join(IMG_DIR, filename), "w", encoding="utf-8") as f:
        f.write(content)


def write_theme_screenshot_png(path: str, width: int = 1200, height: int = 900) -> None:
    """Create a clean 1200x900 emerald/gold gradient PNG for WordPress Themes screen."""
    rows = []
    for y in range(height):
        row = bytearray(b"\x00")
        t = y / height
        # Smooth dark emerald gradient with a gold accent band at top
        if y < 18:
            r, g, b = 234, 179, 8
        elif 110 <= y <= 116:
            r, g, b = 212, 160, 23
        elif y < 520:
            r = int(7 + 8 * t)
            g = int(41 + 42 * t)
            b = int(26 + 24 * t)
        else:
            r, g, b = 240, 247, 243
        pixel = bytes((r, g, b))
        row.extend(pixel * width)
        rows.append(bytes(row))
    raw = b"".join(rows)

    def chunk(tag: bytes, data: bytes) -> bytes:
        return struct.pack(">I", len(data)) + tag + data + struct.pack(">I", zlib.crc32(tag + data) & 0xFFFFFFFF)

    ihdr = struct.pack(">IIBBBBB", width, height, 8, 2, 0, 0, 0)
    png = b"\x89PNG\r\n\x1a\n" + chunk(b"IHDR", ihdr) + chunk(b"IDAT", zlib.compress(raw, 6)) + chunk(b"IEND", b"")
    with open(path, "wb") as f:
        f.write(png)


write_theme_screenshot_png(os.path.join(THEME_DIR, "screenshot.png"))
print(f"Generated {len(ASSETS)} SVG assets and screenshot.png in {THEME_DIR}")
