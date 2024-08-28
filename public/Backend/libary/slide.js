(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // V76 Sự kiện khi click vào nút thêm slide '.addSlide'
    HT.addSlide = (type) => {
        // console.log(123)
        $(document).on('click', '.addSlide', function(e){
            e.preventDefault()
            if(typeof(type)=='undefined'){
                type='Images';
            }
            var finder = new CKFinder();
            finder.resourceType = type;
            finder.selectActionFunction = function(fileUrl, data, allFiles){
                // object.val(fileUrl);
                let html = ''
                for(var i = 0; i<allFiles.length;i++){
                    let image = allFiles[i].url
                    html += HT.renderSlideItemHtml(image)
                }

                $('.slide-list').append(html)
                HT.checkSlideNotification()
            }
            finder.popup();
        })
    }

    // V80 Sự kiện khi click vào '.change-slide'
    HT.changeImage = (type) => {
        // console.log(123)
        $(document).on('click', '.change-image', function(e){
            e.preventDefault()
            let _this = $(this)
            if(typeof(type)=='undefined'){
                type='Images';
            }
            var finder = new CKFinder();
            finder.resourceType = type;
            finder.selectActionFunction = function(fileUrl, data){
                // object.val(fileUrl);
                _this.parents('.slide-image').find('img').attr('src', fileUrl)

                // Cập nhật giá trị của input hidden tương ứng
                _this.parents('.slide-image').find('input[type="hidden"]').val(fileUrl);
            }
            finder.popup();
        })
    }

    // V76 xây dựng hàm render ra nội dung cho từng slide-item mỗi lần click vào '.addSlide'
    HT.renderSlideItemHtml = (image) => {
        let tab_1 = "tab_" + counter
        let tab_2 = "tab_" + (counter + 1)
    
        let html = '';
        
        html += '<div class="col-lg-12 ui-state-default">';
        html += '    <div class="slide-item">';
        html += '        <div class="row custom-row">';
        html += '            <div class="col-lg-3">';
        html += '                <span class="slide-image img-cover">';
        html += '                    <img src="'+image+'" alt="">';
        html += '                    <input type="hidden" name="slide[image][]" value="'+image+'">';
        html += '                    <span class="delete-slide"><i class="fa fa-trash btn btn-danger"></i></span>';
        html += '                    <span class="change-image"><i class="fa fa-pencil-square-o btn btn-warning"></i></span>';
        html += '                </span>';
        html += '            </div>';
        html += '            <div class="col-lg-9">';
        html += '                <div class="tabs-container">';
        html += '                    <ul class="nav nav-tabs">';
        html += '                        <li class="active"><a data-toggle="tab" href="#'+tab_1+'"> Thông tin chung</a></li>';
        html += '                        <li class=""><a data-toggle="tab" href="#'+tab_2+'">SEO</a></li>';
        html += '                    </ul>';
        html += '                    <div class="tab-content">';
        html += '                        <div id="'+tab_1+'" class="tab-pane active">';
        html += '                            <div class="panel-body">';
        html += '                                <div class="label-text mb5">Mô tả:</div>';
        html += '                                <div class="form-row mb10">';
        html += '                                    <textarea name="slide[description][]" class="form-control"></textarea>';
        html += '                                </div>';
        html += '                                <div class="form-row form-row-canonical">';
        html += '                                    <input type="text" name="slide[canonical][]" class="form-control" placeholder="URL">';
        html += '                                    <div class="overlay">';
        html += '                                        <div class="uk-flex uk-flex-middle">';
        html += '                                            <label for="input_'+tab_1+'">Mở trong tab mới</label>';
        html += '                                            <input type="checkbox" name="slide[window][]" value="_blank" id="input_'+tab_1+'">';
        html += '                                        </div>';
        html += '                                    </div>';
        html += '                                </div>';
        html += '                            </div>';
        html += '                        </div>';
        html += '                        <div id="'+tab_2+'" class="tab-pane">';
        html += '                            <div class="panel-body">';
        html += '                                <div class="label-text mb5">Tiêu đề ảnh:</div>';
        html += '                                <div class="form-row form-row-canonical slide-seo-tab">';
        html += '                                    <input type="text" name="slide[name][]" class="form-control" placeholder="Tiêu đề ảnh">';
        html += '                                </div>';
        html += '                                <div class="label-text mt12">Mô tả ảnh:</div>';
        html += '                                <div class="form-row form-row-canonical slide-seo-tab">';
        html += '                                    <input type="text" name="slide[alt][]" class="form-control" placeholder="Mô tả ảnh">';
        html += '                                </div>';
        html += '                            </div>';
        html += '                        </div>';
        html += '                    </div>';
        html += '                </div>';
        html += '            </div>';
        html += '        </div>';
        html += '        <hr />';
        html += '    </div>';
        html += '</div>';
    
        counter += 2;
    
        return html;
    }
    
    
    // V76 xây dựng hàm kiểm tra mõi lần thêm slide '.addSlide' hoặc xóa slide '.delete-slide'
    HT.checkSlideNotification = () => {
        let slideItem = $('.slide-item')
        if(slideItem.length){
            $('.slide-notification').hide()
        }else{
            $('.slide-notification').show()
        }
    }

    // V76 xây dựng hàm xóa slide-item khi click vào span.delete-slide
    HT.deleteSlide = () =>{
        $(document).on('click', '.delete-slide', function(){
            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            HT.checkSlideNotification()
        })
    }

    // V77
    HT.checkValueSileWindow = () =>{
        $('form').on('submit', function() {
            // Duyệt qua tất cả các checkbox với name là slide[window][]
            $('input[type="checkbox"][name="slide[window][]"]').each(function() {
                if (!$(this).is(':checked')) {
                    // Nếu checkbox không được chọn, tạo một input hidden với cùng name và giá trị no
                    $(this).after('<input type="hidden" name="slide[window][]" value="no">');
                }
            });
            // V81
            $('input[type="checkbox"][name="translate[window][]"]').each(function() {
                if (!$(this).is(':checked')) {
                    // Nếu checkbox không được chọn, tạo một input hidden với cùng name và giá trị no
                    $(this).after('<input type="hidden" name="translate[window][]" value="no">');
                }
            });
        });
    }

    // V78
    HT.checkValueSetting = () =>{
        $('form').on('submit', function() {
            // Duyệt qua tất cả các checkbox với name là slide[arrow][]
            $('input[type="checkbox"][name="setting[arrow]"]').each(function() {
                if (!$(this).is(':checked')) {
                    // Nếu checkbox không được chọn, tạo một input hidden với cùng name và giá trị no
                    $(this).after('<input type="hidden" name="setting[arrow]" value="no">');
                }
            });
            $('input[type="checkbox"][name="setting[autoplay]"]').each(function() {
                if (!$(this).is(':checked')) {
                    $(this).after('<input type="hidden" name="setting[autoplay]" value="no">');
                }
            });
            $('input[type="checkbox"][name="setting[pauseHover]"]').each(function() {
                if (!$(this).is(':checked')) {
                    $(this).after('<input type="hidden" name="setting[pauseHover]" value="no">');
                }
            });
        });
    }

    // V79 kéo thả danh sách ảnh ở slide/table.blade.php lưu vào DB real-time 
    HT.updatePositionItemInAlbum = () => {
        $(document).on('sortupdate', '.list-image-table', function() {
            // Lấy phần tử hiện tại được kéo thả
            let _this = $(this);

            // Lấy các thuộc tính cần thiết
            let slideId = _this.attr('data-slideId');
            let languageSessionId = _this.attr('data-languageSessionId');

            // Tạo mảng để lưu trữ dữ liệu hình ảnh đã được sắp xếp
            let sortedItems = [];

            // Duyệt qua từng phần tử hình ảnh trong danh sách đã sắp xếp
            _this.find('.img-list').each(function() {
                let $imgItem = $(this);
                let itemData = {
                    image: $imgItem.find('input[type="hidden"]').data('image'),
                    description: $imgItem.find('input[type="hidden"]').data('description'),
                    window: $imgItem.find('input[type="hidden"]').data('window'),
                    canonical: $imgItem.find('input[type="hidden"]').data('canonical'),
                    name: $imgItem.find('input[type="hidden"]').data('name'),
                    alt: $imgItem.find('input[type="hidden"]').data('alt')
                };
                sortedItems.push(itemData);
            });

            // Tạo đối tượng dữ liệu để gửi qua AJAX
            let requestData = {
                slideId: slideId,
                languageSessionId: languageSessionId,
                items: sortedItems,
                _token: _token
            };

            console.log(sortedItems)
    
            //Nếu cần gửi AJAX
            $.ajax({
                url: 'ajax/slide/drag',
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    
                },
                // error: function(jqXHR, textStatus, errorThrown){
                //     console.log('Lỗi: '+jqXHR);
                //     console.log('Lỗi request: '+ textStatus);
                //     console.log('Lỗi nội dung: '+ errorThrown);
                // }
            });
        });
    }

    $(document).ready(function(){
        HT.addSlide()
        HT.changeImage()
        HT.deleteSlide()
        HT.checkValueSileWindow()
        HT.checkValueSetting()
        HT.updatePositionItemInAlbum()
    })

})(jQuery)