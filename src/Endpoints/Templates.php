<?php
namespace Avido\Smtpeter\Endpoints;

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
