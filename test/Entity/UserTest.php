<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Entity;

use PHPUnit\Framework\TestCase;
use SimpleUserManager\Entity\User;

use function sprintf;
use function trim;

class UserTest extends TestCase
{
    public function testCanInitialiseEntityCorrectly(): void
    {
        $userData = [
            'id'         => 1,
            'first_name' => 'Matthew',
            'last_name'  => 'Setter',
            'email'      => 'matthew@example.org',
            'phone'      => '+610412123456',
        ];

        $user = new User(
            $userData['id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['email'],
            $userData['phone'],
        );

        $this->assertSame($userData['id'], $user->getId());
        $this->assertSame($userData['first_name'], $user->getFirstName());
        $this->assertSame($userData['last_name'], $user->getLastName());
        $this->assertSame($userData['email'], $user->getEmailAddress());
        $this->assertSame($userData['phone'], $user->getPhone());
        $this->assertSame(
            trim(sprintf("%s %s", $userData['first_name'], $userData['last_name'])),
            $user->getFullname()
        );
    }
}
