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
            $database = $this->makeDatabase($request);
            // $this->makeController();
            // $this->makeModel();
            // $this->makeRepository();
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
}
