#!/bin/bash

echo "Starting sammui installation: " $(date)
echo "--------------------------------------------------------"
export COMPOSER_PROCESS_TIMEOUT=1200
composer create-project -n --keep-vcs renatomefidf/sammui /vagrant/sammui dev-master
echo "Removing old and wrong vhosts:"
sudo rm -rf /etc/nginx/sites-enabled/vhost-sammui*.conf
sudo ln -s /vagrant/sammui/docs/nginx/vhost-sammui.conf /etc/nginx/sites-enabled/
echo "Enabling sammui nginx virtualhost: " $([ "$?" == 0 ] && echo "ok" || echo "error")
nginx -t
echo "Testing nginx configuration: " $([ "$?" == 0 ] && echo "ok" || echo "error")
echo "Finishing sammui installation: " $(date)
echo "Checking installation:"
php /vagrant/sammui/app/check.php
echo "Restoring MongoDB for sammui:"
mongorestore /vagrant/sammui/docs/mongo/master/
echo "--------------------------------------------------------"
echo "---------- End   ---------------------------------------"