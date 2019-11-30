<?php

namespace Eloquent\Orderer\Tests;

use Eloquent\Orderer\Tests\TestCase;

class StepTest extends TestCase
{
    /** @test */
    public function it_should_has_title_and_order_as_fillable()
    {
        $this->assertEquals([
            'order',
            'title',
        ], (new Step)->getFillable());
    }
    /** @test */
    public function it_should_create_step_with_recent_order_plus_one_by_default()
    {
        $step = factory(Step::class)->create();
        $anotherStep = factory(Step::class)->create();
        $this->assertEquals($step->order + 1, $anotherStep->order);
    }
    /** @test */
    public function it_should_create_step_after_specific_order_and_before_next_order()
    {
        $steps = factory(Step::class, 2)->create();
        $order = $steps->first()->orderer()->after();
        $anotherStepInBetween = factory(Step::class)->create(compact('order'));
        $this->assertEquals($order, ($steps->first()->order + $steps->last()->order) / 2);
        $this->assertDatabaseHas('steps', $anotherStepInBetween->toArray());
    }
    /** @test */
    public function it_should_create_step_before_sepcific_order_in_between()
    {
        $steps = factory(Step::class, 2)->create();
        $order = $steps->last()->orderer()->before();
        $anotherStepInBetween = factory(Step::class)->create(compact('order'));
        $this->assertEquals($order, ($steps->first()->order + $steps->last()->order) / 2);

        $this->assertDatabaseHas('steps', $anotherStepInBetween->toArray());
    }
    /** @test */
    public function it_should_create_a_step_before_the_first_order()
    {
        $step = factory(Step::class)->create();
        $order = $step->orderer()->before();
        $anotherStepInBetween = factory(Step::class)->create(compact('order'));
        $this->assertEquals($order, $step->order - 1);
        $this->assertDatabaseHas('steps', $anotherStepInBetween->toArray());

    }
    /** @test */
    public function it_should_create_a_step_after_the_last_order()
    {
        $step = factory(Step::class)->create();
        $order = $step->orderer()->after();
        $anotherStepInBetween = factory(Step::class)->create(compact('order'));
        $this->assertEquals($order, $step->order + 1);
        $this->assertDatabaseHas('steps', $anotherStepInBetween->toArray());

    }
    /** @test */
    public function it_should_drag_a_step_between_two_existing_orders()
    {
        $steps = factory(Step::class, 2)->create();
        $step = factory(Step::class)->create([
            'order' => 10,
        ]);
        $this->assertEquals(1.5, $step->orderer()->dragBetween($steps->first(), $steps->last())->order);
    }
}
