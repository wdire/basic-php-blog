<div class="main">
    <div class="mainContent">
        <h1 class="center">Contact</h1>
        <div class="contactForm">
            <form action="/sendContact" method="post">
                <div>
                    <div>Name</div>
                    <input name="name" type="text" minlength="3" maxlength="30" required>
                </div>
                <div>
                    <div>E-Mail</div>
                    <input name="email" type="email" minlength="4" maxlength="60" required>
                </div>
                <div>
                    <div>Subject</div>
                    <input name="subject" type="text" required minlength="3" maxlength="30">
                </div>
                <div>
                    <div>Message</div>
                    <textarea name="message" rows="5" required minlength="10" maxlength="1000"></textarea>

                </div>
                <div>
                    <input type="hidden" name="token" value="<?=$this->token?>">
                </div>
                <div>
                    <input type="submit" value="Send">
                </div>
            </form>
        </div>
    </div>
</div>