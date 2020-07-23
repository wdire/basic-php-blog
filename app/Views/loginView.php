<div class="loginContainer">
    <div class="login" id="login_form">
        <h1>Login</h1>
        <div class="inputForm">
            <div class="formErrorList hidden">
                <ul>
                    
                </ul>
            </div>
        </div>
        <div class="inputForm">
            <input type="text" name="email" placeholder="E-Mail">
        </div>
        <div class="inputForm">
            <input type="password" class="password" name="password" placeholder="Password">
            <div class="togglePassword insideInput">
                <i class="las la-eye-slash"></i>
            </div>
        </div>
        <div class="inputForm">
            <input type="hidden" name="token" value="<?php echo $this->token ?>">
        </div>
        <div class="inputForm twoInput btn">
            <a href="register" class="linkBtn">Register</a>
            <button id="login_btn" type="submit">Login</button>
        </div>
    </div>
</div>