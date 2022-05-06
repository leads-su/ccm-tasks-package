<?php

namespace ConsulConfigManager\Tasks\Events\PipelineTask;

use ConsulConfigManager\Tasks\Events\AbstractEvent;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class PipelineTaskUpdated
 * @package ConsulConfigManager\Tasks\Events\PipelineTask
 */
class PipelineTaskUpdated extends AbstractEvent
{
    /**
     * Order reference
     * @var int
     */
    private int $order;

    /**
     * PipelineTaskUpdated constructor.
     * @param int $order
     * @param UserInterface|int|null $user
     * @return void
     */
    public function __construct(int $order, UserInterface|int|null $user = null)
    {
        $this->order = $order;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Get order
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }
}
