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
                parent_id: $('.review_parent_id').val(),
                publish: 2,
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
	
    HT.filterReview = () => {
        $(document).on('click', '.filter-item span', function () {
            let _this = $(this)
            // thêm class active cho item đã chọn
            $('.filter-item span').removeClass('active')
            _this.addClass('active')

            // Lấy giá trị lọc từ span (ví dụ: 5 sao, 4 sao...)
            let score = _this.text().trim().match(/\d+/); // ép chuỗi để lấy số nguyên
            score = score ? parseInt(score[0]) : null; // nếu không tìm thấy số, gán là null

            let attribute_id = [];

            // Lấy tất cả attribute_id đang active
            $('.attribute-value .choose-attribute.active').each(function() {
                attribute_id.push($(this).attr('data-attributeid'));
            });

            let option = {
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: $('.reviewable_id').val(),
                reviewable_link: $('.reviewable_link').val(),
                score: score,
            }
            console.log(option);
            
            $.ajax({
                url: 'ajax/review/filter',
                type: 'GET',
                data: option,
                dataType: 'json',
                beforeSend: function () {
                    $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
                },
                success: function (res) {
                    if (res.code === 200) {
                        $('.review-wrapper').html(res.html)
                        HT.setupUrlForFilterOrSwitchPage()
                    } else {
                        toastr.error(res.messages, 'Thông báo từ hệ thống!')
                    }
                }
            });
        });
    }

    HT.paginationForFilterReview = () => {
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();

            let _this = $(this);
            let href = _this.attr('href');
            console.log(href);
            // lấy từ reviewable_type, reviewable_id, reviewable_link, score từ href
            let urlParams = href.split('?')[1];
            console.log(urlParams);
            
            $.ajax({
                url: 'ajax/review/filter?' + urlParams,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
                },
                success: function(res) {
                    console.log(res);
                    if (res.code === 200) {
                        $('.review-wrapper').html(res.html);
                        HT.setupUrlForFilterOrSwitchPage(res.current_page); // Cập nhật URL với trang hiện tại
                    } else {
                        toastr.error(res.messages, 'Thông báo từ hệ thống!');
                    }
                },
            })
            
        })
    }

    HT.setupUrlForFilterOrSwitchPage = (page) => {
        let attribute_id = [];
        // Lấy tất cả attribute_id đang active
        $('.attribute-value .choose-attribute.active').each(function() {
            attribute_id.push($(this).attr('data-attributeid'));
        });

        let score = $('.filter-item span.active').text().trim().match(/\d+/);
        score = score ? parseInt(score[0]) : null; // nếu không tìm thấy số, gán là null

        if (!page) {
            page = 1; // Mặc định là trang 1 nếu không có tham số
        }
        let queryString = '?attribute_id=' + attribute_id.join(',') + '&score=' + score + '&page=' + page;
        let productCanonical = $('.productCanonical').val();
        productCanonical = productCanonical + queryString;

        let stateObject = { attribute_id: attribute_id };
        history.pushState(stateObject, "Page Title", productCanonical);
    }

    HT.loadFilterReview = () => {        
        let urlParams = new URLSearchParams(window.location.search);        
        let score = urlParams.get('score');
        let page = urlParams.get('page');
        console.log('score:', score);
        
        if(score == null){
            score = 'null'; // Nếu không có score, gán là 'null'
        }
        if (score != 'null') {
            // Tự động chọn span tương ứng với số sao
            $('.filter-item span').each(function() {
                if ($(this).text().trim().includes(score)) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        } else {
            // Nếu không có score, active span đầu tiên
            $('.filter-item span').removeClass('active');
            $('.filter-item span').first().addClass('active');
        }


        $.ajax({
            url: 'ajax/review/filter',
            type: 'GET',
            data: {
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: $('.reviewable_id').val(),
                reviewable_link: $('.reviewable_link').val(),
                score: score,
                page: page || 1, // Nếu không có page thì mặc định là 1
            },
            dataType: 'json',
            beforeSend: function() {
                $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
            },
            success: function(res) {
                if (res.code === 200) {
                    $('.review-wrapper').html(res.html);
                    HT.setupUrlForFilterOrSwitchPage(page); // Cập nhật URL với trang hiện tại
                } else {
                    toastr.error(res.messages, 'Thông báo từ hệ thống!');
                }
            },
        })
    }

	$(document).ready(function(){
		HT.review()
        HT.filterReview()
        HT.paginationForFilterReview()
        HT.loadFilterReview()
	});

})(jQuery);

