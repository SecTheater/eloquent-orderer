<?php

namespace Eloquent\Orderer\Ordering;

use Illuminate\Database\Eloquent\Model;

class Orderer
{
    const DEFAULT_ORDER = 1;

    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function after()
    {

        $adjacent = $this->model->query()->where('order', '>', $this->model->order)->orderBy('order', 'asc')->first();
        if (!$adjacent) {
            return $this->last();
        }

        return ($this->model->order + $adjacent->order) / 2;
    }
    public function last()
    {
        return optional($this->model->query()->orderBy('order', 'desc')->first(), function ($step) {
            return $step->order + self::DEFAULT_ORDER;
        }) ?? self::DEFAULT_ORDER;
    }
    public function first()
    {
        return $this->model->query()->orderBy('order', 'asc')->first()->order - self::DEFAULT_ORDER;
    }
    public function before()
    {
        $adjacent = $this->model->query()->where('order', '<', $this->model->order)
            ->orderBy('order', 'desc')
            ->first();
        if (!$adjacent) {
            return $this->first();
        }
        return ($this->model->order + $adjacent->order) / 2;
    }
    public function dragBetween($beforeId, $afterId)
    {
        if ($beforeId instanceof Model && $afterId instanceof Model) {
            $orders = collect([$beforeId, $afterId]);
        } else {
            $orders = $this->model->query()->find([$beforeId, $afterId]);
        }

        return tap($this->model, function ($model) use ($orders) {
            $model->update([
                'order' => $orders->sum('order') / 2,
            ]);
        });
    }
    public function refresh()
    {
        return $this->model->query()->orderBy('order', 'asc')->get()->each(function ($model, $index) {
            $model->update([
                'order' => $index + self::DEFAULT_ORDER,
            ]);
        });
    }
}
