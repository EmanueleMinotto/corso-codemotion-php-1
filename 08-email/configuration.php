<?php

require_once 'vendor/autoload.php';

session_start();

$conn = \Doctrine\DBAL\DriverManager::getConnection([
	'url' => 'sqlite:///blog.db',
]);