(function($){

    //var $document = $(document)
    var HT={};

    //Dùng để cập nhật các huyện khi chọn vào các TP khác nhau
    HT.province=()=>{
        $(document).on('change', '.province', function(){
            let _this = $(this);
            let province_id=_this.val(); // _this.val() để lấy giá trị của option
            console.log(province_id);
            $.ajax({
                url: 'ajax/location/getLocation',
                type: 'GET',
                data: {
                    'province_id':province_id //trả về id thành phố từ name của select
                },
                dataType: 'json',
                success: function(res){
                    //console.log(res);
                    $('.districts').html(res.html)//cái biến .html được lấy từ lớp LocationContrller truyền về
                },
                error: function(jqXHR, textStatus, errorThrown){
                    console.log('Lỗi: '+jqXHR);
                    console.log('Lỗi request: '+ textStatus);
                    console.log('Lỗi nội dung: '+ errorThrown);
                }
            });
        })
    }

    $(document).ready(function(){
        //console.log(123);
        HT.province();
    })

})(jQuery)