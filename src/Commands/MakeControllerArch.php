<?php

namespace Quanticheart\Laravel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class MakeControllerArch extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:arch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

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
        $nameSpace = "\\" . $this->rootNamespace() . "Http\Controllers\\" . ucfirst($this->argument('name')) . "\\" . $this->argument('name') . "Controller";
        $name = strtolower($this->argument('name'));
        $firstLine = "Route::prefix('" . $name . "')->controller(" . $nameSpace . "::class)->group(function () {\n";
        $appServiceProviderFile = base_path('routes/api.php');
        if ($this->canWrite($appServiceProviderFile, $firstLine)) {
            $codeToAdd =
                "\n" . $firstLine .
                "\tRoute::get('/refresh', 'refresh');\n" .
                "});\n";
            $this->injectCodeToRegisterMethod($appServiceProviderFile, $codeToAdd);
        }

        Artisan::call('make:arch-service', [
            'name' => $this->argument('name')
        ]);
        return parent::handle();
    }

    private function canWrite($path, $line): bool
    {
        if (file_exists($path)) {
            $user_pass = fopen($path, "r");
            $flag = 0;
            while (!feof($user_pass)) {
                $p = fgets($user_pass);
                if ($line == $p) {
                    $flag = 1;
                }
            }
            fclose($user_pass);
            if ($flag == 1) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function injectCodeToRegisterMethod($appServiceProviderFile, $codeToAdd)
    {
//        $reflectionClass = new ReflectionClass(\App\Providers\AppServiceProvider::class);
//        $reflectionMethod = $reflectionClass->getMethod('register');

        $myfile = fopen($appServiceProviderFile, "a") or die("Unable to open file!");
        fwrite($myfile, $codeToAdd);
        fclose($myfile);
    }

    protected function getNameInput()
    {
        return trim($this->argument('name')) . "Controller";
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
        $modelClass = $this->parseModel($name);
        $modelClassService = str_replace("Controller", "Service", $this->getNameInput());

        $replace = [
            '{{ namespacedModel }}' => str_replace($this->getNameInput(), "", $modelClass),
            '{{ class }}' => $this->getNameInput(),
            '{{ classService }}' => $modelClassService,
//            '{{ m }}' => class_basename($modelClass),
//            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
        ];

        return str_replace(
            array_keys($replace), array_values($replace), $stub
        );
    }


    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Get the stub file for the generator.
     *
     */
    protected function getStub()
    {
        return __DIR__ . '/Stubs/Arch/dummy-controller.stub';
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
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
        ];
    }
}
