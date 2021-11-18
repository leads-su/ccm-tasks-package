<?php

namespace ConsulConfigManager\Tasks\Events\Pipeline;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class PipelineUpdated
 * @package ConsulConfigManager\Tasks\Events\Pipeline
 */
class PipelineUpdated extends AbstractEvent
{
    /**
     * Pipeline name
     * @var string
     */
    private string $name;

    /**
     * Pipeline description
     * @var string
     */
    private string $description;

    /**
     * PipelineUpdated constructor.
     * @param string $name
     * @param string $description
     * @param UserEntity|int|null $user
     * @return void
     */
    public function __construct(string $name, string $description, UserEntity|int|null $user = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get pipeline name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get pipeline description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
