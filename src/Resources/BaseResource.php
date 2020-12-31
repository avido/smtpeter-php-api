<?php
namespace Avido\Smtpeter\Resources;

use JsonSerializable;
use Avido\Smtpeter\Contracts\Arrayable;
use Avido\Smtpeter\Contracts\Jsonable;
use Avido\Smtpeter\Exceptions\JsonEncodingException;

abstract class BaseResource implements Arrayable, Jsonable, JsonSerializable
{
    use Concerns\HasAttributes;

    /**
     * @param  array|object  $attributes
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * @param  array|object  $attributes
     */
    public function fill($attributes): self
    {
        collect($attributes)->each(function ($value, $key) {
            $this->setAttribute($key, $value);
        });

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return collect($this->attributesToArray())
            ->reject(function ($value) {
                return $value === null;
            })
            ->all();
    }

    /**
     * @param int $options
     * @return string
     * @throws JsonEncodingException
     */
    public function toJson(int $options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forResource($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Dynamically retrieve attributes on the resource.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the resource.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}
