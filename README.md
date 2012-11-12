# SpiffyNavigation Module for Zend Framework 2 [![Master Branch Build Status](https://secure.travis-ci.org/spiffyjr/spiffy-navigation.png?branch=master)](http://travis-ci.org/spiffyjr/spiffy-navigation)

SpiffyNavigation is a navigation module for ZF2 intended to replace the out-of-date Zend\Navigation.

## Requirements
 - PHP 5.3 or higher
 - [Zend Framework 2](http://www.github.com/zendframework/zf2)

## Installation

Installation of SpiffyNavigation uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

#### Installation steps

  1. `cd my/project/directory`
  2. create a `composer.json` file with following contents:

     ```json
     {
         "require": {
             "spiffy/spiffy-navigation": "dev-master"
         }
     }
     ```
  3. install composer via `curl -s http://getcomposer.org/installer | php` (on windows, download
     http://getcomposer.org/installer and execute it with PHP)
  4. run `php composer.phar install`
  5. open `my/project/directory/configs/application.config.php` and add the following key to your `modules`:

     ```php
     'SpiffyNavigation',
     ```