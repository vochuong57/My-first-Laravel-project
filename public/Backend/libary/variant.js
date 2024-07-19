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
 
    // Cập nhật lại option chưa được chọn (liên tục làm mới trạng thái các option)
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

    // Tạo ra form select2 thực hiện chọn multiple cho col-lg-8
    HT.select2Variant = (attributeCatalogueId) => {
        let html = '<select class="selectVariant form-control" name="attribute['+attributeCatalogueId+'][]" multiple data-catid="'+attributeCatalogueId+'"></select>'
        return html
    }

    // Truyền và Nhận dữ liệu của Attributes
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
                    console.log(data)
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

        //gọi phương thức tạo niceSelect (giao diện)
        HT.niceSelect();
    })

})(jQuery)