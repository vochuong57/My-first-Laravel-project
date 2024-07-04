<?php

namespace App\Services;

use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface as GenerateRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//gọi thư viện userRepository để cập nhật trạng thái khi đã chọn thay đổi trạng thái của userCatalogue
//use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;

//Chèn thư viện để làm $this->makeDatabase()
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Class UserService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{
    protected $generateRepository;
    //protected $userRepository;

    public function __construct(GenerateRepository $generateRepository){
        $this->generateRepository=$generateRepository;
        //$this->userRepository=$userRepository;
    }

    public function paginate($request){//$request để tiến hành chức năng tìm kiếm
        //dd($request);
        //echo 123; die();
        $condition['keyword']=addslashes($request->input('keyword'));
        $condition['publish']=$request->input('publish');
        // Kiểm tra nếu giá trị publish là 0, thì gán lại thành null
        if ($condition['publish'] == '0') {
            $condition['publish'] = null;
        }
        //dd($condition);
        $perpage=$request->integer('perpage', 20);
        $generates=$this->generateRepository->pagination(
            $this->paginateSelect(),
            $condition,
            $perpage,
            ['path'=> 'generate/index']
        );
        //dd($userCatalogues);
        return $generates;
    }
    public function createGenerate($request){
        DB::beginTransaction();
        try{
            // $database = $this->makeDatabase($request);
            // $controller = $this->makeController($request);
            // $model = $this->makeModel($request);
            $repository = $this->makeRepository($request);
            // $this->makeService();
            // $this->makeProvider();
            // $this->makeRequest();
            // $this->makeView();
            // $this->makeRoute();
            // $this->makeRule();
            // $this->makeLang();

            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            
            //vì chúng ta có khóa ngoại khi thêm bảng này mà khóa ngoại này là user_id thì đó là tài khoản đã đăng nhập thì
            $payload['user_id']=Auth::id();
            //dd($payload);
            $generate=$this->generateRepository->create($payload);
            //dd($generate);
            //echo -1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateGenerate($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);

            $generate=$this->generateRepository->update($id, $payload);
            //echo 1; die();
            //dd($user);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
   
    public function deleteGenerate($id){
        DB::beginTransaction();
        try{
            $generate=$this->generateRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    
    private function paginateSelect(){
        return[
            'id','name','schema'
        ];
    }

    //-----------------------------------------------MAKE DATABASE--------------------------------------------------

    // Tạo file migration và Database
    private function makeDatabase($request){
        try{
            $payload = $request->only('schema', 'name', 'module_type');
            // dd($payload);
    
            $tableNames = $this->coverModuleNameToTableName($payload['name']).'s';
            // echo $tableNames; die();
    
            // Tạo TÊN FILE migrations từ tên module
            $migrationFileName = date('Y_m_d_His').'_create_'.$tableNames.'_table.php';
            // echo $migrationFileName; die();
    
            // Tạo ĐƯỜNG DẪN tói folder migrations
            $migrationPath = database_path('migrations/'.$migrationFileName);
            // echo $migrationPath; die();
    
            // Tạo NỘI DUNG cho file migrations
            $migrationTemplate = $this->createMigrationFile($payload);
    
            // TIẾN HÀNH tạo file migrations
            FILE::put($migrationPath, $migrationTemplate);//kq: module_catalogues || modules
    
            if($payload['module_type'] !== 3){//Nếu chọn vào module khác thì sẽ ra thêm bảng mudule_catalogue_language || module_language
                $foreignKey = $this->coverModuleNameToTableName($payload['name']).'_id';
                //echo $foreignKey; die();
    
                $pivotTableName = $this->coverModuleNameToTableName($payload['name']).'_language';
                //echo $pivotTableName; die();
    
                // Tạo NỘI DUNG cho file migrations
                $migrationPivotTemplate = $this->createMigrationFile([
                    'schema' => $this->pivotSchema($foreignKey, $tableNames, $pivotTableName),
                    'name' => $pivotTableName,
                ]);
                // dd($migrationPivotTemplate);
    
                $tableName = $this->coverModuleNameToTableName($payload['name']);
    
                // Tạo TÊN FILE migrations từ tên module
                $migrationPivotFileName = date('Y_m_d_His', time() + 10).'_create_'.$tableName.'_language_table.php';
                // echo $migrationPivotFileName; die();
    
                // Tạo ĐƯỜNG DẪN tói folder migrations
                $migrationPivotPath = database_path('migrations/'.$migrationPivotFileName);
                // echo $migrationPivotPath; die();
    
                // TIẾN HÀNH tạo file pivotMigrations
                FILE::put($migrationPivotPath, $migrationPivotTemplate);//kq: module_catalogue_language || module_language
            }
    
            // Tạo cơ sở dữ liệu
            ARTISAN::call('migrate');
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    private function createMigrationFile($payload){

    //Tạo template 
    $migrationTemplate = <<<MIGRATION
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        {$payload['schema']}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$this->coverModuleNameToTableName($payload['name'])}');
    }
};

MIGRATION;
        return $migrationTemplate;
    }

    private function coverModuleNameToTableName($name){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp;
    }

    private function pivotSchema($foreignKey = '', $tableNames = '', $pivotTableName = ''){
        //Tạo template
        $pivotSchema = <<<SCHEMA
Schema::create('{$pivotTableName}', function (Blueprint \$table) {
    \$table->bigInteger('{$foreignKey}')->unsigned();
    \$table->foreign('{$foreignKey}')->references('id')->on('{$tableNames}')->onDelete('cascade');
    \$table->bigInteger('language_id')->unsigned();
    \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
    \$table->string('name');
    \$table->text('description')->nullable();
    \$table->string('canonical')->nullable()->unique();
    \$table->longText('content')->nullable();
    \$table->string('meta_title')->nullable();
    \$table->string('meta_keyword')->nullable();
    \$table->text('meta_description')->nullable();
    \$table->timestamp('deleted_at')->nullable();
    \$table->timestamps();
});

SCHEMA;
        return $pivotSchema;
    }

    //-----------------------------------------------MAKE CONTROLLER--------------------------------------------------

    private function makeController($request){
        $payload = $request->only('name', 'module_type');

        switch ($payload['module_type']){
            case 1:
                $this->createTemplateController($payload['name'], 'TemplateCatalogueController');
                break;
            case 2:
                $this->createTemplateController($payload['name'], 'TemplateController');
                break;
            default:
                $this->createSingleController();
        }
    }

    private function createTemplateController($name, $controllerFile){
        try{
            //ProductCatalogue

            // Tạo TÊN FILE controller từ tên module
            $controllerFileName = $name.'Controller.php';
            // echo $controllerFileName; die();

            // Tạo ĐƯỜNG DẪN tới file TemplateCatalogueController || TemplateController để lấy nội dung dựng
            $templateControllerPath = base_path('app/Templates/Controller/'.$controllerFile.'.php');
            // echo $templateControllerPath; die();

            // Đọc NỘI DUNG từ đường dẫn TemplateCatalogueController || TemplateController
            $controllerContent = file_get_contents($templateControllerPath);
            // echo $controllerContent; die();

            // Chuẩn bị các biến để đổ vào nội dung TemplateCatalogueController || TemplateController
            $replace=[
                'ModuleTemplate' => $name,
                'moduleTemplate' => lcfirst($name),
                'tableNames' => $this->coverModuleNameToTableName($name).'s',
                'foreignKey' => $this->coverModuleNameToTableName($name).'_id',
                'moduleView' => str_replace('_', '.', $this->coverModuleNameToTableName($name))
            ];
            //dd($replace);

            // Tiến hành THAY THẾ các biến ban đầu của TemplateCatalogueController || TemplateController thành các biến đã được chuẩn bị
            $controllerContent = str_replace('{ModuleTemplate}', $replace['ModuleTemplate'], $controllerContent);
            $controllerContent = str_replace('{moduleTemplate}', $replace['moduleTemplate'], $controllerContent);
            $controllerContent = str_replace('{tableNames}', $replace['tableNames'], $controllerContent);
            $controllerContent = str_replace('{foreignKey}', $replace['foreignKey'], $controllerContent);
            $controllerContent = str_replace('{moduleView}', $replace['moduleView'], $controllerContent);
            // echo $controllerContent; die();

            // Tạo ĐƯỜNG DẪN tới folder Backend
            $controllerPath = base_path('app/Http/Controllers/Backend/'.$controllerFileName);
            // echo $controllerPath; die();

            // TIẾN HÀNH tạo file ModuleCatalogueController || ModuleController
            FILE::put($controllerPath, $controllerContent);//kq: ModuleCatalogueController.php || ModuleController.php
            // die();
            return true;
        }catch(\Exception $ex){
            echo $ex->getMessage();//die();
            return false;
        }
    }

    //-----------------------------------------------MAKE MODEL--------------------------------------------------

    private function makeModel($request){
        try{
            $payload = $request->only('name', 'module_type');

            if($payload['module_type'] == 1){
                $this->createTemplateModel($payload['name'], 'TemplateCatalogueModel');
            }else if($payload['module_type'] == 2){
                $this->createTemplateModel($payload['name'], 'TemplateModel');
            }else{
                $this->createSingleModel();
            }
        }catch(\Exception $ex){
            echo $ex->getMessage();//die();
            return false;
        }
    }

    private function createTemplateModel($name, $modelFile){
        try{
            //ProductCatalogue

            // Tạo TÊN FILE model từ tên module
            $modelFileName = $name.'.php';
            // echo $modelFileName; die();

            // Tạo ĐƯỜNG DẪN tới file TemplateCatalogueModel || TemplateModel để lấy nội dung dựng
            $templateModelPath = base_path('app/Templates/Model/'.$modelFile.'.php');
            // echo $templateModelPath; die();

            // Đọc NỘI DUNG từ đường dẫn TemplateCatalogueModel || TemplateModel
            $modelContent = file_get_contents($templateModelPath);
            // echo $modelContent; die();

            // Chuẩn bị các biến để đổ vào nội dung TemplateCatalogueModel || TemplateModel
            $replace=[
                'ModuleTemplate' => $name,
                'moduleTemplate' => lcfirst($name),
                'tableNames' => $this->coverModuleNameToTableName($name).'s',
                'moduleKey' => $this->coverModuleNameToTableName($name).'_id',
                'foreignKey' => $this->coverModuleNameToTableName($name).'_catalogue_id',
                'pivotTable' => $this->coverModuleNameToTableName($name).'_language',
                'pivotModel' => $name.'Language',

                'relation' => explode('_', $this->coverModuleNameToTableName($name))[0],
                'relationCatalogue' => $this->coverModuleNameToTableName($name).'_catalogue',

                'relationModel' => ucfirst(explode('_', $this->coverModuleNameToTableName($name))[0]),
                'relationModelCatalogue' => $name.'Catalogue',

                'relationTable1' => $this->coverModuleNameToTableName($name).'_'.explode('_', $this->coverModuleNameToTableName($name))[0],
                'relationTable2' => $this->coverModuleNameToTableName($name).'_catalogue_'.explode('_', $this->coverModuleNameToTableName($name))[0],
            ];
            // dd($replace['relationModel']); die();

            // Tiến hành THAY THẾ các biến ban đầu của TemplateCatalogueModel || TemplateModel thành các biến đã được chuẩn bị
            foreach($replace as $key => $val){
                $modelContent = str_replace('{'.$key.'}', $replace[$key], $modelContent);
            }
            // echo $modelContent; die();

            // Tạo ĐƯỜNG DẪN tới folder Models
            $modelPath = base_path('app/Models/'.$modelFileName);
            // echo $modelPath; die();

            // TIẾN HÀNH tạo file ModuleCatalogueModel || ModuleModel
            FILE::put($modelPath, $modelContent);//kq: ModuleCatalogueModel.php || ModuleModel.php

            // Tạo file ModelLanguage
            $modelPivotFileName = $name.'Language.php';
            $templateModelPivotPath = base_path('app/Templates/Model/TemplateModelLanguage.php');
            $modelPivotContent = file_get_contents($templateModelPivotPath);
            foreach($replace as $key => $val){
                $modelPivotContent = str_replace('{'.$key.'}', $replace[$key], $modelPivotContent);
            }
            $modelPivotPath = base_path('app/Models/'.$modelPivotFileName);
            FILE::PUT($modelPivotPath, $modelPivotContent);

            // die();
            return true;
        }catch(\Exception $ex){
            echo $ex->getMessage();//die();
            return false;
        }
    }

    //-----------------------------------------------MAKE REPOSITORY--------------------------------------------------

    private function makeRepository($request){
        $payload = $request->only('name', 'module_type');

        switch ($payload['module_type']){
            case 1:
                $this->createTemplateRepository($payload['name'], 'TemplateCatalogueRepository');
                break;
            case 2:
                $this->createTemplateRepository($payload['name'], 'TemplateRepository');
                break;
            default:
                $this->createSingleRepository();
        }
    }

    private function createTemplateRepository($name, $repositoryFile){
        try{
            //ProductCatalogue

            // Tạo TÊN FILE repository từ tên module
            $repositoryFileName = $name.'Repository.php';
            // echo $repositoryFileName; die();

            // Tạo ĐƯỜNG DẪN tới file TemplateCatalogueRepository || TemplateRepository để lấy nội dung dựng
            $templateRepositoryPath = base_path('app/Templates/Repository/'.$repositoryFile.'.php');
            // echo $templateRepositoryPath; die();

            // Đọc NỘI DUNG từ đường dẫn TemplateCatalogueRepository || TemplateRepository
            $repositoryContent = file_get_contents($templateRepositoryPath);
            // echo $repositoryContent; die();

            // Chuẩn bị các biến để đổ vào nội dung TemplateCatalogueRepository || TemplateRepository
            $replace=[
                'ModuleTemplate' => $name,
                'tableNames' => $this->coverModuleNameToTableName($name).'s',
                'moduleKey' => $this->coverModuleNameToTableName($name).'_id',
                'pivotTable' => $this->coverModuleNameToTableName($name).'_language',
                'relationCatalogue' => $this->coverModuleNameToTableName($name).'_catalogue',
            ];
            // dd($replace['relationModel']); die();

            // Tiến hành THAY THẾ các biến ban đầu của TemplateCatalogueRepository || TemplateRepository thành các biến đã được chuẩn bị
            foreach($replace as $key => $val){
                $repositoryContent = str_replace('{'.$key.'}', $replace[$key], $repositoryContent);
            }
            // echo $repositoryContent; die();

            // Tạo ĐƯỜNG DẪN tới folder Repositorys
            $repositoryPath = base_path('app/Repositories/'.$repositoryFileName);
            // echo $repositoryPath; die();

            // TIẾN HÀNH tạo file ModuleCatalogueRepository || ModuleRepository
            FILE::put($repositoryPath, $repositoryContent);//kq: ModuleCatalogueRepository.php || ModuleRepository.php

            // Tạo file RepositoryInterface
            $repositoryInterfaceFileName = $name.'RepositoryInterface.php';
            $templateRepositoryInterfacePath = base_path('app/Templates/Repository/TemplateRepositoryInterface.php');
            $repositoryInterfaceContent = file_get_contents($templateRepositoryInterfacePath);
            foreach($replace as $key => $val){
                $repositoryInterfaceContent = str_replace('{'.$key.'}', $replace[$key], $repositoryInterfaceContent);
            }
            $repositoryInterfacePath = base_path('app/Repositories/Interfaces/'.$repositoryInterfaceFileName);
            FILE::PUT($repositoryInterfacePath, $repositoryInterfaceContent);

            // Tạo file LanguageRepository
            $repositoryPivotFileName = $name.'LanguageRepository.php';
            $templateRepositoryPivotPath = base_path('app/Templates/Repository/TemplateLanguageRepository.php');
            $repositoryPivotContent = file_get_contents($templateRepositoryPivotPath);
            foreach($replace as $key => $val){
                $repositoryPivotContent = str_replace('{'.$key.'}', $replace[$key], $repositoryPivotContent);
            }
            $repositoryPivotPath = base_path('app/Repositories/'.$repositoryPivotFileName);
            FILE::PUT($repositoryPivotPath, $repositoryPivotContent);

            // Tạo file LanguageRepositoryInterface
            $repositoryPivotInterfaceFileName = $name.'LanguageRepositoryInterface.php';
            $templateRepositoryPivotInterfacePath = base_path('app/Templates/Repository/TemplateLanguageRepositoryInterface.php');
            $repositoryPivotInterfaceContent = file_get_contents($templateRepositoryPivotInterfacePath);
            foreach($replace as $key => $val){
                $repositoryPivotInterfaceContent = str_replace('{'.$key.'}', $replace[$key], $repositoryPivotInterfaceContent);
            }
            $repositoryPivotInterfacePath = base_path('app/Repositories/Interfaces/'.$repositoryPivotInterfaceFileName);
            FILE::PUT($repositoryPivotInterfacePath, $repositoryPivotInterfaceContent);

            // die();
            return true;
        }catch(\Exception $ex){
            echo $ex->getMessage();//die();
            return false;
        }
    }
}
