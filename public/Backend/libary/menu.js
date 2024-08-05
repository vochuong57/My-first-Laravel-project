(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // V62 Tiến hành tạo chức năng "chọn vị trí hiển thị" menu mỗi lần submit form
    HT.createMenuCatalogue = () =>{
        $(document).on('submit', '.create-menu-catalogue', function(e){
            e.preventDefault()
            // console.log(123)
            let _form = $(this)
            let option = {
                'name': _form.find('input[name=name]').val(),
                'keyword': _form.find('input[name=keyword]').val(),
                '_token': _token
            }
            
            $.ajax({
                url: 'ajax/menu/createCatalogue',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    if(res.code == 0){
                        $('.form-error').removeClass('error hidden').addClass('success').html(res.message)
                        const menuCatalogueSelect = $('select[name="menu_catalogue_id"]')
                        menuCatalogueSelect.append('<option value="'+res.data.id+'">'+res.data.name+'</option>')
                    }else{
                        $('.form-error').removeClass('success hidden').addClass('error').html(res.message)
                    }
                },
                beforeSend: function(){
                    _form.find('.error').html('')
                    _form.find('.form-error').hide('')
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if(jqXHR.status === 422){
                        let errors = jqXHR.responseJSON.errors
                        // console.log(errors)

                        for(let field in errors){
                            let errorMessage = errors[field]
                            // console.log(errorMessage)
                            errorMessage.forEach(function(message){
                                $('.'+field).html(message)
                            })
                        }
                    }
                }
            });
        })
    }
    // V63 Xây sự kiện cho nút thêm đường dẫn bên trái (sẽ những thêm div.menu-item bên phải bên trong div.menu-wrapper)
    HT.createMenuRow = () =>{
        $(document).on('click', '.add-menu', function(e){
            e.preventDefault()
            let _this= $(this)
            $('.menu-wrapper').append(HT.menuRowHtml()).find('.notification').hide()

        })
    }

    // V63 Xử dụng để Render ra giao diện cho sự kiện click vào thẻ a.add-menu và cho từng cái input.checkbox ở .m-item
    HT.menuRowHtml = (option) =>{
        let html = '';
        let row = $('<div>').addClass('row mb10 menu-item '+ ((typeof(option) != 'undefined') ? option.canonical : '') +'')
        const colums = [
            { class: 'col-lg-4', name: 'menu[name][]', value: (typeof(option) != 'undefined') ? option.name : '' },
            { class: 'col-lg-4', name: 'menu[canonical][]', value: (typeof(option) != 'undefined') ? option.canonical : '' },
            { class: 'col-lg-2', name: 'menu[order][]', value: 0 },
        ]
        colums.forEach($col => {
            let col = $('<div>').addClass($col.class)
            let input = $('<input>')
            .attr('type', 'text')
            .attr('value', $col.value)
            .addClass('form-control '+ (($col.name == 'menu[order][]') ? 'int text-right' : ''))
            .attr('name', $col.name)

            col.append(input)
            row.append(col)
        })

        let removeCol = $('<div>').addClass('col-lg-2')
        let a = $('<a>').addClass('delete-menu img-scaledown').attr('style', 'width: 15%; height: 30px; margin-left: 6px;');
        let img = $('<img>').attr('src', 'Backend/img/close.png')
        a.append(img)
        removeCol.append(a)
        row.append(removeCol)

        return row

        // <div class="row mb-10 menu-item">
        //     <div class="col-lg-4">
        //         <input type="text" class="form-control" name="menu[name][]">
        //     </div>
        //     <div class="col-lg-4">
        //         <input type="text" class="form-control" name="menu[canonical][]">
        //     </div>
        //     <div class="col-lg-2">
        //         <input type="text" class="form-control" name="menu[order][]">
        //     </div>
        //     <div class="col-lg-2">
        //         <a href="" class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px"><img src="Backend/img/close.png" alt=""></a>
        //     </div>
        // </div>
    }

    // V63 Xây dựng xự kiện cho nút xóa bên phải a.delete-menu và kiểm tra trạng thái input.checkbox và trạng thái menu-item
    HT.deleteMenuRow = () => {
        $(document).on('click', '.delete-menu', function(){
            let _this = $(this)

            // kiểm tra trạng thái trực tiếp input.checkbox đó nếu có hiển thị
            let element =  _this.parents('.menu-item')
            if (element.length > 0) {
                // Lấy danh sách các class của phần tử
                let classList = element.attr('class').split(/\s+/);
                // Lấy class thứ 4 (nếu có)
                if (classList.length >= 3) {
                    let fourthClass = classList[3];
                    // console.log(fourthClass); // In ra để kiểm tra
                    let checkbox = _this.parents('.wrapper-content').find('#'+fourthClass)
                    // console.log(checkbox)
                    checkbox.prop('checked', false);
                }
            }
            _this.parents('.menu-item').remove()

            // kiểm tra trạng thái menu-item
            HT.checkMenuItemLength()
        })
    }

    // V63 kiểm tra trạng thái menu-item mỗi lần chạy sự kiện nút xóa hoặc thay đổi input.checkbox ở .m-item
    HT.checkMenuItemLength=()=>{
        if($('.menu-item').length == 0){
            $('.notification').show()
        }
    }

    // V63 Xây dựng chức năng lấy dữ liệu đổ ra giao diện danh sách của từng model khi click vào a.menu-model đồng thời kiểm tra tình trạng input:checkbox ở .m-item
    HT.getMenu = () => {
        $(document).on('click', '.menu-module', function(){
            let _this = $(this)
            let option = {
                model: _this.attr('data-model')
            }

            $.ajax({
                url: 'ajax/dashboard/getMenu',
                type: 'GET',
                data: option,
                dataType: 'json',
                success: function(res){
                    // console.log(res)
                    let html = ''
                    let canonical=[]
                    for(let i = 0; i< res.data.length; i++){
                        html += HT.renderModelMenu(res.data[i])

                        canonical.push(res.data[i].canonical);//Lây ra danh sách canonical của từng menu-module để tiến hành kiểm tra checked của bên trái
                    }
                    _this.parents('.panel-default').find('.menu-list').html(html)//Đổ dữ liệu và giao diện vừa được gửi ajax vào trong vùng menu-list

                   
                    canonical.forEach(value => {
                        let element = _this.parents('.wrapper-content').find('.' + value);//Tìm bên phải trước
                        // console.log(element); // In ra để kiểm tra
                        if(element.length > 0){//Nếu như bên phải có danh sách thì mới tìm bên trái
                            let checkbox = _this.parents('.panel-default').find('#'+value)// tiến hành tìm bên trái
                            // console.log(checkbox)
                            //Nếu bên trái có danh sách giống bên phải theo cái value đó thì tiến hành checked những cái input:checkbox này
                            checkbox.prop('checked', true);
                        }
                    });
                },
                beforeSend: function(){
                    _this.parents('.panel-default').find('.menu-list').html('')
                },
                error: function(jqXHR, textStatus, errorThrown){
                    
                }
            });
        })
    }

    // V63 Tạo chức năng khi click vào từng thẻ a.menu-module tương ứng thì đổ dữ liệu và giao diện vừa được gửi ajax vào trong vùng menu-list
    HT.renderModelMenu = (object) => {
        let html = '';
        html += '<div class="m-item mb10">';
        html += '    <div class="uk-flex uk-flex-middle">';
        html += '        <input type="checkbox" name="" class="m0 choose-menu" value="'+object.canonical+'" id="'+object.canonical+'">';
        html += '        <label for="'+object.canonical+'">'+object.name+'</label>';
        html += '    </div>';
        html += '</div>';
        return html;
    }

    // V63 Tạo chức năng khi click vào từng phần tử input:checkbox trong danh sách của từng model sẽ đổ dữ liệu đó qua bên phải div.menu-wrapper
    HT.chooseMenu = () => {
        $(document).on('click', '.choose-menu', function(){
            let _this = $(this)
            let canonical = _this.val()
            let name = _this.siblings('label').text()
            let row = HT.menuRowHtml({
                name: name,
                canonical: canonical
            })
            let isChecked = _this.prop('checked')
            if(isChecked == true){
                $('.menu-wrapper').append(row).find('.notification').hide()
            }else{
                $('.menu-wrapper').find('.'+canonical).remove()
                HT.checkMenuItemLength()
            }
        })
    }

    // V63 Dùng trong form product/aside để chuyển chuỗi string về dạng thành tiền 1.234.567
    HT.int = () => {
        $(document).on('change keyup blur', '.int', function() {
            let _this = $(this);
            let value = _this.val();
            if (value === '') {
                _this.val('0');
            } else {
                value = value.replace(/\./g, '');
                if (isNaN(value)) {
                    _this.val('0');
                } else {
                    _this.val(HT.addCommas(value));
                }
            }
        });

        $(document).on('keydown', '.int', function(e) {
            let _this = $(this);
            let data = _this.val();
            if (data == '0') {
                let unicode = e.keyCode || e.which;
                // Check for numbers on both the main keyboard and the numpad
                if (unicode != 190 && ((unicode >= 48 && unicode <= 57) || (unicode >= 96 && unicode <= 105))) {
                    _this.val('');
                }
            }
        });
    };

    HT.addCommas = (nStr) => {
        nStr = String(nStr);
        nStr = nStr.replace(/\./g, '');
        let str = '';
        for (let i = nStr.length; i > 0; i -= 3) {
            let a = (i - 3) < 0 ? 0 : (i - 3);
            str = nStr.slice(a, i) + '.' + str;
        }
        str = str.slice(0, str.length - 1);
        return str;
    };

    $(document).ready(function(){
        // V62 Tiến hành tạo chức năng xây dựng vị trí hiển thị menu mỗi lần submit form
        HT.createMenuCatalogue()

        // V63 Xây sự kiện cho nút thêm đường dẫn bên trái (sẽ những thêm div.menu-item bên phải bên trong div.menu-wrapper)
        HT.createMenuRow()

        // V63 Xây dựng xự kiện cho nút xóa bên phải a.delete-menu và kiểm tra trạng thái input.checkbox và trạng thái menu-item
        HT.deleteMenuRow()

        // V63 Xây dựng chức năng lấy dữ liệu đổ ra giao diện danh sách của từng model khi click vào a.menu-model đồng thời kiểm tra tình trạng input:checkbox ở .m-item
        HT.getMenu()

        // V63 Tạo chức năng khi click vào từng phần tử input:checkbox trong danh sách của từng model sẽ đổ dữ liệu đó qua bên phải div.menu-wrapper
        HT.chooseMenu()
        
        // V63 Dùng trong form product/aside để chuyển chuỗi string về dạng thành tiền 1.234.567
        HT.int()
    })

})(jQuery)