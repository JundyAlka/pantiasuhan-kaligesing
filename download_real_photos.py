"""Download all real photos and official logo from https://pantimuhammadiyahkaligesing.my.id."""
import os
import urllib.request

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DEST_DIR = os.path.join(BASE_DIR, "panti-muhammadiyah-kaligesing", "assets", "images", "photos")
os.makedirs(DEST_DIR, exist_ok=True)

FILES = {
    "logo-panti.png": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/cropped-Gemini_Generated_Image_e37fgxe37fgxe37f-removebg-preview.png",
    "panti-1.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/panti-1-1024x768.jpg",
    "panti-3.png": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/panti3-846x1024.png",
    "panti-4.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/panti-4-1024x768.jpg",
    "photo-1.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/1-1-1024x1005.jpeg",
    "photo-3.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/3-1024x600.jpeg",
    "photo-4.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/4-768x750.jpeg",
    "photo-5.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/5-1024x891.jpeg",
    "photo-6.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/6-768x548.jpeg",
    "photo-8.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/8-1024x626.jpeg",
    "photo-9.png": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/9-1024x457.png",
    "photo-10.png": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/10-1024x594.png",
    "wa-kegiatan-1.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/WhatsApp-Image-2026-06-15-at-15.26.19-820x1024.jpeg",
    "wa-landscape-2.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/WhatsApp-Image-2026-06-18-at-18.42.20-1024x732.jpeg",
    "kaligrafi-muhammadiyah.jpg": "https://pantimuhammadiyahkaligesing.my.id/wp-content/uploads/2026/06/g7db5401e2880efbd3c7b71eab129dd2849ac5453a5b330e79a39ab02619b691f43e2db495b3ae3c15afbbf7dfd7ae47022478ea917671a27a9fa45d681e7a735_1280-5179174-1024x682.jpg",
}

for filename, url in FILES.items():
    out_path = os.path.join(DEST_DIR, filename)
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req, timeout=25) as resp:
        data = resp.read()
        content_type = resp.headers.get("Content-Type", "")
        assert resp.status == 200 and len(data) > 1000 and "image" in content_type, f"Invalid response for {filename}: {resp.status} {content_type}"
    with open(out_path, "wb") as f:
        f.write(data)
    print(f"Downloaded {filename} ({len(data)} bytes)")

print(f"Successfully downloaded all {len(FILES)} real site photos to {DEST_DIR}")
