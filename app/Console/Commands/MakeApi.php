<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class MakeApi extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MaxDevApi';

    protected $type = 'Api';
    private $apiName;
    private $modelName;

    private $modelsNameSpace = 'MaxDev\Models\\';
    private $modelFillable;
    private $inputs;
    private $controllerFilePath = 'MaxDev/Modules/Api/';
    private $resourceFilePath = 'MaxDev/Modules/Api/Resource/';
    private $stubDir = 'MaxDev/Stubs/Api/';
    private $apiController;
    private $apiResource;
    private $relations;
    private $ExtraUse;
    private $RelationObjects;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument crud name");
        }

        if(!$this->argument('model')){
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $this->setApiVariables();
        $this->CreateFormRequest();
        $this->CreateFactory();
        // todo create Dummyresource from fillable
        $this->CreateResource();
        $this->CreateController();
        // todo create feature test for API


    }



    private function setApiVariables()
    {
        $this->apiName = strtolower($this->argument('name'));
        $this->modelName = $this->argument('model');
        $name = ucwords($this->apiName);
        $this->model = $this->modelsNameSpace.$this->argument('model');
        $this->modelFillable = array_diff((new $this->model)->getFillable(), (new $this->model)->getHidden());
        $NewFillable = [];
        $model = $this->modelsNameSpace . $this->modelName;
        foreach ($this->modelFillable as $fillable){
            if (isset((new $model)->translatable) && array_search($fillable,(new $model)->translatable) !== false) {
                foreach(config('app.locales') as $locale){
                    $NewFillable[] = $fillable.'[\''.$locale.'\']';
                }
            } else {
                $NewFillable[] = $fillable;
            }
        }
        $this->inputs = $NewFillable;
        $this->GetExtraUse();
        $this->apiController = $name . 'Controller';
        $this->apiResource = $name . 'Resource';
    }

    private function CreateFormRequest(){
        $stub = $this->files->get($this->stubDir.'DummyFormRequest.stub');
        $stub = $this->replaceAny(
            ['DummyFormRequest','DummyFormRules'],
            [ucfirst($this->apiName).'FormRequest',$this->formRequestRules()],
            $stub
        );
        $this->files->put($this->controllerFilePath.'Requests/'.ucfirst($this->apiName).'FormRequest.php', $stub);
    }

    private function CreateFactory(){
        if (file_exists(base_path('database/Factories/').ucfirst($this->apiName).'Factory.php')) {
            return;
        }
        $stub = $this->files->get($this->stubDir.'DummyModelFactory.stub');
        $stub = $this->replaceAny(
            ['DummyModel','DummyFactoryFillable'],
            [$this->modelName,$this->ModelFactoryFillable()],
            $stub
        );
        $this->files->put(base_path('database/Factories/').ucfirst($this->apiName).'Factory.php', $stub);
    }

    private function CreateController()
    {
        $path = $this->controllerFilePath . $this->apiController . '.php';
        if ($this->alreadyExists($path)) {
            $this->error($this->type . ' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->stubDir . 'DummyController.stub');

        $stub = $this->replaceAny(
            [
                'DummyController',
                'DummyCrudName',
                'DummyModelNameSpace',
                'DummyModel',
                'DummyLoweredModel',
                'ExtraUseClasses',
                'DummyFormRequest',
                'DummyAllowedFilters',
                'DummyResource',
            ],
            [
                $this->apiController,
                $this->apiName,
                $this->model,
                $this->modelName,
                strtolower($this->modelName),
                $this->ExtraUse,
                ucfirst($this->apiName) . 'FormRequest',
                '\''.implode("','",$this->modelFillable).'\'',
                ucfirst($this->apiResource),
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->line("<info>Api Controller created :</info> $this->apiController");
        AppendTextBeforeLastLine(base_path('routes/api.php'),"Route::get('$this->apiName', '$this->apiController@index');",1);
        AppendTextBeforeLastLine(base_path('routes/api.php'),"Route::get('$this->apiName/{id}', '$this->apiController@show');",1);
        $this->line("<info>Route created</info>");
    }


    private function CreateResource()
    {
        $path = $this->resourceFilePath . $this->apiResource . '.php';
        if ($this->alreadyExists($path)) {
            $this->error('Resource already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->stubDir . 'DummyResource.stub');

        $stub = $this->replaceAny(
            [
                'DummyResource',
                'DummySourceItems',
            ],
            [
                $this->apiResource,
                $this->ModelResourceItems(),
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->line("<info>Api Resource created :</info> $this->apiController");
    }

    public function ModelFactoryFillable(){
        $factoryFillable = [];
        $model = $this->modelsNameSpace . $this->modelName;
        foreach($this->modelFillable as $fillable){
            if($fillable == 'status'){
                $factoryFillable[] = '        \''.$fillable.'\'          =>          rand(0,1),';
            } elseif (isset($this->relations[$fillable])) {
                $Model = 'Database\Factories\\'.ucfirst($this->relations[$fillable]['modelName']).'Factory';
                $factoryFillable[] = '        "'.$fillable.'"          =>          '.$Model.'::new()->create([]),';
            } else {
                if (isset((new $model)->translatable) && array_search($fillable,(new $model)->translatable) !== false) {
                    $factoryFillable[] = '        "'.$fillable.'"          =>          [\'en\'=>$this->faker->name,\'ar\'=>$this->faker->name],';
                } else
                    $factoryFillable[] = '        "'.$fillable.'"          =>          $this->faker->name,';
            }
        }
        $model = $this->modelsNameSpace.$this->modelName;
        foreach ((new $model)->getHidden() as $hidden){
            $factoryFillable[] = '        \''.$hidden.'\'          =>          $faker->name,';
        }
        return implode(PHP_EOL,$factoryFillable);
    }


    public function ModelResourceItems(){
        $modelResourceItems = [];
        foreach($this->modelFillable as $fillable){
            $modelResourceItems[] = '        "'.$fillable.'"          =>          $this->'.$fillable.',';
        }
        return implode(PHP_EOL,$modelResourceItems);
    }

    public function GetExtraUse(){
        if(is_array($this->relations) && count($this->relations)){
            $uses = $relations = [];
            foreach($this->relations as $relation){
                $uses[] = $relation['namespace'];
                $relations[] = $relation['object'];
            }
            $this->ExtraUse = implode(PHP_EOL,$uses);
            $this->RelationObjects = implode(PHP_EOL,$relations);
        }
    }

    public function formRequestRules(){
        $rules = [];
        foreach($this->inputs as $fillable){
            $fillable = str_replace(['[',']','\''],['.','',''],$fillable);
            $ExtraRule = '';
            if(isset($this->relations[$fillable])) {
                $Model = 'MaxDev\Models\\'.ucfirst($this->relations[$fillable]['modelName']);
                if($object = new $Model) {
                    $ExtraRule = '|exists:' . $object->getTable() . ',id';
                }
            }
            $rules[] = '            \''.$fillable.'\'                  => \'sometimes|nullable'.$ExtraRule.'\',';
        }
        return implode(PHP_EOL,$rules);
    }

    public function replaceAny($DummyWord,$newName,$stub){
        return str_replace($DummyWord, $newName, $stub);
    }

    public function getStub()
    {
        return  base_path($this->stubDir);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the crud.'],
            ['model', InputArgument::REQUIRED, 'The Model of the crud.'],
        ];
    }
}
