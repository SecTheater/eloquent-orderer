<?php

namespace Eloquent\Orderer;

trait Orderable
{

    public static function boot()
    {
        parent::boot();
        static::creating(function ($step) {
            if (is_null($step->order)) {
                $step->order = $step->orderer()->last();
            }
        });
    }
    public function orderer()
    {
        return new Orderer($this);
    }
}
