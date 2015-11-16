<?php


namespace PhalconUtils\Validation\Validators;

/**
 * Class IMEINumber
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package PhalconUtils\Validation\Validators
 */
class IMEINumber extends BaseValidator
{

    /**
     * Executes the validation
     *
     * @param mixed $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        if (!$this->validateImei($validation->getValue($attribute))) {
            $this->addMessageToValidation($validation, 'Invalid :field supplied', $attribute, 'IMEINumber');
            return false;
        } else {
            return true;
        }
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $imei
     * @return bool
     * @credits http://www.phpsnaps.com/snaps/view/imei-validation-luhn-check/
     */
    private function validateImei($imei)
    {
        if (!preg_match('/^[0-9]{15}$/', $imei)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 14; $i++) {
            $num = $imei[$i];
            if (($i % 2) != 0) {
                $num = $imei[$i] * 2;
                if ($num > 9) {
                    $num = (string)$num;
                    $num = $num[0] + $num[1];
                }
            }
            $sum += $num;
        }
        if ((($sum + $imei[14]) % 10) != 0) return false;
        return true;
    }
}