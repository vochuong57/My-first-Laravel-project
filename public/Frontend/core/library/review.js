(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng
	var timer;
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.review = () => {

        var modal = UIkit.modal("#review");

        $(document).on('click', '.btn-send-review', function(){
            let option = {
                score: $('.rate input[name="rate"]:checked').val(),
                description : $('.review-textarea').val(),
                gender: $('.gender:checked').val(),
                fullname: $('.product-review input[name=fullname]').val(),
                email: $('.product-review input[name=email]').val(),
                phone: $('.product-review input[name=phone]').val(),
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: $('.reviewable_id').val(),
                _token: _token,
                parent_id: $('.review_parent_id').val()
            }
            // console.log(option);

            let productReview = $(this).closest('.product-review');

            $.ajax({
				url: 'ajax/review/create', 
				type: 'POST', 
				data: option, 
				dataType: 'json', 
				beforeSend: function(){
                    productReview.find('.error').html('')
                    productReview.find('.score-error').html('')
                },
				success: function(res) {
					if(res.code === 200){
                        toastr.success(res.messages, 'Thông báo từ hệ thống!')
                        modal.hide()
                        location.reload()

                     }else{
                        toastr.error(res.messages, 'Thông báo từ hệ thống!')
                     }
				},
                error: function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 422){
                        let errors = jqXHR.responseJSON.errors
                        console.log(errors)

                        for(let field in errors){
                            let errorMessage = errors[field]
                            // console.log(errorMessage)
                            errorMessage.forEach(function(message){
                                $('.'+field+'-error').html(message)
                            })
                        }
                    }
                }
			});
        })
    }
	

	$(document).ready(function(){
		HT.review()
	});

})(jQuery);

