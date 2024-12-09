# Introduction

The project uses a combination of handlers, middleware, and service adapters to provide it's functionality.
The handlers, where applicable, provide forms for users to fill in and submit, such as when resetting their password, logging in, and registering for the first time.
Middleware handles these form submissions, relying on service adapters to interact with an underlying data source, where the information will be stored.

The default implementation uses laminas-db to interact with a backend database, such as SQLite.
However, each service adapter implements an applicable AdapterInterface, which each middleware class is provided.
So, if you prefer a different database layer, such as Doctrine, or a different backend storage layer, then feel free to create a new implementation.

## Getting started

To start using the project, from within an existing Mezzio project, use Composer to integrate the library, using the following command.

```bash
composer require settermjd/mezzio-simple-user-manager
```

As well as adding the project's source files to your application's vendor directory, the command: 

- Adds the project's [ConfigProvider][laminas-configprovider-url] to the application's ConfigProvider list
- Copies the project's default configuration to the _config/autoload_ directory

Now, through the project's ConfigProvider file, the application will have several new routes:

| Route | Description |
|---|---|
| /forgot-password | Let's the user start the reset password process |
| /login | Let's the user login |
| /logout | Let's the user log out of their current session |
| /register | Let's a new user register with the application |
| /reset-password | Let's a user reset their password |
| /user/profile | Let's a user view their profile |

<!-- Page Links -->
[laminas-configprovider-url]: https://docs.laminas.dev/laminas-config-aggregator/config-providers/