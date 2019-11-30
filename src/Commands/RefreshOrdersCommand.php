<?php

namespace Eloquent\Orderer\Commands;

use Illuminate\Console\Command;
use ReflectionClass;

class RefreshOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eloquent-orderer:refresh {model : the fully namespace of the model you wish to sort.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'refresh the orders of all of the models.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reflectionModel = new ReflectionClass($this->argument('model'));
        if (in_array("Eloquent\Orderer\Orderable", array_keys($reflectionModel->getTraits()))) {
            $model = app($this->argument('model'));
            $model->refresh();
            $this->info(sprintf('Fixing up %s orders', class_basename($model)));
        }
        $this->info('Finished up refreshing the orders');
    }
}
