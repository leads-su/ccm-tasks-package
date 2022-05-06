<?php

namespace ConsulConfigManager\Tasks\Models;

use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

/**
 * Class AbstractSourcedPivot
 * @package ConsulConfigManager\Tasks\Models
 */
abstract class AbstractSourcedPivot extends AbstractSourcedModel
{
    use AsPivot;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
