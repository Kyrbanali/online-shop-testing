<?php  require_once './../View/nav.php'; ?>

<form action="post_login.php" method="post">
    <div class="container">
        <h1>Login</h1>
        <p>Please fill in this form to log in account.</p>
        <hr>

        <label for="email"><b>Email</b></label>
        <?php   if (isset($errors['email'])): ?>
            <div class="labelError">
                <label><?php echo $errors['email']; ?></label>
            </div>
        <?php endif; ?>
        <input type="text" placeholder="Enter Email" name="email" id="email" required>

        <label for="psw"><b>Password</b></label>
        <?php   if (isset($errors['psw'])): ?>
            <div class="labelError">
                <label><?php echo $errors['psw']; ?></label>
            </div>
        <?php endif; ?>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required>

        <hr>

        <button type="submit" class="registerbtn">Login</button>
    </div>
</form>


<style>
    .labelError {
        color: red;
    }

    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }



</style>