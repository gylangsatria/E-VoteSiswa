#!/bin/bash
# ====================================================
# Merge Script: docker → main
# Menggabungkan perubahan dari branch docker ke main
# TANPA membawa file Docker (Dockerfile, compose, dll)
# ====================================================
# Cara pakai:
#   ./merge-docker-to-main.sh
# ====================================================

set -e

BRANCH_DOCKER="docker"
BRANCH_MAIN="main"

echo "=== Merge: $BRANCH_DOCKER → $BRANCH_MAIN ==="
echo ""

# 1. Pastikan di branch docker, ambil perubahan terbaru
echo "[1/6] Update branch $BRANCH_DOCKER dari remote..."
git checkout "$BRANCH_DOCKER"
git pull origin "$BRANCH_DOCKER"

# 2. Beralih ke main dan update
echo ""
echo "[2/6] Update branch $BRANCH_MAIN dari remote..."
git checkout "$BRANCH_MAIN"
git pull origin "$BRANCH_MAIN"

# 3. Merge docker ke main (tanpa commit dulu)
echo ""
echo "[3/6] Merge $BRANCH_DOCKER ke $BRANCH_MAIN..."
git merge "$BRANCH_DOCKER" --no-commit --no-ff

# 4. Unstage file-file Docker agar tidak ikut ke main
echo ""
echo "[4/6] Hapus file Docker dari staging..."
FILES_TO_UNSTAGE=(
    "Dockerfile"
    "docker-compose.yml"
    ".dockerignore"
    "docker/"
    ".gitignore"
)

for f in "${FILES_TO_UNSTAGE[@]}"; do
    if git ls-files --cached --error-unmatch "$f" &>/dev/null 2>&1; then
        git reset HEAD "$f" 2>/dev/null
        git checkout -- "$f" 2>/dev/null || true
        echo "    ✓ $f dikeluarkan"
    fi
done

# 5. Commit merge
echo ""
echo "[5/6] Commit merge..."
read -p "    Pesan commit (biarkan kosong untuk default): " MSG
if [ -z "$MSG" ]; then
    MSG="merge: branch docker ke main"
fi
git commit -m "$MSG"

# 6. Push ke remote
echo ""
echo "[6/6] Push $BRANCH_MAIN ke remote..."
git push origin "$BRANCH_MAIN"

echo ""
echo "✅ Selesai! Branch $BRANCH_MAIN sudah mendapat perubahan dari $BRANCH_DOCKER."
echo ""
echo "💡 Jangan lupa sync balik main → docker setelah ini:"
echo "   ./sync-docker.sh"
