sarapearce/Wordpress WPCLI Too to clean remote Intercom database
===================================

a command line tool for removing duplicate leads in the Intercom database

[![Build Status](https://travis-ci.org/sarapearce/cxl-intercom-lead-dedupe.svg?branch=master)](https://travis-ci.org/sarapearce/cxl-intercom-lead-dedupe)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing) | [Support](#support)

## Using

This package is used to use the WP-CLI tool to run a single command `wp cxl-intercom` to be able to dedupe/clean the Intercom database via the Intercom API.

After cloning the repo, be sure you are in the root directory, then run `wp cxl-intercom` which will then do all the api authentication and cleaning. 

NOTE: The keys are out of date, if you install and follow these directions, nothing will happen. 

To use this to clean your own Intercom API in Wordpress, update the command.php file with your own auth info, or point the auth to your appropriate environment variables.


## Installing

Installing this package requires WP-CLI v1.1.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with:

    wp package install git@github.com:sarapearce/cxl-intercom-lead-dedupe.git


## Support

Github issues aren't for general support questions, but there are other venues you can try: https://wp-cli.org/#support

## Versioning

This project can be found publicly at: https://github.com/sarapearce/cxl-intercom-lead-deduper

## Authors

* **Sara Pearce** - [Portfolio Site](http://sarapearce.net)

## License

This project is licensed under the GNU License - see the (https://www.gnu.org/licenses/gpl-3.0.en.html) for details

## Acknowledgments/Support Materials

* https://github.com/intercom/intercom-php
* https://github.com/wp-cli/scaffold-package-command
