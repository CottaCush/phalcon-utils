Phalcon Utils
=============
Phalcon Utils is a repository for libraries that are used across different phalcon projects.


Features
--------
* Mailer - Mail sending library
* S3 - AWS S3 library
* Validation - Custom Validators and Validation Utils



Contributors
------------
* Adeyemi Olaoye <yemi@cottacush.com>
* Adegoke Obasa <goke@cottacush.com>


Requirements
------------
* [Phalcon 2.0.*](https://docs.phalconphp.com/en/latest/reference/install.html)
* [Composer](https://getcomposer.org/doc/00-intro.md#using-composer)



Installation
------------
modify your composer.json

```json
    "require": {
        ...
        "vendor/phalcon-utils": "dev-master"
        ...
    },
    "repositories": [
         ...
        {
            "type": "vcs",
            "url":  " git@bitbucket.org:cottacush/phalcon-utils.git"
        },
        ...
    ]
```

run `composer update`











