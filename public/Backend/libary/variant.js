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

    //----------------------------------------------------------------------48------------------------------------------------------------------

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

    //----------------------------------------------------------------------49------------------------------------------------------------------

    // Sau khi người dùng đã chọn các thuộc tính ở form select2 multiple (.selectVariant) ta sẽ cần phải xây dựng được mảng sản phẩm gồm nhiều phiên bản
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
        let attributeTitle = []

        let variants = []

        $('.variant-item').each(function(){ //variant gồm có (choose-attibute, selectVariant )
            let _this = $(this)
            let attr = []
            let attrVariant = []
            let attributeCatalogueId = _this.find('.choose-attribute option:selected').val() // .choose-attribute (l)
            let optionText = _this.find('.choose-attribute option:selected').text()
            let attribute = $('.variant-'+attributeCatalogueId).select2('data')
            // console.log(attribute)

            for(let i = 0; i < attribute.length; i++){
                let item = {}
                let itemVariant = {}

                //Xử lý tạo mảng tên tuộc tính để hiển thị trong bảng danh sách phiên bản
                item[optionText] = attribute[i].text
                attr.push(item)

                // Xử lý tạo mảng id phiên bản là gồm các id thuộc tính (attribute) để lưu vào name="" trong input <td hidden>
                itemVariant[attributeCatalogueId] = attribute[i].id
                attrVariant.push(itemVariant)
            }
            //Xử lý tạo mảng tên tuộc tính để hiển thị trong bảng danh sách phiên bản
            attributeTitle.push(optionText)
            attributes.push(attr)

            // Xử lý tạo mảng id phiên bản là gồm các id thuộc tính (attribute) để lưu vào name="" trong input <td hidden>
            variants.push(attrVariant)

        })
        // console.log(attributeTitle)
        // console.log(attributes)
        // console.log(variants)
        
        attributes = attributes.reduce(
            (a, b) => a.flatMap( d => b.map( e => ( { ...d,...e } )))
        )
        // console.log(attributes)

        variants = variants.reduce((a, b) => 
            a.flatMap(d => b.map(e => ({ ...d, ...e })))
        );
        // console.log(variants)

        let html = HT.renderTableHtml(attributes, attributeTitle, variants)
        $('table.variantTable').html(html)
    }

    //Render mảng phiên bản sản phẩm thành dạng bảng
    HT.renderTableHtml = (attributes, attributeTitle, variants) => {
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
                                        let attributeArray = []
                                        let attributeString = ''
                                        $.each(attributes[j], function(index, value){
                                            html += '        <td>'+value+'</td>';
                                            attributeArray.push(value)
                                        })
                                        //Xử lý việc tạo tên phiên bản vd: value="Màu xanh, Vàng"
                                        attributeString = attributeArray.join(', ')

                                        let attributeArrayId = []
                                        let attributeId = ''
                                        $.each(variants[j], function(index, value){
                                            attributeArrayId.push(value)
                                        })
                                        //Xử lý việc tạo id phiên bản vd: value="4, 7"
                                        attributeId = attributeArrayId.join(', ')
                        html += '        <td class="td-quantity">-</td>';
                        html += '        <td class="td-price">-</td>';
                        html += '        <td class="td-sku">-</td>';
                        html += '        <td class="hidden td-variant">'
                        html += '           <input type="text" name"variant[quantity][]" class="variant-quantity">';
                        html += '           <input type="text" name"variant[sku][]" class="variant-sku">';
                        html += '           <input type="text" name"variant[price][]" class="variant-price">';    
                        html += '           <input type="text" name"variant[barcode][]" class="variant-barcode">';   
                        html += '           <input type="text" name"variant[file_name][]" class="variant-filename">'; 
                        html += '           <input type="text" name"variant[file_path][]" class="variant-filepath">'; 
                        html += '           <input type="text" name"variant[album][]" class="variant-album">';
                        html += '           <input type="text" name"attribute[name][]" value="'+attributeString+'">';   
                        html += '           <input type="text" name"attribute[id][]" value="'+attributeId+'">';     
                        html += '        </td>';
                        
                        html += '    </tr>';
                    }
        html += '</tbody>';
        return html;
    }

    //----------------------------------------------------------------------50------------------------------------------------------------------
    //Xây dựng giao diện một hàng toolbox để cập nhật thông tin phiên bản sản phẩm, hình ảnh, giá v.v... tương ứng cho từng hàng trong phiên bản sản phẩm
    // xây dụng upload album ảnh trong product/productVariant và delete album ảnh
    HT.variantAlbum = () => {
        $(document).on('click', '.click-to-upload-variant', function(e){
            e.preventDefault()
            HT.browseServerAlbum()
        })
    }

    HT.browseServerAlbum=()=>{
        var type='Images';
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data, allFiles){
            //console.log(allFiles)
            let html=''
            for(var i = 0; i<allFiles.length;i++){
                var image = allFiles[i].url
                
                html+='<li class="ui-state-default">'
                    html+='<div class="thumb">'
                        html+='<span class="span image img-scaledown">'
                            html+='<img src="'+image+'" alt="'+image+'">'
                            html+='<input type="hidden" name="variantAlbum[]" value="'+image+'">'
                        html+='</span>'
                        html+='<button class="varitant-delete-image"><i class="fa fa-trash"></i></button>'
                    html+='</div>'
                html+='</li>'
               
            }
            $('.click-to-upload-variant').addClass('hidden')
            $('#sortable2').append(html)
            $('.upload-variant-list').removeClass('hidden')
        }
        finder.popup();
    }

    // Cho phép hoặc không để thao tác lên các ô input số lượng, tên, đường dẫn
    HT.switchChange = () => {
        $(document).on('change', '.js-switch', function(){
            let _this = $(this)
            let isChecked = _this.prop('checked')
            // console.log(isChecked)
            if(isChecked == true){
                _this.parents('.col-lg-2').siblings('.col-lg-10').find('.disabled').removeAttr('disabled')
            }else{
                _this.parents('.col-lg-2').siblings('.col-lg-10').find('.disabled').attr('disabled', true)
            }
        })
    }

    //----------------------------------------------------------------------51------------------------------------------------------------------
    // Xây dựng được hành động cơ bản mở, tắt, lưu để tạo thuộc tính cho từng phiên bản sản phẩm và tiến hành lưu nó cho từng tr thuộc tính sản phẩm

    // Hiển thị nội dung của toolBox để cập nhật thông tin từng phiên bản sản phẩm của mỗi dòng khi click vào tr ('.variant-row')
    HT.updateVariant = () => {
        $(document).on('click', '.variant-row', function(){
            let _this = $(this)
            let updateVariantBox = HT.updateVariantHTML()
            if($('.updateVariantTr').length == 0){
                _this.after(updateVariantBox)
                HT.switchery()
            }
           
        })
    }

    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394', size: 'small' });
        })
    }

    HT.updateVariantHTML = () => {
        let html = '';
    
        html += '<tr class="updateVariantTr">';
        html += '    <td colspan="6">';
        html += '        <div class="updateVariant ibox">';
        html += '            <div class="ibox-title">';
        html += '                <div class="uk-flex uf-flex-middle uk-flex-space-between">';
        html += '                    <h5>'+updateVersionInformation+'</h5>';
        html += '                    <div class="button-group">';
        html += '                        <div class="uk-flex uk-flex-middle">';
        html += '                            <button type="button" class="cancelUpdate btn btn-danger mr10">'+cancel+'</button>';
        html += '                            <button type="button" class="saveUpdate btn btn-success">'+save+'</button>';
        html += '                        </div>';
        html += '                    </div>';
        html += '                </div>';
        html += '            </div>';
        html += '            <div class="ibox-content">';
        html += '                <div class="click-to-upload-variant">';
        html += '                    <div class="icon">';
        html += '                        <a href="" class="upload-variant-picture">';
        html += '                            <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">';
        html += '                                <path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path>';
        html += '                            </svg>';
        html += '                        </a>';
        html += '                    </div>';
        html += '                    <div class="small-text">'+adviseAlbum+'</div>';
        html += '                </div>';
        html += '                <div class="upload-variant-list hidden">';
        html += '                    <div class="row">';
        html += '                        <ul id="sortable2" class="clearfix data-album sortui ui-sortable"></ul>';
        html += '                    </div>';
        html += '                </div>';
        html += '                <div class="row mt20 uk-flex uk-flex-middle">';
        html += '                    <div class="col-lg-2 uk-flex uk-flex-middle">';
        html += '                        <label for="" class="mr10">'+inventory+'</label>';
        html += '                        <input type="checkbox" name="quantity" value="0" class="js-switch" data-target="variantQuantity">';
        html += '                    </div>';
        html += '                    <div class="col-lg-10">';
        html += '                        <div class="row">';
        html += '                            <div class="col-lg-3">';
        html += '                                <label for="" class="control-label">'+storageProductVariant+'</label>';
        html += '                                <input type="text" name="variant_quantity" value="0" class="form-control int disabled" disabled>';
        html += '                            </div>';
        html += '                            <div class="col-lg-3">';
        html += '                                <label for="" class="control-label">SKU</label>';
        html += '                                <input type="text" name="variant_sku" value="" class="form-control text-right">';
        html += '                            </div>';
        html += '                            <div class="col-lg-3">';
        html += '                                <label for="" class="control-label">'+priceProductVariant+'</label>';
        html += '                                <input type="text" name="variant_price" value="0" class="form-control int">';
        html += '                            </div>';
        html += '                            <div class="col-lg-3">';
        html += '                                <label for="" class="control-label">Barcode</label>';
        html += '                                <input type="text" name="variant_barcode" value="" class="form-control text-right">';
        html += '                            </div>';
        html += '                        </div>';
        html += '                    </div>';
        html += '                </div>';
        html += '                <div class="row mt20 uk-flex uk-flex-middle">';
        html += '                    <div class="col-lg-2 uk-flex uk-flex-middle">';
        html += '                        <label for="" class="mr10">'+manageFile+'</label>';
        html += '                        <input type="checkbox" name="" class="js-switch" data-target="disabled">';
        html += '                    </div>';
        html += '                    <div class="col-lg-10">';
        html += '                        <div class="row">';
        html += '                            <div class="col-lg-6">';
        html += '                                <label for="" class="control-label">'+fileName+'</label>';
        html += '                                <input type="text" name="variant_file_name" value="" class="form-control disabled" disabled>';
        html += '                            </div>';
        html += '                            <div class="col-lg-6">';
        html += '                                <label for="" class="control-label">'+filePath+'</label>';
        html += '                                <input type="text" name="variant_file_path" value="" class="form-control disabled" disabled>';
        html += '                            </div>';
        html += '                        </div>';
        html += '                    </div>';
        html += '                </div>';
        html += '            </div>';
        html += '        </div>';
        html += '    </td>';
        html += '</tr>';
    
        return html;
    }

    // Sự kiện tắt form toolbox của thuộc tính từng phiên bản
    HT.cancelVariantUpdate = () =>{
        $(document).on('click','.cancelUpdate', function(){
            $('.updateVariantTr').remove()
        })
    }

    // Sự kiện tắt form toolbox và lưu các thuộc tính từng phiên bản
    HT.saveVariantUpdate = () =>{
        $(document).on('click','.saveUpdate', function(){

            let variant = {
                'quantity': $('input[name=variant_quantity]').val(),
                'sku': $('input[name=variant_sku]').val(),
                'price': $('input[name=variant_price]').val(),
                'barcode': $('input[name=variant_barcode]').val(),
                'filename': $('input[name=variant_file_name]').val(),
                'filepath': $('input[name=variant_file_path]').val(),
                'album': $("input[name='variantAlbum[]']").map(function(){
                    return $(this).val()
                }).get(),
            }
            // console.log(variant)

            $.each(variant, function(index, value){
                $('.variant-'+index).val(value)
            })

            $('.updateVariantTr').remove()
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

        // Tạo Sản Phẩm có nhiều phiên bản
        HT.createProductVariant()

        // xây dụng upload album ảnh trong product/productVariant và delete album ảnh
        HT.variantAlbum()
        
        // Cho phép hoặc không để thao tác lên các ô input số lượng, tên, đường dẫn
        HT.switchChange()

        // Hiển thị nội dung của toolBox để cập nhật thông tin từng phiên bản sản phẩm của mỗi dòng khi click vào tr
        HT.updateVariant()
        
        // Sự kiện tắt form toolbox của thuộc tính từng phiên bản
        HT.cancelVariantUpdate()

        // Sự kiện tắt form toolbox và lưu các thuộc tính từng phiên bản
        HT.saveVariantUpdate()

        //gọi phương thức tạo niceSelect (giao diện)
        HT.niceSelect();
    })

})(jQuery)