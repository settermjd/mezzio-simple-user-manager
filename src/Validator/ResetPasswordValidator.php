<?php

declare(strict_types=1);

namespace SimpleUserManager\Validator;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Identical;
use Laminas\Validator\StringLength;

/**
 * This class provides input validation and filtering for the reset password
 * process. It ensures that the password provided meets the validation
 * requirements and doesn't contain extraneous, malicious data.
 */
final class ResetPasswordValidator extends InputFilter
{
    public function __construct()
    {
        $password = new Input("password");
        $password->getValidatorChain()
            ->attach(new StringLength([
                "min" => 5,
                "max" => 50,
            ]))
            ->attach(new Identical([
                "token" => "confirm_password",
            ]));
        $password->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
            ->attach(new StripNewlines());

        $email = new Input("email");
        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $this->add($password);
        $this->add($email);
    }
}
