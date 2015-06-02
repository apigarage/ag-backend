## Install LAMP/MAMP/WAMP Stack

## Setup Database

## Add the local config file named .env.local.php
```
<?php

return array(
    /* Database */
    'db_type'	     => '_______',
    'db_host'      => '_______',
    'db_name'      => '_______',
    'db_username'  => '_______',
    'db_password'  => '_______',
);
```
## Run Migrations
```
php artisan migrate
```

## Style Guide

camleCase for function names
CamleCase for model names
snake_case for variable names
plural table_names
4 space tabs
