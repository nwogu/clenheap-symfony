<?php
namespace App\Service;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class CheckNumber
{


    public function checkNumber($number)
    {
        $bool = true;
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($number, "NG");
        }catch (\libphonenumber\NumberParseException $e) {
            $bool = false;
        }
        if ($bool === false){
            return $bool;
        }else{
        return $phoneUtil->isValidNumber($phoneNumber);
        }
    }

        
}