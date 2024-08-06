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
    
    // ---------------------------V63 Xây dựng hành động thêm, xóa menu, gửi AJAX lấy ra danh sách m-item theo từng menu-module, 
    //----------------------------khi click vào m-item bên trái đổ dữ liệu qua bên menu-wrapper bên phải--------------------------------

    // V63 Xây sự kiện cho nút thêm đường dẫn bên trái a.add-menu (sẽ những thêm div.menu-item bên phải bên trong div.menu-wrapper)
    HT.createMenuRow = () =>{
        $(document).on('click', '.add-menu', function(e){
            e.preventDefault()
            let _this= $(this)
            $('.menu-wrapper').append(HT.menuRowHtml()).find('.notification').hide()

        })
    }

    // V63 Tạo function dùng để Render ra giao diện cho sự kiện click vào thẻ a.add-menu và cho từng cái input.checkbox ở .m-item
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

    // V63 Xây dựng xự kiện cho nút xóa bên phải a.delete-menu và kiểm tra trạng thái input.checkbox và độ dài menu-item
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

            // kiểm tra độ dài menu-item
            HT.checkMenuItemLength()
        })
    }

    // V63 kiểm tra độ dài menu-item mỗi lần chạy sự kiện nút xóa hoặc thay đổi input.checkbox ở .m-item
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
            let target = _this.parents('.panel-default').find('.menu-list')

            let arrayMenuItem = HT.checkMenuRowExits()
            // console.log(arrayMenuItem)

            HT.sendAjaxGetMenu(_this, option, target, arrayMenuItem)
        })
    }

    HT.sendAjaxGetMenu = (_this, option, target, arrayMenuItem) => {
        $.ajax({
            url: 'ajax/dashboard/getMenu',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                // console.log(res)
                let html = ''
                // let canonical=[]
                for(let i = 0; i< res.data.length; i++){
                    html += HT.renderModelMenu(res.data[i], arrayMenuItem)

                    // canonical.push(res.data[i].canonical);//Lây ra danh sách canonical của từng a.menu-module để tiến hành kiểm tra checkbox nào được checked bên trái
                }
                // console.log(canonical)
                // console.log(res.links)
                html += HT.menuLinks(res.links)// V64 đổ ra giao diện phân trang theo res.links
                target.html(html)//Đổ dữ liệu và giao diện vừa được gửi ajax vào trong vùng menu-list

                // // Kiểm tra checkbox nào được checked mỗi lần chạy lại ajax
                // canonical.forEach(value => {
                //     // console.log(value)
                //     let element = _this.parents('.wrapper-content').find('.' + value);//Tìm bên phải trước
                //     console.log(element); // In ra để kiểm tra
                //     if(element.length > 0){//Nếu như bên phải có danh sách giống với bên trái thì mới tìm bên trái và checked nó lên
                //         let checkbox = _this.parents('.panel-default').find('#'+value)// tiến hành tìm bên trái
                //         // console.log(checkbox)
                //         //Nếu bên trái có danh sách giống bên phải theo cái value đó thì tiến hành checked những cái input:checkbox này
                //         checkbox.prop('checked', true);
                //     }
                // });

               
            },
            beforeSend: function(){
                _this.parents('.panel-default').find('.menu-list').html('')
            },
            error: function(jqXHR, textStatus, errorThrown){
                
            }
        });
    }

    // V63 Tạo chức năng khi click vào từng thẻ a.menu-module tương ứng thì đổ dữ liệu và giao diện vừa được gửi ajax vào trong vùng menu-list
    HT.renderModelMenu = (object, arrayMenuItem) => {
        let html = '';
        html += '<div class="m-item mb10">';
        html += '    <div class="uk-flex uk-flex-middle">';
        html += '        <input type="checkbox" '+((arrayMenuItem.includes(object.canonical)) ? 'checked' : '')+' name="" class="m0 choose-menu" value="'+object.canonical+'" id="'+object.canonical+'">';
        html += '        <label for="'+object.canonical+'">'+object.name+'</label>';
        html += '    </div>';
        html += '</div>';
        return html;
    }

    // V63 Tạo chức năng khi click vào từng phần tử input:checkbox bên trái trong danh sách của từng model sẽ đổ dữ liệu đó qua bên phải div.menu-wrapper
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

    // ---------------------------------V64 Xây dựng chức năng phân trang và kiểm tra m-item bên trái nào được checked--------------------------------

    // V64 đổ ra giao diện phân trang theo res.links khi gửi AJAX khi click .module-menu hoặc .page-link bên trái
    HT.menuLinks = (links) => {
        let html = '';
        if(links.length > 3){
            html += '<nav><ul class="pagination">';
            $.each(links, function(index, link) {
                let liClass = 'page-item';
                
                if (link.active) {
                    liClass += ' active';
                } else if (!link.url) {
                    liClass += ' disabled';
                }
                
                html += '<li class="' + liClass + '">';
                
                if (link.label === 'pagination.previous') {
                    if (link.url) {
                        html += '<a class="page-link" aria-label="pagination.previous" href="' + link.url + '">‹</a>';
                    } else {
                        html += '<span class="page-link" aria-hidden="true">‹</span>';
                    }
                } 
                else if (link.label === 'pagination.next') {
                    if (link.url) {
                        html += '<a class="page-link" aria-label="pagination.next" href="' + link.url + '">›</a>';
                    } else {
                        html += '<span class="page-link" aria-hidden="true">›</span>';
                    }
                } 
                else if (link.url) {
                    html += '<a class="page-link" href="' + link.url + '">' + link.label + '</a>';
                } 
                else {
                    html += '<span class="page-link">' + link.label + '</span>';
                }
                
                html += '</li>';
            });
            html += '</ul></nav>';
        }
        
        return html;

        // <nav>
        //     <ul class="pagination">
    
        //         <li class="page-item disabled" aria-disabled="true" aria-label="pagination.previous">
        //             <span class="page-link" aria-hidden="true">‹</span>
        //         </li>
        //         <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
        //         <li class="page-item"><a class="page-link" href="http://127.0.0.1:8000/product/index?page=2">2</a></li>
                                                                
        //         <li class="page-item">
        //             <a class="page-link" href="http://127.0.0.1:8000/product/index?page=2" rel="next" aria-label="pagination.next">›</a>
        //         </li>
        //     </ul>
        // </nav>
    };
    
    // V64 khi click vào .page-link thì nó sẽ hiển thị ra được m-item tương ứng (hiện thị dữ liệu khi đã click vào số trang tương ứng)
    HT.getPaginationMenu = () => {
        $(document).on('click', '.page-link', function(e) {
            e.preventDefault();
            let _this = $(this);
            
            // Chỉ xử lý nếu liên kết không bị vô hiệu hóa
            if (!_this.parent().hasClass('disabled') && !_this.parent().hasClass('active')) {
                let option = {
                    model: _this.parents('.panel-default').find('.menu-module').attr('data-model'),
                    page: _this.attr('href').split('page=')[1] // Lấy số trang từ URL
                };

                let arrayMenuItem = HT.checkMenuRowExits()
                
                let target = _this.parents('.menu-list');
                HT.sendAjaxGetMenu(_this, option, target, arrayMenuItem);
            }
        });
    };

    // V64 lấy ra danh sách là mảng các class đã có ở bên phải để ss đối chiếu với mỗi lần chạy AJAX render lại m-item checkbox nào được checked bên trái
    HT.checkMenuRowExits = () =>{
        let arrayMenuItem = $('.menu-item').map(function(){//Lấy ra danh sách menu-item đã được chọn bên phải
            let allClasses = $(this).attr('class').split(' ').slice(3).join(' ')//tạo một mảng gồm các class của vị trí thứ 4

            return allClasses
        }).get()

        return arrayMenuItem//trả về mảng vừa được tạo ra
    }

    // ----------------------------V65 Xây dựng chức năng tìm kiếm theo .search-menu bên trái đổ AJAX dữ liệu được tìm kiếm vào lại .menu-list------------

    // V65 Xây dựng chức năng tìm kiếm theo .search-menu bên trái đổ AJAX dữ liệu được tìm kiếm vào lại .menu-list
    HT.searchMenu = () =>{
        let typingTimer;
        let doneTypingInterval = 1000; //1s
        $(document).on('keyup', '.search-menu', function(){
            // console.log(123)
            let _this = $(this)
            let keyword = _this.val()
            let option = {
                model: _this.parents('.panel-default').find('.menu-module').attr('data-model'),
                keyword: keyword
            }
            // console.log(option)

            let specialKeys = [9, 16, 17, 18, 27]; // Tab, Shift, Ctrl, Alt, Esc
            // Kiểm tra xem mã phím có thuộc danh sách các phím đặc biệt không
            if (specialKeys.includes(event.which)) {
                return; // Nếu đúng, thoát khỏi hàm mà không thực hiện AJAX
            }
           
            clearTimeout(typingTimer)
            typingTimer = setTimeout(function(){
                // console.log(keyword)

                let target = _this.parents('.panel-body').find('.menu-list')
                let arrayMenuItem = HT.checkMenuRowExits()
                HT.sendAjaxGetMenu(_this, option, target, arrayMenuItem)
            }, doneTypingInterval)
             
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

        // V63 Xây sự kiện cho nút thêm đường dẫn bên trái a.add-menu (sẽ những thêm div.menu-item bên phải bên trong div.menu-wrapper)
        HT.createMenuRow()

        // V63 Xây dựng xự kiện cho nút xóa bên phải a.delete-menu và kiểm tra trạng thái input.checkbox và độ dài menu-item
        HT.deleteMenuRow()

        // V63 Xây dựng chức năng lấy dữ liệu đổ ra giao diện danh sách của từng model khi click vào a.menu-model đồng thời kiểm tra tình trạng input:checkbox ở .m-item
        HT.getMenu()

        // V63 Tạo chức năng khi click vào từng phần tử input:checkbox bên trái trong danh sách của từng model sẽ đổ dữ liệu đó qua bên phải div.menu-wrapper
        HT.chooseMenu()

        // V64 khi click vào page-link thì nó sẽ hiển thị ra được m-item tương ứng (hiện thị dữ liệu khi đã click vào số trang tương ứng)
        HT.getPaginationMenu()

        // V65 Xây dựng chức năng tìm kiếm theo .search-menu bên trái đổ AJAX dữ liệu được tìm kiếm vào lại .menu-list
        HT.searchMenu()

        // V63 Dùng trong form product/aside để chuyển chuỗi string về dạng thành tiền 1.234.567
        HT.int()
    })

})(jQuery)