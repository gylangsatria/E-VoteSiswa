#!/bin/bash
# ====================================================
# Sync Script: main → docker branch
# Sinkronisasi perubahan dari main ke branch docker
# ====================================================
# Cara pakai:
#   ./sync-docker.sh
# ====================================================

set -e

BRANCH_DOCKER="docker"
BRANCH_MAIN="main"

echo "=== Sync: $BRANCH_MAIN → $BRANCH_DOCKER ==="

# 1. Pastikan di main, ambil perubahan terbaru
echo ""
echo "[1/4] Update branch $BRANCH_MAIN..."
git checkout "$BRANCH_MAIN"
git pull origin "$BRANCH_MAIN"

# 2. Beralih ke branch docker
echo ""
echo "[2/4] Beralih ke branch $BRANCH_DOCKER..."
git checkout "$BRANCH_DOCKER"

# 3. Rebase ke main
echo ""
echo "[3/4] Rebase $BRANCH_DOCKER ke $BRANCH_MAIN..."
git rebase "$BRANCH_MAIN"

# 4. Push paksa ke remote
echo ""
echo "[4/4] Push $BRANCH_DOCKER ke remote..."
git push origin "$BRANCH_DOCKER" --force-with-lease

echo ""
echo "✅ Selesai! Branch $BRANCH_DOCKER sudah sinkron dengan $BRANCH_MAIN."

# Kembali ke main
git checkout "$BRANCH_MAIN"
echo "Kembali ke branch $BRANCH_MAIN."
