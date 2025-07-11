(function($) {
	"use strict";
	var HT = {}; // Khai báo là 1 đối tượng
	var timer;
    var filter = $('.filtering');
    var filterContainer = $('.filter-content');



    HT.priceRange = () => {
        let isInitialized = false;
		$("#price-range").slider({
            step: 50000,
            range: true, 
            min: 0, 
            max: 100000000, 
            values: [0, 100000000], 
            slide: function(event, ui){
                $('.min-value').val(addCommas(ui.values[0]))
                $('.max-value').val(addCommas(ui.values[1]))

            },
            create: function(event, ui) {
                isInitialized = true;
            },
            change: function(event, ui) {
            if (isInitialized) {
                // let filterOption = HT.filterOption();
                HT.sendDataToFilter();
                let queryString = HT.buildQueryStringFromFilterOption();
            }
            }
        });
        $("#priceRange").val($("#price-range")
                        .slider("values", 0) + " - " + $("#price-range")
                        .slider("values", 1));
    }


    HT.filter = () => {
        $(document).on('change', '.filtering', function(){
            HT.sendDataToFilter()
            let queryString = HT.buildQueryStringFromFilterOption();
        })
    }


    HT.sendDataToFilter = () => {
        // console.log(123);
        
        let option = HT.filterOption()
        // console.log(option);
        
        $.ajax({
            url: 'ajax/product/filter', 
            type: 'GET', 
            data: option, 
            dataType: 'json', 
            beforeSend: function() {
                
            },
            success: function(res) {

                let html = res.data
                $('.product-catalogue .product-list').html(html);
                 if(res.pagination != '') {
                    $('.product-catalogue .pannel-body .pagination-ajax').remove();
                    $('.product-catalogue .pannel-body').append(res.pagination);
                }
            },
        });
    }

    // Hàm xử lý khi chuyển trang ở class .pagination a --> dạng ajax sẽ cần lấy được thêm query string để muốn qua trang khác thì ajax này phải có thêm tham số page
    HT.switchPagination = () => {
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault(); // chặn hành vi chuyển trang mặc định

            let pageUrl = $(this).attr('href'); // vd: "http://127.0.0.1:8000/san-pham.html?perpage=5&path=http%3A%2F%2F127.0.0.1%3A8000%2Fsan-pham.html&price%5Bprice_min%5D=0%C4%91&price%5Bprice_max%5D=100.000.000%C4%91&productCatalogueId=17&page=2"
            // console.log(pageUrl);
            // Lấy query string từ URL
            let queryString = pageUrl.split('?')[1]; // vd: "perpage=5&path=http%3A%2F%2F127.0.0.1%3A8000%2Fsan-pham.html&price%5Bprice_min%5D=0%C4%91&price%5Bprice_max%5D=100.000.000%C4%91&productCatalogueId=17&page=2"
            // console.log(queryString);
            
            // let option = HT.filterOption()

            $.ajax({
                url: 'ajax/product/filter?' + queryString,
                type: 'GET',
                //data: option, // không cần gửi lại dữ liệu filterOption vì đã truyền query string
                success: function(res) {
                    let html = res.data
                    // console.log(html);
                    history.pushState(null, '', pageUrl); // cập nhật URL mà không làm mới trang
                    $('.product-catalogue .product-list').html(html);
                    if(res.pagination != '') {
                        $('.product-catalogue .pannel-body .pagination-ajax').remove();
                        $('.product-catalogue .pannel-body').append(res.pagination);
                    }
                },
            });
        });
    }

    // Hàm kiểm tra xem thử là chạy pagination laravel hay ajax filter
    // nếu có tham số price_min và price_max thì sẽ chạy ajax filter, ngược lại sẽ chạy pagination laravel
    HT.checkTypePagination = () => {
        const urlParams = new URLSearchParams(window.location.search);

        // Kiểm tra xem filter giá có tồn tại không
        const hasPriceMin = urlParams.has('price[price_min]');
        const hasPriceMax = urlParams.has('price[price_max]');

        if (hasPriceMin && hasPriceMax) {
            // Xóa phân trang Laravel nếu đang lọc giá bằng AJAX FILTER
            $('.product-catalogue .pannel-body .pagination-laravel').remove();
        }

        // Lấy query string từ URL hiện tại
        const queryString = window.location.search.substring(1); // bỏ dấu ?

        // Gửi AJAX nếu có query
        if (queryString.length > 0) {
            $.ajax({
                url: 'ajax/product/filter?' + queryString,
                type: 'GET',
                success: function(res) {
                    // Cập nhật HTML sản phẩm
                    $('.product-catalogue .product-list').html(res.data);

                    // Cập nhật phân trang nếu có
                    if (res.pagination !== '') {
                        $('.product-catalogue .pannel-body .pagination-ajax').remove();
                        $('.product-catalogue .pannel-body').append(res.pagination);
                    }
                }
            });
        }
    };

    // Hàm tạo query string từ sự kiện change .filtering (HT.filterOption()) để render ra url và từ url mới tạo này có thể giúp cho việc khi truy cập lại url đó sẽ tự động lọc theo các tham số đã chọn
    HT.buildQueryStringFromFilterOption = () => {
        const option = HT.filterOption();
        const queryParts = [];

        // Thêm theo đúng thứ tự: perpage -> sort -> path -> ...
        if (option.perpage) queryParts.push(`perpage=${option.perpage}`);
        if (option.sort) queryParts.push(`sort=${option.sort}`);

        // Đưa path ngay sau sort
        const rawPath = $('.canonical').val() || option.path || window.location.pathname;
        queryParts.push(`path=${rawPath}`);

        // Mảng rate[]
        if (option.rate && option.rate.length > 0) {
            option.rate.forEach(rate => {
                queryParts.push(`rate[]=${rate}`);
            });
        }

        // price[price_min], price[price_max]
        if (option.price) {
            if (option.price.price_min) {
                queryParts.push(`price[price_min]=${option.price.price_min}`);
            }
            if (option.price.price_max) {
                queryParts.push(`price[price_max]=${option.price.price_max}`);
            }
        }

        // attributes[group_id][]=attribute_id
        if (option.attributes) {
            for (const group in option.attributes) {
                option.attributes[group].forEach(value => {
                    queryParts.push(`attributes[${group}][]=${value}`);
                });
            }
        }

        if (option.productCatalogueId) queryParts.push(`productCatalogueId=${option.productCatalogueId}`);

        const queryString = queryParts.join('&');

        // Gắn URL mới vào thanh trình duyệt
        const fullUrl = rawPath + (queryString ? '?' + queryString : '');
        history.pushState(null, '', fullUrl);

        return queryString;
    };


    HT.filterOption = () => {
        var filterOption = {
            perpage: $('select[name=perpage]').val(),
            sort: $('select[name=sort]').val(),
            path: $('.canonical').val(),
            rate: $('input[name="rate[]"]:checked').map(function(){
                return this.value
            }).get(),
            price: {
                price_min : $('.min-value').val(),
                price_max : $('.max-value').val()
            },
            productCatalogueId: $('.product_catalogue_id').val(),
            attributes:  {}
        }

        $('.filterAttribute:checked').each(function(){
            let attributeId = $(this).val()
            let attributeGroup  = $(this).attr('data-group')

            if (!filterOption.attributes.hasOwnProperty(attributeGroup)) {
                filterOption.attributes[attributeGroup] = [];
            }
        
            filterOption.attributes[attributeGroup].push(attributeId);
        })

        return filterOption
    }

    HT.openFilter = () => {
		$('.filter-button .btn-filter').on('click', function(e){
			
			if(filterContainer.hasClass('filter-minimize')){
				filterContainer.removeClass('filter-minimize')
				filterContainer.addClass('filter-open')
			}else {
				filterContainer.removeClass('filter-open')
				filterContainer.addClass('filter-minimize')
			}
			e.preventDefault()
		})
	}

    HT.closeFilter = () => {
		$('.filter-close').on('click', function(){
			filterContainer.removeClass('filter-open')
			filterContainer.addClass('filter-minimize')
		})
	}
    

	$(document).ready(function(){
        HT.checkTypePagination()
        HT.priceRange()
        HT.filter()
        HT.openFilter()
        HT.closeFilter()
        HT.switchPagination()
	});

})(jQuery);

