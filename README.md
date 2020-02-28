# Ambientia_CollectorFoomanSurcharge
## TL;DR
This Magento 2 module improves the compatibility between Customweb_CollectorCw and Fooman_Surcharge modules.

* Fixes issues with tax calculation in a sucrharge invoce items
* Adds support for multiple surcharges

## Installation
```
$ cd {magento_base_dir}
$ composer config repositories.collector-fooman-surcharge vcs git@github.com:ambientiaoy/magento2-collector-fooman-surcharge.git
$ composer require ambientia/collector-fooman-surcharge
$ bin/magento setup:upgrade
```
