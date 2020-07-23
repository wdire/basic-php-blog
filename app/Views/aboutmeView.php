<div class="main">
    <div class="mainContent">
        <h1 class="center">About Me</h1>
        <p id="aboutText" style="font-family:consolas;font-size:18px"></p>
    </div>
    <script>
        (function(){
            let text = Array(11).join("Lorem ipsum, dolor sit amet consectetur adipisicing elit. Earum pariatur nulla reiciendis. Odio quasi fuga in adipisci quis nisi quam quod est dignissimos architecto, molestias eaque, iste ipsa ab consequuntur. "),
            index = 0, elm = document.getElementById("aboutText");
            const START_SPEED = 50;
            let speed = START_SPEED;
            function kalem(){
                if(index >= text.length){clearInterval(interval);return;};
                elm.innerHTML += text[index];
                if(index % 15 === 0){
                    elm.innerHTML += text[index+1];
                    speed = START_SPEED - Math.abs(Math.sin(index/10) * 30) | 0;
                    clearInterval(interval);
                    interval = setInterval(kalem,speed);
                    index+=2;
                }else index++;
                if(index % 500 === 0) elm.innerHTML += "<br><br>";
            }
            let interval = setInterval(kalem,speed);
        })();
    </script>
</div>