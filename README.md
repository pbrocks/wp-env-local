# wp-env-local

Starting Plugin for development, with using Production URLs

# README.md

Install the `wp-env` package globally.

 npm -g i @wordpress/env

Configure the Docker settings via the local json file.

```json
{
 "phpVersion": "7.4",
 "themes": [
  "."
 ],
 "mappings": {
  "wp-content/plugins": "./wp-core/plugins",
  "wp-content/themes": "./wp-core/themes"
 },
 "port": 7113,
 "env": {
  "tests": {
   "port": 7123
  }
 }
}
```

### To Start the docker container

Think of `wp-env` as an alias to `docker-compose`.

 wp-env start

With the above command run in your terminal, six (6) Docker containers, `wordpress`, `mysql`, and `cli` for running WordPress, and `tests-wordpress`, `tests-mysql`, and `tests-cli` for testing, will be created.

Your initial spin up may take a while to create the containers. When done, `wp-env` will return the URLs for accessing the sites and MySQL ports for your data.

% wp-env start
WordPress development site started at <http://localhost:7113>
WordPress test site started at <http://localhost:7123>
MySQL is listening on port 59662
MySQL for automated testing is listening on port 59757

### Delete Debug Log

 wp-env install-path

returns the full path to the container, e.g.:

 /Users/apple/.wp-env/2b41f13493045b7e8731365353b0b4b3

```sh
rm -rf /Users/apple/.wp-env/2b41f13493045b7e8731365353b0b4b3/WordPress/wp-content/debug.log
touch /Users/apple/.wp-env/2b41f13493045b7e8731365353b0b4b3/WordPress/wp-content/debug.log
```

### To Import Existing DB

 wp-env run cli db import ./db/ehuman-wpdb.sql.gz

### To Export DB

 wp-env run cli db export ./db/ehuman-wpdb-export.sql

### Rewrite Flush

  If not working, use Dashboard: Enter Settings > Permalinks and click “Save”.

wp-env run cli rewrite flush --hard

### To Reset DB

 wp-env run cli db reset --yes

### To update upload max size

 wp-env run cli vi .htaccess

then add:

 php_value upload_max_filesize "8M"

I also added “max_post_size” on next line.

### Going into the container shell

 wp-env run cli bash

 wp-env run cli wp pantheon session add-index

<https://bitbucket.org/wpengine/wpe-site-deploy/src/main/>
