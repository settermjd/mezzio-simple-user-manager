<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Mezzio\Authentication\DefaultUser;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\RegisterUser\Adapter\DbAdapter;
use SimpleUserManager\Service\RegisterUser\Result;
use SimpleUserManagerTest\Service\DbTestTrait;

class DbAdapterTest extends TestCase
{
    use DbTestTrait;

    #[TestWith([
        [
            'first_name' => 'matthew',
            'last_name'  => 'setter',
            'email'      => 'matthew@example.org',
            'phone'      => '+61123456789',
            'username'   => 'username',
            'password'   => 'password',
        ],
        Result::FAILURE,
    ])]
    #[TestWith([
        [
            'first_name' => 'matthew',
            'last_name'  => 'setter',
            'email'      => 'user@example.org',
            'phone'      => '+61123456789',
            'username'   => 'username',
            'password'   => 'password',
        ],
        Result::SUCCESS,
    ])]
    public function testCanRegisterUserAndHandleExceptions(array $details, int $code): void
    {
        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter());
        $result = $middlewareAdapter->registerUser(
            new DefaultUser(
                identity: "",
                details: [
                    "first_name" => $details["first_name"],
                    "last_name"  => $details["last_name"],
                    "email"      => $details["email"],
                    "phone"      => $details["phone"],
                    "username"   => $details["username"],
                    "password"   => $details["password"],
                ]
            )
        );

        $this->assertInstanceOf(Result::class, $result);
        $this->assertSame($code, $result->getCode());
    }
}
