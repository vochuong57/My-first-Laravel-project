(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // P1. V96 Tạo sự kiện change cho input:checkbox #neverDate không có ngày kết thúc (trái)
    HT.promotionNeverDate = () => {
        $(document).on('change', '#neverDate', function(){
            let _this = $(this)
            let isChecked = _this.prop('checked')
            // console.log(isChecked)
            if(isChecked){
                $('input[name=endDate]').val('').attr('readonly', true)
            }else{
                let endDate = $('input[name=startDate]').val()
                $('input[name=endDate]').val(endDate).attr('readonly', false)
            }
        })
    }

    // P2.1 V96 tạo sự kiện click ở input:radio .chooseSource để chọn dạng (nguồn khách áp dụng) (trái)
    HT.promotionSource = () => {
        $(document).on('click', '.chooseSource', function(){
            let _this = $(this)
            let flag = (_this.attr('id') == 'allSource') ? true : false 
            if(flag){
                _this.parents('.ibox-content').find('.source-wrapper').remove()
            }else{
                _this.parents('.ibox-content').find('.source-wrapper').remove()
                let sourceData = [
                    {
                        id: 1,
                        name: 'TikTok'
                    },
                    {
                        id: 2,
                        name: 'Shopee'
                    },
                ]
                let sourceHtml = HT.renderPromotionSource(sourceData)
                // console.log(sourceHtml)
                _this.parents('.ibox-content').append(sourceHtml)
                HT.promotionMultipleSelect2()
            }
        })
    }

    // P2.2 V96 hàm render giao diện ra select2 dạng multiple cho chọn nguồn khách hàng áp dụng
    HT.renderPromotionSource = (sourceData) => {
        let wrapper = $('<div>').addClass('source-wrapper')
        if(sourceData.length){
            let select = $('<select>').addClass('multipleSelect2').attr('name', 'source').attr('multiple', true)

            for(let i = 0; i < sourceData.length; i++){
                let option = $('<option>').attr('value', sourceData[i].id).text(sourceData[i].name)
                select.append(option)
            }
            wrapper.append(select)
        }
        return wrapper

        // <div class="source-wrapper">
        //     <select name="source" id="" class="multipleSelect2" multiple>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //         <option value="">Shopee</option>
        //     </select>
        // </div>
    }

    // V96 Xây dựng hàm gọi khởi tạo select2
    HT.promotionMultipleSelect2 = () => {
        $('.multipleSelect2').select2({
            // minimumInputLength: 2,
            placeholder: 'Click vào ô để lựa chọn...',
            // ajax: {
            //     url: 'ajax/attribute/getAttribute',
            //     type: 'GET',
            //     dataType: 'json',
            //     delay: 250,
            //     data: function (params){
            //         return{
            //             search: params.term,
            //             option: option,
            //         }
            //     },
            //     processResults: function(data){
            //         // console.log(data)
            //         return {
            //             results: $.map(data, function(obj, i){
            //                 return obj
            //             })
            //         }
            //     },
            //     cache: true
            // }
        })
    }

    // P3.1 V96 tạo sự kiện click ở input:radio .chooseApply để chọn dạng đối tượng áp dụng (trái)
    HT.chooseCustomerCondition = () => {
        $(document).on('change', '.chooseApply', function(){
            let _this = $(this)
            let flag = (_this.attr('id') == 'allApply') ? true : false 
            if(flag){
                _this.parents('.ibox-content').find('.apply-wrapper').remove()
            }else{
                _this.parents('.ibox-content').find('.apply-wrapper').remove()
                let applyData = [
                    {
                        id: 'staff_take_care_customer',
                        name: 'Nhân viên phụ trách'
                    },
                    {
                        id: 'customer_group',
                        name: 'Nhóm khách hàng'
                    },
                    {
                        id: 'customer_gender',
                        name: 'Giới tính'
                    },
                    {
                        id: 'customer_birthday',
                        name: 'Ngày sinh'
                    },
                ]
                let applyHtml = HT.renderApplyCondition(applyData)
                // console.log(sourceHtml)
                _this.parents('.ibox-content').append(applyHtml)
                HT.promotionMultipleSelect2()
                HT.chooseApplyItem()
            }
        })
    }

    // P3.2 V96 hàm render giao diện ra select2 dạng multiple cho chọn đối tượng áp dụng nhóm đối tượng (cấp 1)
    HT.renderApplyCondition = (applyData) => {
        let wrapper = $('<div>').addClass('apply-wrapper')
        let wrapperConditionItem = $('<div>').addClass('wrapper-condition')
        if(applyData.length){
            let select = $('<select>').addClass('multipleSelect2 conditionItem').attr('name', 'apply').attr('multiple', true)

            for(let i = 0; i < applyData.length; i++){
                let option = $('<option>').attr('value', applyData[i].id).text(applyData[i].name)
                select.append(option)
            }
            wrapper.append(select)
            wrapper.append(wrapperConditionItem)
        }
        return wrapper
    }

    // P3.3 V96 hàm tạo sự kiện change khi thay đổi select.conditionItem nhóm đối tượng (cấp 1)
    HT.chooseApplyItem = () =>{
        $(document).on('change', '.conditionItem', function(){
            let _this = $(this)
            // console.log(_this.val())
            let condition = {
                value: _this.val(),
                label: _this.select2('data')
            }
            // console.log(condition)

            // Xử lý khi xóa các conditionItem (cấp 1) đã chọn ở select2 multiple
            $('.wrapperConditionItem').each(function(){
                let _item = $(this)
                let itemClass = _item.attr('class').split(' ')[2]
                if(condition.value.includes(itemClass) == false){
                    _item.remove()
                }
            })

            // xử lý đổ ra giao diện label và select2 multiple cho đối tượng đó (cấp 2) với nhóm đối tượng đã chọn (select.conditionItem) cấp 1
            for(let i = 0; i < condition.value.length; i++){
                let value = condition.value[i]
                let html = HT.createConditionItem(value, condition.label[i].text)
                $('.wrapper-condition').append(html)
            }
        })
    }

    // P3.4 V96 Hàm đỗ ra giao diện label tương ứng theo từng conditionItem đã chọn (select.conditionItem) cấp 1
    HT.createConditionLabel = (label, value) => {
        let deleteButton = $('<div>').addClass('delete').html('<a class="delete-menu img-scaledown" style="width: 40%; height: 30px; margin-left: 6px"> <img src="Backend/img/close.png" alt=""></a>').attr('data-condition-item', value)
        let conditionLabel = $('<div>').addClass('conditionLabel').text(label)
        let flex = $('<div>').addClass('uk-flex uk-flex-middle uk-flex-space-between')
        let wrapperBox = $('<div>').addClass('mb10')

        flex.append(conditionLabel).append(deleteButton)
        wrapperBox.append(flex)

        return wrapperBox

        // <div class="mb10">
        //     <div class="uk-flex uk-flex-middle uk-flex-space-between">
        //         <div class="conditionLabel">Nhóm khách hàng</div>
        //         <div class="delete">
        //             <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px">
        //                 <img src="Backend/img/close.png" alt="">
        //             </a>
        //         </div>
        //     </div>
        // </div>
    }

    // P3.5 V96 Hàm đỗ ra giao diện select2 dạng multiple cấp 2 tương ứng theo từng conditionItem đã chọn (select.conditionItem) cấp 1
    HT.createConditionItem = (value, label) => {

        let optionData = [
            {
                id: 1,
                name: 'Khach Vip'
            },
            {
                id: 2,
                name: 'Khách bán buôn'
            }
        ]
        let conditionItem = $('<div>').addClass('wrapperConditionItem mt10 '+value)
        let select = $('<select>').addClass('multipleSelect2 objectItem').attr('name', 'customerGroup').attr('multiple', true)
        for(let i = 0; i < optionData.length; i++){
            let option = $('<option>').attr('value', optionData[i].id).text(optionData[i].name)
            select.append(option)
        }
        const conditionLabel = HT.createConditionLabel(label, value)
        conditionItem.append(conditionLabel)
        conditionItem.append(select)

        if($('.wrapper-condition').find('.'+value).elExist()){
            return
        }
        $('.wrapper-condition').append(conditionItem)
        HT.promotionMultipleSelect2()
    }

    // P3.6 V96 Hàm xóa div.delete để xóa các wrapperConditionItem (cấp 2) tương ứng
    HT.deleteCondition = () => {
        $(document).on('click', '.wrapperConditionItem .delete', function(){
            let _this = $(this)
            let unSelectedItem = _this.attr('data-condition-item')
            let selectedItem = $('.conditionItem').val()
            console.log(selectedItem)
            if(selectedItem.includes(unSelectedItem)){
                selectedItem = selectedItem.filter(value => value !== unSelectedItem)
            }
            $('.conditionItem').val(selectedItem).trigger('change')
        })
    }

    // V96 Hàm kiểm tra phần tử class định danh đó đã tồn tại hay chưa
    $.fn.elExist = function(){
        return this.length > 0
    }

    HT.renderOrderRangeConditionContainer = () => {
        $(document).on('change', '.promotionMethod', function(){
            let _this = $(this)
            let option = _this.val()
            switch (option){
                case "order_amount_range":
                    HT.renderAmountRange()
                    console.log("order_amount_range")
                    break
                case "product_and_quantity":
                    HT.renderProductAndQuantity()
                    console.log("product_and_quantity")
                    break
                // case "product_quantity_range":
                //     console.log("product_quantity_range")
                //     break
                // case "goods_discount_by_quantity":
                //     console.log("goods_discount_by_quantity")
                //     break
                default:
                    HT.removePromotionContainer()
            }
        })
    }

    HT.removePromotionContainer = () => {
        $('.promotion-container').html('')
        ranges = [];
    }

    HT.select2=()=>{
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
    }

    HT.renderAmountRange = () => {
        let html = `
            <div class="order-amount-range">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Giá trị từ</th>
                            <th>Giá trị đến</th>
                            <th>Chiết khấu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr">
                            <td class="order-amount-range-from">
                                <input type="text" class="form-control int" name="amountFrom[]" placeholder="0" value="0">
                            </td>
                            <td class="order-amount-range-to">
                                <input type="text" class="form-control int" name="amountTo[]" placeholder="0" value="0">
                            </td>
                            <td class="discountType">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="text" class="form-control int" name="amountValue[]" placeholder="0" value="0">
                                    <select name="amountType" class="setupSelect2">
                                        <option value="cash">đ</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="delete-some-item delete-order-amount-range-condition">
                                    <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px">
                                        <img src="Backend/img/close.png" alt="">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-success btn-custom btn-js-100" value="" type="button">Thêm điều kiện</button>
            </div>
        `
        $('.promotion-container').html(html)
        HT.select2()
    }

    // -------------------------------------Method order_amount_range-------------------------------------

    // V98
    HT.btnJs100 = () => {
        $(document).on('click', '.btn-js-100', function(){
            let _button = $(this)

            let trLastChild = $('.order-amount-range').find('tbody tr:last-child');
            if(trLastChild.length){

                let from = parseInt(trLastChild.find('.order-amount-range-from input').val().replace(/\./g, ''));
                let to = parseInt(trLastChild.find('.order-amount-range-to input').val().replace(/\./g, ''));
                let currentPosition = trLastChild.data('position') || null;
                
                if (!HT.isValidRange(from, to)) {
                    alert('Khoảng điều kiện không hợp lệ, giá trị tới phải lớn hơn giá trị từ');
                    return;
                }
                
                // Kiểm tra xung đột
                let conflictResult = HT.checkBtnJs100ConflictRange(from, to, currentPosition);
                if (conflictResult.conflict) {
                    trLastChild.addClass('errorLine');
                    alert(`Có xung đột với khoảng điều kiện tại vị trí: ${conflictResult.position}`);
                    return;
                }
                
                // Xóa class lỗi nếu thêm thành công
                $('.order-amount-range').find('tr').removeClass('errorLine');
                
                // Nếu hàng này chưa có `position`, thêm mới
                if (!currentPosition) {
                    currentPosition = ranges.length + 1;
                    trLastChild.data('position', currentPosition);
                }

                trLastChild.find('.order-amount-range-from input').attr('readonly', true)
                trLastChild.find('.order-amount-range-to input').attr('readonly', true)
                
                // Thêm hoặc cập nhật mảng ranges
                let existingIndex = ranges.findIndex(range => range.position === currentPosition);
                if (existingIndex !== -1) {
                    // Cập nhật khoảng đã tồn tại
                    ranges[existingIndex] = { newFrom: from, newTo: to, position: currentPosition };
                } else {
                    // Thêm mới
                    ranges.push({ newFrom: from, newTo: to, position: currentPosition });
                }
            }

            let $tr = $('<tr>')
            let tdList = [
                {class: 'order-amount-range-from td-range', name: '', value: 0},
                {class: 'order-amount-range-to td-range', name: '', value: 0},
            ]

            for(let i = 0; i < tdList.length; i++){
                let $td = $('<td>').addClass(tdList[i].class)
                let $input = $('<input>').addClass('form-control int').attr('name', tdList[i].name).attr('value', tdList[i].value)

                $td.append($input)
                $tr.append($td)
            }

            let $discountTypeTd = $('<td>').addClass('discountType')

            $discountTypeTd
                .append(
                    $('<div>',{
                        class: 'uk-flex uk-flex-middle'
                    })
                    .append(
                        $('<input>', {
                            type: 'text',
                            name: '',
                            class: 'form-control int',
                            placeholder: 0,
                            value: 0
                        })
                    )
                    .append(
                        $('<select>', {
                            class: 'setupSelect2'
                        })
                            .append(
                                $('<option>', {
                                    value: 'cash',
                                    text: 'đ'
                                })
                            )
                            .append(
                                $('<option>', {
                                    value: 'percent',
                                    text: '%'
                                })
                            )
                    )
                )
            $tr.append($discountTypeTd)

            let $deleteButtonTd = $('<td>')
            
            $deleteButtonTd
                .append(
                    $('<div>', {
                        class: 'delete-some-item delete-order-amount-range-condition',
                    })
                        .append(
                            $('<a>', {
                                class: 'delete-menu img-scaledown',
                            }).css({
                                width: '15%',
                                height: '30px',
                                marginLeft: '6px'
                            })
                            .append(
                                $('<img>', {
                                    src: 'Backend/img/close.png',
                                    alt: '',
                                })
                            )
                        )
                )
            $tr.append($deleteButtonTd)
            $('.order-amount-range table tbody').append($tr)
            HT.select2()

            // let html = `
            //     <tr>
            //         <td>
            //             <input type="text" class="form-control int" name="" placeholder="0" value="0">
            //         </td>
            //         <td>
            //             <input type="text" class="form-control int" name="" placeholder="0" value="0">
            //         </td>
            //         <td class="discountType">
            //             <div class="uk-flex uk-flex-middle">
            //                 <input type="text" class="form-control int" name="" placeholder="0" value="0">
            //                 <select name="" class="setupSelect2">
            //                     <option value="cash">đ</option>
            //                     <option value="percent">%</option>
            //                 </select>
            //             </div>
            //         </td>
            //         <td>
            //             <div class="delete-some-item delete-order-amount-range-condition">
            //                 <a class="delete-menu img-scaledown" style="width: 15%; height: 30px; margin-left: 6px">
            //                     <img src="Backend/img/close.png" alt="">
            //                 </a>
            //             </div>
            //         </td>
            //     </tr>
            // `
        })
    }
    var ranges = [];

    HT.checkBtnJs100ConflictRange = (newFrom, newTo, currentPosition = null) => {
        console.log("Kiểm tra xung đột với khoảng:", ranges);
    
        for (let i = 0; i < ranges.length; i++) {
            let existRange = ranges[i];
    
            // Bỏ qua kiểm tra nếu cùng vị trí (cập nhật chính nó)
            if (currentPosition && currentPosition === existRange.position) {
                continue;
            }
    
            // Kiểm tra các trường hợp chồng lấn
            if (
                (newFrom >= existRange.newFrom && newFrom <= existRange.newTo) || // Điểm đầu mới nằm trong khoảng cũ
                (newTo >= existRange.newFrom && newTo <= existRange.newTo) ||     // Điểm cuối mới nằm trong khoảng cũ
                (newFrom <= existRange.newFrom && newTo >= existRange.newTo)      // Khoảng mới bao trùm khoảng cũ
            ) {
                return { conflict: true, position: existRange.position }; // Có xung đột
            }
        }
    
        return { conflict: false }; // Không có xung đột
    };
    
    HT.isValidRange = (newFrom, newTo) => {
        if(newTo <= newFrom){
            return false
        }
        return true
    }

    HT.deleteAmountRangeCondition = () => {
        $(document).on('click', '.delete-order-amount-range-condition', function(){
            let _row = $(this).closest('tr');
            let position = _row.data('position');

            // Xóa hàng khỏi giao diện
            _row.remove();

            // Xóa khoảng tương ứng trong mảng ranges
            ranges = ranges.filter(range => range.position !== position);

            console.log("Dữ liệu ranges sau khi xóa:", ranges);
        })
    }

    // -------------------------------------Method product_and_quantity-------------------------------------

    HT.renderProductAndQuantity = () => {
        let selectData = JSON.parse($('.input-product-and-quantity').val())
        // console.log(selectData)
        let selectHtml = ''
        for(let key in selectData){
            selectHtml += '<option value="'+key+'">'+selectData[key]+'</option>'
        }
        let html = `
            <div class="product-and-quantity">
                <div class="choose-module mt20">
                    <div class="fix-label" style="color:blue" for="">Sản phẩm áp dụng</div>
                    <select name="" id="" class="setupSelect2 select-product-and-quantity">
                        ${selectHtml}
                    </select>
                </div>
                <table class="table table-striped mt20">
                    <thead>
                        <tr>
                            <th style="width: 400px;">Sản phẩm mua</th>
                            <th style="width: 80px;">SL tối thiểu</th>
                            <th>Giới hạn KM</th>
                            <th class="text-right">Chiết khấu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr">
                            <td class="order-amount-range-from">
                                <select type="text" class="form-control ajaxSearch" multiple name="amountFrom[]" placeholder="" value="" data-model="Product"></select>
                            </td>
                            <td>
                                <input type="text" class="form-control int" name="amountValue[]" placeholder="0" value="1">
                            </td>
                            <td class="order-amount-range-to">
                                <input type="text" class="form-control int" name="amountTo[]" placeholder="0" value="0">
                            </td>
                            <td class="discountType">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="text" class="form-control int" name="amountValue[]" placeholder="0" value="0">
                                    <select name="amountType" class="setupSelect2">
                                        <option value="cash">đ</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `
        $('.promotion-container').html(html)
        HT.select2()
        HT.setupAjaxSearch()
    }

    HT.setupAjaxSearch = () => {
        $('.ajaxSearch').each(function(){
            let _this = $(this)
            let option = {
                model: _this.attr('data-model'),
            }
            _this.select2({
                minimumInputLength: 2,
                placeholder: 'Nhập vào 2 từ để tìm kiếm...',
                ajax: {
                    url: 'ajax/dashboard/getPromotionObject',
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
        })
    }

    
    HT.productQuantityListProduct = () => {
        $(document).on('click', '.product-quantity', function(e){
            e.preventDefault()
            let option = {
                model: $('.select-product-and-quantity').val(),
            }
            HT.loadProduct(option)
        })
    }
    
    HT.loadProduct = (option) => {
        $.ajax({
            url: 'ajax/product/loadProductPromotion',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                console.log(res)
                HT.fillToObjectList(res)
            },
        })
    }
    
    HT.fillToObjectList = (data) =>{
        switch (data.model){
            case "Product":
                HT.fillProductToList(data.objects)
                console.log("fillProductToList")
                break
            case "ProductCatalogue":
                HT.fillProductCatalogueToList(data.objects)
                console.log("fillProductCatalogueToList")
                break
        }
    }

    HT.fillProductToList = (objects) => {
        console.log(objects)
        let html = ''
        if(objects.data.length){
            for(let i = 0; i < objects.data.length; i++){
                let image = objects.data[i].image ?? 'Backend/img/not-found.png'
                let name = objects.data[i].name
                let variant_name = objects.data[i].variant_name ?? 'null'
                let product_variant_id = objects.data[i].product_variant_id ?? 'null'
                let product_id = objects.data[i].product_id
                let inventory = objects.data[i].inventory ? objects.data[i].inventory : '0'
                let couldSale = objects.data[i].couldSale ? objects.data[i].couldSale : '0'
                let sku = objects.data[i].sku
                let price = objects.data[i].price

                html += `
                    <div class="search-object-item" data-productId = "${product_id+'-'+product_variant_id}" data-productVariantId = "${product_variant_id}" data-name = "${name}">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="object-info">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="checkbox" name="" value="${product_id}" class="input-checkbox">
                                    <span class="image img-scaledown">
                                        <img src="${image}" alt="">
                                    </span>
                                    <div class="object-name">
                                        <div class="m0">${variant_name}</div>
                                        <div class="jscode">Mã SP: ${sku}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="object-extract-info">
                                <div class="price">Giá: ${addCommas(price)}đ</div>
                                <div class="object-inventory">
                                    <div class="uk-flex uk-flex-middle">
                                        <span class="text-1">Tồn kho:</span>
                                        <span class="text-value">${inventory}</span>
                                        <span class="text-1 slash">|</span>
                                        <span class="text-value">Có thể bán: ${couldSale}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            }
        }
        html = html + HT.paginationLinks(objects.links)
        $('.search-list').html(html)
    }

    HT.paginationLinks = (links) => {
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

    HT.getPaginationMenu = () => {
        $(document).on('click', '.page-link', function(e){
            e.preventDefault()
            let _this = $(this)
            let option = {
                model: $('.select-product-and-quantity').val(),
                page: _this.text()
            }
            HT.loadProduct(option)
        })
    }

    $(document).ready(function(){
        // V96
        HT.promotionNeverDate()
        HT.promotionSource()
        HT.promotionMultipleSelect2()
        HT.chooseCustomerCondition()
        HT.deleteCondition()

        // V98
        HT.select2()
        HT.renderOrderRangeConditionContainer()

        // V98 order_amount_range
        HT.btnJs100()
        HT.deleteAmountRangeCondition()

        // V101 product_and_quantity
        // HT.setupAjaxSearch()
        // HT.renderProductAndQuantity()

        // V104
        HT.productQuantityListProduct()
        HT.getPaginationMenu()
    })

})(jQuery)