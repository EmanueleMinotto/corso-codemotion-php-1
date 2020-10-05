<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'url' => getenv('CLEARDB_DATABASE_URL') ?: sprintf('sqlite:///%s/blog.db', __DIR__.'/..'),
    ),
));

$app->get('/', function () use ($app) {
	$articoli = $app['db']->fetchAllAssociative('select * from articoli');

    return $app['twig']->render('home-page.html.twig', array(
        'articoli' => $articoli,
        'admin' => $app['session']->get('admin') ?: false,
    ));
});

$app
	->match('/post/{id}', function ($id) use ($app) {
		$articoli = $app['db']->fetchAllAssociative('select * from articoli');
		$articolo = $app['db']->fetchAssociative('select * from articoli where id = ?', array(
			$id,
		));

		if (empty($articolo)) {
			return $app['twig']->render('error.html.twig');
		}

		if (!empty($_POST['commento'])) {
			$app['db']->insert('commenti', array(
				'commento' => $_POST['commento'],
				'creato' => time(),
				'path' => $_SERVER['REQUEST_URI'],
			));
		}

		$commenti = $app['db']->fetchAllAssociative('select * from commenti where path = ?', [
			$_SERVER['REQUEST_URI'],
		]);

		$voti = $app['db']->fetchAllAssociative('select voto from voti_articoli where path = ?', [
			$_SERVER['REQUEST_URI'],
		]);
		$voti = array_column($voti, 'voto');

		$voto = (int) round(array_sum($voti) / max(count($voti), 1), 0);

	    return $app['twig']->render('post.html.twig', array(
        	'articoli' => $articoli,
	        'articolo' => $articolo,
	        'voto' => $voto,
	        'commenti' => $commenti,
	        'admin' => $app['session']->get('admin') ?: false,
	    ));
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

		return $app['twig']->render('admin-login.html.twig');
	})
	->method('GET|POST');

$app->get('/logout.php', function () use ($app) {
	$app['session']->remove('admin');

    return $app->redirect('/');
});

$app->error(function (\Exception $exception, Request $request, $code) use ($app) {
	$output = $app['twig']->render('error.html.twig');

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

    return new Response($output, $code);
});

return $app;
