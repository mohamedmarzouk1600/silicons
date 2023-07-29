<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class GenerateCrudsTestCases extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud:testcases {models}';

    public $models = [];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test cases for all provided models';
    private $modelsNameSpace = 'MaxDev\Models\\';
    private $stubDir = 'MaxDev/Stubs/';
    private $testCasesFile = 'tests/TestCases.md';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!$this->argument('models')){
            throw new InvalidArgumentException("Missing required argument crud test case creation");
        }
        $this->models = explode(',',$this->argument('models'));
        $testCaseIndexStub = $this->files->get($this->stubDir.'TestCases/CrudTestCasesIndex.stub');
        $stub = $this->replaceAny(
            [
                'DummyCruds',
            ],
            [
                implode(", ",$this->models),
            ],
            $testCaseIndexStub
        );
        $this->files->put($this->testCasesFile, $stub);
        $this->line("<info>Main test cases file been created</info>");

        foreach ($this->models as $model){
            $name = ucwords($model);
            $modelNamespace = $this->modelsNameSpace.$model;
            $modelFillable = array_diff((new $modelNamespace)->getFillable(), (new $modelNamespace)->getHidden());
            $testCaseStub = $this->files->get($this->stubDir.'TestCases/CrudTestCase.stub');

            $stub = $this->replaceAny(
                [
                    'DummyCrudName',
                    'DummyFillable',
                    'DummyEnteringFillableSteps',
                    'DummyPluralsCrudName'
                ],
                [
                    $name,
                    implode(", ",$modelFillable),
                    $this->EnteringFillableSteps($modelFillable),
                    \Str::plural($name),
                ],
                $testCaseStub
            );

            AppendTextBeforeLastLine($this->testCasesFile, $stub, 1);

            $this->line("<info>Test cases for $name has been added</info>");
        }
    }

    protected function getStub()
    {
        return  base_path($this->stubDir);
    }

    protected function EnteringFillableSteps($fillable)
    {
        $steps = '';
        foreach ($fillable as $onefillable){
            $steps .= '* Enter '.$onefillable."\r\n\t\t";
        }
        return $steps;
    }

    public function replaceAny($DummyWord,$newName,$stub){
        return str_replace($DummyWord, $newName, $stub);
    }
}
