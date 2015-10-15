# [1.0.0](https://bitbucket.org/cottacush/phalcon-utils/src/f392c7569e523dc85f36e80300b32c01a7e9647a/?at=v1.0.0) (2015-09-11)
- Added `PhalconUtils/Mailer` to easily send mails

# [1.1.0](https://bitbucket.org/cottacush/phalcon-utils/src/4da5f8d8a539fc35277ccdf11a2ccdb239fb792f/?at=1.1.0) (2015-09-12)
- Changed `Mailer` class to `MailerHandler`
- Added `MailerHandler::getActualMessage` method to handle templating in email text and html body

# [1.1.1](https://bitbucket.org/cottacush/phalcon-utils/src/dfab344a7d083058fe0462d267c9eb77f517ec7e/?at=1.1.1) (2015-09-14)
- Fix issue with mailer namespace case sensitivity 

# [1.1.2](https://bitbucket.org/cottacush/phalcon-utils/src/bcedaeb787dd08635ad997d21452c54e95840274/?at=1.1.2) (2015-09-15)
- Modified `composer.json` to enable version compatibility

# [1.2.0](https://bitbucket.org/cottacush/phalcon-utils/src/18e47e8f3f3e5217345362b7f74f2c2c3c92b4d7?at=1.2.0) (2015-09-22)
- Added `PhalconUtils/S3` to handle AWS S3 actions

# [1.3.0](https://bitbucket.org/cottacush/phalcon-utils/src/8bbce471a0d15c3b6192d96251189413cca114bc/?at=1.3.0) (2015-10-07)
- Added `PhalconUtils/Validation` for custom Phalcon Validators and other Validation Utils
- Added `PhalconUtils/Validation/Validators/Model` to allow for model validation checks
- Added `PhalconUtils/Validation/Validators/NotExisting` to allow not-existing model validation checks
- Added `PhalconUtils/Validation/BaseValidation`
- Added `PhalconUtils/Validation/RequestValidation` to allow for validating request post data

# [1.3.1](https://bitbucket.org/cottacush/phalcon-utils/src/1ec7452c7c09c9ed694ad6cd9e69856da49b1921/?at=1.3.1) (2015-10-13)
- Fixed issue with `Model::findFirst` in `PhalconUtils/Validation/Validators/Model`

# [1.4.0](https://bitbucket.org/cottacush/phalcon-utils/src/567f3852e58966daa62e23bae624c1d8b43e42f6/?at=1.4.0) (2015-10-14)
- Added `PhalconUtils/Validation/Validators/NigerianPhoneNumber` to validate Nigerian phone numbers

# [1.5.0](https://bitbucket.org/cottacush/phalcon-utils/src/401a534f1125d2c9d433da12912c0f9545ba8d9a/?at=1.5.0) (2015-10-15)
- Added release script to help automate release process

# [1.6.0](https://bitbucket.org/cottacush/phalcon-utils/src/0de102641434bcc71edd14301550c8c5d3611f95/?at=1.6.0) (2015-10-15)
- Modified release script to allow change log updates

# [1.7.0](https://bitbucket.org/cottacush/phalcon-utils/src/22ce34027e692446302080cfbec994b11abf035d/?at=1.7.0) (2015-10-15)
- Added Slack update to release script
