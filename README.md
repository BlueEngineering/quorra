# Quorra mediawiki Managementsystem by Blue Engineering

[![Build Status](https://api.travis-ci.org/cakephp/app.png)](https://travis-ci.org/cakephp/app)

Quorra is a managementsystem to administrate a mediawiki installation. It's based on CakePHP Version 3.0 and works with mediawiki API.

Currently Quorra supports mediawiki Version 1.25 only. The support for older versions comes later.



# Required mediaWiki Extensions

Quorra need some mediaWiki extensions to work:
+ Lockdown (http://www.mediawiki.org/wiki/Extension:Lockdown)



# Installation

1. run git pull in your download directory
2. edit configuration file in config/quorra.php



# Changelogs

## Version 1.0 Build 5
**new features:**
* Users MVC
  * adding methods add(), addbycsv() and create() to create users
  * adding method edit() to edit userinformations
  * adding hasMany relation to Groups MVC
* adding Groups MVC, an ACL table
  * some ACL little checks
* adding Seminar MVC
	* adding seminar model with table informations
	* adding method index() to list existing seminars
	* adding method add() to create a seminar
* MediawikiAPIComponent:
  * Add mw_getUserinfos() to receive userinformations of specific user (old mw_getUserinfos() method are renamed)
  * Add mw_createUseraccount() to create useraccounts in mediawiki system
  * Add mw_getUserrightToken() to receive an userright token. This is needed for editing userrights in mediawiki system
  * Add mw_editUserrights() to edit userrights of a specific user

**changes / bugfixing:*
* Demosite MVC
  * Fixing overwrite mediawiki session if user are logged.
* MediawikiAPIComponent:
  * Fixing incorrect cookie settings in mw_createArticle(), mw_editArticle() and mw_getEditToken()
  * Renamed mw_getUserinfos() to mw_getMyUserinfos()

## Version 1.0 Build 4
**new features:**
* adding Users MVC with authentification 
  * controller: src/Controller/UsersController.php
  * model: src/Model/Table/UsersTable.php
  * view: src/Template/Users/*
* MediawikiAPIComponent:
  * Adding mw_getUserinfos()

**changes / bugfixing:**
* MediawikiAPIComponent:
  * Fixing mw_createArticle()



## Version 1.0 Build 3
**new features:**
* Parser between wikisyntax and HTML supports now:
  * bold text
  * italic text
  * headings
  * weblinks
* Add Demosite MVC to testing the Quorra WYSITYG Editor without a mediawiki account
  * controller: src/Controller/DemositeController.php
  * views: src/Template/Demosite/*

**changes:**
* mediawiki api controller moves into MediawikiAPIComponent
  * src/Controller/Component/MediawikiAPIComponent.php
* edit quorra config
  * add 'version' variable
  * add 'srclink' variable where linked projectsite
  * renamed 'testuser' to 'demouser' and 'testpass' to 'demopass'

**removes:**
* remove model file src/Model/ArticleModel.php



## Version 1.0 Build 2
**new features:**
* create some frontend masks
* create few placeholder



## Version 1.0 Build 1
**new features:**
* create Quorra as CakePHP application
* include Bootstrap framework in version 3.3.4