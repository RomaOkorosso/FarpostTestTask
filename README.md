# FarpostTestTask

### *Required PHP 7.4 or latest

### FOR STANDART USING:

To install composer use:

1. ``php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"``
2. ``php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"``
3. ``php composer-setup.php``
4. ``php -r "unlink('composer-setup.php');"``

To install phpunit use `php composer.phar install`

For starting use `cat <filename> | php analyze.php -u <min uptime percents> -t <max response time ms>`

* Change filename `in docker-compose.yml` if you need to storage logs in other file or change it path

To start tests use `vendor/bin/phpunit Test.php` or `phpunit Test.php` (depends on phpunit location)

### FOR DOCKER USING

use `docker-compose up -d`