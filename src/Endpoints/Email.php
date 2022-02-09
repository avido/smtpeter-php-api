<?php
namespace Avido\Smtpeter\Endpoints;

use Avido\Smtpeter\Resources\Email as EmailResource;
use Avido\Smtpeter\Resources\Recipient;
use Illuminate\Support\Collection;

class Email extends BaseEndpoint
{
    public function send(EmailResource $email)
    {
        $response = $this->performApiCall(
            'POST',
            '/send',
            json_encode($email->toArray())
        );

        $collection = new Collection();

        collect($response)
            ->each(function ($email, $id) use ($collection) {
                $collection->push(new Recipient([
                    'id' => $id,
                    'email' => $email
                ]));
            });

        return $collection;
    }

    public function resend(string $messageId)
    {
        $response = $this->performApiCall(
            'POST',
            '/resend',
            json_encode([
                'message' => $messageId
            ])
        );

        $collection = new Collection();

        collect($response)
            ->each(function ($email, $id) use ($collection) {
                $collection->push(new Recipient([
                    'id' => $id,
                    'email' => $email
                ]));
            });

        return $collection->first();
    }

    public function get(
        string $messageId,
        bool $loadHtml = true,
        bool $loadAttachments = false,
        bool $loadHeaders = false
    ) {
        // data placeholders
        $htmlBody = null;
        $attachments = [];
        $headers = null;

        // collect html body
        if ($loadHtml) {
            $htmlBody = $this->performApiCall(
                'GET',
                "/html/{$messageId}",
            );
        }
        if ($loadAttachments) {
            $attachments = $this->performApiCall(
                'GET',
                "/attachments/{$messageId}",
            );
        }
        if ($loadHeaders) {
            $headers = $this->performApiCall(
                'GET',
                "/headers/{$messageId}",
            );
        }

        return new EmailResource([
            'html' => $htmlBody,
            'attachments' => $attachments,
            'headers' => $headers
        ]);
    }
}
