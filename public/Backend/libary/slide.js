(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')
    var counter = 1

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
        html += '                    <input type="hidden" name="slide[title][]" value="'+image+'">';
        html += '                    <span class="delete-slide"><i class="fa fa-trash btn btn-danger"></i></span>';
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
        html += '                                <div class="form-row form-row-url">';
        html += '                                    <input type="text" name="slide[url][]" class="form-control" placeholder="URL">';
        html += '                                    <div class="overlay">';
        html += '                                        <div class="uk-flex uk-flex-middle">';
        html += '                                            <label for="input_'+tab_1+'">Mở trong tab mới</label>';
        html += '                                            <input type="checkbox" name="slide[windown][]" value="_blank" id="input_'+tab_1+'">';
        html += '                                        </div>';
        html += '                                    </div>';
        html += '                                </div>';
        html += '                            </div>';
        html += '                        </div>';
        html += '                        <div id="'+tab_2+'" class="tab-pane">';
        html += '                            <div class="panel-body">';
        html += '                                <div class="label-text mb5">Tiêu đề ảnh:</div>';
        html += '                                <div class="form-row form-row-url slide-seo-tab">';
        html += '                                    <input type="text" name="slide[name][]" class="form-control" placeholder="Tiêu đề ảnh">';
        html += '                                </div>';
        html += '                                <div class="label-text mt12">Mô tả ảnh:</div>';
        html += '                                <div class="form-row form-row-url slide-seo-tab">';
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

        counter += 2

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

    $(document).ready(function(){
        HT.addSlide()
        HT.deleteSlide()
    })

})(jQuery)