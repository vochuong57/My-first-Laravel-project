(function($){

    var $document = $(document)
    var HT={};

    //cài đặt ckfinder 2
    HT.uploadImageToInput=()=>{
        $(document).on('click','.upload-image',function(){
            let input = $(this);
            let type=input.attr('data-type');
            HT.setupCkFinder2(input,type);
        })
    }

    HT.setupCkFinder2=(object, type)=>{
        if(typeof(type)=='undefined'){
            type='Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data){
            object.val(fileUrl);
        }
        finder.popup();
    }

    //cài đặt ckeditor 4
    HT.setupCkeditor=()=>{
        if($('.ck-editor')){
            $('.ck-editor').each(function(){
                let editor=$(this)
                let elementId = editor.attr('id')
                let elementHeight = editor.attr('data-height')
                HT.ckeditor4(elementId,elementHeight)
            })
        }
    }

    HT.ckeditor4=(elementId, elementHeight)=>{
        if(typeof(elementHeight)=='undefined'){
            elementHeight=500;
        }
        CKEDITOR.replace( elementId, {
            height: elementHeight,
            removeButtons: '',
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: 'clipboard',    groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',  groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'colors' },
                { name: 'others' },
                '/',
                { name: 'basicstyles',    groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',    groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' }
            ],
        });
    }

    //xấy dựng upload ảnh ảnh đại diện cho postCatalogues
    HT.uploadImageAvatar=()=>{
        $('.image-target').click(function(){
            let input=$(this)
            let type = 'Images'
            HT.browseServerAvatar(input,type)
        })
    }

    HT.browseServerAvatar=(object, type)=>{
        if(typeof(type)=='undefined'){
            type='Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data){
            object.find('img').attr('src', fileUrl)
            object.siblings('input').val(fileUrl)
        }
        finder.popup();
    }

    $document.ready(function(){
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.uploadImageAvatar();
    })

})(jQuery)