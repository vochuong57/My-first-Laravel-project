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
                    HT.sendAjax(option)
                }, doneTypingInterval)
            }else{
                $('.ajax-search-result').html('').hide()
            }
             
        })
    }

    // V87 Xây dừng hàm gửi Ajax khi tìm kiềm ở input:search-model hoặc chọn lại input:input-radio
    HT.sendAjax = (option) => {
        $.ajax({
            url: 'ajax/dashboard/getModelObject',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                console.log(res)
                
                let Html = HT.renderSearchResult(res)
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
    HT.renderSearchResult = (data) => {
        let html = '';

        if(data.length){
            for(let i = 0; i<data.length; i++){
                html += '<button class="ajax-search-item">';
                html += '    <div class="uk-flex uk-flex-middle uk-flex-space-between">';
                html += '        <span>'+data[i].languages[0].pivot.name+'</span>';
                html += '        <div class="auto-icon">';
                html += '            <i class="fa fa-check"></i>';
                html += '        </div>';
                html += '    </div>';
                html += '</button>';
            }
        }
    
        return html;
    }
    
    // V87 Xây dựng sự kiện chọn selected khi chọn vào từng input:input-radio
    HT.chooseModel = () => {
        $(document).on('change', '.input-radio', function(){
            let keyword = $('.search-model').val()
            if(keyword.trim() != ''){
                let _this = $(this)
                let option = {
                    model: _this.val(),
                    keyword: keyword
                }
                HT.sendAjax(option)
            }
        })
    }

    $(document).ready(function(){
        HT.searchModel()
        HT.chooseModel()
    })

})(jQuery)