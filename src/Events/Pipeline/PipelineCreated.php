<?php

namespace ConsulConfigManager\Tasks\Events\Pipeline;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineCreated
 * @package ConsulConfigManager\Tasks\Events\Pipeline
 */
class PipelineCreated extends AbstractEvent
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
     * PipelineCreated constructor.
     * @param string $name
     * @param string $description
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(string $name, string $description, UserInterface|int|null $user = null)
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
