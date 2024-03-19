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
            let value = HT.removeUtf8(input.val())
            $('.canonical').html(BASE_URL + value + SUFFIX)
        })

        $('textarea[name=meta_description]').on('keyup', function(){
            //console.log(123)
            let input = $(this)
            let value = input.val()
            $('.meta-description').html(value)
        })
    }

    HT.removeUtf8=(str)=>{
            str = str.toLowerCase();
            str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
            str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e' );
            str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i' );
            str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
            str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u' );
            str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)g/, 'y' );
            str = str.replace(/(đ)/g, 'd' );

            str = str.replace(/\s+/g, "-");
            str=str.replace(/^\-+|\-+$/g, "");
            return str;
        
    }

    $document.ready(function(){
        HT.seoPreview()
    })

})(jQuery)