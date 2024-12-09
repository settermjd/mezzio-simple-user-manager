<?php

declare(strict_types=1);

namespace SimpleUserManager\Validator;

use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;

class ForgotPasswordValidator extends InputFilter
{
    public function __construct()
    {
        $email = new Input("email");
        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $this->add($email);
    }
}