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

    $(document).ready(function(){
        // V96
        HT.promotionNeverDate()
        HT.promotionSource()
        HT.promotionMultipleSelect2()
        HT.chooseCustomerCondition()
        HT.deleteCondition()
    })

})(jQuery)