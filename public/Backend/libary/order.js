(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

    HT.editOrder = () => {
        $(document).on('click', '.edit-order', function(){
            let _this = $(this)
            let target = _this.attr('data-target')
            let html = ''
            let originalHtml = _this.parents('.ibox').find('.ibox-content').html()
            if(target == 'description'){
                html = HT.renderDescriptionOrder(_this)
                description = _this.parents('.ibox').find('.ibox-content .description').text()
            }else if(target == 'customerInfo'){
                html = HT.renderCustomerInfoOrder()
                setTimeout(() => {
                    HT.select2()
                }, 0);
            }
            HT.changeEditToCancle(_this, originalHtml)
            _this.parents('.ibox').find('.ibox-content').html(html)
        })
    }

    HT.renderDescriptionOrder = (_this) => {
        let value = _this.parents('.ibox').find('.ibox-content').text().trim()
        console.log(value);
        
        return '<input type="text" name="description" class="form-control ajax-edit" data-field="description" value="'+value+'"/>'
    }

    HT.renderCustomerInfoOrder = () => {

        let data = {
            fullname: $('.fullname').text(),
            email: $('.email').text(),
            phone: $('.phone').text(),
            address: $('.address').text(),
            province_id: $('.province_id').val(),
            district_id: $('.district_id').val(),
            ward_id: $('.ward_id').val(),
        }
        console.log(data.province_id);
        
        console.log(provinces);
        

        let html = `
            <div class="mb15">
                <label for="">Họ Tên</label>
                <input type="text" name="fullname" value="${data.fullname}" class="form-control">
            </div>
            <div class="mb15">
                <label for="">Email</label>
                <input type="text" name="email" value="${data.email}" class="form-control">
            </div>
            <div class="mb15">
                <label for="">Điện Thoại</label>
                <input type="text" name="phone" value="${data.phone}" class="form-control">
            </div>
            <div class="mb15">
                <label for="">Địa Chỉ</label>
                <input type="text" name="address" value="${data.address}" class="form-control">
            </div>
            <div class="mb15">
                <label for="">Tỉnh/Thành</label>
                <select name="province_id" class="form-control setupSelect2 provinces location" data-target="DTdistricts">
                    <option value="0">Chọn tỉnh/thành</option>
                    ${HT.provinceList()}
                    ${setTimeout(() => {
                        HT.loadCity(data.province_id)
                    }, 0)}
                </select>
            </div>
            <div class="mb15">
                <label for="">Quận/Huyện</label>
                <select name="district_id" class="form-control setupSelect2 location DTdistricts" data-target="DTwards">
                    <option value="0">Chọn quận/huyện</option>
                </select>
            </div>
            <div class="mb15">
                <label for="">Phường/Xã</label>
                <select name="ward_id" class="form-control setupSelect2 DTwards">
                    <option value="0">Chọn phường/xã</option>
                </select>
            </div>
            <div class="mb15">
                <button class="btn btn-primary saveCustomer">Lưu lại</button>
            </div>
        `
        return html
    }

    HT.provinceList = () => {
        let html = ''
        provinces.forEach(function(item, index){
            html += `<option value="${item.id}">${item.name}</option>`
        })
        return html
    }

    HT.loadCity=(province_id)=>{
        if(province_id!=''){
            $('.provinces').val(province_id).trigger('change');
        }
    }

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
        let district_id = $('.district_id').val()
        let ward_id = $('.ward_id').val()
        $.ajax({
            url: 'ajax/location/getLocation',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                //console.log(res);
                $('.'+option.target).html(res.html);

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

    HT.saveCustomer = (e) => {
        $(document).on('click', '.saveCustomer', function(){
            let _this = $(this)
            let orderId = $('.orderId').val()
            let option = {
                id: orderId,
                payload: {
                    fullname: $('input[name="fullname"]').val(),
                    email: $('input[name="email"]').val(),
                    phone: $('input[name="phone"]').val(),
                    address: $('input[name="address"]').val(),
                    province_id: $('select[name="province_id"]').val(),
                    district_id: $('select[name="district_id"]').val(),
                    ward_id: $('select[name="ward_id"]').val(),
                },
                _token: _token
            }
            // console.log(option);
            HT.ajaxUpdateOrderInfo(option, _this);
        })
    }
    
    HT.changeEditToCancle = (_this, originalHtml) => {
        // console.log(originalHtml);
        
        let encodeHtml = btoa(encodeURIComponent(originalHtml.trim()))
        _this.html('Hủy bỏ').removeClass('edit-order').addClass('cancel-edit').attr('data-html', encodeHtml)
    }

    let isCanceled = false; // Biến kiểm tra xem có bấm "Cancel" không
    let description;

    HT.cancelEdit = () => {
        $(document).on('click', '.cancel-edit', function(){
            let _this = $(this);

            isCanceled = true;

            let decodeHtml = decodeURIComponent(atob(_this.attr('data-html')));
            // console.log(decodeHtml)

            _this.parents('.ibox').find('.ibox-content').empty().html(decodeHtml);

            _this.html('Sửa').removeClass('cancel-edit').addClass('edit-order');
        });
    };

    HT.updateDescription = () => { // input
        $(document).on('change', '.ajax-edit', function(){
            let _this = $(this)
            let field = _this.attr('data-field')
            let value = _this.val()
            let orderId = $('.orderId').val()
            let option = {
                id: orderId,
                payload: {
                    [field]: value,
                },
                _token: _token
            }
            // Trì hoãn trước khi gửi AJAX để kiểm tra có bấm "Cancel" không
            setTimeout(() => {
                if (isCanceled) {
                    console.log("Change bị dừng do đã bấm Cancel!");
                    HT.renderDescriptionHtml(description, _this.parents('.ibox'))
                    
                    isCanceled = false; // Reset lại để lần sau vẫn có thể change
                    return ;
                }
                
                console.log(option);
                HT.ajaxUpdateOrderInfo(option, _this);
                
            }, 500); // Kiểm tra sau 1000ms
        })
    }

    HT.ajaxUpdateOrderInfo = (option, _this) => {
        console.log(option);
        
        $.ajax({
            url: 'ajax/order/update',
            type: 'POST',
            data: option,
            dataType: 'json',
            success: function(res){
                console.log(res);
                toastr.clear()
                if(res.code = 200){
                    if(_this.parents('.ibox').find('.cancel-edit').attr('data-target') == 'description'){
                        toastr.success(res.message, 'Thông báo từ hệ thống!')                        
                        HT.renderDescriptionHtml(option.payload.description, _this.parents('.ibox'))
                    }
                    else if(_this.parents('.ibox').find('.cancel-edit').attr('data-target') == 'customerInfo'){
                        toastr.success(res.message, 'Thông báo từ hệ thống!') 
                        // console.log(res.order);
                                               
                        HT.renderCustomerInfoHtml(res, _this.parents('.ibox'))
                    }
                }
            },
            error: function(xhr, status, error){
                console.log(xhr.responseText);
                toastr.error('Có lỗi xảy ra! Hãy thử lại.', 'Thông báo từ hệ thống!');
            }
        })
    }

    HT.renderDescriptionHtml = (value, target) => {
        target.find('.ibox-title .cancel-edit').html('Sửa').removeClass('cancel-edit').addClass('edit-order').attr('data-html', '')

        target.find('.ibox-content').html('<div class="description">'+value+'</div>')
    }

    HT.renderCustomerInfoHtml = (res, target) => {
        let html = `
            <div class="customer-line">
                <strong>N: </strong><span class="fullname">${res.order.fullname}</span>
            </div>
            <div class="customer-line">
                <strong>E: </strong><span class="email">${res.order.email}</span>
            </div>
            <div class="customer-line">
                <strong>P: </strong><span class="phone">${res.order.phone}</span>
            </div>
            <div class="customer-line">
                <strong>A: </strong><span class="address">${res.order.address}</span>
            </div>
            <div class="customer-line">
                <strong>P: </strong>${res.order.wards.name}
            </div>
            <div class="customer-line">
                <strong>Q: </strong>${res.order.districts.name}
            </div>
            <div class="customer-line">
                <strong>T: </strong>${res.order.provinces.name}
            </div>
        `
        $('.province_id').val(res.order.province_id)
        $('.district_id').val(res.order.district_id)
        $('.ward_id').val(res.order.ward_id)
        target.find('.ibox-title .cancel-edit').html('Sửa').removeClass('cancel-edit').addClass('edit-order').attr('data-html', '')
        target.find('.ibox-content').html(html)
    }

    HT.updateField = () => {
        $(document).on('click', '.updateField', function(){
            let _this = $(this)
            let field = _this.attr('data-field')
            let value = _this.attr('data-confirm')
            let orderId = $('.orderId').val()
            let option = {
                id: orderId,
                payload: {
                    [field]: value,
                },
                _token: _token
            }
            console.log(option);

            $.ajax({
                url: 'ajax/order/update',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    toastr.clear()
                    toastr.success(res.message, 'Thông báo từ hệ thống!')                        
                    HT.createOrderConfirmSection(_this)
                },
                error: function(xhr, status, error){
                    console.log(xhr.responseText);
                    toastr.error('Có lỗi xảy ra! Hãy thử lại.', 'Thông báo từ hệ thống!');
                }
            })
        })
    }

    HT.createOrderConfirmSection = (_this) => {
        let button = `<button class="button cancel updateField" data-field="confirm" data-confirm="cancel" data-title="${txtOrderCancelled}">${txtCancelButton}</button>`
        $('.cancel-block').html(button)
        
        let correctImage = `Backend/img/correct.png`
        $('.confirm-box').find('img').attr('src', correctImage)

        $('.isConfirm').html(_this.attr('data-title'))

        if(_this.attr('data-confirm') == 'confirm'){
            $('.confirm-block').html(txtConfirmed)
        }
        if(_this.attr('data-confirm') == 'cancel'){
            $('.confirm-block').html(txtCancelled)
            $('.cancel-block').empty()
        }
    }

    HT.updateBadge = () => {
        $(document).on('change', '.updateBadge', function(){
            let _this = $(this)

            // Nếu đang trong trạng thái skip (bỏ qua change), thì reset và thoát
            if (_this.data('skip') === true) {
                _this.data('skip', false); // reset lại
                return; // thoát không chạy tiếp
            }

            let field = _this.attr('data-field')
            let value = _this.val()
            let orderId = _this.parents('tr').find('.checkBoxItem').val()
            let option = {
                id: orderId,
                payload: {
                    [field]: value,
                },
                _token: _token
            }
            console.log(option);

            let confirmStatus = _this.parents('tr').find('.td-confirm').attr('data-confirm')

            if(confirmStatus != 'confirm'){
                // Đánh dấu là skip lần kế tiếp khi trigger change
                _this.data('skip', true);
                toastr.clear()
                toastr.error('Đơn hàng chưa được xác nhận! Bạn phải xác nhận đơn hàng trước khi thực hiện cập nhật này!', 'Thông báo từ hệ thống!');
                if(field == 'payment'){
                    _this.val('unpaid').trigger('change')
                }
                if(field == 'delivery'){
                    _this.val('pending').trigger('change')
                }
                return;
            }

            $.ajax({
                url: 'ajax/order/update',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    toastr.clear()
                    toastr.success(res.message, 'Thông báo từ hệ thống!')                        
                },
                error: function(xhr, status, error){
                    console.log(xhr.responseText);
                    toastr.error('Có lỗi xảy ra! Hãy thử lại.', 'Thông báo từ hệ thống!');
                }
            })
        })
    }

    $(document).ready(function(){
        HT.updateDescription()
        HT.cancelEdit()
        HT.editOrder()
        HT.getLocation()
        HT.saveCustomer()
        HT.updateField()
        HT.updateBadge()
    })

})(jQuery)