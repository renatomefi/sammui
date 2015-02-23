#!/bin/bash

echo "Starting sammui installation: " $(date)
export COMPOSER_PROCESS_TIMEOUT=900
composer create-project renatomefidf/sammui /vagrant/sammui dev-master
sudo ln -s /vagrant/sammui/docs/nginx/vhost-sammui.conf /etc/nginx/sites-enabled/
echo "Enabling sammui nginx virtualhost: " $([ "$?" == 0 ] && echo "ok" || echo "error")
echo "Finishing sammui installation: " $(date)
echo "Checking installation:"
php /vagrant/sammui/app/check.php
