<?php

namespace ConsulConfigManager\Tasks\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Interface ActionHostRepositoryInterface
 * @package ConsulConfigManager\Tasks\Interfaces
 */
interface ActionHostRepositoryInterface
{
    /**
     * Get list of all entries from database
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find entity by searching for specified value in specified field
     * @param string $field
     * @param string $value
     * @param array $with
     * @return Collection
     */
    public function findBy(string $field, string $value, array $with = []): Collection;

    /**
     * Get list of entities by matching action identifier
     * @param string $identifier
     * @param array $with
     * @return Collection
     */
    public function findByAction(string $identifier, array $with = []): Collection;

    /**
     * Get list of entities by matching server identifier
     * @param string $identifier
     * @param array $with
     * @return Collection
     */
    public function findByServer(string $identifier, array $with = []): Collection;

    /**
     * Find exact entity
     * @param string $actionIdentifier
     * @param string $serverIdentifier
     * @return ActionHostInterface|null
     */
    public function findExact(string $actionIdentifier, string $serverIdentifier): ActionHostInterface|null;

    /**
     * Find exact entity or fail if model does not exist
     * @param string $actionIdentifier
     * @param string $serverIdentifier
     * @throws ModelNotFoundException
     * @return ActionHostInterface
     */
    public function findExactOrFail(string $actionIdentifier, string $serverIdentifier): ActionHostInterface;

    /**
     * Create new entity
     * @param string $actionIdentifier
     * @param string $serverIdentifier
     * @return ActionHostInterface
     */
    public function create(string $actionIdentifier, string $serverIdentifier): ActionHostInterface;

    /**
     * Delete existing entity
     * @param string $actionIdentifier
     * @param string $serverIdentifier
     * @throws ModelNotFoundException
     * @return bool
     */
    public function delete(string $actionIdentifier, string $serverIdentifier): bool;
}
