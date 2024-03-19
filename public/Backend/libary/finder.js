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

    //xấy dựng upload ảnh ảnh đại diện hiển thị vào div img cũng như input hidden (avatar) cho postCatalogues
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

    //xây dựng upload nhiều ảnh trong ckedior (textarea)
    HT.multipleUploadImageCkeditor=()=>{
        $('.multipleUploadImageCkeditor').click(function(e){
            e.preventDefault()
            let object=$(this)
            let target=object.attr('data-target')
            HT.browseServerCkeditor(object,'Images', target)
        })
    }

    HT.browseServerCkeditor=(object,type,target)=>{
        if(typeof(type)=='undefined'){
            type='Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data, allFiles){
            //console.log(allFiles)
            let html=''
            for(var i = 0; i<allFiles.length;i++){
                var image = allFiles[i].url
                
                html+='<div class="image-content"><figure>';
                    html+='<img src="'+image+'" alt="'+image+'"/>'
                    html+='<figcaption>Nhập vào mô tả cho ảnh</figcation>'
                html+='</figure></div>'
               
            }
            CKEDITOR.instances[target].insertHtml(html)
        }
        finder.popup();
    }

    //xây dụng upload album ảnh trong dashboard/component/album và delete album ảnh
    HT.uploadAlbum=()=>{
        $('.upload-picture').click(function(e){
            e.preventDefault()
            HT.browseServerAlbum();
        })
    }

    HT.browseServerAlbum=()=>{
        var type='Images';
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function(fileUrl, data, allFiles){
            //console.log(allFiles)
            let html=''
            for(var i = 0; i<allFiles.length;i++){
                var image = allFiles[i].url
                
                html+='<li class="ui-state-default">'
                    html+='<div class="thumb">'
                        html+='<span class="span image img-scaledown">'
                            html+='<img src="'+image+'" alt="'+image+'">'
                            html+='<input type="hidden" name="album[]" value="'+image+'">'
                        html+='</span>'
                        html+='<button class="delete-image"><i class="fa fa-trash"></i></button>'
                    html+='</div>'
                html+='</li>'
               
            }
            $('.click-to-upload').addClass('hidden')
            $('#sortable').append(html)
            $('.upload-list').removeClass('hidden')
        }
        finder.popup();
    }

    HT.deletePicture=()=>{
        $(document).on('click','.delete-image',function(){
            let _this=$(this)
            _this.parents('.ui-state-default').remove()
            if($('.ui-state-default').length == 0){
                $('.click-to-upload').removeClass('hidden')
                $('.upload-list').addClass('hidden')
            }
        })
    }

    $document.ready(function(){
        HT.uploadImageToInput();

        HT.setupCkeditor();

        HT.uploadImageAvatar();
        
        HT.multipleUploadImageCkeditor();

        HT.uploadAlbum();
        HT.deletePicture();
    })

})(jQuery)