(function($){

    //var $document = $(document)
    var HT={};

    // //cách1 Dùng để cập nhật các huyện khi chọn vào các TP khác nhau
    //     HT.province=()=>{
    //         $(document).on('change', '.provinces', function(){
    //             let _this = $(this);
    //             let province_id=_this.val(); // _this.val() để lấy giá trị của option
    //             console.log(province_id);
    //             $.ajax({
    //                 url: 'ajax/location/getLocation',
    //                 type: 'GET',
    //                 data: {
    //                     'province_id':province_id //trả về id thành phố từ name của select
    //                 },
    //                 dataType: 'json',
    //                 success: function(res){
    //                     //console.log(res);
    //                     $('.districts').html(res.htmlDistricts)//cái biến .html được lấy từ lớp LocationContrller truyền về
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown){
    //                     console.log('Lỗi: '+jqXHR);
    //                     console.log('Lỗi request: '+ textStatus);
    //                     console.log('Lỗi nội dung: '+ errorThrown);
    //                 }
    //             });
    //         })
    //     }

    //     //cách1 Dùng để cập nhật các xã khi chọn vào các huyện khác nhau
    //     HT.district=()=>{
    //         $(document).on('change', '.districts', function(){
    //             let _this = $(this);
    //             let district_id=_this.val(); // _this.val() để lấy giá trị của option
    //             console.log(district_id);
    //             $.ajax({
    //                 url: 'ajax/location/getLocation',
    //                 type: 'GET',
    //                 data: {
    //                     'district_id':district_id //trả về id thành district từ name của select
    //                 },
    //                 dataType: 'json',
    //                 success: function(res){
    //                     //console.log(res);
    //                     $('.wards').html(res.htmlWards)//cái biến .html được lấy từ lớp LocationContrller truyền về
    //                 },
    //                 error: function(jqXHR, textStatus, errorThrown){
    //                     console.log('Lỗi: '+jqXHR);
    //                     console.log('Lỗi request: '+ textStatus);
    //                     console.log('Lỗi nội dung: '+ errorThrown);
    //                 }
    //             });
    //         })
    //     }

    //cách 2 Dùng để cập nhật các huyện, xã 
    HT.getLocation=()=>{
        $(document).on('change', '.location', function(){
            let _this = $(this);
            let option = {
                'data': {
                    'location_id': _this.val(),
                },
                'target': _this.data('target')
            };
            console.log(option);
            HT.sendDataTogetLocation(option);
        });
        
    }

    HT.sendDataTogetLocation=(option)=>{
        $.ajax({
            url: 'ajax/location/getLocation',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                //console.log(res);
                $('.'+option.target).html(res.html);//Tiến hành rennder cho từng select tương ứng ở mỗi lần chạy data-target

                //xử lí load lại dữ liệu khi trang bị load lại do nhập sai ở city, district, ward
                if(district_id!=''&& option.target=='DTdistricts'){
                    $('.DTdistricts').val(district_id).trigger('change')
                }
                if(ward_id!=''&& option.target=='DTwards'){
                    $('.DTwards').val(ward_id).trigger('change')
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Lỗi: '+jqXHR);
                console.log('Lỗi request: '+ textStatus);
                console.log('Lỗi nội dung: '+ errorThrown);
            }
        });
    }

    HT.loadCity=()=>{
        if(province_id!=''){
            $('.provinces').val(province_id).trigger('change');//trigger chaneg để tự động chạy sự kiện on change khi đã có giá trị value của province_id
        }
    }

    $(document).ready(function(){
        //console.log(123);
        //cách 1 ajax huyện xã
        //HT.province();
        //HT.district();

        //cách 2 ajax huyện xã
        HT.getLocation();

        //gọi hàm xử lí load lại dữ liệu khi trang bị load lại do nhập sai ở city, district, ward
        HT.loadCity(); 
    })

})(jQuery)