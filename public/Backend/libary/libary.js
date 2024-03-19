(function($){

    var $document = $(document)
    //lặp switchchery
    var HT={};
    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394' });
        })
    }

    $document.ready(function(){
        console.log(123);
        //gọi function lặp switchery
        HT.switchery();
    })

})(jQuery)