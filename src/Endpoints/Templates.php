<?php
namespace Avido\Smtpeter\Endpoints;

use Avido\Smtpeter\Exceptions\SmtpeterException;
use Avido\Smtpeter\Resources\Template as TemplateResource;
use Illuminate\Support\Collection;

class Templates extends BaseEndpoint
{
    /**
     * List all templates
     * @param int $start
     * @param int|null $limit
     * @return Collection
     * @throws \Avido\Smtpeter\Exceptions\SmtpeterException
     */
    public function list(int $start = 0, ?int $limit = null)
    {
        $response = $this->performApiCall(
            'GET',
            '/templates' . $this->limit($start, $limit)
        );

        // smtpeter returns wrong response header (text/html instead of application/json)
        // decode body first
        // as we don't know when smtpeter will fix this, we'll test for valid object first
        if (!is_array($response)) {
            $response = @json_decode($response);
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new SmtpeterException("Unable to decode smtpeter response: '{$response}'.");
            }
        }

        $collection = new Collection();

        collect($response)->each(function ($item) use ($collection) {
            $collection->push(new TemplateResource($item));
        });

        return $collection;
    }

    /**
     * Get template by ID
     * @param int $id
     * @return TemplateResource
     * @throws \Avido\Smtpeter\Exceptions\SmtpeterException
     */
    public function get(int $id)
    {
        $response = $this->performApiCall(
            'GET',
            '/template/' . $id
        );
        return new TemplateResource(array_merge([
            'id' => $id
        ], collect($response)->all()));
    }

    /**
     * Not yet implemented
     * @todo implement
     */
    public function create()
    {
    }

    /**
     * Not yet implemented
     * @todo implement
     */
    public function update()
    {
    }

    /**
     * Limit templates list results
     * @param int $start
     * @param int|null $limit
     * @return string
     */
    private function limit(int $start = 0, ?int $limit = null): string
    {
        return "/{$start}" . (!is_null($limit) ? "/{$limit}" : "");
    }
}
