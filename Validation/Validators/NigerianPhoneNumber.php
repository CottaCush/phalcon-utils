<?php


namespace PhalconUtils\Validation\Validators;

use Phalcon\Validation;

/**
 * Class NigerianPhoneNumber
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
class NigerianPhoneNumber extends BaseValidator
{

    /**
     * Executes the validation
     *
     * @param mixed $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(Validation $validation, $attribute)
    {
        $includeCountryCode = $this->getOption('must_include_country_code', false);
        $includePlus = $this->getOption('must_include_plus', false);
        $pattern = '/^' . '[+]' . (($includePlus) ? '' : '?') . (($includeCountryCode) ? '234' : '') . '[0]' . (($includeCountryCode) ? '?' : '') . '[\d]{10}$/';
        $this->setOption('pattern', $pattern);

        if (!preg_match($pattern, $validation->getValue($attribute))) {
            $this->addMessageToValidation($validation, 'Invalid :field supplied', $attribute, 'NigerianPhoneNumber');
            return false;
        } else {
            return true;
        }
    }
}