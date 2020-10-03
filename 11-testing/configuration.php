<?php

require_once 'vendor/autoload.php';

session_start();

$conn = \Doctrine\DBAL\DriverManager::getConnection([
	'url' => getenv('CLEARDB_DATABASE_URL') ?: 'sqlite:///blog.db',
]);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	mail(
		'admin@example.com',
		'Errore nel blog',
		sprintf(
			"Errore %d: %s\n\nalla linea %d del file %s",
			$errno,
			$errstr,
			$errline,
			$errfile
		)
	);
});

set_exception_handler(function ($exception) {
	mail(
		'admin@example.com',
		'Eccezione nel blog',
		sprintf(
			"Eccezione %d: %s\n\nalla linea %d del file %s\n\nstacktrace: %s",
			$exception->getCode(),
			$exception->getMessage(),
			$exception->getLine(),
			$exception->getFile(),
			$exception->getTraceAsString()
		)
	);
});