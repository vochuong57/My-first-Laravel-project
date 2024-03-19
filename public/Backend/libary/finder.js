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
                HT.ckeditor4(elementId)
            })
        }
    }

    HT.ckeditor4=(elementId)=>{
        CKEDITOR.replace( elementId, {
            height:250,
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

    $document.ready(function(){
        HT.uploadImageToInput();
        HT.setupCkeditor();
    })

})(jQuery)