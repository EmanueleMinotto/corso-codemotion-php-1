<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());

$app->get('/', function () use ($app) {
	ob_start();

	include(__DIR__.'/../index.php');

	$output = ob_get_contents();

	ob_end_clean();

    return $output;
});

$app
	->match('/post/{id}', function ($id) use ($app) {
		ob_start();

		include(__DIR__.'/../index.php');

		$output = ob_get_contents();

		ob_end_clean();

	    return $output;
	})
	->method('GET|POST');

$app->post('/api.php', function ($id) use ($app) {
	ob_start();

	include(__DIR__.'/../api.php');

	$output = ob_get_contents();

	ob_end_clean();

    return $output;
});

$app
	->match('/admin-login.php', function () use ($app) {
		$password = 'g7e3G6?vnKX>myRY';

		if (!empty($_POST['password']) && $_POST['password'] === $password) {
			$app['session']->set('admin', true);

			return $app->redirect('/');
		}

		ob_start();

		include(__DIR__.'/../templates/admin-login.php');

		$output = ob_get_contents();

		ob_end_clean();

	    return $output;
	})
	->method('GET|POST');

$app->get('/logout.php', function () use ($app) {
	$app['session']->remove('admin');

    return $app->redirect('/');
});

$app->error(function (\Exception $e, Request $request, $code) {
	ob_start();

	include(__DIR__.'/../index.php');

	$output = ob_get_contents();

	ob_end_clean();

    return new Response($output, $code);
});

$app->run();
