<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Mezzio\Authentication\DefaultUser;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\RegisterUser\Adapter\DbAdapter;
use SimpleUserManagerTest\Service\DbTestTrait;

use function sprintf;

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
        false,
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
        true,
    ])]
    public function testCanRegisterUserAndHandleExceptions(array $details, bool $status): void
    {
        if (! $status) {
            $this->expectException(InvalidQueryException::class);
        }

        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter(),);
        $this->assertSame(
            $status,
            $middlewareAdapter->registerUser(
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
            )
        );
    }
}
