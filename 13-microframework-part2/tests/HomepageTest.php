<?php

use PHPUnit\Framework\TestCase;

class HomepageTest extends TestCase
{
    public function testFooterShouldBeVisible()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $this->expectOutputRegex('/Blog by Emanuele/');

        require_once __DIR__.'/../index.php';
    }
}
