(function($){

    var $document = $(document)
    //lặp switchchery cho form chính quản lý user cho cột trạng thái
    var HT={};
    var _token=$('meta[name="csrf-token"]').attr('content');
    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394' });
        })
    }

    //Dùng trong form creat, edit userv.v... để tạo bộ lọc cho select option
    HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

    //thay đổi trạng thái publish_at user
    HT.changeStatus = () => {
        if ($('.status').length) {
            $(document).on('change', '.status', function(){
                let _this=$(this);
                let currentValue = _this.val(); // Lấy giá trị mới từ select sau mỗi lần thay đổi
    
                let option={
                    'value': currentValue, // Sử dụng giá trị mới từ select
                    'modelId': _this.attr('data-modelId'),
                    'model': _this.attr('data-model'),
                    'field': _this.attr('data-field'),
                    '_token': _token
                }
                console.log(option)
                $.ajax({
                    url: 'ajax/dashboard/changeStatus',
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        // Sau khi AJAX được gửi thành công, cập nhật giá trị của currentValue
                        currentValue = currentValue == 1 ? 0 : 1;
                        _this.val(currentValue); // Cập nhật giá trị của select
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log('Lỗi: '+jqXHR);
                        console.log('Lỗi request: '+ textStatus);
                        console.log('Lỗi nội dung: '+ errorThrown);
                    }
                });
            })  
        }
    }
    

    //chọn tất cả các checkbox ở thead từ #checkAll
    HT.checkAll=()=>{
        if($('#checkAll').length){
            $(document).on('change', '#checkAll', function(){
                let isChecked=$(this).prop('checked')
                $('.checkBoxItem').prop('checked', isChecked);
                $('.checkBoxItem').each(function(){
                    let _this=$(this)
                    if(_this.prop('checked')){
                        _this.closest('tr').addClass('active-bg')
                    }else{
                        _this.closest('tr').removeClass('active-bg')
                    }
                })
            })
        }
    }

    //chọn từng checkboxItem từ tbody
    HT.checkBoxItem=()=>{
        if($('.checkBoxItem').length){
            $(document).on('change','.checkBoxItem', function(){
                let _this=$(this)
                let isChecked=_this.prop('checked')
                if(isChecked){
                    _this.closest('tr').addClass('active-bg')
                }else{
                    _this.closest('tr').removeClass('active-bg')
                }
                HT.allChecked()
            })
        }
    }

    HT.allChecked=()=>{//phương thức này để xử lý khi click hết các checkBoxItem thì trạng thái checkAll mới click
        let allChecked=$('.checkBoxItem:checked').length===$('.checkBoxItem').length;
        $('#checkAll').prop('checked', allChecked);
    }

    HT.changeStatusAll=()=>{
        if($('.changeStatusAll').length){
            $(document).on('click', '.changeStatusAll', function(e){
                e.preventDefault();
                let _this=$(this);
                let id=[];
                $('.checkBoxItem').each(function(){
                    let checkBox=$(this)
                    if(checkBox.prop('checked')){
                        id.push(checkBox.val())
                    }
                })
                // console.log(id);
                // return false;
                let option={
                    'value': _this.attr('data-value'),
                    'model': _this.attr('data-model'),
                    'field': _this.attr('data-field'),
                    'id': id,
                    '_token': _token
                }
                //console.log(option)
                //return false;
                $.ajax({
                    url: 'ajax/dashboard/changeStatusAll',
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        if(res.flag==true){
                            let cssActive1='background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;';
                            let cssActive2='left: 20px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;';
                            if(option.value==1){
                                for(let i=0;i<id.length;i++){
                                    $('.js-switch-'+id[i]).find('span.switchery').attr('style',cssActive1).find('small').attr('style', cssActive2)
                                }
                            }   
                            
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log('Lỗi: '+jqXHR);
                        console.log('Lỗi request: '+ textStatus);
                        console.log('Lỗi nội dung: '+ errorThrown);
                    }
                });
            })
        }
    }

    $document.ready(function(){
        console.log(123);
        //gọi function lặp switchery (giao diện)
        HT.switchery();

        //gọi phương thức tạo select2 (giao diện)
        HT.select2();

        //gọi thay đổi trạng thái (cột trạng thái) user cập nhật lại giá trị vào lưu vào DB
        HT.changeStatus();

        //gọi click all checkbox (giao diện)
        HT.checkAll();
        HT.checkBoxItem();

        HT.changeStatusAll();
    })

})(jQuery)