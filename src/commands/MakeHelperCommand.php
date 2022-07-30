<?php
    
    namespace App\Console\Commands;
    
    use Illuminate\Console\GeneratorCommand;
    use Symfony\Component\Console\Input\InputArgument;

    class MakeHelper extends GeneratorCommand
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $name = 'make:helper';
        
        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Create a new custom helper class';
        
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
        protected $type = 'Helper';
        
        /**
         * Replace the class name for the given stub.
         *
         * @param string $stub
         * @param string $name
         * @return string
         */
        protected function replaceClass($stub, $name)
        {
            $stub = parent::replaceClass($stub, $name);
            
            return str_replace('DummyHelper', $this->argument('name'), $stub);
        }
        
        /**
         * Get the stub file for the generator.
         *
         * @return string
         */
        protected function getStub()
        {
            return app_path() . '/Console/Commands/Stubs/dummy-helper.stub';
        }
        
        /**
         * Get the default namespace for the class.
         *
         * @param string $rootNamespace
         * @return string
         */
        protected function getDefaultNamespace($rootNamespace)
        {
            return $rootNamespace . '\Helpers';
        }
        
        /**
         * Get the console command arguments.
         *
         * @return array
         */
        protected function getArguments()
        {
            return [
                ['name', InputArgument::REQUIRED, 'The name of the helper.'],
            ];
        }
    }
