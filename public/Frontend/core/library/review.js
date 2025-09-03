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
	
    // V192
    // Hàm này sẽ được gọi khi người dùng click vào các span để lọc đánh giá
    // Nó sẽ lấy giá trị số sao từ span đã chọn, sau đó gửi ajax để
    // lọc các đánh giá theo số sao đó và các thuộc tính đã chọn
    // Nó cũng sẽ cập nhật URL với các tham số attribute_id, score (HT.setupUrlForFilterOrSwitchPage)
    // để có thể sử dụng lại khi tải lại trang với url chuẩn khi đã lọc
    // trước đó nếu có
    HT.filterReview = () => {
        $(document).on('click', '.filter-item span', function () {
            let _this = $(this)
            // thêm class active cho item đã chọn
            $('.filter-item span').removeClass('active')
            _this.addClass('active')

            // Lấy giá trị lọc từ span (ví dụ: 5 sao, 4 sao...)
            let score = _this.text().trim().match(/\d+/); // ép chuỗi để lấy số nguyên
            score = score ? parseInt(score[0]) : null; // nếu không tìm thấy số, gán là null

            // lấy ra thuộc tính sort và perpage từ các select
            let sort = $('.sort-select').val() || 'id:desc'; // Lấy giá trị sắp xếp, mặc định là 'id:desc'
            let perpage = $('.perpage-select').val() || '5'; // Lấy giá trị số lượng hiển thị mỗi trang, mặc định là 5

            let option = {
                reviewable_type: $('.reviewable_type').val(),
                reviewable_id: $('.reviewable_id').val(),
                reviewable_link: $('.reviewable_link').val(),
                score: score,
                sort: sort,
                perpage: perpage,
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

    // V192
    // Hàm này sẽ được gọi khi người dùng click vào các trang phân trang
    // Nó sẽ lấy các tham số từ href của trang hiện tại và gửi ajax để tải
    // các đánh giá tương ứng với các tham số đã lọc trước đó
    // Các tham số này bao gồm reviewable_type, reviewable_id, reviewable_link
    // và score (nếu có)
    // Nó cũng sẽ cập nhật URL với các tham số attribute_id, score và page (HT.setupUrlForFilterOrSwitchPage)
    // để có thể sử dụng lại khi tải lại trang với url chuẩn khi đã lọc
    // trước đó nếu có
    // Nếu không có các tham số này, nó sẽ sử dụng các giá trị mặc định
    // reviewable_type, reviewable_id, reviewable_link từ các input ẩn
    // và score là null (không lọc theo số sao)
    // Nếu không có page thì mặc định là 1
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

    // V192, V193
    // Hàm này được gọi khi người dùng chọn các thuộc tính để lọc hoặc chuyển trang hoặc nếu lần đầu tiên truy cập trang với url gốc thì sẽ render thêm các thuộc tính url lọc ban đầu
    // Nó sẽ cập nhật URL với các tham số attribute_id, score, page, sort và perpage
    // để có thể sử dụng lại khi tải lại trang với url chuẩn khi đã lọc trước đó nếu có
    HT.setupUrlForFilterOrSwitchPage = (page, sort, perpage) => {
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

        if (!sort) {
            sort = $('.sort-select').val() || 'id:desc'; // Lấy giá trị sắp xếp, mặc định là 'id:desc'
        }

        if (!perpage) {
            perpage = $('.perpage-select').val() || '5'; // Lấy giá trị số lượng hiển thị mỗi trang, mặc định là 5
        }

        let queryString = '?attribute_id=' + attribute_id.join(',') + '&score=' + score + '&page=' + page + '&sort=' + sort + '&perpage=' + perpage;
        let productCanonical = $('.productCanonical').val();
        productCanonical = productCanonical + queryString;

        let stateObject = { attribute_id: attribute_id };
        history.pushState(stateObject, "Page Title", productCanonical);
    }

    // V192, V193
    // Hàm này sẽ được gọi khi trang được tải lại. dựa trên URL hiện tại nó lấy ra các tham số
    // score và page để tự động chọn các span tương ứng với số sao đã lọc
    // và chạy lại ajax để tải lại các đánh giá tương ứng
    HT.loadFilterReview = () => {        
        let urlParams = new URLSearchParams(window.location.search);        
        let score = urlParams.get('score');
        let page = urlParams.get('page');
        let sort = urlParams.get('sort');
        let perpage = urlParams.get('perpage');
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
        console.log('sort:', sort);
        
        if (sort) {
            $('.sort-select').val(sort); // chọn lại giá trị sort từ URL vào select
        }

        if (perpage) {
            $('.perpage-select').val(perpage); // chọn lại giá trị perpage từ URL vào select
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
                sort: sort || 'id:desc', // Nếu không có sort thì mặc định là 'id:desc'
                perpage: perpage || '5', // Nếu không có perpage thì mặc định là '5'
            },
            dataType: 'json',
            beforeSend: function() {
                $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
            },
            success: function(res) {
                if (res.code === 200) {
                    $('.review-wrapper').html(res.html);
                    HT.setupUrlForFilterOrSwitchPage(page, sort); // Cập nhật URL với trang hiện tại
                } else {
                    toastr.error(res.messages, 'Thông báo từ hệ thống!');
                }
            },
        })
    }

    HT.sortReview = () => {
        $(document).on('change', '.sort-select', function() {
            HT.setupUrlForFilterOrSwitchPage();
            let _this = $(this);
            let sort = _this.val();
            let score = $('.filter-item span.active').text().trim().match(/\d+/);
            score = score ? parseInt(score[0]) : null; // nếu không tìm thấy số sao, gán là null
            let perpage = $('.perpage-select').val() || '5'; // Lấy giá trị số lượng hiển thị mỗi trang, mặc định là 5
            $.ajax({
                url: 'ajax/review/filter',
                type: 'GET',
                data: {
                    reviewable_type: $('.reviewable_type').val(),
                    reviewable_id: $('.reviewable_id').val(),
                    reviewable_link: $('.reviewable_link').val(),
                    score: score,
                    perpage: perpage,
                    sort: sort,
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
                },
                success: function(res) {
                    if (res.code === 200) {
                        $('.review-wrapper').html(res.html);
                        HT.setupUrlForFilterOrSwitchPage(); // Cập nhật URL với các tham số đã lọc
                    } else {
                        toastr.error(res.messages, 'Thông báo từ hệ thống!');
                    }
                }
            });
        });
    }
    
    HT.perpageReview = () => {
        $(document).on('change', '.perpage-select', function() {
            HT.setupUrlForFilterOrSwitchPage();
            let _this = $(this);
            let perpage = _this.val();
            let score = $('.filter-item span.active').text().trim().match(/\d+/);
            score = score ? parseInt(score[0]) : null; // nếu không tìm thấy số sao, gán là null
            let sort = $('.sort-select').val() || 'id:desc'; // Lấy giá trị sắp xếp, mặc định là 'id:desc'
            $.ajax({
                url: 'ajax/review/filter',
                type: 'GET',
                data: {
                    reviewable_type: $('.reviewable_type').val(),
                    reviewable_id: $('.reviewable_id').val(),
                    reviewable_link: $('.reviewable_link').val(),
                    score: score,
                    sort: sort,
                    perpage: perpage,
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.review-wrapper').html('<div class="uk-text-center"><i class="uk-icon-spinner uk-icon-spin"></i> Đang tải...</div>');
                },
                success: function(res) {
                    if (res.code === 200) {
                        $('.review-wrapper').html(res.html);
                        HT.setupUrlForFilterOrSwitchPage(); // Cập nhật URL với các tham số đã lọc
                    } else {
                        toastr.error(res.messages, 'Thông báo từ hệ thống!');
                    }
                }
            });
        });
    }

	$(document).ready(function(){
		HT.review()
        HT.filterReview()
        HT.paginationForFilterReview()
        HT.loadFilterReview()
        HT.sortReview()
        HT.perpageReview()
	});

})(jQuery);

