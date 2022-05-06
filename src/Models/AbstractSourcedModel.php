<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use ConsulConfigManager\Users\Models\User;
use ConsulConfigManager\Tasks\Interfaces\SourcedInterface;
use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

/**
 * Class AbstractSourcedModel
 * @package ConsulConfigManager\Tasks\Models
 */
abstract class AbstractSourcedModel extends Model implements SourcedInterface
{
    /**
     * @inheritDoc
     */
    public function history(?string $uuid = null): Collection
    {
        // @codeCoverageIgnoreStart
        if (!$uuid) {
            if (method_exists($this, 'getUuid')) {
                $uuid = $this->getUuid();
            } elseif (method_exists($this, 'getUUID')) {
                $uuid = $this->getUUID();
            } else {
                return collect([]);
            }
        }
        // @codeCoverageIgnoreEnd

        $storedEventRepository = new EloquentStoredEventRepository();
        $storedEvents = $storedEventRepository->retrieveAll($uuid);
        $history = [];

        foreach ($storedEvents as $index => $eventModel) {
            $eventMethods = array_filter(get_class_methods($eventModel->event), static function (string $method): bool {
                $accessor = Str::startsWith($method, 'get');
                $boolean = Str::startsWith($method, 'is');
                return ($accessor || $boolean) && $method !== 'getUser';
            });

            foreach ($eventMethods as $method) {
                $key = preg_replace([
                    '/^' . preg_quote('get', '/') . '/',
                    '/^' . preg_quote('is', '/') . '/',
                ], '', $method);
                $propertyKey = lcfirst(trim($key));
                $historyKey = Str::snake($propertyKey);
                $history[$index]['data'][$historyKey] = $eventModel->event_properties[$propertyKey];
            }

            Arr::set($history, $index . '.event', [
                'id'        =>  $eventModel->id,
                'uuid'      =>  $eventModel->aggregate_uuid,
                'class'     =>  $eventModel->event_class,
                'version'   =>  $eventModel->aggregate_version,
            ]);

            $eventUser = User::find($eventModel->event_properties['user'])
                ->withoutRelations()->toArray();

            Arr::set($history, $index . '.user', Arr::only($eventUser, [
                'id',
                'first_name',
                'last_name',
                'username',
                'email',
            ]));
        }

        return collect($history);
    }

    /**
     * @inheritDoc
     */
    public function getHistoryAttribute(): Collection
    {
        return $this->history();
    }
}
