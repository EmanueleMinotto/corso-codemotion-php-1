<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Client;

class HomepageTest extends TestCase
{
	public function createApplicationClient()
	{
	    $app = require_once __DIR__.'/../src/app.php';
	    $app['debug'] = true;
	    $app['session.test'] = true;
	    unset($app['exception_handler']);

	    return new Client($app);
	}

    public function testFooterShouldBeVisible()
    {
	    $client = $this->createApplicationClient();
	    $crawler = $client->request('GET', '/');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('footer'));
    }
}
