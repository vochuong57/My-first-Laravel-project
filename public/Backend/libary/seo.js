(function($){

    var $document = $(document)
    var HT={};

    HT.seoPreview=()=>{
        $('input[name=meta_title]').on('keyup', function(){
            //console.log(123)
            let input = $(this)
            let value = input.val()
            $('.meta-title').html(value)
        })

        //tính độ rộng của .baseUrl 161.984375
        //console.log($('.baseUrl').outerWidth())

        $('input[name=canonical]').css({
            'padding-left': parseInt($('.baseUrl').outerWidth())
        })

        $('input[name=canonical]').on('keyup', function(){
            //console.log(123)
            let input = $(this)
            let value = input.val()
            $('.canonical').html(BASE_URL + value + SUFFIX)
        })

        $('textarea[name=meta_description]').on('keyup', function(){
            //console.log(123)
            let input = $(this)
            let value = input.val()
            $('.meta-description').html(value)
        })
    }

    $document.ready(function(){
        HT.seoPreview()
    })

})(jQuery)