<?php
namespace Eloquent\Orderer\Tests;

use Faker\Factory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        $pathToFactories = realpath(dirname(__DIR__) . '/tests');

        parent::setUp();

        // This overrides the $this->factory that is established in TestBench's setUp method above
        $this->factory = EloquentFactory::construct(Factory::create(), $pathToFactories);
        $this->setUpDatabase();
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
        ];
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->float('order', 131072, 16383)->index();
            $table->timestamps();

        });
        collect(range(1, 20))->each(function (int $i) {
            Step::create([
                'title' => $i,
                'order' => rand(),
            ]);
        });
    }
}
