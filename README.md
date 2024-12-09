# Mezzio Simple User Manager

This is a small and simplistic user manager for Mezzio-based applications. 

It's not intended to be too feature-rich. 
Rather, it's meant to be a simple user manager that provides the following functionality:

- Login
- Logout
- New user registration
- User profile; and 
- Password reset 

It's heavily inspired by [mezzio-auth-url][Mezzio Authentication] and [mezzio-auth-laminas-auth-url][mezzio-authentication-laminasauthentication].

For more information, [check out the documentation](./docs).

## Prerequisites

To use the package, you'll need the following:

- PHP 8.3 or above
- One of the following extensions (based on your desired database):
  - [MySQL via the PDO extension](https://www.php.net/manual/en/ref.pdo-mysql.php)
  - [PostgreSQL via the PDO extension](https://www.php.net/manual/en/ref.pdo-pgsql.php)
  - [SQLite via the PDO extension](https://www.php.net/manual/en/ref.pdo-sqlite.php)
  - [The ext/ibm_db2 driver](https://www.php.net/manual/en/ibm-db2.installation.php)
  - [The ext/mysqli driver](https://www.php.net/manual/en/intro.mysqli.php)
  - [The ext/oci8 driver](https://www.php.net/manual/en/intro.oci8.php)
  - [The ext/pgsql driver](https://www.php.net/manual/en/intro.pgsql.php)
  - [The ext/sqlsrv driver (from Microsoft)](https://www.php.net/manual/en/intro.sqlsrv.php)
- Access to one of the following databases:
 - SQLite
 - IBM DB2
 - Microsoft SQLServer
 - MySQL or MariaDB
 - Oracle
 - PostgreSQL

## Contributing

If you want to contribute to the project, whether you have found issues with it or just want to improve it, here's how:

- [Issues][issues-url]: ask questions and submit your feature requests, bug reports, etc
- [Pull requests][prs-url]: send your improvements

## Did You Find The Project Useful?

If the project was useful and you want to say thank you and/or support its active development, here's how:

- Add a GitHub Star to the project
- Write an interesting article about the project wherever you blog

## Disclaimer

No warranty expressed or implied. Software is as is.

[mezzio-auth-url]: https://docs.mezzio.dev/mezzio-authentication
[mezzio-auth-laminas-auth-url]: https://docs.mezzio.dev/mezzio-authentication-laminasauthentication
[issues-url]: https://github.com/settermjd/mezzio-simple-user-manager/issues/new/choose
[prs-url]: https://github.com/settermjd/mezzio-simple-user-manager/pulls