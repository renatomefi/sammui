#!/bin/bash

echo "Starting sammui installation: " $(date)
echo "--------------------------------------------------------"
export COMPOSER_PROCESS_TIMEOUT=1200
composer self-update
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
echo "Creating MongoDB indexes and loading data"
php /vagrant/sammui/app/console --env=dev doctrine:mongodb:schema:create --index
php /vagrant/sammui/app/console --env=dev doctrine:mongodb:fixtures:load --append
php /vagrant/sammui/app/console --env=test doctrine:mongodb:schema:create --index
php /vagrant/sammui/app/console --env=test doctrine:mongodb:fixtures:load --append
mongorestore /vagrant/sammui/docs/mongo/master/
echo "--------------------------------------------------------"
echo "---------- End   ---------------------------------------"
