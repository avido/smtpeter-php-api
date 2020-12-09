<?php
namespace Avido\Smtpeter\Tests;

use Avido\Smtpeter\Exceptions\SmtpeterException;

class ClientTest extends TestCase
{
    /** @testx */
    public function client_has_templates_endpoint()
    {
        $this->assertInstanceOf(ServicePoints::class, $this->client->servicePoints);
    }

    /** @test */
    public function performing_an_http_call_without_setting_an_api_token_throws_an_exception()
    {
        $this->expectException(SmtpeterException::class);
        $this->client->setApiToken('');
        $this->client->performHttpCall('GET', 'some-resource');
    }
}
