"""Package WordPress theme folder into a valid WordPress-uploadable .zip with POSIX slashes."""
import os
import zipfile

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
THEME_FOLDER_NAME = "panti-muhammadiyah-kaligesing"
THEME_DIR = os.path.join(BASE_DIR, THEME_FOLDER_NAME)
ZIP_PATH = os.path.join(BASE_DIR, f"{THEME_FOLDER_NAME}.zip")

with zipfile.ZipFile(ZIP_PATH, "w", compression=zipfile.ZIP_DEFLATED) as zf:
    for root, _, files in os.walk(THEME_DIR):
        for filename in sorted(files):
            full_path = os.path.join(root, filename)
            rel_path = os.path.relpath(full_path, BASE_DIR).replace("\\", "/")
            zf.write(full_path, rel_path)

print(f"Packaged WordPress Theme ZIP: {ZIP_PATH} ({os.path.getsize(ZIP_PATH)} bytes)")
