(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // P1. V96 Tạo sự kiện change cho input:checkbox #neverDate không có ngày kết thúc (Phải)
    HT.promotionNeverDate = () => {
        $(document).on('change', '#neverDate', function(){
            let _this = $(this)
            let isChecked = _this.prop('checked')
            // console.log(isChecked)
            if(isChecked){
                $('input[name=endDate]').val('').attr('disabled', true)
            }else{
                let endDate = $('input[name=startDate]').val()
                $('input[name=endDate]').val(endDate).attr('disabled', false)
            }
        })
    }

    // P2.1 V96 V110 tạo sự kiện click ở input:radio .chooseSource để chọn dạng (nguồn khách áp dụng) (phải)
    HT.promotionSource = () => {
        $(document).on('click', '.chooseSource', function(){
            let _this = $(this)
            let flag = (_this.attr('id') == 'allSource') ? true : false 
            if(flag){
                _this.parents('.ibox-content').find('.source-wrapper').remove()
            }else{
                _this.parents('.ibox-content').find('.source-wrapper').remove()

                $.ajax({
                    url: 'ajax/source/getAllSource',
                    type: 'GET',
                    // data: option,
                    dataType: 'json',
                    success: function(res){
                        console.log(res)
                        let sourceData = res.data
                        // let sourceData = [
                        //     {
                        //         id: 1,
                        //         name: 'TikTok'
                        //     },
                        //     {
                        //         id: 2,
                        //         name: 'Shopee'
                        //     },
                        // ]
                        let sourceHtml = HT.renderPromotionSource(sourceData)
                        // console.log(sourceHtml)
                        _this.parents('.ibox-content').append(sourceHtml)
                        HT.promotionMultipleSelect2()
                    },
                })
            }
        })
    }

    // P2.2 V96 hàm render giao diện ra select2 dạng multiple cho chọn nguồn khách hàng áp dụng
    HT.renderPromotionSource = (sourceData) => {
        let wrapper = $('<div>').addClass('source-wrapper')
        if(sourceData.length){
            let select = $('<select>').addClass('multipleSelect2').attr('name', 'sourceValue[]').attr('multiple', true)

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
                let applyData = JSON.parse($('.applyValueList').val())
                // console.log(applyData)
                
                // let applyData = [
                //     {
                //         id: 'staff_take_care_customer',
                //         name: 'Nhân viên phụ trách'
                //     },
                //     {
                //         id: 'customer_group',
                //         name: 'Nhóm khách hàng'
                //     },
                //     {
                //         id: 'customer_gender',
                //         name: 'Giới tính'
                //     },
                //     {
                //         id: 'customer_birthday',
                //         name: 'Ngày sinh'
                //     },
                // ]
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
            let select = $('<select>').addClass('multipleSelect2 conditionItem').attr('name', 'applyValue[]').attr('multiple', true)

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

    // P3.5 V96 V110 Hàm đỗ ra giao diện select2 dạng multiple cấp 2 tương ứng theo từng conditionItem đã chọn (select.conditionItem) cấp 1
    HT.createConditionItem = (value, label) => {
        if(!$('.wrapper-condition').find('.'+value).elExist()){
            
            let option = {
                value: value,
            }
            $.ajax({
                url: 'ajax/dashboard/getPromotionConditionValue',
                type: 'GET',
                data: option,
                dataType: 'json',
                success: function(res){
                    console.log(res)
                    let optionData = res.data
                    // let optionData = [
                    //     {
                    //         id: 1,
                    //         name: 'Khach Vip'
                    //     },
                    //     {
                    //         id: 2,
                    //         name: 'Khách bán buôn'
                    //     }
                    // ]

                    let conditionItem = $('<div>').addClass('wrapperConditionItem mt10 '+value)
                    let select = $('<select>').addClass('multipleSelect2 objectItem').attr('name', value + "[]").attr('multiple', true)

                    // V113 save form objectItem - 1
                    let conditionHiddenInput = $('.condition_input_'+value) // from file blade
                    let conditionHiddenInputValue = []
                    if(conditionHiddenInput.length){
                        conditionHiddenInputValue = JSON.parse(conditionHiddenInput.val())
                    }

                    for(let i = 0; i < optionData.length; i++){
                        let option = $('<option>').attr('value', optionData[i].id).text(optionData[i].text)
                        select.append(option)
                    }

                    // V113 save form objectItem - 2
                    select.val(conditionHiddenInputValue).trigger('change')//

                    const conditionLabel = HT.createConditionLabel(label, value)
                    conditionItem.append(conditionLabel)
                    conditionItem.append(select)
            
                   
                    $('.wrapper-condition').append(conditionItem)
                    HT.promotionMultipleSelect2()
                },
            })
        }

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

        // V114 save form .promotionMethod 'product_and_quantity'
        let method = JSON.parse($('.preload_promotionMethod').val())
        console.log(method)
        if(method !== null){
            $('.promotionMethod').val(method).trigger('change')
        }
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
        // V114 save form promotion method order_amount_range
        let order_amount_range = JSON.parse($('.input_order_amount_range').val()) ?? {
            amountFrom: ['0'],
            amountTo: ['0'],
            amountValue: ['0'],
            amountType: ['default'],
        }
        console.log(order_amount_range)
        
        let $tr = ''

        ranges = []

        console.log(ranges)
        
        for(let i = 0; i < order_amount_range.amountFrom.length; i++){
            let $amountFrom = order_amount_range.amountFrom[i]
            let $amountTo = order_amount_range.amountTo[i]
            let $amountValue = order_amount_range.amountValue[i]
            let $amountType = order_amount_range.amountType[i]

            // Bỏ qua lần lặp cuối cùng và nếu độ dài lớn hơn hoặc bằng 2 thì mới thêm vào mảng ranges
            if (order_amount_range.amountFrom.length >= 1 && i !== order_amount_range.amountFrom.length - 1) {
                ranges.push({
                    newFrom: $amountFrom,
                    newTo: $amountTo,
                    position: i,
                });
            }

            // Kiểm tra nếu không phải là lần lặp cuối cùng thì thêm thuộc tính readonly
            let readonlyFrom = ($amountFrom != '0' || ($amountFrom == '0' && $amountTo != '0')) ? 'readonly' : '';
            let readonlyTo =  ($amountTo != '0') ? 'readonly' : '';

            $tr += `
            <tr class="tr">
                <td class="order-amount-range-from">
                    <input type="text" class="form-control int" name="promotion_order_amount_range[amountFrom][]" placeholder="0" value="${$amountFrom}" ${ readonlyFrom }>
                </td>
                <td class="order-amount-range-to">
                    <input type="text" class="form-control int" name="promotion_order_amount_range[amountTo][]" placeholder="0" value="${$amountTo}" ${ readonlyTo }>
                </td>
                <td class="discountType">
                    <div class="uk-flex uk-flex-middle">
                        <input type="text" class="form-control int" name="promotion_order_amount_range[amountValue][]" placeholder="0" value="${$amountValue}">
                        <select name="promotion_order_amount_range[amountType][]" class="setupSelect2">
                            <option ${ ($amountType == 'cash' || $amountType == 'default') ? 'selected' : '' } value="cash">đ</option>
                            <option ${ ($amountType == 'percent') ? 'selected' : '' } value="percent">%</option>
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
            `
        }
        
        
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
                        ${$tr}
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
                {class: 'order-amount-range-from td-range', name: 'promotion_order_amount_range[amountFrom][]', value: 0},
                {class: 'order-amount-range-to td-range', name: 'promotion_order_amount_range[amountTo][]', value: 0},
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
                            name: 'promotion_order_amount_range[amountValue][]',
                            class: 'form-control int',
                            placeholder: 0,
                            value: 0
                        })
                    )
                    .append(
                        $('<select>', {
                            class: 'setupSelect2',
                            name: 'promotion_order_amount_range[amountType][]',
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

    // V101 hàm render giao diện của hình thức khuyến mãi chiết khấu theo sản phẩm và số lượng (product_and_quantity)
    HT.renderProductAndQuantity = () => {
        let selectData = JSON.parse($('.input-product-and-quantity').val())
        // console.log(selectData)
        let selectHtml = ''
        let moduleType = JSON.parse($('.preload_select-product-and-quantity').val()) // V114 save form .select-product-and-quantity
        console.log(moduleType)
        
        for(let key in selectData){
            selectHtml += '<option '+((moduleType !== null && moduleType == key) ? 'selected' : '')+' value="'+key+'">'+selectData[key]+'</option>'
        }

        // V115 save form 4 columns condition end for product_and_quantity method
        let preloadData = JSON.parse($('.input_product_and_quantity').val()) ?? {
            quantity: ['1'],
            maxDiscountValue: ['0'],
            discountValue: ['0'],
            discountType: ['cash'],
        }

        let html = `
            <div class="product-and-quantity">
                <div class="choose-module mt20">
                    <div class="fix-label" style="color:blue" for="">Sản phẩm áp dụng</div>
                    <select name="chooseModule" id="" class="setupSelect2 select-product-and-quantity">
                        ${selectHtml}
                    </select>
                </div>
                <table class="table table-striped mt20">
                    <thead>
                        <tr>
                            <th>Sản phẩm mua</th>
                            <th style="width: 90px;">SL tối thiểu</th>
                            <th>Giới hạn KM</th>
                            <th class="text-right">Chiết khấu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr">
                            <td class="chooseProductPromotionTd">
                                <div class="product-quantity" data-toggle="modal" data-target="#findProduct">
                                    <div class="boxWrapper">
                                        <div class="boxSearchIcon">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        
                                        <div class="boxSearchInput fixGrid6">
                                            <p>Tìm theo tên, mã sản phẩm</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control int" name="product_and_quantity[quantity]" placeholder="0" value="${preloadData.quantity}">
                            </td>
                            <td class="order-amount-range-to">
                                <input type="text" class="form-control int" name="product_and_quantity[maxDiscountValue]" placeholder="0" value="${preloadData.maxDiscountValue}">
                            </td>
                            <td class="discountType">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="text" class="form-control int" name="product_and_quantity[discountValue]" placeholder="0" value="${preloadData.discountValue}">
                                    <select name="product_and_quantity[discountType]" class="setupSelect2">
                                        <option ${ (preloadData.discountType == 'cash') ? 'selected' : '' } value="cash">đ</option>
                                        <option ${ (preloadData.discountType == 'percent') ? 'selected' : '' } value="percent">%</option>
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
        // HT.setupAjaxSearch()
    }

    // // V101 không dùng tìm kiếm theo tên sản phẩm (đã bõ)
    // HT.setupAjaxSearch = () => {
    //     $('.ajaxSearch').each(function(){
    //         let _this = $(this)
    //         let option = {
    //             model: _this.attr('data-model'),
    //         }
    //         _this.select2({
    //             minimumInputLength: 2,
    //             placeholder: 'Nhập vào 2 từ để tìm kiếm...',
    //             ajax: {
    //                 url: 'ajax/dashboard/getPromotionObject',
    //                 type: 'GET',
    //                 dataType: 'json',
    //                 delay: 250,
    //                 data: function (params){
    //                     return{
    //                         search: params.term,
    //                         option: option,
    //                     }
    //                 },
    //                 processResults: function(data){
    //                     // console.log(data)
    //                     return {
    //                         results: $.map(data, function(obj, i){
    //                             return obj
    //                         })
    //                     }
    //                 },
    //                 cache: true
    //             }
    //         })
    //     })
    // }

    // V104 106 107 mở popup chọn sản phẩm khuyến mãi và kiểm tra xem sản phẩm, loại sản phẩm đã được checked chưa
    HT.productQuantityListProduct = () => {
        $(document).on('click', '.product-quantity', function(e){
            e.preventDefault()

            // xây dựng lại mảng objectList dựa vào giao diện fixGrid6
            objectList = []
            $('.fixGrid6').each(function() {
                let classList = $(this).attr('class').split(/\s+/);
                let classBox = classList.find(cls => cls.startsWith($('.select-product-and-quantity').val() + '-'));
                if (classBox) {
                    let parts = classBox.split('-');

                    let product_id = parts[1];
                    let product_variant_id = parts[2] || null;
                    let name = $(this).find('.goods-item-name').attr('title');
                    let variant_name = $(this).find('.goods-item-name').data('variantname') || null;

                    objectList.push({
                        product_id: product_id,
                        product_variant_id: product_variant_id,
                        name: name,
                        variant_name: variant_name
                    });
                }
            });

            let option = {
                model: $('.select-product-and-quantity').val(),
            }
            HT.loadProduct(option, objectList)
            
        })
    }
    
    // V104 106 107 hàm gọi ajax lấy dữ liệu sản phẩm, loại sản phẩm khuyến mãi
    HT.loadProduct = (option, objectList) => {
        $.ajax({
            url: 'ajax/product/loadProductPromotion',
            type: 'GET',
            data: option,
            dataType: 'json',
            success: function(res){
                // console.log(res)
                HT.fillToObjectList(res, objectList)
            },
        })
    }
    
    // V104 106 107 hàm xử lý dữ liệu trả về từ ajax theo model của select option .select-product-and-quantity
    HT.fillToObjectList = (data, objectList) =>{
        switch (data.model){
            case "Product":
                HT.fillProductToList(data.objects, objectList)
                console.log("fillProductToList")
                break
            case "ProductCatalogue":
                HT.fillProductCatalogueToList(data.objects, objectList)
                console.log("fillProductCatalogueToList")
                break
        }
    }

    // V104 V106 Hiển thị sản phẩm khuyến mãi khi mở popup chạy ajax lấy dữ liệu và kiểm tra xem sản phẩm nào đã được checked chưa
    HT.fillProductToList = (objects, objectList) => {
        // console.log(objects)
        console.log(objectList)
        let html = ''
        if(objects.data.length){
            for(let i = 0; i < objects.data.length; i++){
                let image = objects.data[i].image ?? 'Backend/img/not-found.png'
                let name = objects.data[i].name
                let variant_name = objects.data[i].variant_name ?? null
                let product_variant_id = objects.data[i].product_variant_id ?? null
                let product_id = objects.data[i].id
                let inventory = objects.data[i].inventory ? objects.data[i].inventory : '0'
                let couldSale = objects.data[i].couldSale ? objects.data[i].couldSale : '0'
                let sku = objects.data[i].sku
                let price = objects.data[i].price

                let classBox = ''
                let model = $('.select-product-and-quantity').val()

                if(objectList.length){
                    for(let j = 0; j < objectList.length; j++){
                        if(objectList[j].product_id == product_id){
                            classBox = model +'-'+ product_id
                            console.log(objectList[j].product_variant_id)
                            if(objectList[j].product_variant_id == product_variant_id){
                                classBox += '-'+ product_variant_id
                                console.log(classBox)
                                break
                            }
                        }
                    }
                }

                // xử lý giao diện fixGrid6 item
                // $('.fixGrid6').each(function() {
                //     let classList = $(this).attr('class').split(/\s+/);
                //     let classBoxCandidate = classList.find(cls => cls.startsWith(model + '-'));
                //     if (classBoxCandidate) {
                //         let parts = classBoxCandidate.split('-');
                //         let product_id_candidate = parts[1];
                //         let product_variant_id_candidate = parts[2] || null;
                
                //         if (product_id_candidate == product_id && product_variant_id_candidate == product_variant_id) {
                //             classBox = classBoxCandidate;
                //             console.log(classBox);
                //             return false; // break the loop
                //         }
                //     }
                // });

                // xử lý mảng objectList
                if (!objectList) {
                    objectList = [];
                }
                let existsInObjectList = objectList.some(item => 
                    item.product_id == product_id && (item.product_variant_id == product_variant_id || item.product_variant_id == null)
                );
                
                // kiểm tra xem sản phẩm đã được checked chưa
                let isChecked = ''
                if(classBox != ''){ // kiểm tra xem sản phẩm đã được checked chưa so với giao diện
                    let existsInDOM = $(`.boxWrapper .${classBox}`).length > 0;
                    isChecked = (existsInDOM || existsInObjectList) ? 'checked' : ''
                } else { // kiểm tra xem sản phẩm đã được checked chưa tại những trang khác khi di chuyển qua lại các trang
                    isChecked = existsInObjectList ? 'checked' : ''
                }

                html += `
                    <div class="search-object-item" data-productId = "${product_id}" data-productVariantId = "${product_variant_id}" data-name = "${name}" data-variantName = "${variant_name}">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="object-info">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="checkbox" name="" value="${product_id}" class="input-checkbox" ${isChecked}>
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

    // V107 Hiển thị loại sản phẩm khuyến mãi khi mở popup chạy ajax lấy dữ liệu và kiểm tra xem loại sản phẩm nào đã được checked chưa
    HT.fillProductCatalogueToList = (objects, objectList) => {
        // console.log(objects)
        console.log(objectList)
        let html = ''
        if(objects.data.length){
            for(let i = 0; i < objects.data.length; i++){
                let image = objects.data[i].image ?? 'Backend/img/not-found.png'
                let name = objects.data[i].name
                let product_id = objects.data[i].id

                let classBox = ''
                let model = $('.select-product-and-quantity').val()

                if(objectList.length){
                    for(let j = 0; j < objectList.length; j++){
                        if(objectList[j].product_id == product_id){
                            classBox = model +'-'+ product_id
                            break
                        }
                    }
                }
                
                // xử lý mảng objectList
                if (!objectList) {
                    objectList = [];
                }
                let existsInObjectList = objectList.some(item => 
                    item.product_id == product_id
                );
                
                // kiểm tra xem loại sản phẩm đã được checked chưa
                let isChecked = ''
                if(classBox != ''){ // kiểm tra xem loại sản phẩm đã được checked chưa so với giao diện
                    let existsInDOM = $(`.boxWrapper .${classBox}`).length > 0;
                    isChecked = (existsInDOM || existsInObjectList) ? 'checked' : ''
                } else { // kiểm tra xem loại sản phẩm đã được checked chưa tại những trang khác khi di chuyển qua lại các trang
                    isChecked = existsInObjectList ? 'checked' : ''
                }

                html += `
                    <div class="search-object-item" data-productId = "${product_id}" data-name = "${name}">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="object-info">
                                <div class="uk-flex uk-flex-middle">
                                    <input type="checkbox" name="" value="${product_id}" class="input-checkbox" ${isChecked}>
                                    <span class="image img-scaledown">
                                        <img src="${image}" alt="">
                                    </span>
                                    <div class="object-name">
                                        <div class="m0">${name}</div>
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

    // V107 hàm chọn kiểu sản phẩm khuyến mãi
    HT.selectProductAndQuantity = () => {
        $(document).on('change', '.select-product-and-quantity', function(){
            $('.search-model').val('')
            let _this = $(this)
            objectList = []
            $('.boxWrapper').html('')
            $('.boxWrapper')
            .append(
                $('<div>', {
                    class: 'boxSearchIcon'
                }).append(
                    $('<i>', {
                        class: 'fa fa-search'
                    })
                )
            ).append(
                $('<div>', {
                    class: 'boxSearchInput fixGrid6'
                }).append(
                    $('<p>').text('Tìm theo tên, mã sản phẩm')
                )
            )

            // cách 2 viết append gọn hơn
            // $('.boxWrapper').append('<div class="boxSearchIcon"><i class="fa fa-search"></i></div><div class="boxSearchInput fixGrid6"><p>Tìm theo tên, mã sản phẩm</p></div>')
        })
    }

    // V104 hiển thị giao diện phân trang
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

    // V104 xử lý đổ dữ liệu khi chọn phân trang
    HT.getPaginationMenu = () => {
        $(document).on('click', '.page-link', function(e){
            e.preventDefault()
            let _this = $(this)
            let option = {
                model: $('.select-product-and-quantity').val(),
                page: _this.text(),
                keyword: $('.search-model').val(),
            }
            HT.loadProduct(option, objectList)
        })
    }

    // V105 hàm tìm kiếm sản phẩm
    HT.searchModel = () =>{
        let typingTimer;
        let doneTypingInterval = 1000; //1s
        $(document).on('keyup', '.search-model', function(){
            // console.log(123)
            let _this = $(this)
            let keyword = _this.val()
            let option = {
                model: $('.select-product-and-quantity').val(),
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

                HT.loadProduct(option, objectList)
            }, doneTypingInterval)
             
        })
    }

    var objectList = []
    
    // V106 click chọn các sản phẩm khuyến mãi popup-item
    HT.chooseProductPromotion = () => {
        $(document).on('click', '.search-object-item', function(e){
            // e.preventDefault()
            let _this = $(this)
            let isChecked = _this.find('.input-checkbox').prop('checked')
            console.log(isChecked)
            let objectItem = {
                product_id: _this.data('productid'),
                product_variant_id: _this.data('productvariantid'),
                name: _this.data('name'),
                variant_name: _this.data('variantname'),
            }
            if(isChecked){
                console.log(objectList);
                console.log(objectItem);
                if(objectItem.product_variant_id != null){
                    objectList = objectList.filter(item => 
                        !(item.product_id == objectItem.product_id && item.product_variant_id == objectItem.product_variant_id)
                    );
                } else {
                    objectList = objectList.filter(item => 
                        !(item.product_id == objectItem.product_id && item.product_variant_id == null)
                    );
                }
                _this.find('.input-checkbox').prop('checked', false);
                console.log(objectList);
            }else{
                objectList.push(objectItem)
                _this.find('.input-checkbox').prop('checked', true)
                console.log(objectList);
            }
        })
    }

    // V106 Xác nhận sản phẩm đã chọn từ popup chọn sản phẩm khuyến mãi
    HT.confirmProductPromotion = () => {

        // V115 save form objectItem for product_and_quantity method
        let preloadObject = JSON.parse($('.input_object').val())
        // console.log(preloadObject)
        if(preloadObject != null){
            
            for(let i = 0; i < preloadObject.id.length; i++){
                objectList.push({
                    product_id: preloadObject.id[i],
                    product_variant_id: preloadObject.product_variant_id[i] ?? null,
                    name: preloadObject.name[i]
                })
            }

            let model = $('.select-product-and-quantity').val()
            let html = ''
            // Hiển thị lại những sản phẩm (div fixGrid6) ra giao diện đã chọn từ objectList ở popup (save form)
            if(objectList.length){
                for(let i = 0; i<objectList.length; i++){
                    let product_id = objectList[i].product_id
                    let product_variant_id = (objectList[i].product_variant_id == 'null') ? null : objectList[i].product_variant_id
                    let name = objectList[i].variant_name ?? objectList[i].name

                    let classBox = model +'-'+ product_id

                    if(product_variant_id){
                        classBox += '-'+ product_variant_id
                    }

                    if(!$(`.boxWrapper .${classBox}`).elExist()){
                        html+=`
                            <div class="fixGrid6 ${classBox}">
                                <div class="goods-item" >
                                    <a class="goods-item-name" title="${name}">${name}</a>
                                    <button class="delete-goods-item">
                                        <img src="Backend/img/close.png" alt="">
                                    </button>
                                </div>
                                <div class="hidden">
                                    <input type="hidden" name="object[id][]" value="${product_id}">
                                    <input type="hidden" name="object[product_variant_id][]" value="${product_variant_id}">
                                    <input type="hidden" name="object[name][]" value="${name}">
                                </div>
                            </div>
                        `
                    }
                }
            }
            HT.checkFixGrid6(html, objectList)
            
            console.log(objectList)
        }

        $(document).on('click', '.confirm-product-promotion', function(e){
            e.preventDefault()
            console.log(objectList)
            
            let model = $('.select-product-and-quantity').val()

            // xóa đi những sản phẩm (div fixGrid6) không còn trong objectList
            $('.fixGrid6').each(function() {
                let classList = $(this).attr('class').split(/\s+/);
                let classBox = classList.find(cls => cls.startsWith(model + '-'));
                // console.log(classBox)
                if (classBox) {
                    let parts = classBox.split('-');
                    let product_id = parts[1];
                    let product_variant_id = parts[2] || null;

                    // Những item nào ở objectList mà không còn tìm thấy tương ứng ở div fixGrid6 giao diện tương ứng thì tiến hành xóa div đó đi
                    let existsInObjectList = objectList.some(item => 
                        item.product_id == product_id && (item.product_variant_id == product_variant_id || item.product_variant_id == null)
                    );

                    if (!existsInObjectList) {
                        $(this).remove();
                    }
                }
            });
            
            let html = ''
            
            // Hiển thị lại những sản phẩm (div fixGrid6) ra giao diện đã chọn từ objectList ở popup
            if(objectList.length){
                for(let i = 0; i<objectList.length; i++){
                    let product_id = objectList[i].product_id
                    let product_variant_id = objectList[i].product_variant_id ?? null
                    let name = objectList[i].variant_name ?? objectList[i].name

                    let classBox = model +'-'+ product_id

                    if(product_variant_id){
                        classBox += '-'+ product_variant_id
                    }

                    if(!$(`.boxWrapper .${classBox}`).elExist()){
                        html+=`
                            <div class="fixGrid6 ${classBox}">
                                <div class="goods-item" >
                                    <a class="goods-item-name" title="${name}">${name}</a>
                                    <button class="delete-goods-item">
                                        <img src="Backend/img/close.png" alt="">
                                    </button>
                                </div>
                                <div class="hidden">
                                    <input type="hidden" name="object[id][]" value="${product_id}">
                                    <input type="hidden" name="object[product_variant_id][]" value="${product_variant_id}">
                                    <input type="hidden" name="object[name][]" value="${name}">
                                </div>
                            </div>
                        `
                    }
                    
                }
            }
            HT.checkFixGrid6(html, objectList)
        })
    }

    HT.checkFixGrid6 = (html, objectList) => {
        if(objectList.length > 0){
            $('.boxSearchIcon').remove();
            $('.boxWrapper').prepend(html);
        }else{
            $('.boxWrapper').prepend(HT.boxSearchIcon());
        }
    }
    
    HT.boxSearchIcon = () => {
        return `
                <div class="boxSearchIcon">
                    <i class="fa fa-search"></i>
                </div>
                `
    }

    // V106 Xây dựng hàm xóa sản phẩm khỏi danh sách
    HT.deleteGoodsItem = () => {
        $(document).on('click', '.delete-goods-item', function(e){
            e.stopPropagation(); // ngăn không cho sự kiện click của div cha nó xảy ra

            let _this = $(this)
            // let deleted_item = _this.closest('.fixGrid6').attr('class').split(' ')[1]
            _this.closest('.fixGrid6').remove()
            console.log('fixGrid6 length:', $('.boxWrapper .fixGrid6').length);
            if($('.boxWrapper .fixGrid6').length == 1){
                console.log('Prepending HT.boxSearchIcon');
                $('.boxWrapper').prepend(HT.boxSearchIcon());
            }
            
            // let parts = deleted_item.split('-');
            // let product_id = parts[1];
            // let product_variant_id = parts[2] || null;
    
            // // xóa phần tử khỏi objectList
            // objectList = objectList.filter(item => 
            //     !(item.product_id == product_id && item.product_variant_id == product_variant_id)
            // );
            // console.log(objectList)
        })
    }

    // V106 Đóng popup chọn sản phẩm khuyến mãi
    HT.closeProductPromotion = () => {
        $(document).on('click', '.close-product-promotion', function(){
            objectList = []
        })
    }

    // V113 save form select condition lv.1
    HT.checkConditionItemSet = () => {
        let checkedValue = $('.conditionItemSelected').val()
        if (Array.isArray(checkedValue) && checkedValue.length > 0) {
            checkedValue = JSON.parse(checkedValue)
            console.log(checkedValue)
            HT.chooseApplyItem()
            $('.conditionItem').val(checkedValue).trigger('change')
        }
    }

    // V114 bắt request cho phần method order_amount_range
    HT.btnSubmitPromotion = () => {
        $(document).on('click', '.btn-submit-promotion', function(e){
            // V114 Bắt request cho order_amount_range method
            let trLastChild = $('.order-amount-range').find('tbody tr:last-child');
            if(trLastChild.length){
                let from = parseInt(trLastChild.find('.order-amount-range-from input').val().replace(/\./g, ''));
                let to = parseInt(trLastChild.find('.order-amount-range-to input').val().replace(/\./g, ''));
                let currentPosition = trLastChild.data('position') || null;

                if (!HT.isValidRange(from, to)) {
                    e.preventDefault();
                    alert('Khoảng điều kiện không hợp lệ, giá trị tới phải lớn hơn giá trị từ');
                    return;
                }

                let conflictResult = HT.checkBtnJs100ConflictRange(from, to, currentPosition);
                if (conflictResult.conflict) {
                    e.preventDefault();
                    trLastChild.addClass('errorLine');
                    alert(`Có xung đột với khoảng điều kiện tại vị trí: ${conflictResult.position}`);
                    return;
                }
            }

            // V115 Bắt request cho product_and_quantity method
            if ($('.boxWrapper').length && $('.boxWrapper').find('.boxSearchIcon').length) {
                e.preventDefault();
                alert(`Vui lòng chọn ít nhất một sản phẩm cho phương thức khuyến mại theo từng sản phẩm`);
                return;
            }
        });
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

        // V105
        HT.searchModel()

        // V106
        HT.chooseProductPromotion()
        HT.confirmProductPromotion()
        HT.deleteGoodsItem()
        HT.closeProductPromotion()
        HT.selectProductAndQuantity()

        // V113
        HT.checkConditionItemSet()

        //V114
        HT.btnSubmitPromotion()
    })

})(jQuery)