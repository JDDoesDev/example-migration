INTRODUCTION
------------

This module is designed to consume data from a JSON endpoint and migrate into
custom content types.

REQUIREMENTS
------------

Since this module is custom and not packaged in composer, there are a few
dependencies that will need to be added to your Drupal installation before
installing this module.

* [Migrate Plus](https://drupal.org/project/migrate_plus) - A module that adds some source plugins for consuming JSON data.
* [Migrate Tools](https://drupal.org/project/migrate_tools) - A module that includes a simple UI and Drush commands for running migrations.

INSTALLATION INSTRUCTIONS
------------

1. Add Migrate Plus and Migrate Tools via Composer `composer require drupal/migrate_plus` and `composer require drupal/migrate_tools`.
2. Enable the `example_migration` module with Drush. `drush en example_migration`
3. Allow required dependencies to be installed as well.
4. If using Migrate Tools, enable that module `drush en migrate_tools`.

Installing the Example Migrations module will also install the User and Companies content types.

RUNNING MIGRATIONS
------------

Migrate Tools includes the following Drush commands:
* `migrate:status` - Lists migrations and their status.
* `migrate:import` - Performs import operations.
* `migrate:rollback` - Performs rollback operations.
* `migrate:stop` - Cleanly stops a running operation.
* `migrate:reset-status` - Sets a migration status to Idle if it's gotten stuck.
* `migrate:messages` - Lists any messages associated with a migration import.
* `migrate:fields-source` - List the fields available for mapping in a source

Run the migrations via Drush by using `drush migrate:import companies` and
`drush migrate:import users`.

After the migrations run, you can rollback with `drush migration:rollback [ID]`
or verify the content has been created by visiting http://sitename/admin/content
or http://sitename/migrated-content.
