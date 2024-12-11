<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser\Adapter;

use Mezzio\Authentication\UserInterface;
use SimpleUserManager\Service\RegisterUser\Result;

interface AdapterInterface
{
    public function registerUser(UserInterface $user): Result;
}
