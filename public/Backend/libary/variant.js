(function($){

    //var $document = $(document)
    var HT={};

    // Ẩn hiện variant-wrapper
    HT.setupProductVariant = () => {
        if ($('.variant-checkbox').length) {
            $(document).on('click', '.variant-checkbox input[type="checkbox"], .variant-checkbox label', function() {
                let _this = $(this).closest('.variant-checkbox');
                let checkbox = _this.find('input[type="checkbox"]');
    
                // Kiểm tra trạng thái của checkbox và ẩn/hiện .variant-wrapper
                if (checkbox.is(':checked')) {
                    $('.variant-wrapper').removeClass('hidden');
                } else {
                    $('.variant-wrapper').addClass('hidden');
                }
            });
        }
    }

    // Ẩn hiện thông tin variant-body
    HT.addVariant = () => {
        if($('.add-variant').length){
            $(document).on('click', '.add-variant', function(){
                let html = HT.renderVariantItem(attributeCatalogues)
                $('.variant-body').append(html)
                HT.checkMaxAtrributeGroup(attributeCatalogues)
                HT.disabledAttributeCatalogueChoose()
            })
        }
    }

    HT.renderVariantItem = (attributeCatalogues) => {
        
        let html = '';
        
        html += '<div class="row mb20 variant-item">';
        html += '    <div class="col-lg-3">';
        html += '        <div class="attribute-catalogue">';
        html += '            <select name="" id="" class="choose-attribute setupNiceSelect">';
        html += '                <option value="0">'+selectAttributeGroup+'</option>';
        
        for (let i = 0; i < attributeCatalogues.length; i++) {
            html += '            <option value="' + attributeCatalogues[i].id + '">' + attributeCatalogues[i].name + '</option>';
        }
    
        html += '            </select>';
        html += '        </div>';
        html += '    </div>';
        html += '    <div class="col-lg-8">';
        html += '        <input type="text" name="" class="fake-variant form-control" disabled>';
        html += '    </div>';
        html += '    <div class="col-lg-1">';
        html += '        <button type="button" class="remove-attribute btn btn-danger"><i class="fa fa-trash"></i></button>';
        html += '    </div>';
        html += '</div>';
    
        return html;
    }

    // Mở lại các tùy chọn và Disabled đi nhưng cái option đã chọn trong NiceSelect trước đó
    HT.disabledAttributeCatalogueChoose = () => {
        let id = []
        $('.choose-attribute').each(function(){
            let _this = $(this)
            let selected = _this.find('option:selected').val()
            if(selected != 0){
                id.push(selected)
            }
        })
        // Kích hoạt lại tất cả các tùy chọn
        $('.choose-attribute option').prop('disabled', false);

        // Vô hiệu hóa các tùy chọn đã được chọn
        for(let i = 0; i<id.length; i++){
            $('.choose-attribute').find('option[value='+id[i]+']').prop('disabled', true)
        }
        HT.destroyNiceSelect()
        HT.niceSelect()
        // console.log(id)
    }
 
    // Liên tục làm mới trạng thái của chọn nhóm thuộc tính (choose-attribute) được chọn 
    HT.chooseVariantGroup = () =>{
        $(document).on('change', '.choose-attribute', function(){
            let _this = $(this)
            let attributeCatalogueId = _this.val()
            if(attributeCatalogueId != 0){
                _this.parents('.col-lg-3').siblings('.col-lg-8').html(HT.select2Variant(attributeCatalogueId))
                $('.selectVariant').each(function(key, index){
                    HT.getSelect2($(this))
                })
            }else{
                _this.parents('.col-lg-3').siblings('.col-lg-8').html('<input type="text" name="" class="fake-variant form-control" disabled>')
            }
            HT.disabledAttributeCatalogueChoose()
        })
    }

    // Kiểm tra số lượng tối đã khi tạo nhóm thuộc tính
    HT.checkMaxAtrributeGroup = (attributeCatalogues) => {
        let variantItem = $('.variant-item').length
        if(variantItem >= attributeCatalogues.length){
            $('.add-variant').remove()
        }else{
            $('.variant-foot').html('<button type="button" class="add-variant">'+addVariant+'</button>')
        }
    }

    // Xóa việc tạo một nhóm thuộc tính
    HT.removeAttribute = () => {
        $(document).on('click', '.remove-attribute', function(){
            let _this = $(this)
            _this.parents('.variant-item').remove()
            HT.checkMaxAtrributeGroup(attributeCatalogues)
        })
    }

    //-----------------------------------------------------------------------------------48
    // Sau khi người dùng đã chọn một option attribute-catalogue ở class choose-attribute
    // Tạo ra form select2 thực hiện chọn multiple cho col-lg-8 lúc này nó không còn là ô input fake nữa mà thay bằng form select2 chọn multiple
    HT.select2Variant = (attributeCatalogueId) => {
        let html = '<select class="selectVariant form-control variant-'+attributeCatalogueId+'" name="attribute['+attributeCatalogueId+'][]" multiple data-catid="'+attributeCatalogueId+'"></select>'
        return html
    }

    // Để lúc này khi người dùng nhập được dữ liệu vào nhờ đoạn code bên dưới này thì nó sẽ biến form select2 chọn multiple thành form nhập tìm kiếm select2
    // cùng với dữ liệu nhập vào để tìm và attributeCatalogueId đã chọn
    // Truyền và Nhận dữ liệu của Attributes tương ứng
    HT.getSelect2 = (object) => {
        let option = {
            'attributeCatalogueId': object.attr('data-catid')
        }
        $(object).select2({
            minimumInputLength: 2,
            placeholder: placeholderSelect2,
            ajax: {
                url: 'ajax/attribute/getAttribute',
                type: 'GET',
                dataType: 'json',
                delay: 250,
                data: function (params){
                    return{
                        search: params.term,
                        option: option,
                    }
                },
                processResults: function(data){
                    // console.log(data)
                    return {
                        results: $.map(data, function(obj, i){
                            return obj
                        })
                    }
                },
                cache: true
            }
        })
    }

    //----------------------------------------------------------------------49
    // Sau khi người dùng đã chọn các thuộc tính ở form select2 multiple ta sẽ cần phải xây dựng được mảng sản phẩm gồm nhiều phiên bản
    // sau đó sẽ tạo ra một table để hiện thị lên trực quan từ mảng sản phẩm gồm nhiều phiên bản đó

    // Tạo Sản Phẩm có nhiều phiên bản
    HT.createProductVariant = () => {
        $(document).on('change', '.selectVariant', function(){// .selectVariant: form select2 multiple (r)
            let _this = $(this)
            // console.log(123)
            HT.createVariant()
        })
    }

    // Xử lý việc tạo phiên bản sản phẩm
    HT.createVariant = () => {

        let attributes = []
        let variant = []
        let attributeTitle = []

        $('.variant-item').each(function(){ //variant gồm có (choose-attibute, selectVariant )
            let _this = $(this)
            let attr = []
            let attributeCatalogueId = _this.find('.choose-attribute option:selected').val() // .choose-attribute (l)
            let optionText = _this.find('.choose-attribute option:selected').text()
            let attribute = $('.variant-'+attributeCatalogueId).select2('data')
            // console.log(attribute)

            for(let i = 0; i < attribute.length; i++){
                let item = {}
                let itemVariant = {}
                item[optionText] = attribute[i].text
                attr.push(item)
            }
            attributeTitle.push(optionText)
            attributes.push(attr)
        })
        // console.log(attributeTitle)
        // console.log(attributes)
        
        attributes = attributes.reduce(
            (a, b) => a.flatMap( d => b.map( e => ( { ...d,...e } )))
        )
        // console.log(attributes)

        let html = HT.renderTableHtml(attributes, attributeTitle)
        $('table.variantTable').html(html)
    }

    //Render mảng phiên bản sản phẩm thành dạng bảng
    HT.renderTableHtml = (attributes, attributeTitle) => {
        let html = '';
        html += '<thead>';
        html += '    <tr>';
        html += '        <td>'+imageProductVariant+'</td>';
                        for(let i = 0; i<attributeTitle.length; i++){
                            html += '<td>'+attributeTitle[i]+'</td>';
                        }
        html += '        <td>'+storageProductVariant+'</td>';
        html += '        <td>'+priceProductVariant+'</td>';
        html += '        <td>SKU</td>';
        html += '    </tr>';
        html += '</thead>';
        html += '<tbody>';
                    for(let j = 0; j < attributes.length; j++){
                        html += '    <tr class="variant-row">';
                        html += '        <td>';
                        html += '            <span class="image-variant img-cover">';
                        html += '                <img src="Backend/img/not-found.png" alt="">';
                        html += '            </span>';
                        html += '        </td>';
                                        $.each(attributes[j], function(index, value){
                                            html += '        <td>'+value+'</td>';
                                        })
                        html += '        <td>-</td>';
                        html += '        <td>-</td>';
                        html += '        <td>-</td>';
                        html += '    </tr>';
                    }
        html += '</tbody>';
        return html;
    }
    
    //Dùng trong form product/variant
    HT.niceSelect = () =>{
        $('.setupNiceSelect').niceSelect();
    }

    HT.destroyNiceSelect = () => {
        if($('.setupNiceSelect').length){
            $('.setupNiceSelect').niceSelect('destroy')
        }
    }
    
    $(document).ready(function(){
        // Ẩn hiện variant-wrapper
        HT.setupProductVariant()

        // Ẩn hiện thông tin variant-body
        HT.addVariant()

        // Cập nhật lại option chưa được chọn
        HT.chooseVariantGroup()

        // Xóa việc tạo một nhóm thuộc tính
        HT.removeAttribute()

        // Tạo Sản Phẩm có nhiều phiên bản
        HT.createProductVariant()

        //gọi phương thức tạo niceSelect (giao diện)
        HT.niceSelect();
    })

})(jQuery)