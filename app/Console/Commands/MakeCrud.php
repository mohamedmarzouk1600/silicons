<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class MakeCrud extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new MaxDevCrud';

    protected $type = 'Crud';
    private $crudName;
    private $modelName;

    private $modelsNameSpace = 'MaxDev\Models\\';
    private $modelFillable;
    private $inputs;
    private $controllerFilePath = 'MaxDev/Modules/Administrators/';
    private $serviceFilePath = 'MaxDev/Services/';
    private $stubDir = 'MaxDev/Stubs/Administrators/';
    private $crudController;
    private $crudService;
    private $relations;
    private $ExtraUse;
    private $RelationObjects;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $model;

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument crud name");
        }

        if(!$this->argument('model')){
            throw new InvalidArgumentException("Missing required argument model name");
        }

        $this->setCrudVariables();
        $this->CreateFormRequest();
        $this->CreateFactory();
        $this->CreateService();
        $this->CreateController();
        $this->CreateViews();
        $this->CreateUnittest();
        $this->updateLanguageFile();

//        $this->call('test');
    }

    private function CreateFormRequest(){
        $stub = $this->files->get($this->stubDir.'DummyFormRequest.stub');
        $stub = $this->replaceAny(
            ['DummyFormRequest','DummyFormRules'],
            [ucfirst($this->crudName).'FormRequest',$this->formRequestRules()],
            $stub
        );
        $this->files->put($this->controllerFilePath.'Requests/'.ucfirst($this->crudName).'FormRequest.php', $stub);
    }

    private function CreateFactory(){
        $stub = $this->files->get($this->stubDir.'DummyModelFactory.stub');
        $stub = $this->replaceAny(
            ['DummyModel','DummyFactoryFillable'],
            [$this->modelName,$this->ModelFactoryFillable()],
            $stub
        );
        $this->files->put(base_path('database/Factories/').ucfirst($this->crudName).'Factory.php', $stub);
    }


    private function CreateService(){
        $path = $this->serviceFilePath.$this->crudService.'.php';
        if ($this->alreadyExists($path)) {
            $this->error($this->type.' service already exists!');
            return false;
        }

        $this->makeDirectory($path);
        $stub = $this->files->get($this->stubDir.'DummyService.stub');

        $stub = $this->replaceAny(
            [
                'DummyService',
                'ExtraUseClasses',
                'DummyModelNameSpace',
                'DummyModel',
                'DummyFillable',
            ],
            [
                $this->crudService,
                $this->ExtraUse,
                $this->model,
                $this->modelName,
                '\''.implode("','",$this->modelFillable).'\'',
            ],
            $stub
        );
        $this->files->put($path, $stub);
        $this->line("<info>Service created :</info> $this->crudService");
    }
    private function CreateController(){
        $path = $this->controllerFilePath.$this->crudController.'.php';
        if ($this->alreadyExists($path)) {
            $this->error($this->type.' already exists!');
            return false;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->stubDir.'DummyController.stub');

        $stub = $this->replaceAny(
            [
                'DummyController',
                'DummyCrudName',
                'DummyModelNameSpace',
                'DummyModel',
                'DummyFillable',
                'DummyLoweredModel',
                'DummyPhraseFillable',
                'DummyCaseFields',
                'ExtraUseClasses',
                'RelationObjects',
                'DummyFormRequest',
                'DummyDataTableColumns',
                'createFilterByInputsForQuery',
                'DummySearchAllInputsQuery',
                'DummyService',
            ],
            [
                $this->crudController,
                $this->crudName,
                $this->model,
                $this->modelName,
                '\''.implode("','",$this->modelFillable).'\'',
                strtolower($this->modelName),
                implode(',',array_map(function($fillable){return '__(\''.ucfirst($fillable).'\')';},$this->modelFillable)),
                $this->createFilterByInputs(),
                $this->ExtraUse,
                $this->RelationObjects,
                ucfirst($this->crudName).'FormRequest',
                $this->ControllerDataTableColumns(),
                $this->createFilterByInputsForQuery(),
                $this->SearchAllColumnsBySearchInput(),
                $this->crudService,
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->line("<info>Controller created :</info> $this->crudController");
        AppendTextBeforeLastLine(base_path('routes/administrators.php'),"\t\tRoute::resource('/$this->crudName', '$this->crudController');",5);
        $this->line("<info>Route created</info>");

        $PermissionStub = $this->files->get($this->stubDir.'ResourcePermission.stub');
        $PermissionStub = $this->replaceAny(
            ['DummyCrudName'],
            [$this->crudName]
        ,$PermissionStub);
        AppendTextBeforeLastLine(base_path('config/permissions.php'),$PermissionStub,4);
        $this->line("<info>Permissions updated</info>");
        AppendTextBeforeLastLine(
            base_path('resources/views/admin/_partial/menus.blade.php'),
            $this->replaceAny(['DummyCrudName'],[$this->crudName],$this->files->get($this->stubDir.'ResourceMenu.stub')),
            10
        );
    }

    private function CreateUnittest(){
        $stub = $this->files->get($this->stubDir.'DummyUnitTest.stub');
        $stub = $this->replaceAny(
            [
                'DummyUnitTestCrud',
                'DummyCrudName',
                'DummyModel',
                'DummyAssertSee',
                'DummyUpdateFillable',
                'DummyAssertDatabaseHasForCreate',
                'DummyAssertDatabaseHas',
                'DummyAssertDatabaseMissing'
            ],
            [
                ucfirst($this->crudName).'Test',
                $this->crudName,
                $this->modelName,
                $this->AssertSee(),
                $this->UpdateFillable(),
                $this->AssertDatabaseHas('create'),
                $this->AssertDatabaseHas('update'),
                $this->AssertDatabaseMissing()
            ],
            $stub
        );
        $this->files->put(base_path('tests/Feature/').ucfirst($this->crudName).'Test.php', $stub);
    }

    private function CreateViews(){
        $this->CreateFormView();
        $this->CreateIndexView();
        $this->CreateShowView();
    }


    private function CreateFormView(){
        $path = base_path('resources/views/admin/'.$this->crudName).'/form.blade.php';
        $this->makeDirectory($path);
        $formStub = $this->files->get($this->stubDir.'Views/form.blade.stub');
        $formStub = $this->replaceAny(
            ['DummyInputs'],
            [$this->createFormInputs()],
            $formStub);
        $this->files->put($path, $formStub);
        $this->line("<info>Form view created</info>");
    }

    private function CreateIndexView(){
        $path = base_path('resources/views/admin/'.$this->crudName).'/index.blade.php';
        $this->makeDirectory($path);
        $indexStub = $this->files->get($this->stubDir.'Views/index.blade.stub');
        $indexStub = $this->replaceAny(
            ['DummyFilterInputs'],
            [$this->createFormInputs()],
            $indexStub);
        $this->files->put($path, $indexStub);
        $this->line("<info>Index view created</info>");
    }

    private function CreateShowView(){
        $path = base_path('resources/views/admin/'.$this->crudName).'/show.blade.php';
        $this->makeDirectory($path);
        $showStub = $this->files->get($this->stubDir.'Views/show.blade.stub');
        $showStub = $this->replaceAny(
            ['DummyShowAvailableAttributes'],
            [$this->ShowViewFillable()],
            $showStub
        );
        $this->files->put($path, $showStub);
        $this->line("<info>Show view created</info>");
    }


    protected function alreadyExists($filePath){
        return $this->files->exists($filePath);
    }

    /**
     * Set repository class name
     *
     * @return  void
     */
    private function setCrudVariables()
    {
        $this->crudName = strtolower($this->argument('name'));
        $this->modelName = $this->argument('model');
        $name = ucwords($this->crudName);
        $this->model = $this->modelsNameSpace.$this->argument('model');
        $this->modelFillable = array_diff((new $this->model)->getFillable(), (new $this->model)->getHidden());
        $NewFillable = [];
        $model = $this->modelsNameSpace . $this->modelName;
        foreach ($this->modelFillable as $fillable){
            if(endsWith($fillable,'_id')){
                $modelName = Str::camel(str_replace('_',' ',substr($fillable,0,-3)));
                if($modelName == 'parent') {
                    $modelName = $this->modelName;
                }
                if(file_exists(base_path('MaxDev/Models/'.ucfirst($modelName).'.php'))) {
                    $this->relations[$fillable] = [
                        'namespace' => 'use MaxDev\Models\\'.ucfirst($modelName).';',
                        'object'    =>  '        $this->viewData[\''.$modelName.'\'] = '.$modelName.'::all()->pluck(\'name\',\'id\');',
                        'modelName' =>  $modelName,
                    ];
                }
            }
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
        $this->crudController = $name . 'Controller';
        $this->crudService = $name . 'Service';
        $this->ExtraUse .= PHP_EOL . 'use MaxDev\Services\\' . $this->crudService . ';';
    }

    public function InputFiledLabel($fillable){
        if(strpos($fillable,'[')!==false){
            return str_replace(["['ar']","['en']"],[' ( Arabic )',' ( English )'],$fillable);
        } else
            return $fillable;
    }

    private function createFormInputs() : string {

        $formInputs = array_map(function($oneInput){
            $isTranslatable = strpos($oneInput,'[')!==false ?? true;
            $textInput = $this->files->get($this->stubDir.($isTranslatable ? 'Views/Inputs/inputTextTranslatable.stub' : 'Views/Inputs/inputText.stub'));
            $selectInput = $this->files->get($this->stubDir.($isTranslatable ? 'Views/Inputs/inputSelectTranslatable.stub':'Views/Inputs/inputSelect.stub'));
            if($isTranslatable){
                preg_match_all('/\[\'(.*?)\'\]/',$oneInput,$matches);
                $local = $matches[1][0];
            } else
                $local = '';

            $RelationName = isset($this->relations[$oneInput]) ? '$'.$this->relations[$oneInput]['modelName'] : '';
            return $this->replaceAny([
                    'DummyFiledName',
                    'DummyNoSingleFiledName',
                    'DummyFiledLabel',
                    'DummyRelationName',
                    'DummyCleanFiledName',
                    'DummyLocal',
                    'DummyValidationFiledName'
                ],
                [
                    $oneInput,
                    str_replace('\'','',$oneInput),
                    $this->InputFiledLabel($oneInput),
                    $RelationName,
                    substr($oneInput,0,-6),
                    $local,
                    str_replace(['[\'','\']'],['.',''],$oneInput),
                ],endsWith($oneInput,'_id') ? $selectInput : $textInput).PHP_EOL;
        },$this->inputs);
        return implode(PHP_EOL,$formInputs);
    }

    private function createFilterByInputs() : string {
        $FilterCases = $indexStub = $this->files->get($this->stubDir.'Views/DummyCases.stub');
        $formInputs = array_map(function($oneInput)use($FilterCases){
            return $this->replaceAny(['DummyFiledFilterBy'],[$oneInput],$FilterCases);
        },$this->modelFillable);
        return implode(PHP_EOL,$formInputs);
    }

    private function createFilterByInputsForQuery() : string {
        $FilterCases = $indexStub = $this->files->get($this->stubDir.'Views/DummyFilter.stub');
        $formInputs = array_map(function($oneInput)use($FilterCases){
            $ClearedFiledName = (strpos($oneInput,'[') !== false) ? substr($oneInput,0,-6): $oneInput;
            return $this->replaceAny(['DummyFiledName','DummyClearedFiledName'],[$oneInput,$ClearedFiledName],$FilterCases);
        },$this->inputs);
        return implode(PHP_EOL,$formInputs);
    }

    public function GetExtraUse(){
        if(is_array($this->relations) && count($this->relations)){
            $uses = $relations = [];
            foreach($this->relations as $relation){
                $uses[] = $relation['namespace'];
                $relations[] = $relation['object'];
            }
            // Add service class use
            $this->ExtraUse = implode(PHP_EOL,$uses);
            $this->RelationObjects = implode(PHP_EOL,$relations);
        }
    }

    public function SearchAllColumnsBySearchInput(){
        $whereClauses = [];
        foreach($this->modelFillable as $fillable){
            $whereClauses[] = '                    ->orWhere(\''.$fillable.'\',\'LIKE\',\'%\'.$name.\'%\')';
        }
        return implode(PHP_EOL,$whereClauses);
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
            $rules[] = '            \''.$fillable.'\'                  => \'required'.$ExtraRule.'\',';
        }
        return implode(PHP_EOL,$rules);
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


    public function ShowViewFillable(){
        $showViewAttributes = [];
        $model = $this->modelsNameSpace . $this->modelName;
        foreach($this->inputs as $fillable){
            if(strpos($fillable,'[')){
                preg_match_all("/(.*?)(\[|$)/",$fillable,$matches);
                $colName = $matches[1][0];
                $language = str_replace(['[','\'',']'],['','',''],$matches[1][1]);
                $showViewAttributes[] = '                                    <tr>
                                        <td>{{__(\'' . $this->ReplaceForTranslatable($fillable) . '\')}}</td>
                                        <td>{{$row->' . "gettranslation('$colName','$language')" . '}}</td>
                                    </tr>';
            } else {
                $showViewAttributes[] = '                                    <tr>
                                        <td>{{__(\'' . $this->ReplaceForTranslatable($fillable) . '\')}}</td>
                                        <td>{{$row->' . $fillable . '}}</td>
                                    </tr>';
            }
        }
        return implode(PHP_EOL,$showViewAttributes);
    }


    public function ControllerDataTableColumns(){
        $controllerColumns = [];
        $model = $this->modelsNameSpace . $this->modelName;
        foreach($this->modelFillable as $fillable){
            $string = '                ->addColumn(\''.$fillable.'\',function($data){'.PHP_EOL;
            if (isset((new $model)->translatable) && array_search($fillable,(new $model)->translatable) !== false) {
                $string .= '                return GetAllTranslations($data,\''.$fillable.'\');'. PHP_EOL;
            } else {
                $string .= '                    return $data->' . $fillable .';'. PHP_EOL;
            }
                $string .= '                })';
            $controllerColumns[] = $string;
        }
        return implode(PHP_EOL,$controllerColumns);
    }


    public function ReplaceForTranslatable($fillable){
        return str_replace(
            [
                '[\'ar\']',
                '[\'en\']',
            ],
            [
                '\').\' (\'.__(\'Arabic',
                '\').\' (\'.__(\'English',
            ],
            $fillable
        );
    }

    public function AssertSee(){
        $AssertSee = [];
        foreach($this->modelFillable as $fillable){
                $AssertSee[] = '        $response->assertSee($'.$this->crudName.'->'.$fillable.');';
        }
        return implode(PHP_EOL,$AssertSee);
    }

    public function UpdateFillable(){
        $UpdateFillable = [];
        foreach($this->modelFillable as $fillable){
            if(($fillable != 'status') || !isset($this->relations[$fillable])){
                $UpdateFillable[] = '        $Updated["'.$fillable.'"] = $faker->name;';
            }
        }
        return implode(PHP_EOL,$UpdateFillable);
    }




    public function AssertDatabaseHas($type){
        $Model = $this->modelsNameSpace . $this->modelName;
        $table = (new $Model)->getTable();
        $assertDBHas = '        $this->assertDatabaseHas(\''.$table.'\',['.PHP_EOL;
        foreach($this->modelFillable as $fillable){
            if (isset((new $Model)->translatable) && in_array($fillable,(new $Model)->translatable)) {
                $assertDBHas .= '            \''.$fillable.'\'=>json_encode('.($type == 'create' ? '$'.$this->crudName : '$Updated').'->getTranslations(\''.$fillable.'\')),'.PHP_EOL."\t";
            } else
                $assertDBHas .= '            \''.$fillable.'\'=>'.($type == 'create' ? '$'.$this->crudName : '$Updated').'->'.$fillable.','.PHP_EOL."\t";
        }
        $assertDBHas .= '	    ]);'.PHP_EOL;
        return $assertDBHas;
    }

    public function AssertDatabaseMissing(){
        $Model = 'MaxDev\Models\\'.$this->modelName;
        $table = (new $Model)->getTable();
        $AssertDbMissing = '        $this->assertDatabaseMissing(\''.$table.'\',['.PHP_EOL;
        foreach($this->modelFillable as $fillable){
                $AssertDbMissing .= '\''.$fillable.'\'=>$'.$this->crudName.'->'.$fillable.','.PHP_EOL."\t";
        }
        $AssertDbMissing .= ']);'.PHP_EOL;
        return $AssertDbMissing;
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

    private function updateLanguageFile()
    {
        AppendTextBeforeLastLine(base_path('resources/views/language.blade.php'),
            "{{__('$this->crudName')}}\r\n
{{__('New $this->crudName')}}\r\n
{{__('Add $this->crudName')}}\r\n
{{__('View $this->crudName')}}\r\n
{{__('Edit $this->crudName')}}\r\n
{{__('Save $this->crudName')}}\r\n
{{__('Successfully Updated $this->crudName')}}\r\n
{{__('Could not update $this->crudName')}}\r\n
{{__('Successfully deleted $this->crudName')}}\r\n
{{__('Could not delete $this->crudName')}}\r\n
            ",
            1);
    }
}
