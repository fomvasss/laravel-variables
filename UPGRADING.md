# Upgrading

## From v4 to v5

The database schema for `variables` table have change, you should update the table accordingly.

- ~~`langcode`~~ -> `group`
- Update DB index

To rename the columns see [Laravel's Documenation on Modifiyng Columns](https://laravel.com/docs/9.x/migrations#modifying-columns)

Replace method: `setLang` -> `setGroup`

Replace argument commang: `--lang` -> `--group`
