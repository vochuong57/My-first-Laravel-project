<?php

namespace App\Services;

use App\Services\Interfaces\ReviewServiceInterface;
use App\Services\BaseService;
use App\Classes\ReviewNested;
use App\Repositories\Interfaces\ReviewRepositoryInterface as ReviewRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/**
 * Class ReviewService
 * @package App\Services
 */
class ReviewService extends BaseService implements ReviewServiceInterface
{
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository){
        $this->reviewRepository=$reviewRepository;
       
    }

    public function paginate($request){
        $condition['keyword']=addslashes($request->input('keyword'));
        // dd($condition);
        $perpage=$request->integer('perpage', 20);
        $reviews=$this->reviewRepository->pagination(
            $this->paginateSelect(), 
            $condition, 
            $perpage, 
            ['path'=> 'review/index'],
            ['reviews.id', 'DESC'],
            [],
        );
        // dd($reviews);
        return $reviews;
    }

    public function create($post){
        DB::beginTransaction();
        try{
            // dd($post);
            $review = $this->reviewRepository->create($post);
            // dd($post['reviewable_type']);
            // echo 567;die();
            $this->nestedset=new ReviewNested([
                'table'=>'reviews',
                'reviewable_type'=>$post['reviewable_type'],
                'reviewable_id'=>$post['reviewable_id'],
                'fullname'=>$post['fullname'],
                'phone'=>$post['phone'],
                'email'=>$post['email'],
                'gender'=>$post['gender'],
                'description'=>$post['description'],
                'score'=>$post['score'],
                'customer_id'=>$post['customer_id'] ?? null,

            ]);
            $this->nestedset();
            DB::commit();
            return [
                'code' => 200,
                'message' => 'Thêm đánh giá thành công.',
            ];
        }catch(\Exception $ex){
            DB::rollBack();
            echo $ex->getMessage();die();
            return [
                'code' => 500,
                'message' => 'Lỗi hệ thống, vui lòng thử lại sau.',
            ];
        }
    }
    
    private function paginateSelect(){
        return [
            'id',
            'fullname',
            'phone',
            'email',
            'description',
            'customer_id',
            'reviewable_id',
            'reviewable_type',
            'score',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
