# Quorra mediawiki Managementsystem by Blue Engineering

[![Build Status](https://api.travis-ci.org/cakephp/app.png)](https://travis-ci.org/cakephp/app)

Quorra is a managementsystem to administrate a mediawiki installation. It's based on CakePHP Version 3.0 and works with mediawiki API.

Currently Quorra supports mediawiki Version 1.25 only. The support for older versions comes later.


##########################################################
## Required mediaWiki Extensions
##########################################################
# Quorra need some mediaWiki extensions to work.
#
# Lockdown (http://www.mediawiki.org/wiki/Extension:Lockdown)
#
##########################################################


##########################################################
## Installation
##########################################################
#
# 1. run git pull in your download directory
# 2. edit configuration file in config/quorra.php
#
##########################################################


##########################################################
## Changelog Version 1.0 Build 3
##########################################################
# legend:
#--------------------------------------------------------
# + new features
# o changes
# - removes
#
##########################################################
#
# + Parser between wikisyntax and HTML supports now:
#	-> bold text ( <strong></strong>, <b></b> )
#	-> italic text ( <em></em>, <i></i> )
#	-> headings
#	-> weblinks
# + Add Demosite MVC to testing the Quorra WYSITYG Editor without a mediawiki account (controller: src/Controller/DemositeController.php, views: src/Template/Demosite/*)
#
# o mediawiki api controller moves into MediawikiAPIComponent (src/Controller/Component/MediawikiAPIComponent.php)
# o MediawikiAPIComponent:
#	-> Fixing mw_createArticle()
# o edit quorra config
#	-> add 'version' variable
#	-> add 'srclink' variable where linked projectsite
#	-> renamed 'testuser' to 'demouser' and 'testpass' to 'demopass'
#
# - remove model file src/Model/ArticleModel.php
#
##########################################################


##########################################################
## Changelog Version 1.0 Build 2
##########################################################
# legend:
#--------------------------------------------------------
# + new features
# o changes
# - removes
#
##########################################################
#
# + create some frontend masks
# + create few placeholder
#
##########################################################


##########################################################
## Changelog Version 1.0 Build 1
##########################################################
# legend:
#--------------------------------------------------------
# + new features
# o changes
# - removes
#
##########################################################
#
# + create Quorra as CakePHP application
# + include Bootstrap framework in version 3.3.4
#
##########################################################