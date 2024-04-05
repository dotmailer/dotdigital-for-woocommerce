Dotdigital for WooCommerce
======
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE)

## Requirements
This plugin requires you to have WooCommerce installed in WordPress (see [detailed version requirements and installation guide](readme.txt)).

## Installation

### Plugin directory
The plugin can be installed from the WordPress plugin directory as standard. 

### Manual installation via git
This repository no longer contains the required /vendor folder, because vendor dependencies should properly be prefixed before use. 

_Option 1 (no prefixing)_
- Clone this repository into wp-content/dotdigital-for-woocommerce
- Run:
```
composer install --no-dev
```

_Option 2 (with prefixing)_
- Clone this repository into wp-content
- Run:
```
cd wp-content/dotdigital-for-woocommerce
composer install --no-dev
wget https://github.com/humbug/php-scoper/releases/download/0.18.7/php-scoper.phar
composer prefix-dependencies
mv build_prefixed ../plugins/dotdigital-for-woocommerce
cd wp-content
rm -rf dotdigital-for-woocommerce/
```

## Release Notes
There is now a [Changelog](readme.txt) in the readme.txt file.

## Contribution
You are welcome to contribute to Dotdigital for WooCommerce! You can either:
* Report a bug: create a GitHub issue including description, repro steps, WooCommerce/WordPress and connector version
* Fix a bug: please clone this repo and submit your Pull Request
* Request a feature on our [community forum](https://support.dotdigital.com/hc/en-gb/community/topics/200432508-Feedback-and-feature-requests)
