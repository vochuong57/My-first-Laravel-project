<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
//thêm thư viện tự tạo
use App\Repositories\Interfaces\WardRepositoryInterface as WardRepository;
use App\Repositories\Interfaces\DistrictRepositoryInterface as DistrictRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
//chèn thêm thư viện có sẵn Request để lấy thông tin value id từ option mà ajax đã thiết lập
use Illuminate\Http\Request;


class LocationController extends Controller
{
    protected $wardRepository;
    protected $districtRepository;
    protected $provinceRepository;
    public function __construct(WardRepository $wardRepository,DistrictRepository $districtRepository, ProvinceRepository $provinceRepository){
        $this->wardRepository=$wardRepository;
        $this->districtRepository=$districtRepository;
        $this->provinceRepository=$provinceRepository;
    }

    public function getLocation(Request $request){
        
        // // cách 1 lấy dữ liệu từ function tự tạo dùng riêng của mỗi lớp ở trong lớp model của cách 1 chỉ cần khai báo ra tên bảng cho mỗi model xã huyện tỉnh
        // $htmlDistricts = '';
        // $htmlWards = '';

        // $province_id = $request->input('province_id');
        // $province_id=(int)$province_id;
        // $listDistricts = $this->districtRepository->findDistrictByProvinceId($province_id);
        // $htmlDistricts = $this->renderHTML($listDistricts, '[Chọn Quận/Huyện]');

        // $district_id = $request->input('district_id');
        // $district_id = (int)$district_id;
        // $listWards = $this->wardRepository->findWardByDistrictId($district_id);
        // $htmlWards = $this->renderHTML($listWards, '[Chọn Phường/Xã]');

        // $response = [
        //     'htmlDistricts' => $htmlDistricts,
        //     'htmlWards' => $htmlWards,
        // ];
        // return response()->json($response);//sau đó trả biến response('htmlDistricts', 'htmlWards') về lại ajax


        // cách 2 lấy dữ liệu từ function tự tạo dùng chung trong Base lưu ý nhớ xây dựng lại phương thức trong lớp model để định nghĩa lại tên khóa ngoại, khóa chính của mỗi bảng nếu tên đó không đúng với chuẩn larvel
        $html='';
        $get=$request->input();

        if($get['target']=='DTdistricts'){
            $ListDistricts=$this->provinceRepository->findById($get['data']['location_id'],['code','name'],['districts']);
            //dd(ListDistricts);
            $html=$this->renderHTML($ListDistricts->districts, '[Chọn Quận/Huyện]');
        }else if($get['target']=='DTwards'){
            $ListWards=$this->districtRepository->findById($get['data']['location_id'],['code','name'],['wards']);
            //dd($ListWards);
            $html=$this->renderHTML($ListWards->wards, '[Chọn Phường/Xã]');

        }

        $response=[
            'html'=>$html
        ];
        return response()->json($response);//sau đó trả biến response('html') về lại ajax
    }
    public function renderHTML($ListLocations, $root=''){
        $html="<option value='0'>$root</option>";
        foreach($ListLocations as $location){
            $html.="<option value='$location->code'>$location->name</option>";
        }
        return $html;
    }
}
