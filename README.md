sammui
===============

[![Build Status](https://travis-ci.org/renatomefidf/sammui.svg?branch=master)](https://travis-ci.org/renatomefidf/sammui)
[![Code Climate](https://codeclimate.com/github/renatomefidf/sammui/badges/gpa.svg)](https://codeclimate.com/github/renatomefidf/sammui)
[![Test Coverage](https://codeclimate.com/github/renatomefidf/sammui/badges/coverage.svg)](https://codeclimate.com/github/renatomefidf/sammui)
[![Latest Stable Version](https://poser.pugx.org/renatomefidf/sammui/v/stable.svg)](https://packagist.org/packages/renatomefidf/sammui)
[![Total Downloads](https://poser.pugx.org/renatomefidf/sammui/downloads.svg)](https://packagist.org/packages/renatomefidf/sammui)
[![Latest Unstable Version](https://poser.pugx.org/renatomefidf/sammui/v/unstable.svg)](https://packagist.org/packages/renatomefidf/sammui)
[![License](https://poser.pugx.org/renatomefidf/sammui/license.svg)](https://packagist.org/packages/renatomefidf/sammui)


The Symfony Angular MongoDB Mobile UI Project

Original project by @flyers: https://github.com/FlyersWeb/angular-symfony 

Mirror: http://gitlab.renatomefi.com.br/renatomefi/sammui/commits/master 

Introduction
------------

This project is a template application with secured communication via a RestFul API between the client part with AngularJS and the server part with Symfony2.

In use libs and technologies
------------

- Symfony 2.6

- Doctrine MongoDB Bundle (doctrine/mongodb-odm-bundle) 3.0

- FOS Rest Bundle (friendsofsymfony/rest-bundle) 1.4

- FOS User Bundle (friendsofsymfony/user-bundle) 1.3.5

- Layout Mobile Angular UI - http://mobileangularui.com/

Automatic Installation
------------

Master:

	composer create-project renatomefidf/sammui sammui dev-master
	
Any tag:

	composer create-project renatomefidf/sammui sammui v0.0.1


Check the composer package at: https://packagist.org/packages/renatomefidf/sammui

Manual Installation
------------

Clone the project :

	git clone git@github.com:renatomefidf/sammui sammui

Update packages :

	cd angular-symfony
	composer.phar install

Configuration
-------------

Create cache and logs folders :

	mkdir app/cache
	mkdir app/logs
	chmod -R 777 app/cache
	chmod -R 777 app/logs

Edit database credentials :

	vim app/config/parameters.yml

To switch between ORM and ODM (Database and MongoDB) uncomment/comment the following lines on config.yml :
	
	# FOS User Bundle for ORM
    fos_user:
        db_driver: orm
        firewall_name: main
        user_class: Flyers\BackendBundle\Entity\User
    
    # FOS User Bundle for MongoDB
    fos_user:
        db_driver: mongodb
        firewall_name: main
        user_class: Flyers\BackendBundle\Document\User

Update schemas (FOSUserBundle) : * Not needed for MongoDB

 	php app/console doctrine:schema:create

Create and activate user :

	php app/console fos:user:create
	php app/console fos:user:activate

Link project to your webserver and access it :

	ln -snf ./ /var/www/html/angular-symfony
	firefox http://localhost/angular-symfony/ &

Want to run it via nginx? There is a sample virtual host on docs/nginx

Database
---------------------
If you want to use my current dev database you can get it at /docs/mongo/DATE_dump
I'm using mongodump tool, so it's easy to recover!

Authentication system
---------------------

The Authentication system is based on the custom Authentication Provider of the Symfony2 Cookbook : http://symfony.com/doc/2.1/cookbook/security/custom_authentication_provider.html

> The following chapter demonstrates how to create a custom authentication provider for WSSE authentication. The security protocol for WSSE provides several security benefits:
> * Username / Password encryption
> * Safe guarding against replay attacks
> * No web server configuration required
> 
> WSSE is very useful for the securing of web services, may they be SOAP or REST.

I used the exact same authentication system with a little change in moment of generating the digest, we use the hexadecimal value of the hashed seed in lieu of the binary value.

Client Side specifics
---------------------

On the client side, I've inspired my code from Nils Blum-Oeste article explaining how to send an authorization token for every request. To do this you have to register a wrapper for every resource actions that execute a specific code before doing the action. For more information you can check http://nils-blum-oeste.net/angularjs-send-auth-token-with-every--request/.

The differences there is that I send the token, username and user digest in the HTTP Header *X-WSSE*.

Conclusion
----------

You can use this template and adapt it to your needs.

@FlyersWeb

Adaptations by @renatomefidf

- MongoDB support

- Symfony and related dependencies/bundles update

- Packagist project