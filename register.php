<?php 

require_once '_inc/add-register.php' ;
include 'partials/header.php'
?>


<div class="wrapper m-auto" id="register">
    <h2>Vytvor si účet </h2>
    <p>Ak si chcete vytvoriť účet vypľne tento formulár.</p>
    <form action="<?php echo htmlspecialchars( $_SERVER[ "PHP_SELF" ] ); ?>" method="post">
        <div class="form-group">
            <label>Použivateľské meno</label>
            <input type="text" name="username"
                class="form-control <?php echo ( !empty( $username_err )) ? 'is-invalid' : ''; ?>"
                value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Heslo</label>
            <input type="password" name="password"
                class="form-control <?php echo ( !empty( $password_err )) ? 'is-invalid' : ''; ?>"
                value="<?php echo $password; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Potvrďte heslo</label>
            <input type="password" name="confirm_password"
                class="form-control <?php echo ( !empty( $confirm_password_err )) ? 'is-invalid' : ''; ?>"
                value="<?php echo $confirm_password; ?>">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Potvrdiť">
            <input type="reset" class="btn btn-secondary ml-2" value="Resetovať">
        </div>
        <p>Úspešne ste vytvorili účet ?<a href="login.php"> Prihláste sa tu.</a></p>
    </form>
</div>
</body>

</html>