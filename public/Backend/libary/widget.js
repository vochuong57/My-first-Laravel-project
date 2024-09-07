(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // V87 Xây dựng sự kiện tìm kiếm khi nhập vào input:seach-model
    HT.searchModel = () => {
        let typingTimer;
        let doneTypingInterval = 0; //1s
        $(document).on('keyup', '.search-model', function(e){
            // console.log(123)
            e.preventDefault()

            let specialKeys = [9, 16, 17, 18, 27]; // Tab, Shift, Ctrl, Alt, Esc
            // Kiểm tra xem mã phím có thuộc danh sách các phím đặc biệt không
            if (specialKeys.includes(event.which)) {
                return; // Nếu đúng, thoát khỏi hàm mà không thực hiện AJAX
            }

            let _this = $(this)

            if($('input[type=radio][name=model]:checked').length == 0){
                alert('Bạn chưa chọn module!')
                _this.val('')
                return
            }
            if(_this.val().trim() != ''){
                let keyword = _this.val()
                let option = {
                    model: $('input[type=radio][name=model]:checked').val(),
                    keyword: keyword
                }
                // console.log(option)
            
                clearTimeout(typingTimer)
                typingTimer = setTimeout(function(){
                    // console.log(keyword)
                    let arrayModelItemExists = HT.checkModelExists()
                    HT.sendAjax(option, arrayModelItemExists)
                }, doneTypingInterval)
            }else{
                $('.ajax-search-result').html('').hide()
            }
             
        })
        // V88 khi click vào lại input.seach-model thì sẽ load lại dữ liệu đã nhập tìm kiếm
        $(document).on('click', '.search-model', function(e){
            let _this = $(this)
            if(_this.val() != '' && $('input[type=radio][name=model]:checked').length != 0){
                let option = {
                    model: $('input[type=radio][name=model]:checked').val(),
                    keyword: _this.val()
                }
                let arrayModelItemExists = HT.checkModelExists()
                // console.log(arrayModelItemExists)

                HT.sendAjax(option, arrayModelItemExists)
                
            }
        })
    }

    // V87 Xây dừng hàm gửi Ajax khi tìm kiềm ở input.search-model hoặc chọn lại input.input-radio
    HT.sendAjax = (option, arrayModelItemExists) => {
        $.ajax({
            url: 'ajax/dashboard/getModelObject',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                console.log(res)
                
                let Html = HT.renderSearchResult(res, arrayModelItemExists)
                if(Html.length){
                    $('.ajax-search-result').html(Html).show()
                }else{
                    $('.ajax-search-result').html(Html).hide()
                }
            },
            beforeSend: function(){
                $('.ajax-search-result').html('').hide()
            },
            error: function(jqXHR, textStatus, errorThrown){
                
            }
        });
    }

    // V87 Xây dựng hàm hiển thị ra kết quả tìm kiếm được lấy từ DB bằng AJAX gửi đi
    HT.renderSearchResult = (data, arrayModelItemExists) => {
        let html = '';

        if(data.length){
            for(let i = 0; i<data.length; i++){
                html += '<button class="ajax-search-item" data-id="'+data[i].id+'" data-name="'+data[i].languages[0].pivot.name+'" data-canonical="'+data[i].languages[0].pivot.canonical+'" data-image="'+data[i].image+'">';
                html += '    <div class="uk-flex uk-flex-middle uk-flex-space-between">';
                html += '        <span>'+data[i].languages[0].pivot.name+'</span>';
                html += '        <div class="auto-icon">';
                html += (arrayModelItemExists.includes(data[i].languages[0].pivot.canonical)) ? '<i class="fa fa-check"></i>' : '';
                html += '        </div>';
                html += '    </div>';
                html += '</button>';
            }
        }
    
        return html;
    }
    
    // V87 Xây dựng sự kiện chọn selected khi chọn vào từng input.input-radio
    HT.chooseModel = () => {
        $(document).on('change', '.input-radio', function(){
            let keyword = $('.search-model').val()
            if(keyword.trim() != ''){
                let _this = $(this)
                let option = {
                    model: _this.val(),
                    keyword: keyword
                }
                let arrayModelItemExists = HT.checkModelExists()
                $('.search-model-result').html('')
                HT.sendAjax(option, arrayModelItemExists)
            }
        })
    }

    // V88 khi click ra ngoài khỏi input:search-model và div.search-model-result thì ta sẽ làm trống trong vùng div.ajax-search-result
    HT.unfocusSearchBox = () => {
        $(document).on('click', 'html', function(e){
            if(!$(e.target).hasClass('search-model-result') && !$(e.target).hasClass('search-model')){
                $('.ajax-search-result').html('')
            }
        })
        $(document).on('click', '.ajax-search-result', function(e){
            // let _this = $(this)
            // _this.hide()     
            e.stopPropagation()
        })
    }

    // V88 khi click vào button.ajax-search-item này thì sẽ đỗ dữ liệu này ra div.search-model-result
    HT.addModel = () =>{
        $(document).on('click', '.ajax-search-item', function(e){
            e.preventDefault()
            let _this = $(this)
            let data = _this.data()
            // console.log(data)
            let html = HT.modelTemplate(data)
            let arrayModelItemExists = HT.checkModelExists()
            if(arrayModelItemExists.includes(data.canonical)){
                $('.'+data.canonical).remove()
                _this.find('.auto-icon').html('')
            }else{
                $('.search-model-result').append(html)
                _this.find('.auto-icon').append('<i class="fa fa-check"></i>')
            }
          
        })
    }

    // V88 sườn HTML để đỗ dữ liệu cho hàm HT.addModel
    HT.modelTemplate = (data) => {
        let html = '';
        html += '<div class="search-result-item '+data.canonical+'">';
        html += '  <div class="uk-flex uk-flex-middle uk-flex-space-between">';
        html += '    <div class="uk-flex uk-flex-middle">';
        html += '      <span class="image img-cover">';
        html += '        <img src="'+(data.image ? data.image : 'Backend/img/not-found.png')+'" alt="">';
        html += '        <input type="hidden" name="widget[image][]" value="'+(data.image ? data.image : '')+'"></input>';
        html += '        <input type="hidden" name="widget[id][]" value="'+data.id+'"></input>';
        html += '        <input type="hidden" name="widget[name][]" value="'+data.name+'"></input>';
        html += '        <input type="hidden" name="widget[canonical][]" value="'+data.canonical+'"></input>';
        html += '      </span>';
        html += '      <span class="name">'+data.name+'</span>';
        html += '    </div>';
        html += '    <div class="delete">';
        html += '      <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>';
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
        
        return html;
    }

    // V88 kiểm tra các div.search-result-item trong vùng div.search-model-result nào đa được chọn và hiển thị và lưu nó vào thành 1 mảng
    HT.checkModelExists = () => {
        let arrayModelItem = $('.search-result-item').map(function(){//Lấy ra danh sách search-result-item đã được chọn và hiện thị ở .search-model-result
            let allClasses = $(this).attr('class').split(' ').slice(1).join(' ')//tạo một mảng gồm các class của vị trí thứ 2

            return allClasses
        }).get()

        return arrayModelItem//trả về mảng vừa được tạo ra
    }
    
    // V88 Khi click vào div.delete này ta sẽ tìm đúng tới div.search-result-item đó để xóa nó khỏi vùng div.search-model-result
    HT.deleteModel = () => {
        $(document).on('click', '.delete', function(){
            let _this = $(this)
            _this.parents('.search-result-item').remove()
        })
    }

    $(document).ready(function(){
        // V87
        HT.searchModel()
        HT.chooseModel()

        // V88
        HT.unfocusSearchBox()
        HT.addModel()
        HT.deleteModel()
    })

})(jQuery)