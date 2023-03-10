<?php

namespace Botble\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

/**
 * Class Setting
 * @package Botble\Setting\Models
 */
class Error extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'errors';

    /**
     * @var array
     */
    protected $fillable = [
        'content', 'created_at', 'updated_at'
    ];

    /**
     * @var string
     */
    protected $screen = ERROR_MODULE_SCREEN_NAME;
}
