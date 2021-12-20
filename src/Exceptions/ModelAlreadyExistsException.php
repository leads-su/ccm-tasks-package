<?php

namespace ConsulConfigManager\Tasks\Exceptions;

use RuntimeException;
use Illuminate\Support\Arr;

// @codeCoverageIgnoreStart

/**
 * Class ModelAlreadyExistsException
 * @package ConsulConfigManager\Tasks\Exceptions
 */
class ModelAlreadyExistsException extends RuntimeException
{
    /**
     * Name of the affected Eloquent model.
     *
     * @var string
     */
    protected string $model;

    /**
     * The affected model IDs.
     *
     * @var int|array
     */
    protected int|array $ids;

    /**
     * Set the affected Eloquent model and instance ids.
     *
     * @param  string  $model
     * @param  int|array  $ids
     * @return $this
     */
    public function setModel(string $model, int|array $ids = []): ModelAlreadyExistsException
    {
        $this->model = $model;
        $this->ids = Arr::wrap($ids);

        $this->message = "Model with same parameters already exists [{$model}]";

        if (count($this->ids) > 0) {
            $this->message .= ' '.implode(', ', $this->ids);
        } else {
            $this->message .= '.';
        }

        return $this;
    }

    /**
     * Get the affected Eloquent model.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Get the affected Eloquent model IDs.
     *
     * @return int|array
     */
    public function getIds(): int|array
    {
        return $this->ids;
    }
}

// @codeCoverageIgnoreEnd
