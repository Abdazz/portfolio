#!/usr/bin/env sh
# Daily backup: pg_dump → gzip → Cloudflare R2
# Expected env vars (set via docker-compose.prod.yml or .env.prod):
#   DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
#   R2_BUCKET, R2_ENDPOINT, AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY
#   BACKUP_RETENTION_DAYS (default: 14)

set -eu

TIMESTAMP=$(date -u '+%Y%m%d_%H%M%S')
BACKUP_FILE="/tmp/portfolio_${TIMESTAMP}.sql.gz"
RETENTION_DAYS="${BACKUP_RETENTION_DAYS:-14}"

echo "[backup] Starting PostgreSQL dump — ${TIMESTAMP}"

PGPASSWORD="${DB_PASSWORD}" pg_dump \
    -h "${DB_HOST:-postgres}" \
    -p "${DB_PORT:-5432}" \
    -U "${DB_USERNAME}" \
    -d "${DB_DATABASE}" \
    --no-password \
    | gzip > "${BACKUP_FILE}"

echo "[backup] Uploading to R2 bucket: ${R2_BUCKET}"

aws s3 cp "${BACKUP_FILE}" \
    "s3://${R2_BUCKET}/postgres/${TIMESTAMP}.sql.gz" \
    --endpoint-url "${R2_ENDPOINT}" \
    --no-progress

rm -f "${BACKUP_FILE}"

echo "[backup] Pruning backups older than ${RETENTION_DAYS} days"

CUTOFF=$(date -u -d "${RETENTION_DAYS} days ago" '+%Y-%m-%dT%H:%M:%SZ' 2>/dev/null \
    || date -u -v-${RETENTION_DAYS}d '+%Y-%m-%dT%H:%M:%SZ')

aws s3 ls "s3://${R2_BUCKET}/postgres/" \
    --endpoint-url "${R2_ENDPOINT}" \
    | awk '{ print $4 }' \
    | while read -r key; do
        FILE_DATE=$(echo "${key}" | grep -oE '[0-9]{8}' | head -1 || true)
        if [ -n "${FILE_DATE}" ] && [ "${FILE_DATE}" -lt "$(date -u -d "${CUTOFF}" '+%Y%m%d' 2>/dev/null || date -u -j -f '%Y-%m-%dT%H:%M:%SZ' "${CUTOFF}" '+%Y%m%d')" ]; then
            echo "[backup] Deleting old backup: ${key}"
            aws s3 rm "s3://${R2_BUCKET}/postgres/${key}" --endpoint-url "${R2_ENDPOINT}"
        fi
    done

echo "[backup] Done."
