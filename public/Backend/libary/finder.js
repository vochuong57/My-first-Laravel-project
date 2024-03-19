(function($){

    var $document = $(document)
    //lặp switchchery cho form chính quản lý user cho cột trạng thái
    var HT={};

    HT.inputImgae=()=>{
        $(document).on('click','.input-image',function(){
            let _this=$(this);
            let fileUpload=_this.data('data-upload');
            HT.BrownseServerInput($(this), fileUpload);
        })
    }

    HT.BrownseServerInput=(object, fileUpload)=>{
        if(typeof(fileUpload)=='undefined'){
            fileUpload='Images';
        }
        var finder = new CKFinder();
        finder.resourceType=fileUpload;
        finder.selectActionFunction=function(fileUrl, data){
            //console.log(fileUrl)
            file=fileUrl.replace(BASE_URL, "/");
            object.values(fileUrl)
        }
        finder.popup();
    }

    $document.ready(function(){
        HT.inputImgae()
    })

})(jQuery)