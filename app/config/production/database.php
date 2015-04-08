<?php

return array(

	'default' => 'pgsql',

	'connections' => array(

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'      => $_ENV['db_host'],
			'database'  => $_ENV['db_name'],
			'username'  => $_ENV['db_username'],
			'password'  => $_ENV['db_password'],
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),

	),

);
