Tool to manage new OpenStreetMap users monitoring in a country.

Inspired by [OSM Welcome Belgium](https://github.com/osmbe/osm-welcome-belgium), uses the [newestosm feed](http://resultmaps.neis-one.org/newestosm.php) by Pascal Neis.

## Installation ##
To install:
* Have a working *AMP web server (Apache with mod-rewrite, PHP, MySQL)
* Clone this repository in a folder, run ```composer update``` to donwload the libraries, create a mysql database and import ```welcometool.sql```. 
* Register a new Oauth client in your OpenStreetMap user profile.
* Copy ```variables.sample.ini.php``` to ```variables.ini.php``` and modify this with your variables (database credentials, Oauth, feed url and main language).

To download new users, run ```cron_new_user.php``` either in the browser, or via command line (you could set it up as a cronjob running daily).

## Usage ##
From the admin section set up as many languages as you want with their ISO-alpha-2 code (at least one language, which needs to be registered in the ```mainLanguage``` parameter in the variables file). Now you can create "snippets" per each language (a title and a markdown text), which you will use to compose new messages.
