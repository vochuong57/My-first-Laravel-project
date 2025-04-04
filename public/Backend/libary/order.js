(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    HT.editOrder = () => {
        $(document).on('click', '.edit-order', function(){
            let _this = $(this)
            let target = _this.attr('data-target')
            let html = ''
            let originalHtml
            if(target == 'description'){
                html = HT.renderDescriptionOrder(_this)
                
                originalHtml = _this.parents('.ibox').find('.ibox-content').html()
                
                HT.changeEditToCancle(_this, originalHtml)
                
                description = _this.parents('.ibox').find('.ibox-content .description').text()
            }else if(target == 'customerInfo'){
                html = HT.renderCustomerInfoOrder()
            }
            _this.parents('.ibox').find('.ibox-content').html(html)
        })
    }

    HT.renderDescriptionOrder = (_this) => {
        let value = _this.parents('.ibox').find('.ibox-content').text().trim()
        console.log(value);
        
        return '<input type="text" name="description" class="form-control ajax-edit" data-field="description" value="'+value+'"/>'
    }

    HT.renderCustomerInfoOrder = () => {
        return 123;
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

    $(document).ready(function(){
        HT.updateDescription()
        HT.cancelEdit()
        HT.editOrder()
    })

})(jQuery)