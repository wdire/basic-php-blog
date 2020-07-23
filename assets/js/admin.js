document.addEventListener("DOMContentLoaded", function () {

    document.querySelector(".deleteArticle") && document.addEventListener("click", function(e){
        if(e.target.classList.contains("deleteArticle")){
            if(!confirm("Makaleyi silmek istediğine emin misin ?")){
                e.preventDefault();
            }
        }
    });

    document.querySelector(".articleEditorContainer") && tinymce.init({
        selector: '#editor',
        image_uploadtab: true,
        image_upload_url: "managearticles/uploadImage",
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        plugins: [
            "advlist autolink link image lists charmap print preview hr pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table directionality emoticons paste code imagetools fullpage fullscreen autoresize",
        ],

        toolbar1: "undo redo | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | visualblocks pastetext charmap",
        toolbar2: "fontselect styleselect fontsizeselect | link unlink | image media | preview code | fullpage fullscreen | addElm",
        image_advtab: true,
        external_filemanager_path:_url_+"/app/Utils/filemanager/",
        filemanager_title:"Responsive Filemanager",
        external_plugins: { "filemanager" : _url_+"/app/Utils/filemanager/plugin.min.js"},
        relative_urls : true,
        remove_script_host : false,
        convert_urls : true,
        branding: false,
        min_height: 300,
        max_width:"100%",
        autoresize_bottom_margin: 30,
        resize:true,
        toolbar_sticky: true,
        font_formats:'System Font=-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats;',
        content_style:"img{max-width:100%;height:auto}",
        formats:{
            removeFormats:[
                {
                    selector: 'b,strong,em,i,font,u,strike,sub,sup,dfn,code,samp,kbd,var,cite,mark,q,del,ins',
                    remove: 'all',
                    split: true,
                    block_expand: true,
                    expand: false,
                    deep: true
                },
                { selector: 'span', attributes: ['style', 'class'], remove: 'empty', split: true, expand: false, deep: true },
                { selector: '*', attributes: ['style', 'class'], split: false, expand: false, deep: true }   
            ],
            underline: { inline: 'span', styles: { 'text-decoration': 'underline' }, exact: true },
            strikethrough: { inline: 'span', styles: { 'text-decoration': 'line-through' }, exact: true }
        },
        //extended_valid_elements:'p[class=test1 test2]',
        setup: function (ed) {
            ed.ui.registry.addButton('addElm', {
                text: 'Add Element',
                onAction: function() {
                    var text = ed.selection.getContent({
                        'format': 'html'
                    });
                    var element = prompt("Enter element:(Ex: div, img)","div") || false;
                    var style = prompt("Enter attributes:(Ex: style, class, id)", "") || false;
                    var html = "<"+element+ (style ? (" " + style + " ") : "") +">";
                    html += "</"+element+">";
                    element && ed.execCommand('mceInsertContent', false, html);
                }
            });
        },
        images_upload_handler: function (blobInfo, success, failure) {
            const xhr = new XMLHttpRequest();

            let formData;

            xhr.addEventListener("readystatechange", function () {
                if (this.readyState === 4 && this.status == 200) {
                    try{
                        let json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location !== "string") {
                            failure("Invalid JSON: " + xhr.responseText);
                            return;
                        }
                        
                        success(json.location);
                    }catch(e){
                        console.log(xhr.responseText);
                    }
                }
            });

            xhr.addEventListener('error', function (event) {
                failure("HTTP Error: " + xhr.status);
            });

            formData = new FormData();
            formData.append("file", blobInfo.blob(), blobInfo.filename());
            xhr.open('POST', "../uploadImage");
            xhr.send(formData);
        }
    });

});