<?php
namespace Avido\Smtpeter\Tests\Endpoints;

/**
 * Email tests are currentl disabled
 *
 * @todo add mocking
 */
use Avido\Smtpeter\Exceptions\SmtpeterException;
use Avido\Smtpeter\Resources\Email;
use Avido\Smtpeter\Resources\Recipient;
use Avido\Smtpeter\Tests\TestCase;
use Illuminate\Support\Collection;

class EmailTest extends TestCase
{
    /** @test_disabled  */
    public function send_template_email()
    {
        $email = new Email([
            'from' => 'from@domain.tld',
            'templateId' => 8,
            'to' => 'to@domain.tld',
            'subject' => 'Subject overruled',
            'bcc' => 'bcc@domain.tld',
            'replyTo' => 'replyto@domain.tld'
        ]);
        $response = $this->client->email->send($email);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertInstanceOf(Recipient::class, $response->first());
    }

    /** @test_disabled  */
    public function send_template_email_with_only_recipient()
    {
        $email = new Email([
            'from' => 'from@domain.tld',
            'templateId' => 8,
            'recipient' => 'recipient@domain.tld',
        ]);
        $response = $this->client->email->send($email);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertInstanceOf(Recipient::class, $response->first());
    }

    /** @test_disabled */
    public function send_simple_email()
    {
        $email = new Email([
            'from' => 'from@domain.tld',
            'to' => 'recipient@domain.tld',
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
            'from' => 'from@invalid.domain.tld',
            'to' => 'recipient@domain.tld',
            'subject' => 'Subject',
            'html' => '<b>html body</b>',
            'text' => 'email text version'
        ]);
        $this->client->email->send($email);
    }

    /** @test_disabled  */
    public function can_resend()
    {
        $messageId = 'ayuaaajaugesqri';
        $this->client->email->resend($messageId);
    }

}
