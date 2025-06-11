#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Define the target Nginx config file and the template
NGINX_TEMPLATE_CONF="/assets/nginx.template.conf"
NGINX_CONF="/nginx.conf"
PRESTART_SCRIPT="/assets/scripts/prestart.mjs"
CLIENT_MAX_BODY_SIZE_VALUE="64M" # Set your desired value here

echo "Custom Start: Running original prestart script..."
node "${PRESTART_SCRIPT}" "${NGINX_TEMPLATE_CONF}" "${NGINX_CONF}"

echo "Custom Start: Modifying ${NGINX_CONF} to add client_max_body_size..."

# Make a backup (optional, good for debugging if you SSH in)
# cp "${NGINX_CONF}" "${NGINX_CONF}.bak"

# Use awk to insert client_max_body_size into the http block.
# This looks for the line containing 'http {' and inserts our directive after it.
# It handles cases where 'http {' might have leading/trailing spaces.
awk -v size="${CLIENT_MAX_BODY_SIZE_VALUE}" '
{ print } # Print the current line
/^[[:space:]]*http[[:space:]]*\{/ { # Match lines starting with optional space, then 'http', optional space, then '{'
  print "    client_max_body_size " size ";" # Insert our directive with some indentation
}
' "${NGINX_CONF}" > "${NGINX_CONF}.tmp" && mv "${NGINX_CONF}.tmp" "${NGINX_CONF}"

echo "Custom Start: Verifying client_max_body_size in ${NGINX_CONF}..."
grep 'client_max_body_size' "${NGINX_CONF}" || echo "WARNING: client_max_body_size not found after modification!"

echo "Custom Start: Starting PHP-FPM and Nginx with modified config..."
# The original start command also includes php-fpm
php-fpm -y /assets/php-fpm.conf & # Start php-fpm in the background
nginx -c "${NGINX_CONF}"          # Start nginx in the foreground (as the main process)

PHP_VERSION_DIR=$(php -r 'echo PHP_INI_SCAN_DIR;' | sed 's|/conf.d$||') # Tries to get /etc/php/X.Y/fpm
TARGET_PHP_CONF_D="${PHP_VERSION_DIR}/conf.d"
CUSTOM_PHP_INI_SOURCE="./.nixpacks/php.ini" # Assuming it's copied to the app root by Nixpacks
CUSTOM_PHP_INI_TARGET="${TARGET_PHP_CONF_D}/99-nixpacks-custom.ini" # Use a name that loads late

if [ -f "${CUSTOM_PHP_INI_SOURCE}" ]; then
    echo "Custom Start: Attempting to copy ${CUSTOM_PHP_INI_SOURCE} to ${CUSTOM_PHP_INI_TARGET}..."
    mkdir -p "${TARGET_PHP_CONF_D}"
    cp "${CUSTOM_PHP_INI_SOURCE}" "${CUSTOM_PHP_INI_TARGET}"
    echo "Custom Start: Copied custom PHP ini."
else
    echo "Custom Start: WARNING - ${CUSTOM_PHP_INI_SOURCE} not found."
fi

echo "Custom Start: Starting PHP-FPM and Nginx with modified config..."

# Keep the script running if nginx exits (though nginx -g 'daemon off;' should keep it foreground)
wait