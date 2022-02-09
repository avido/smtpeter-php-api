<?php
namespace Avido\Smtpeter\Endpoints;

use Avido\Smtpeter\Resources\Email as EmailResource;
use Avido\Smtpeter\Resources\Event;
use Avido\Smtpeter\Resources\Recipient;
use Illuminate\Support\Collection;

class Events extends BaseEndpoint
{
    /**
     * Get events by message id
     * @see https://www.smtpeter.com/nl/documentation/rest-events
     */
    public function message(string $messageId, $start = null, $end = null, array $tags = []): Collection
    {
        $arguments = $this->parseArguments($start, $end, $tags);

        $response = $this->performApiCall(
            'GET',
            "/events/messageid/{$messageId}",
            null,
            $arguments
        );
        $collection = new Collection();

        collect($response)
            ->each(function ($event) use ($collection) {
                $collection->push(new Event($event));
            });

        return $collection;
    }

    /**
     * Get events by email
     * @see https://www.smtpeter.com/nl/documentation/rest-events
     */
    public function email(string $email, $start = null, $end = null, array $tags = []): Collection
    {
        $arguments = $this->parseArguments($start, $end, $tags);

        $response = $this->performApiCall(
            'GET',
            "/events/email/{$email}",
            null,
            $arguments
        );
        $collection = new Collection();

        collect($response)
            ->each(function ($event) use ($collection) {
                $collection->push(new Event($event));
            });

        return $collection;
    }

    /**
     * Get events by template
     * @see https://www.smtpeter.com/nl/documentation/rest-events
     */
    public function template(int $templateId, $start = null, $end = null, array $tags = []): Collection
    {
        $arguments = $this->parseArguments($start, $end, $tags);

        $response = $this->performApiCall(
            'GET',
            "/events/template/{$templateId}",
            null,
            $arguments
        );
        $collection = new Collection();

        collect($response)
            ->each(function ($event) use ($collection) {
                $collection->push(new Event($event));
            });

        return $collection;
    }

    /**
     * Get events by tags
     * @see https://www.smtpeter.com/nl/documentation/rest-events
     */
    public function tags(array $tags, $start = null, $end = null): Collection
    {
        $arguments = $this->parseArguments($start, $end);

        $response = $this->performApiCall(
            'GET',
            "/events/tags/" . implode(";", $tags),
            null,
            $arguments
        );
        $collection = new Collection();

        collect($response)
            ->each(function ($event) use ($collection) {
                $collection->push(new Event($event));
            });

        return $collection;
    }

    /**
     * Parse optional arguments
     * @param $start
     * @param $end
     * @param array $tags
     * @return array
     */
    private function parseArguments($start = null, $end = null, array $tags = []): array
    {
        return collect([
            'start' => $start,
            'end' => $end,
            'tags' => $tags
        ])->reject(function ($item) {
            return is_array($item) && !count($item) || is_null($item);
        })->map(function ($item) {
            if (is_array($item)) {
                return implode(";", $item);
            }
            return $item;
        })->all();
    }
}
