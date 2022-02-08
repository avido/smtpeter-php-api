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

        return $collection;
    }
}
