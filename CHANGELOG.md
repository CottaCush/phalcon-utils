# [1.0.0](https://github.com/CottaCush/phalcon-utils/releases/tag/v1.0.0) (2015-09-11)
- Added `PhalconUtils/Mailer` to easily send mails

# [1.1.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.1.0) (2015-09-12)
- Changed `Mailer` class to `MailerHandler`
- Added `MailerHandler::getActualMessage` method to handle templating in email text and html body

# [1.1.1](https://github.com/CottaCush/phalcon-utils/releases/tag/1.1.1) (2015-09-14)
- Fix issue with mailer namespace case sensitivity 

# [1.1.2](https://github.com/CottaCush/phalcon-utils/releases/tag/1.1.2) (2015-09-15)
- Modified `composer.json` to enable version compatibility

# [1.2.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.2.0) (2015-09-22)
- Added `PhalconUtils/S3` to handle AWS S3 actions

# [1.3.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.3.0) (2015-10-07)
- Added `PhalconUtils/Validation` for custom Phalcon Validators and other Validation Utils
- Added `PhalconUtils/Validation/Validators/Model` to allow for model validation checks
- Added `PhalconUtils/Validation/Validators/NotExisting` to allow not-existing model validation checks
- Added `PhalconUtils/Validation/BaseValidation`
- Added `PhalconUtils/Validation/RequestValidation` to allow for validating request post data

# [1.3.1](https://github.com/CottaCush/phalcon-utils/releases/tag/1.3.1) (2015-10-13)
- Fixed issue with `Model::findFirst` in `PhalconUtils/Validation/Validators/Model`

# [1.4.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.4.0) (2015-10-14)
- Added `PhalconUtils/Validation/Validators/NigerianPhoneNumber` to validate Nigerian phone numbers

# [1.5.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.5.0) (2015-10-15)
- Added release script to help automate release process

# [1.6.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.6.0) (2015-10-15)
- Modified release script to allow change log updates

# [1.7.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.0) (2015-10-15)
- Added Slack update to release script

# [1.7.1](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.1) (2015-10-15)
- Fixed issue with slack channel update

# [1.7.2](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.2) (2015-10-15)
- Fixed issue with slack update text

# [1.7.3](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.3) (2015-10-15)
- Finally Fixed issue with Slack Channel Release Updates

# [1.7.4](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.4) (2015-10-16)
- Fixed bug in Model Validator

# [1.7.5](https://github.com/CottaCush/phalcon-utils/releases/tag/1.7.5) (2015-10-19)
- Fixed null binding bug `PhalconUtils/Validation/Validators/Model`
- Added `PhalconUtils/Validation/BaseValidation::reset()` to allow for reseting validation after setting data or namespace

# [1.8.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.8.0) (2015-11-16)
- Fixed issue with setting data in `PhalconUtils/BaseValidation::validate()`
- Added PhalconUtils/Validation/Validators/IMEINumber` to allow for IMEI number validation

# [1.9.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.9.0) (2015-11-20)
- Allow disabling of messages in validators by setting `append_message` option to `false`

# [1.10.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.10.0) (2015-11-22)
- Added `PhalconUtils\Validation\Validators\InlineValidator` to allow for inline validations using closures

# [1.10.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.10.1) (2016-12-02)
- Fix issue with `cancelOnFail` not working for InlineValidator

# [1.11.0](https://github.com/CottaCush/phalcon-utils/releases/tag/1.11.0) (2017-05-02)
- Added optional `code` Validator options 

# [2.0.0](https://github.com/CottaCush/phalcon-utils/releases/tag/2.0.0) (2017-05-30)
- Update dependencies to support PHP >=7.0 and Phalcon >=3.0

# [2.0.1](https://github.com/CottaCush/phalcon-utils/releases/tag/2.0.1) (2017-05-30)
- Update PHPUnit Version to support PHP versions lower than 7

# [2.0.2](https://github.com/CottaCush/phalcon-utils/releases/tag/2.0.2) (2017-06-29)
- Add CommonValidations to handle common data validations
- Allow custom messages for `PhalconUtils\Validation\Validators\InlineValidator`
- Pass Validation object into `PhalconUtils\Validation\Validators\InlineValidator` closure
- Add `PhalconUtils\Util\ArrayUtils` with commonly used array functions