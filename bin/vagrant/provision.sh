#!/bin/bash

echo "Starting sammui installation: " $(date)
composer create-project renatomefidf/sammui /vagrant/sammui dev-master
echo "Enabling sammui nginx virtualhost: " $(date)
sudo ln -s /vagrant/sammui/docs/nginx/vhost-sammui.conf /etc/nginx/sites-enabled/
echo "Finishing sammui installation: " $(date)
