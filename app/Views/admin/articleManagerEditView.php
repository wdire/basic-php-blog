<div class="articleManage">
    <h1 class="center">Edit Article</h1>
    <div class="articleEditorContainer">
        <?php if(!empty($this->articleInfo["article_image"])){ ?>
        <div class="articleEditorImg">
            <img src="<?=$this->url?><?=$this->articleInfo["article_image"]?>">
        </div>
        <?php } ?>
        <div class="articleEditorInfo">
            <div>
                <div>Article Header</div>
                <textarea id="articleEditorTitle"><?=$this->articleInfo["article_title"]?></textarea>
            </div>
            <div>
                <div>Article Cover Photo</div>
                <input name="file" id="articleEditorThumb" accept="image/*" type="file">
            </div>
            <div>
                <button id="saveArticle">Save</button>
            </div>
        </div>
        <div id="editor">
        <?=htmlspecialchars_decode($this->articleInfo["article_content"])?>
        </div>
    </div>
    <script>
        document.getElementById("saveArticle").addEventListener("click", function(){
            tinymce.activeEditor.setProgressState(true);       
            setTimeout(function(){
                tinymce.activeEditor.setProgressState(false);
            },1000);
            let file = document.querySelector("#articleEditorThumb").files, allowedExtensions = ["png","jpg","gif","jpeg"],imageLocation = null;
            let articleEditorTitle = document.querySelector("#articleEditorTitle").value.trim(), withImg = false;
            if(!articleEditorTitle){
                alert("Başlık girin");
                return;
            }else if(articleEditorTitle.length > 200){
                alert("Başlık fazla uzun");
                return;
            }

            if(file.length > 1){
                if(!confirm("İlk seçtiğiniz " + file[0].name + " dosyasını kullanıyoruz")){
                    return;
                }
            }
            
            if(file.length > 0){
                file = file[0];

                if(allowedExtensions.indexOf(file.name.split('.').pop()) >= 0){
                    if(file && file.size < 10485760){
                        const xhr = new XMLHttpRequest();

                        let formData;
                        withImg = true;
                        xhr.addEventListener("readystatechange", function () {
                            if (this.readyState === 4 && this.status == 200) {
                                try{
                                    let json = JSON.parse(xhr.responseText);
                                    
                                    if(json.error){
                                        alert("Hata: "+json.error);
                                        return;
                                    }else if (!json || !json.location) {
                                        alert("Hata");
                                        return;
                                    }

                                    imageLocation = json.location;
                                    var editorContent = tinymce.activeEditor.getBody().innerHTML;
                                    var data = {
                                        "articleTitle":articleEditorTitle,
                                        "articleImage":imageLocation || 0,
                                        "articleContent":editorContent,
                                        "currArticleUrl":"<?=$this->articleInfo["article_url"]?>"
                                    }

                                    xhrData("/managearticles/edit", data, function(rsp){
                                        if(rsp === "equal"){
                                            alert("Aynı değerler")
                                        }
                                        else if(rsp === "success"){
                                            alert("Başarılı");
                                            location.reload();
                                        }else if(rsp === "error"){
                                            alert("Hata");
                                        }else{
                                            try{
                                                let response = JSON.parse(rsp);
                                                if(response.status === "success" && response.newUrl){
                                                    location.href = "<?=$this->url?>/managearticles/edit/"+response.newUrl;
                                                }
                                            }catch(e){
                                                console.log(rsp);
                                            }
                                        }
                                    });
                                    
                                }catch(e){
                                    console.log("Image upload error "+ e, xhr.responseText);
                                }
                            }
                        });

                        xhr.addEventListener('error', function (event) {
                            console.log("HTTP Error: " + xhr.status, event);
                        });
            
                        formData = new FormData();
                        formData.append("file", file);
                        xhr.open('POST', "/managearticles/uploadImage");
                        xhr.send(formData);
                    }else{
                        alert("Fotoğrafın boyutu çok fazla");
                    }
                }else{
                    alert("Dosya türü geçersiz");
                }
            }

            if(withImg === false){
                var editorContent = tinymce.activeEditor.getBody().innerHTML;
                var data = {
                    "articleTitle":articleEditorTitle,
                    "articleImage":imageLocation || "<?=$this->articleInfo["article_image"]?>",
                    "articleContent":editorContent,
                    "currArticleUrl":"<?=$this->articleInfo["article_url"]?>"
                }

                xhrData("<?=$this->url?>/managearticles/edit", data, function(rsp){
                    if(rsp === "equal"){
                        alert("Aynı değerler")
                    }
                    else if(rsp === "success"){
                        alert("Başarılı");
                        location.reload();
                    }else if(rsp === "error"){
                        alert("Hata");
                    }else{
                        try{
                            let response = JSON.parse(rsp);
                            if(response.status === "success" && response.newUrl){
                                location.href = "<?=$this->url?>/managearticles/edit/"+response.newUrl;
                            }
                        }catch(e){
                            console.log(rsp);
                        }
                    }
                });
            }
            
            function xhrData(to, data, callback) {
                const xhr = new XMLHttpRequest();

                let urlEncodedData = "",
                    urlEncodedDataPairs = [],
                    name;

                for (name in data) {
                    urlEncodedDataPairs.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[name]));
                }

                urlEncodedData = urlEncodedDataPairs.join('&').replace(/%20/g, '+');

                xhr.addEventListener("readystatechange", function () {
                    if (this.readyState === 4 && this.status == 200) {
                        callback(xhr.response);
                    }
                });

                xhr.addEventListener('error', function (event) {
                    console.log("Hata");
                });

                xhr.open('POST', to);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(urlEncodedData);
            }
        });
    </script>
</div>