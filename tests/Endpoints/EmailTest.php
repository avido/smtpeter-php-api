<?php
namespace Avido\Smtpeter\Tests\Endpoints;

use Avido\Smtpeter\Exceptions\SmtpeterException;
use Avido\Smtpeter\Resources\Email;
use Avido\Smtpeter\Resources\Recipient;
use Avido\Smtpeter\Resources\Template;
use Avido\Smtpeter\Tests\TestCase;
use Illuminate\Support\Collection;

class EmailTest extends TestCase
{
    /** @test_disabled */
    public function send_template_email()
    {
        $email = new Email([
            'from' => 'info@123lens.nl',
            'templateId' => 4,
            'recipient' => 'sebastiaan@avido.nl',
            'subject' => 'Subject overruled',
        ]);
        $response = $this->client->email->send($email);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertInstanceOf(Recipient::class, $response->first());
    }

    /** @test_disabled */
    public function send_simple_email()
    {
        $email = new Email([
            'from' => 'info@123lens.nl',
            'recipient' => 'sebastiaan@avido.nl',
            'subject' => 'Subject',
            'html' => '<b>html body</b>',
            'text' => 'email text version'
        ]);
        $response = $this->client->email->send($email);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertInstanceOf(Recipient::class, $response->first());
    }

    /** @test_disabled */
    public function can_not_send_invalid_sender_domain()
    {
        $this->expectException(SmtpeterException::class);
        $this->expectExceptionMessage('You cannot send mailings from some-invalid-domain.nl without configuring it as sender domain');

        $email = new Email([
            'from' => 'info@some-invalid-domain.nl',
            'recipient' => 'sebastiaan@avido.nl',
            'subject' => 'Subject',
            'html' => '<b>html body</b>',
            'text' => 'email text version'
        ]);
        $this->client->email->send($email);
    }
}
