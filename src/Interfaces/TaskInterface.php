<?php

namespace ConsulConfigManager\Tasks\Interfaces;

/**
 * Interface TaskInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface TaskInterface
{
    /**
     * Get task instance by UUID
     * @param string $uuid
     * @param bool $withTrashed
     * @return TaskInterface|null
     */
    public static function uuid(string $uuid, bool $withTrashed = false): ?TaskInterface;

    /**
     * Get task identifier
     * @return int
     */
    public function getID(): int;

    /**
     * Set task identifier
     * @param int $id
     * @return $this
     */
    public function setID(int $id): self;

    /**
     * Get task name
     * @return string
     */
    public function getName(): string;

    /**
     * Set task name
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get task description
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set task description
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self;

    /**
     * Get task type
     * @return int
     */
    public function getType(): int;

    /**
     * Set task type
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self;
}
