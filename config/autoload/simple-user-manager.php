<?php

return [
    "reset_password" => [
        "adapter" => [
            "db_adapter" => [
                "table" => "users",
                "password_column" => "password",
                "identity_column" => "email",
            ],
        ],
        "redirect_to" => "/reset-password",
        "validation" => [
            "password" => [
                "length" => [
                    "min" => 5,
                    "max" => 50,
                ],
            ],
        ],
    ],
    "forgot_password" => [
        "adapter" => [
            "db_adapter" => [
                "table" => "password_resets",
                "identity_column" => "user_id",
            ]
        ],
        "redirect_to" => "/forgot-password",
        "validation" => [
            "email" => [
                "allow" => [
                    "allow_local" => false,
                    "allow_dns" => true,
                    "allow_international_domain_names" => true,
                ],
                "use_deep_mx_check" => true,
                "hostname_validator" => true,
                "use_mx_check" => true,
            ],
        ],
    ],
    "register_user" => [
        "adapter" => [
            "db_adapter" => [
                "table" => "users",
            ],
        ],
    ],
];
