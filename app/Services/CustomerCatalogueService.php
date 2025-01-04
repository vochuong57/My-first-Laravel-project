<?php

namespace App\Services;

use App\Services\Interfaces\CustomerCatalogueServiceInterface;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface as CustomerCatalogueRepository;
//thêm thư viện cho việc xử lý INSERT
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//thêm thư viện xử lý xử lý DATE
use Illuminate\Support\Carbon;
//thêm thư viện xử lý password
use Illuminate\Support\Facades\Hash;
//gọi thư viện customerRepository để cập nhật trạng thái khi đã chọn thay đổi trạng thái của customerCatalogue
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository;


/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerCatalogueService implements CustomerCatalogueServiceInterface
{
    protected $customerCatalogueRepository;
    protected $customerRepository;

    public function __construct(CustomerCatalogueRepository $customerCatalogueRepository, CustomerRepository $customerRepository){
        $this->customerCatalogueRepository=$customerCatalogueRepository;
        $this->customerRepository=$customerRepository;
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
        $customerCatalogues=$this->customerCatalogueRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'customer/catalogue/index'], 
            [],
            [], 
            ['customers']
        );
        //dd($customerCatalogues);
        return $customerCatalogues;
    }
    public function createCustomerCatalogue($request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //dd($payload);

            $customerCatalogue=$this->customerCatalogueRepository->create($payload);
            //dd($customer);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }

    public function updateCustomerCatalogue($id, $request){
        DB::beginTransaction();
        try{
            $payload = $request->except('_token','send');//lấy tất cả ngoại trừ hai trường này thay vì dùng input là lấy tất cả
            //$payload['birthday']=$this->convertBirthdayDate($payload['birthday']);
            //dd($payload);

            $customerCatalogue=$this->customerCatalogueRepository->update($id, $payload);
            //echo 1; die();
            //dd($customer);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function convertBirthdayDate($birthday=''){
        $carbonDate=Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday=$carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }
    public function deleteCustomerCatalogue($id){
        DB::beginTransaction();
        try{
            $customerCatalogue=$this->customerCatalogueRepository->delete($id);

            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function updateStatus($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            $payload[$post['field']]=(($post['value']==1)?2:1);
            
            //dd($payload);
            $customerCatalogue=$this->customerCatalogueRepository->update($post['modelId'], $payload);
            //echo 1; die();
            $this->changeCustomerStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
        
    }
    public function updateStatusAll($post=[]){
        //echo 123; die();
        DB::beginTransaction();
        try{
            //dd($post);
            $payload[$post['field']]=$post['value'];
            
            //dd($payload);
            $customerCatalogues=$this->customerCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            //echo 1; die();
            $this->changeCustomerStatus($post,$post['value']);
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    public function deleteAll($post=[]){
        DB::beginTransaction();
        try{
            $customerCatalogues=$this->customerCatalogueRepository->deleteByWhereIn('id',$post['id']);
            //echo 1; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    private function changeCustomerStatus($post, $value){
       
        DB::beginTransaction();
        try{
            //dd($post);
            $array=[];
            if(isset($post['modelId'])){
                $array[]=$post['modelId'];
            }else{
                $array=$post['id'];
            }//push vào trong mảng để update theo kiểu by where in
            //dd($post);
            $payload[$post['field']]=$value;
            $this->customerRepository->updateByWhereIn('customer_catalogue_id', $array, $payload);
            //echo 123; die();
            DB::commit();
            return true;
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();//die();
            return false;
        }
    }
    private function paginateSelect(){
        return [
            'id','name','description','publish'
        ];
    }

}
