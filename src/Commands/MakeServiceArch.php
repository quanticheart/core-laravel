<?php

namespace Quanticheart\Laravel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

class MakeServiceArch extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:arch-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Architecture';

    public function handle()
    {
        Artisan::call('make:arch-repository', [
            'name' => $this->argument('name')
        ]);
        return parent::handle();
    }

    protected function getNameInput()
    {
        return trim($this->argument('name')) . "Service";
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        return $name ? $this->replaceModel($stub, $name) : $stub;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceModel($stub, $name)
    {
        $modelClassService = str_replace("Service", "Repository", $this->getNameInput());

        $replace = [
            '{{ class }}' => $this->getNameInput(),
            '{{ classRepository }}' => $modelClassService,
        ];

        return str_replace(
            array_keys($replace), array_values($replace), $stub
        );
    }

    /**
     * Get the stub file for the generator.
     *
     */
    protected function getStub()
    {
        return app_path() . '/Console/Commands/Stubs/Arch/dummy-service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Http\Controllers\\" . ucfirst($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service.'],
        ];
    }
}
