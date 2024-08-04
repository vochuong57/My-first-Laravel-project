(function($){
    var HT={};
    var _token = $('meta[name="csrf-token"]').attr('content')

    // V62 Tiến hành tạo chức năng xây dựng vị trí hiển thị menu mỗi lần submit form
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

    $(document).ready(function(){
        // V62 Tiến hành tạo chức năng xây dựng vị trí hiển thị menu mỗi lần submit form
        HT.createMenuCatalogue()
    })

})(jQuery)