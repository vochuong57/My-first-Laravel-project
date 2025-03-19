(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng

	HT.addCart = (setting) => {
        $(document).on('click', '.addToCart', function(e){
            e.preventDefault()

            let _this = $(this)
            let id = _this.attr('data-id')
            let quantity = $('.quantity-text').val()
            if(typeof quantity == 'undefined'){
                quantity = 1;
            }

            let attribute_id = []
            $('.attribute-value .choose-attribute').each(function(){
                let _this = $(this)
                if(_this.hasClass('active')){
                    attribute_id.push(_this.attr('data-attributeid'))
                }
            })

            if(attribute_id.length == 0){
                $('.popupProduct').hide();
            }

            let option = {
                id: id,
                quantity: quantity,
                attribute_id: attribute_id,
            }
            console.log(option)
            
            $.ajax({
				url: 'ajax/cart/create',
				type: 'GET',
				data: option,
				dataType: 'json',
				beforeSend: function (){
	
				},
				success: function(res){
					console.log(res)
                    toastr.clear()
					if(res.code == 200){
                        toastr.success(res.message, 'Thông báo từ hệ thống!')
                    }else{
                        toastr.error('Có vấn đề xảy ra! Hãy thử lại', 'Thông báo từ hệ thống!')
                    }
				},
                error: function(xhr, status, error){
                    console.log('Error:', xhr.responseText); // Xem nội dung lỗi
                    toastr.error('Có lỗi xảy ra! Hãy thử lại.', 'Thông báo từ hệ thống!');
                }
			})
        })
    }

    HT.changeQuantity = () => {
        $(document).on('click', '.btn-qty', function(){
            let _this = $(this)
            let qtyElement = _this.siblings('.input-qty')
            let qty = qtyElement.val()
            let newQty = (_this.hasClass('minus')) ? parseInt(qty) - 1 : parseInt(qty) + 1
            newQty = (newQty < 1) ? 1 : newQty
            // console.log(newQty)
            qtyElement.val(newQty)

            let option = {
                rowId: _this.siblings('.rowId').val(),
                qty: newQty,
            }
            console.log(option)

            HT.handleUpdateCart(_this, option)
        })
    }
    HT.changeCartTotal = (res) => {
        $('.show-total-price').html(addCommas(res.response.cartTotal)+'đ')
    }

    HT.changeMiniCartQuantity = (res) => {
        $('#cartTotalItem').html(res.response.cartTotalItems)
    }

    HT.changeCartItemSubTotal = (item, res) => {
        item.parents('.cart-item-info').find('.cart-price-total').html(addCommas(res.response.cartItemSubTotal)+'đ')
    }

    HT.changeCartItemSubQuantity = (item, res) => {
        item.parents('.cart-item').find('.cart-item-number').html(res.response.cartItemSubQuantity)
    }

    HT.changeQuantityInput = () => {
        $(document).on('change', '.input-qty', function(){
            let _this = $(this)
            
            if(_this.val() <= 0){
                _this.html(1)
                _this.val(1)
            }

            console.log(_this.val())

            let option = {
                rowId: _this.siblings('.rowId').val(),
                qty: _this.val(),
            }

            // console.log(option.qty)

            if(isNaN(option.qty)){
                toastr.error('Có vấn đề xảy ra khi nhập số lượng! Hãy thử lại.', 'Thông báo từ hệ thống!')
                return false
            }

            HT.handleUpdateCart(_this, option)
        })
    }

    HT.handleUpdateCart = (_this, option) => {
        $.ajax({
            url: 'ajax/cart/update',
            type: 'GET',
            data: option,
            dataType: 'json',
            beforeSend: function (){

            },
            success: function(res){
                console.log(res)
                toastr.clear()
                if(res.code == 200){
                    toastr.success(res.message, 'Thông báo từ hệ thống!')
                    HT.changeCartTotal(res)
                    HT.changeMiniCartQuantity(res)
                    HT.changeCartItemSubTotal(_this, res)
                    HT.changeCartItemSubQuantity(_this, res)
                }else{
                    toastr.error('Có vấn đề xảy ra! Hãy thử lại', 'Thông báo từ hệ thống!')
                }
            },
            error: function(xhr, status, error){
                console.log('Error:', xhr.responseText); // Xem nội dung lỗi
                toastr.error('Có lỗi xảy ra! Hãy thử lại.', 'Thông báo từ hệ thống!');
            }
        })
    }


	$(document).ready(function(){
		HT.addCart()
        HT.changeQuantity()
        HT.changeQuantityInput()
	});

})(jQuery);



