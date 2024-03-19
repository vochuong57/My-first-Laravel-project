(function($){

    var $document = $(document)
    //lặp switchchery cho form chính quản lý user cho cột trạng thái
    var HT={};
    HT.switchery=()=>{
        $('.js-switch').each(function(){
            var switchery = new Switchery(this, { color: '#1AB394' });
        })
    }

    //Dùng trong form creat userv.v... để tạo bộ lọc cho select option
    HT.select2=()=>{
        $('.setupSelect2').select2();
    }

    $document.ready(function(){
        console.log(123);
        //gọi function lặp switchery
        HT.switchery();
        HT.select2();
    })

})(jQuery)