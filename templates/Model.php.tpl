<?php

namespace {{namespace}}\Models;

use Fooino\Base\Casts\HumanReadableDate;
use Fooino\Base\Casts\UserTimezoneDate;
use Fooino\Base\Concerns\Modelable;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded('id')]
class {{class}} extends Model
{
    use Modelable;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Accessors, Mutators & Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'created_at_tz'  => UserTimezoneDate::class,
            'updated_at_tz'  => UserTimezoneDate::class,
            'created_at_ago' => HumanReadableDate::class,
            'updated_at_ago' => HumanReadableDate::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Custom Functions
    |--------------------------------------------------------------------------
    */
}
