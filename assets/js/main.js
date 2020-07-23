document.addEventListener("DOMContentLoaded", function(){

    document.body.removeAttribute("class");

    document.querySelectorAll("a[href='"+location.href.replace(_url_,"")+"']").forEach((e) => {
        e.childNodes.length === 1 ? e.style.borderBottom = "1px solid black" : null;
    });
    
    document.querySelector(".menuBtn").addEventListener("click", function(){
        if(window.innerWidth <= 850){
            this.classList.toggle("open");
            document.querySelector(".navContainer").classList.toggle("show");
        }
    });

    document.querySelector(".profileBtn") && document.querySelector(".profileBtn").addEventListener("click", function(){
        this.classList.toggle("open");
    });

    document.getElementById("comment") && document.getElementById("comment").addEventListener("input", function(){
        this.style.height = 'inherit';

        var computed = window.getComputedStyle(this);

        var height = parseInt(computed.getPropertyValue('border-top-width'), 10)
                    //+ parseInt(computed.getPropertyValue('padding-top'), 10)
                    + this.scrollHeight
                    //+ parseInt(computed.getPropertyValue('padding-bottom'), 10)
                    + parseInt(computed.getPropertyValue('border-bottom-width'), 10);

        this.style.height = height + 'px';
    });

    document.getElementById("makeComment") && document.getElementById("makeComment").addEventListener("click", function(){
        let comment = document.getElementById("comment").value.trim();
        if(comment){
            let commToken = document.getElementById("_comtoken");
            let data = {
                "action":"addComment",
                "articleUrl":location.href.substring(location.href.lastIndexOf('/') + 1),
                "commentContent":comment,
                "token":commToken && commToken.value || false,
            };
            xhrData("/articleControl", data, function(rsp){
                try{
                    let jRsp = JSON.parse(rsp);
                    if(jRsp.status){
                        if(jRsp.status === "failed"){
                            console.log(jRsp);
                            alert("An Error occured while adding the comment");
                        }else if(jRsp.status === "success"){
                            location.reload();
                        }
                    }
                }catch(e){
                    console.log("Error, ",rsp);
                }
            });
        }
    });

    document.addEventListener("click", function(e){
        if(e.target.parentElement.className === "deleteComment"){
            if(!confirm("Are you sure you want to delete this comment ?")){
                return;
            }
            let id = e.target.parentElement.getAttribute("data-id");
            id = Number(id);
            if(!id) return;
            let commToken = document.getElementById("_comtoken");
            let data = {
                "action":"deleteComment",
                "commentId":id,
                "articleUrl":location.href.substring(location.href.lastIndexOf('/') + 1),
                "token":commToken && commToken.value || false
            };
            xhrData("/articleControl", data, function(rsp){
                try{
                    let jRsp = JSON.parse(rsp);
                    if(jRsp.status){
                        if(jRsp.status === "failed"){
                            console.log(jRsp);
                            alert("An Error occured while deleting the comment");
                        }else if(jRsp.status === "success"){
                            location.reload();
                        }
                    }else{
                        console.log("Error, ",rsp);
                    }
                }catch(e){
                    console.log("Error, ",rsp);
                }
            });
        }
    });

    document.querySelector(".login") && document.querySelector(".togglePassword").addEventListener("click",function(){
        document.querySelectorAll(".password").forEach(e=>{
            e.getAttribute("type") === "password" ? e.setAttribute("type","text") : e.setAttribute("type","password");
        });
        this.firstElementChild.className = this.firstElementChild.className === "las la-eye-slash" ? "las la-eye" : "las la-eye-slash";
    });

    document.querySelector("#login_form") && document.querySelector("#login_form").addEventListener("keyup", function(e){
        if(e.keyCode === 13){document.querySelector("#login_btn").click()}
    });

    document.querySelector("#register_form") && document.querySelector("#register_form").addEventListener("keyup", function(e){
        if(e.keyCode === 13){document.querySelector("#register_btn").click()}
    });

    document.querySelector("#login_btn") && document.querySelector("#login_btn").addEventListener("click", function(){
        let login_form = document.querySelector("#login_form");
        let data = {}, errors = [], errorListElm = document.querySelector(".formErrorList ul"), nameConvert = {"email":"E-Mail", "password":"Password"};
        for(let item of login_form.querySelectorAll("input")){
            data[item.name] = item.value;
            if(!item.value){
                errors.push(nameConvert[item.name] + " can't be empty");
            }
        }
        if(errors.length > 0){
            writeErrors(errorListElm, errors);
        }else{
            errorListElm.parentElement.classList.add("hidden");
            xhrData("login", data, function(rsp){
                try{
                    var jRsp = JSON.parse(rsp);
                    if(jRsp.status){
                        if(jRsp.error_only){
                            writeErrors(errorListElm, [jRsp.error_only]);
                            return;
                        }
                        if(jRsp.errors){
                            writeErrors(errorListElm, jRsp.errors);
                        }
                        if(jRsp.status === "success"){
                            location.href = "./";
                        }
                    }
                }catch(e){
                    console.log("Error, ",rsp);
                }
            });
        }
    });

    document.querySelector("#register_btn") && document.querySelector("#register_btn").addEventListener("click", function(){
        let register_form = document.querySelector("#register_form");
        let data = {}, errors = [], errorListElm = document.querySelector(".formErrorList ul"), nameConvert = {"name":"Name", "surname":"Surname", "username":"Username", "email":"E-Mail", "password": "Password", "password_again":"Password Again"};
        for(let item of register_form.querySelectorAll("input")){
            data[item.name] = item.value;
            if(!item.value){
                errors.push(nameConvert[item.name] + " can't be empty");
            }
        }

        if(data["password"] !== data["password_again"]){
            errors.push(nameConvert["password"] + "s must be equal");
        }

        if(errors.length > 0){
            writeErrors(errorListElm, errors);   
        }else{
            errorListElm.parentElement.classList.add("hidden");
            xhrData("register", data, function(rsp){
                try{
                    var jRsp = JSON.parse(rsp);
                    if(jRsp.status){
                        if(jRsp.error_only){
                            writeErrors(errorListElm, [jRsp.error_only]);
                            return;
                        }
                        if(jRsp.errors){
                            writeErrors(errorListElm, jRsp.errors);
                        }
                        if(jRsp.status === "success"){
                            location.href = "./";
                        }
                    }
                }catch(e){
                    console.log("Error, ",rsp);
                }
            });
        }
    });

    function writeErrors(errorContainer, errors){
        errorContainer.parentElement.classList.remove("hidden");
        var allerrs = "";
        for(var item of errors){
            allerrs += "<li>"+item+"</li>";
        }
        errorContainer.innerHTML = allerrs;
    }

    function xhrData(to, data, callback) {
        const xhr = new XMLHttpRequest();

        let urlEncodedData = "",
            urlEncodedDataPairs = [],
            name;

        for(name in data){
            urlEncodedDataPairs.push( encodeURIComponent( name ) + '=' + encodeURIComponent( data[name] ) );
        }

        urlEncodedData = urlEncodedDataPairs.join('&').replace( /%20/g, '+' );

        xhr.addEventListener("readystatechange", function(){
            if(this.readyState === 4 && this.status == 200){
                callback(xhr.response);
            }
        });

        xhr.addEventListener( 'error', function(event) {
            console.log("Hata");
        });

        xhr.open('POST', to);
        xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
        xhr.send(urlEncodedData);
    }

});