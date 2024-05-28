<?php

namespace Quanticheart\Laravel\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryArch extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:arch-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

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

    protected function getNameInput()
    {
        return trim($this->argument('name')) . "Repository";
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        return $name ? $this->replaceModel($stub) : $stub;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceModel($stub)
    {
        $replace = [
            '{{ class }}' => $this->getNameInput(),
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
        return app_path() . '/Console/Commands/Stubs/Arch/dummy-repository.stub';
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
