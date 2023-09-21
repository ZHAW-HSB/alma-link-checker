# Alma Link Checker

## About
The Alma Link Checker is an utility tool programmed and maintained by the Zurich University Library of Applied Sciences. The goal of this tool is to provide an easy and streamlined way to update broken links in local electronic databases and portfolios in Alma.

## Technologies

* PHP
* jQuery
* Bootstrap

## Requirements

**Local Development**
* Ideally use [XAMPP](https://www.apachefriends.org/de/index.html) or [MAMPP](https://www.mamp.info/de/downloads/)

**Server Configuration**
* Apache 2.4+
* PHP 8.x+
* cUrl Extension activated in php.ini

**Misc**
* [Composer](https://getcomposer.org/)
* [Alma API Key](https://developers.exlibrisgroup.com/manage/keys/)

## Installation
**1. Local**

Download the source code and unzip it into the root directory of XAMPP / MAMPP, which is normally **xampp-installation/htdocs**

**2. Server**

For Debian based systems place the source code under **/var/www**

**3. Initialize Composer Package**

In the root directory of the source code initialize the composer package with the command:
```
composer install
```

**If you configure a virtual host please remember to set the directory root to "source-code-directory-name/public"**

## Customizing Options

| Files  | Option(s) |
| ------------- |:-------------:|
| configuration.php    | configurations                 |
| src/Application.php  | application logic              |
| resources/*          | html templates & translations  |
| public/css/style.css | style customizations           |
| public/js/index.js   | ui customizations              |

## Broken Link Update Options

* Generated link lists can be exported as Excel and fixed manually in Alma
* Links can directly be updated over the UI

## Multi-Language Support

**Configuration**

* Languages can be configured in configuration.php
* Labels can be set in translations/*
* Fallback language is "de"
* Pre-configured languages are de, en, fr, it

**Usage**
```
use App\Service\LanguageService;

LanguageService::getLabel("language_key");
```

## Cache Support

As the link checks may take several minutes (depending on the amount of broken links), a cache file will be generated after each check in the cache/* directory. The cache file can be deleted if no longer required.

## Screenshots

![Start View](/public/assets/screenshots/preview-screenshot-1.png?raw=true "Start View")

![Link List View](/public/assets/screenshots/preview-screenshot-2.png?raw=true "Link List View")
