<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
//chèn thêm thư viện có sẵn Request để lấy thông tin value id từ option
use Illuminate\Http\Request;


class LocationController extends Controller
{
    protected $districtRepository;
    public function __construct(DistrictRepository $districtRepository){
        $this->districtRepository=$districtRepository;
    }

    public function getLocation(Request $request){
        $province_id=$request->input('province_id');//lấy ra được value của option thông qua thư viện Request
        //echo $province_id; die();
        $districts=$this->districtRepository->findDistrictByProvinceId($province_id);
        //dd($districts);
        // foreach($districts as $district){
        //     echo $district->code;
        // }
        // die();
        $response=[
            'html'=>$this->renderHTML($districts)
        ];
        return response()->json($response);//sau đó trả biến response('html') về lại ajax
    }
    public function renderHTML($districts){
        $html="<option value='0'>[Chọn Quận/Huyện]</option>";
        foreach($districts as $district){
            $html.="<option value='$district->code'>$district->name</option>";
        }
        return $html;
    }
}
