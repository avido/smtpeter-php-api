<?php
namespace Avido\Smtpeter\Tests\Endpoints;

/**
 * Event tests are currentl disabled
 *
 * @todo add mocking
 */
use Avido\Smtpeter\Exceptions\SmtpeterException;
use Avido\Smtpeter\Resources\Email;
use Avido\Smtpeter\Resources\Event;
use Avido\Smtpeter\Resources\Recipient;
use Avido\Smtpeter\Tests\TestCase;
use Illuminate\Support\Collection;

class EventTest extends TestCase
{
    /** @test_disabled */
    public function get_events_for_message_id(): void
    {
        $messageId = 'messageId';
        $response = $this->client->events->message($messageId);
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled */
    public function get_events_for_message_id_filter_by_date(): void
    {
        $messageId = 'messageId';
        $response = $this->client->events->message($messageId, '2022-02-08', '2022-02-09');
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled */
    public function get_events_for_message_id_filter_by_tags(): void
    {
        $messageId = 'messageId';
        $response = $this->client->events->message($messageId, null, null, ['nothing', 'found']);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertSame(0, $response->count());
    }

    /** @test_disabled */
    public function wrong_date_filter_throws_exception(): void
    {
        $this->expectException(SmtpeterException::class);
        $messageId = 'messageId';
        $response = $this->client->events->message($messageId, '2022-02-08', '2022-02-08');
    }

    /** @test_disabled  */
    public function get_events_for_email(): void
    {
        $email = 'email@domain.tld';
        $response = $this->client->events->email($email);
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled */
    public function get_events_for_email_filter_by_date(): void
    {
        $email = 'email@domain.tld';
        $response = $this->client->events->email($email, '2022-02-08', '2022-02-09');
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled   */
    public function get_events_for_template(): void
    {
        $templateId = 1;
        $response = $this->client->events->template($templateId);
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled  */
    public function get_events_for_template_filter_by_date(): void
    {
        $templateId = 1;
        $response = $this->client->events->template($templateId, '2022-02-08', '2022-02-09');
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled   */
    public function get_events_for_tags(): void
    {
        $tags = [
            'tag1', 'tag2'
        ];
        $response = $this->client->events->tags($tags);
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }

    /** @test_disabled  */
    public function get_events_for_tags_filter_by_date(): void
    {
        $tags = [
            'tag1', 'tag2'
        ];
        $response = $this->client->events->tags($tags, '2022-02-08', '2022-02-09');
        $this->assertInstanceOf(Event::class, $response->first());
        $this->assertInstanceOf(\DateTime::class, $response->first()->time);
    }
}
