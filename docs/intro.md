# Introduction

The project uses a combination of handlers, middleware, and service adapters to provide it's functionality.
The handlers, where applicable, provide forms for users to fill in and submit, such as when resetting their password, logging in, and registering for the first time.
Middleware handles these form submissions, relying on service adapters to interact with an underlying data source, where the information will be stored.

The default implementation uses laminas-db to interact with a backend database, such as SQLite.
However, each service adapter implements an applicable AdapterInterface, which each middleware class is provided.
So, if you prefer a different database layer, such as Doctrine, or a different backend storage layer, then feel free to create a new implementation.