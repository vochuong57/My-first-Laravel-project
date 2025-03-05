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
				}
			})
        })
    }

	$(document).ready(function(){
		HT.addCart()
	});

})(jQuery);



addCommas = (nStr) => { 
    nStr = String(nStr);
    nStr = nStr.replace(/\./gi, "");
    let str ='';
    for (let i = nStr.length; i > 0; i -= 3){
        let a = ( (i-3) < 0 ) ? 0 : (i-3);
        str= nStr.slice(a,i) + '.' + str;
    }
    str= str.slice(0,str.length-1);
    return str;
}