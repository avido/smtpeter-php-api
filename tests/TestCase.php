<?php
namespace Avido\Smtpeter\Tests;

use Avido\Smtpeter\Client;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @var Client */
    protected $client;

    protected function setUp(): void
    {
        $this->client = new Client(getenv('API_TOKEN'));
        parent::setUp();
    }
}
