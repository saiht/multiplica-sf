Multiplica color API
=========================

Made with ❤️, by me, with the following technologies:
- [Manala](http://www.manala.io/)
- [Symfony](https://symfony.com)
- [Api Platform](https://api-platform.com/)

### Description

This project allows designers to have access to colors, in order to integrate them into their graphic charters.


## Installation

### Requirements

|Tool|Version|Installation/Download|
|-----|-----|-----|
|VirtualBox|5.1.18+|https://www.virtualbox.org/wiki/Downloads|
|Vagrant|1.8.7|https://releases.hashicorp.com/vagrant/|
|Landrush|1.2.0+|`$ vagrant plugin install landrush`|

Fox OS X, vagrant version 1.8.7 might require the following patch:

```bash
sudo rm -f /opt/vagrant/embedded/bin/curl
```

### Getting started
    
To install the project the first time and boot the VM, execute:
```bash
make setup # or vagrant up
```

Now you can access the API docs by browsing to http://multiplica.test/api/docs.

If you don't want to use the vm, you can simply install the project with:
```bash
composer install
```
Then, maybe you need some `colors` in your database?
For that, you need to update the variable `DATABASE_URL` in the `.env` file, and execute:
```bash
composer do-work
```
which will create the database, creating schema, and importing the `colors.csv` data.
See more about the command in ### Miscellaneous section below.

How to serve the application out of the vm:
```bash
php -S localhost:8000 -t public
```

### Endpoints

On this endpoint you can use the provided sandbox to experiment all routes:

| Name                        |Method    |   Path                       |
|-----------------------------|----------|------------------------------|
| api_doc                     |ANY       | /api/docs.{_format}          |
| api_colors_get_collection   |GET       | /api/colors                  |
| api_colors_post_collection  |POST      | /api/colors                  |
| api_colors_get_item         |GET       | /api/colors/{id}             |
| api_colors_patch_item       |PATCH     | /api/colors/{id}             |
| api_colors_put_item         |PUT       | /api/colors/{id}             |
| api_colors_delete_item      |DELETE    | /api/colors/{id}             |

The retrieved contents by the Color API corresponds to asked `Content-Type` header.
Allowed types are:
- `application/json`
- `application/xml`
- `application/html`

Here is an example of color list XML response:
```xml
<?xml version="1.0"?>
<response>
  <item key="0">
    <id>1</id>
    <name>cerulean</name>
    <color>#98B2D1</color>
  </item>
  <item key="1">
    <id>2</id>
    <name>fuchsia rose</name>
    <color>#C74375</color>
  </item>
  <item key="2">
    <id>3</id>
    <name>true red</name>
    <color>#BF1932</color>
  </item>
    . . .
</response>
```
The listing result can also be paginate by adding the `page` query parameter, like here:
http://multiplica.test/api/colors?page=1 will render the first 20 colors.


**Note:**

For the POST route (api_colors_post_collection), the new color entity must pass those rules:
- name: not blank (null or empty string), 3 characters min
- color: '#' character + the color code (examples: #fff, #FFF or #0101DF")
- pantone_value: not blank, 3 characters min
- year: not blank, 1900-2099 range

### Miscellaneous

If you want to see the format of the Color API, you can use:

```bash
make api-export
```

If you have more colors entries you want to import, you can run the new command:
```bash
vagrant ssh -- "cd /srv/app && php bin/console app:seed-colors <file>"
```
(Or directly `bin/console app:seed-colors <file>`if you don't want to use serve the application inside the vm)

Don't forget to replace `<file>` by the path of your csv file.

Note that you csv file MUST have the `,` delimiter, and those columns:

|id|name|year|color|pantone_value|
|--|----|----|-----|-------------|


### Destroy vms

To destroy entirely the vms, you can use:

```bash
make force-destroy
```

:warning: it will also drop the project database!
