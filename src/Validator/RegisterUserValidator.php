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
 * @psalm-type ValidData = array{
 *     first_name: string,
 *     last_name: string,
 *     email: string,
 *     password: string
 * }
 * @extends InputFilter<ValidData>
 */
final class RegisterUserValidator extends InputFilter
{
    public function __construct()
    {
        $firstName = new Input("first_name");
        $firstName->setAllowEmpty(true);
        $firstName->getValidatorChain()
            ->attach(new StringLength([
                "max" => 50,
            ]));
        $firstName->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
            ->attach(new StripNewlines());

        $lastName = new Input("last_name");
        $lastName->setAllowEmpty(true);
        $lastName->getValidatorChain()
            ->attach(new StringLength([
                "max" => 50,
            ]));
        $lastName->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
            ->attach(new StripNewlines());

        $email = new Input("email");
        $email->getValidatorChain()
            ->attach(new EmailAddress());
        $email->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
            ->attach(new StripNewlines());

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

        $this->add($firstName);
        $this->add($lastName);
        $this->add($email);
        $this->add($password);
    }
}
