(function($){

    var $document = $(document)
    //lặp switchchery cho form chính quản lý user cho cột trạng thái
    var HT={};
    var _token=$('meta[name="csrf-token"]').attr('content');
    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394', size: 'small' });
        })
    }

    //Dùng trong form creat, edit userv.v... để tạo bộ lọc cho select option
    HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

    //Dùng trong form product/variant
    HT.niceSelect = () =>{
        $('.setupNiceSelect').niceSelect();
    }

    // Dùng trong form product/aside để chuyển chuỗi string về dạng thành tiền 1.234.567
    HT.int = () => {
        $(document).on('change keyup blur', '.int', function() {
            let _this = $(this);
            let value = _this.val();
            if (value === '') {
                _this.val('0');
            } else {
                value = value.replace(/\./g, '');
                if (isNaN(value)) {
                    _this.val('0');
                } else {
                    _this.val(HT.addCommas(value));
                }
            }
        });

        $(document).on('keydown', '.int', function(e) {
            let _this = $(this);
            let data = _this.val();
            if (data == '0') {
                let unicode = e.keyCode || e.which;
                // Check for numbers on both the main keyboard and the numpad
                if (unicode != 190 && ((unicode >= 48 && unicode <= 57) || (unicode >= 96 && unicode <= 105))) {
                    _this.val('');
                }
            }
        });
    };

    HT.addCommas = (nStr) => {
        nStr = String(nStr);
        nStr = nStr.replace(/\./g, '');
        let str = '';
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = (i - 3) < 0 ? 0 : (i - 3);
            str = nStr.slice(a, i) + '.' + str;
        }
        str = str.slice(0, str.length - 1);
        return str;
    };

    // V6 thay đổi trạng thái publish user
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
                        currentValue = currentValue == 1 ? 2 : 1;
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
    

    // V6 chọn tất cả các checkbox ở thead từ #checkAll
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

    // V6 chọn từng checkboxItem từ tbody
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

    // V6 Chức năng cập nhật hàng loạt trong tool box
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
                            let cssActive2='left: 10px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;';
                            let cssUnActive1='background-color: rgb(255, 255, 255); border-color: rgb(223, 223, 223); box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;';
                            let cssUnActive2='left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;';
                            for(let i =0;i<id.length;i++){
                                if(option.value==2){
                                    $('.js-switch-'+id[i]).find('span.switchery').attr('style',cssActive1).find('small').attr('style', cssActive2)
                                    
                                }else if(option.value==1){
                                    $('.js-switch-'+id[i]).find('span.switchery').attr('style',cssUnActive1).find('small').attr('style', cssUnActive2)
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

    //V7 xóa hàng loạt trong toolbox
    HT.deleteAll=()=>{
        if($('.deleteAll').length){
            $(document).on('click','.deleteAll', function(e){
                e.preventDefault();
                let _this=$(this);
                let id=[];
                let languageId;
                $('.checkBoxItem').each(function(){
                    let checkBox=$(this)
                    if(checkBox.prop('checked')){
                        id.push(checkBox.val())
                        languageId=checkBox.attr('data-languageId')
                    }
                })
                let option={
                    'model': _this.attr('data-model'),
                    'id': id,
                    'languageId': languageId,
                    '_token': _token
                }
                //console.log(option)
                //return false;
                $.ajax({
                    url: 'ajax/dashboard/deleteAll',
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function(res){
                        console.log(res);
                        if(res.flag==true){
                            for(let i =0;i<id.length;i++){
                                $('.rowdel-'+id[i]).remove();
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

    HT.sortui=()=>{
        $('#sortable').sortable()
        $('#sortable').disableSelection()
        $('#sortable2').sortable()
        $('#sortable2').disableSelection()
    }

    $document.ready(function(){
        console.log(123);
        //gọi function lặp switchery (giao diện)
        HT.switchery();

        //gọi phương thức tạo select2 (giao diện)
        HT.select2();

        //gọi phương thức tạo niceSelect (giao diện)
        HT.niceSelect();

        //gọi chức năng ép chuỗi về giạng số thành tiền 
        HT.int();

        //gọi thay đổi trạng thái (cột trạng thái) user cập nhật lại giá trị vào lưu vào DB
        HT.changeStatus();

        //gọi click all checkbox (giao diện)
        HT.checkAll();
        HT.checkBoxItem();

        // Chức năng cập nhật cột publish user hàng loạt trong tool box
        HT.changeStatusAll();

        // Chức năng xóa hàng loạt user trong toolboox
        HT.deleteAll();

        HT.sortui();
    })

})(jQuery)