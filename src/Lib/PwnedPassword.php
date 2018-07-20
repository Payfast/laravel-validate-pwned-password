<?php

namespace PayFast\Lib;

class PwnedPassword
{
    public static $breachCount;

    /**
     * Validate function called by the validator
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        $hash = sha1($value);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pwnedpasswords.com/range/' . substr($hash, 0, 5));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP - Laravel Pwned Password Validator');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $results = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (($httpcode !== 200) || empty($results)) {
            //hmm if don't get normal 200 response code or empty result then just stop
            return true;
        }

        if (preg_match('/' . preg_quote(substr($hash, 5)) . ':([0-9]+)/ism', $results, $matches) == 1) {
            self::$breachCount = $matches[1];
            return false;
        }

        return true;
    }

    /**
     * Generate the error message displayed for a pwned password
     *
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed|string
     */
    public function message($message, $attribute, $rule, $parameters)
    {
        if ($message == 'validation.pwnedpassword') {
            $message = 'This :attribute is not secure. It appears :count times in the Pwned Passwords database of security breaches. For more information: https://haveibeenpwned.com/Passwords';
        }

        $message = str_replace(':ATTRIBUTE', strtoupper($attribute), $message);
        $message = str_replace(':Attribute', ucwords($attribute), $message);
        $message = str_replace(':attribute', $attribute, $message);
        $message = str_ireplace(':count', self::$breachCount, $message);
        return $message;
    }
}
