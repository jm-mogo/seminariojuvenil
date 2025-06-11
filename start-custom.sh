#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Nginx Configuration ---
NGINX_TEMPLATE_CONF="/assets/nginx.template.conf"
NGINX_CONF="/nginx.conf"
PRESTART_SCRIPT="/assets/scripts/prestart.mjs"
CLIENT_MAX_BODY_SIZE_VALUE="64M" # Set your desired Nginx value here (e.g., 64M)

echo "Custom Start: Running original prestart script for Nginx..."
node "${PRESTART_SCRIPT}" "${NGINX_TEMPLATE_CONF}" "${NGINX_CONF}"

echo "Custom Start: Modifying ${NGINX_CONF} to add client_max_body_size..."
awk -v size="${CLIENT_MAX_BODY_SIZE_VALUE}" '
{ print }
/^[[:space:]]*http[[:space:]]*\{/ {
  print "    client_max_body_size " size ";"
}
' "${NGINX_CONF}" > "${NGINX_CONF}.tmp" && mv "${NGINX_CONF}.tmp" "${NGINX_CONF}"

echo "Custom Start: Verifying client_max_body_size in ${NGINX_CONF}..."
grep 'client_max_body_size' "${NGINX_CONF}" || echo "Custom Start: WARNING - client_max_body_size not found in Nginx config after modification!"

# --- PHP Configuration & Start ---
# Desired PHP settings
PHP_POST_MAX_SIZE="64M"       # e.g., 64M
PHP_UPLOAD_MAX_FILESIZE="64M" # e.g., 64M
PHP_MEMORY_LIMIT="128M"     # e.g., 128M

echo "Custom Start: Starting PHP-FPM with custom INI settings and Nginx..."
php-fpm \
    -y /assets/php-fpm.conf \
    -d post_max_size="${PHP_POST_MAX_SIZE}" \
    -d upload_max_filesize="${PHP_UPLOAD_MAX_FILESIZE}" \
    -d memory_limit="${PHP_MEMORY_LIMIT}" & # Start php-fpm in the background

NGINX_PID=$! # Capture PHP-FPM PID (though it's backgrounded)

nginx -c "${NGINX_CONF}" # Start nginx in the foreground

# Wait for PHP-FPM if needed, or just let nginx be the main process
# If nginx exits, the container will stop.
wait $NGINX_PID # This might not be strictly necessary if nginx is foreground.