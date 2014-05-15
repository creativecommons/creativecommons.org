# A Webroot [Composer](http://getcomposer.org) Library Installer

[![Build Status](https://secure.travis-ci.org/fancyguy/webroot-installer.png)](http://travis-ci.org/fancyguy/webroot-installer)

This is for PHP packages that support composer to configure in their `composer.json`.  It will
allow a root package to define a webroot directory and webroot package and magically install it
in the correct location.

## Example `composer.json` File

``` json
{
    "name": "fancyguy/www-fancyguy-com",
    "description": "Package to build www.fancyguy.com",
    "authors": [
        {
            "name": "Steve Buzonas",
            "email": "steve@fancyguy.com"
        }
    ],
    "repositories": [
        {
            "type": "webroot",
            "package": {
                "name": "wordpress/wordpress",
                "version": "3.5.1",
                "source": {
                    "type": "git",
                    "url": "https://github.com/WordPress/WordPress.git",
                    "reference": "3.5.1"
                },
                "require": {
                    "fancyguy/webroot-installer": "1.0.0"
                }
            }
        }
    ],
    "require": {
        "wordpress/wordpress": "3.5.*"
    },
    "extra": {
        "webroot-dir": "content",
        "webroot-package": "wordpress/wordpress"
    }
}
```

This would install the defined `wordpress/wordpress` package in the `content` directory of the project.
