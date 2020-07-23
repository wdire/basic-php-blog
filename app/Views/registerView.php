<div class="loginContainer">
    <div class="login" id="register_form">
        <h1>Register</h1>
        <div class="inputForm">
            <div class="formErrorList hidden">
                <ul>

                </ul>
            </div>
        </div>
        <div class="inputForm twoInput">
            <input type="text" name="name" placeholder="Name">
            <input type="text" name="surname" placeholder="Surname">
        </div>
        <div class="inputForm">
            <input type="text" name="username" placeholder="Username" >
        </div>
        <div class="inputForm">
            <input type="text" name="email" placeholder="E-Mail">
        </div>
        <div class="inputForm twoInput">
            <input type="password" class="password" name="password" placeholder="Password">
            <input type="password" class="password" name="password_again" placeholder="Password Again">
            <div class="togglePassword">
                <i class="las la-eye-slash"></i>
            </div>
        </div>
        <div class="inputForm">
            <div class="passwordInfo">Password must include at least one uppercase letter and one lowercase letter.</div>
        </div>
        <div class="inputForm">
            <input type="hidden" name="token" value="<?php echo $this->token ?>">
        </div>
        <div class="inputForm twoInput btn">
            <a href="login" class="linkBtn">Login</a>
            <button id="register_btn" type="submit">Register</button>
        </div>
    </div>
</div>