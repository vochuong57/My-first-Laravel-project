<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\OrderRepositoryInterface as OrderRepository;
use App\Services\Interfaces\OrderServiceInterface as OrderService;
use App\Services\Interfaces\CartServiceInterface as CartService;

class VnpayController extends FrontendController
{
    protected $orderRepository;
    protected $cartService;
    protected $orderService;
    public function __construct(OrderRepository $orderRepository, CartService $cartService, OrderService $orderService){
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        parent::__construct();
    }

    public function vnpay_return(Request $request){
        
        $configVnpay = vnpayConfig();

        $vnp_TmnCode = $configVnpay['vnp_TmnCode'];//Mã website tại VNPAY 
        $vnp_HashSecret = $configVnpay['vnp_HashSecret']; //Chuỗi bí mật
        $vnp_Url = $configVnpay['vnp_Url'];
        $vnp_Returnurl = $configVnpay['vnp_Returnurl'];
        $vnp_apiUrl = $configVnpay['vnp_apiUrl'];; //Địa chỉ API của VNPAY
        $apiUrl = $configVnpay['apiUrl'];; //Địa chỉ API của VNPAY
        //Expire time
        $startTime = date('YmdHis');
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        // dd($_GET['vnp_ResponseCode']);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $code = $request->input('vnp_TxnRef');
                // dd($code);
                // dd($code);
                $condition = [
                    ['code', '=', $code],
                ];
                $order = $this->orderRepository->findByCondition($condition);
                // dd($order);
                $order = $this->cartService->getFullAddress($order);
                if(!$order){
                    return redirect()->route('home.index')->with('error','Đơn hàng không tồn tại');
                }
                $languageId = $this->getLanguage();
                $config = $this->config();
                $systems = $this->getSystem();
                $seo = [
                    'meta_title' => $systems['success_meta_title'],
                    'meta_keyword' => $systems['success_meta_keyword'],
                    'meta_description' => $systems['success_meta_description'],
                    'canonical' => write_url('cart/yourCode/success'),
                    'meta_images' => $systems['success_meta_images'],
                ];
                // dd($seo);
                $template = 'frontend.cart.component.vnpay';

                return view('frontend.cart.success', compact('config', 'languageId', 'seo', 'order', 'template', 'secureHash', 'vnp_SecureHash'));
            } 
            else {
                echo "GD Khong thanh cong";
            }
        } else {
            echo "Chu ky khong hop le";
        }
        
    }

    public function vnpay_ipn(){
        /* Payment Notify
        * IPN URL: Ghi nhận kết quả thanh toán từ VNPAY
        * Các bước thực hiện:
        * Kiểm tra checksum 
        * Tìm giao dịch trong database
        * Kiểm tra số tiền giữa hai hệ thống
        * Kiểm tra tình trạng của giao dịch trước khi cập nhật
        * Cập nhật kết quả vào Database
        * Trả kết quả ghi nhận lại cho VNPAY
        */
        
        
        $configVnpay = vnpayConfig();

        $vnp_TmnCode = $configVnpay['vnp_TmnCode'];//Mã website tại VNPAY 
        $vnp_HashSecret = $configVnpay['vnp_HashSecret']; //Chuỗi bí mật
        $vnp_Url = $configVnpay['vnp_Url'];
        $vnp_Returnurl = $configVnpay['vnp_Returnurl'];
        $vnp_apiUrl = $configVnpay['vnp_apiUrl'];; //Địa chỉ API của VNPAY
        $apiUrl = $configVnpay['apiUrl'];; //Địa chỉ API của VNPAY
        //Expire time
        $startTime = date('YmdHis');
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        $inputData = array();
        $returnData = array();
        
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        $vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi
        
        $Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
        $orderId = $inputData['vnp_TxnRef'];
       

        try {
            //Check Orderid    
            //Kiểm tra checksum của dữ liệu
            if ($secureHash == $vnp_SecureHash) {
                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId            
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
                //Giả sử: $order = mysqli_fetch_assoc($result);   
                $condition = [
                    ['code', '=', $orderId],
                ];
                $order = $this->orderRepository->findByCondition($condition);
                if ($order != NULL) {
                    $orderAmount = !is_null($order->promotion) ? ($order->cart['cartTotal'] - $order->promotion['discount']) : $order->cart['cartTotal'];
                    if($orderAmount == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
                    {
                        if ($order->payment != NULL && $order->payment == 'unpaid') {
                            $post['id'] = $order->id;
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                // Trạng thái thanh toán thành công
                                $post['payload']['confirm'] = 'confirm';
                                $post['payload']['payment'] = 'paid';
                            } else {
                                // Trạng thái thanh toán thất bại / lỗi
                                $post['payload']['confirm'] = 'confirm';
                                $post['payload']['paymnent'] = 'failed';
                            }
                            $flag = $this->orderService->update($post);
                            //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
                            //
                            //
                            //
                            //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công                
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    }
                    else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        //Trả lại VNPAY theo định dạng JSON
        echo json_encode($returnData);
            
    }

    private function config(){
        return [
            'js'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'Backend/libary/location.js',
                'Frontend/resources/cart.js',
            ],
            'css'=>[
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ]
        ];
    }
}
