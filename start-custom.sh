#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Nginx Configuration ---
NGINX_TEMPLATE_CONF="/assets/nginx.template.conf"
NGINX_CONF="/nginx.conf"
PRESTART_SCRIPT="/assets/scripts/prestart.mjs"
CLIENT_MAX_BODY_SIZE_VALUE="64M" # Set your desired Nginx value here

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


# --- PHP Configuration ---
# Source of your custom php.ini (copied by Nixpacks to /app/.nixpacks/php.ini)
CUSTOM_PHP_INI_SOURCE="/app/.nixpacks/php.ini"

# Determine PHP's scan directory for additional .ini files
# This assumes PHP CLI's php.ini scan dir is the same or relevant for FPM, which is usually true for conf.d
# A more robust way would be to get this from php-fpm -i if possible, but this is a good heuristic
PHP_SCAN_DIR=$(php -r "echo ini_get('scan_dir') ?: (dirname(php_ini_loaded_file()) . '/conf.d');" 2>/dev/null || echo "/etc/php/$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')/fpm/conf.d")
# Fallback if the above fails to give a sensible path
if [ -z "$PHP_SCAN_DIR" ] || [ ! -d "$PHP_SCAN_DIR" ]; then
    PHP_MAJOR_MINOR=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
    PHP_SCAN_DIR="/etc/php/${PHP_MAJOR_MINOR}/fpm/conf.d" # Common default path structure
fi

TARGET_PHP_CUSTOM_INI="${PHP_SCAN_DIR}/99-nixpacks-custom.ini" # Use 99- to load it late, potentially overriding others

if [ -f "${CUSTOM_PHP_INI_SOURCE}" ]; then
    echo "Custom Start: PHP scan directory determined as: ${PHP_SCAN_DIR}"
    echo "Custom Start: Attempting to copy ${CUSTOM_PHP_INI_SOURCE} to ${TARGET_PHP_CUSTOM_INI}..."
    mkdir -p "${PHP_SCAN_DIR}" # Ensure the directory exists
    cp "${CUSTOM_PHP_INI_SOURCE}" "${TARGET_PHP_CUSTOM_INI}"
    echo "Custom Start: Copied custom PHP ini."
    echo "Custom Start: Content of custom PHP ini (${TARGET_PHP_CUSTOM_INI}):"
    cat "${TARGET_PHP_CUSTOM_INI}" # Log the content for verification
else
    echo "Custom Start: WARNING - Custom PHP ini source ${CUSTOM_PHP_INI_SOURCE} not found."
fi


# --- Start Services ---
echo "Custom Start: Starting PHP-FPM and Nginx..."
php-fpm -y /assets/php-fpm.conf & # Start php-fpm in the background
nginx -c "${NGINX_CONF}"          # Start nginx in the foreground (as the main process)

# Keep the script running
wait