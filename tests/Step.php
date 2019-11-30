<?php

namespace Eloquent\Orderer\Tests;

use Eloquent\Orderer\Orderable;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use Orderable;

    protected $fillable = [
        'order',
        'title',
    ];
}
