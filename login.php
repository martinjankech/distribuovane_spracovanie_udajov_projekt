<?php 

    include 'partials/header.php' ; 
    require '_inc/add-login.php';
    require '_inc/add-register.php';

    

    
?>
<script>
    $("#register").data('bs.modal')._config.backdrop = 'static'; 
</script>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<nav class="navbar navbar-expand-lg navbar-light bg-dark ">

    <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#login">
        Prihlásiť sa
    </button>

    <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#register">
        Registrovať sa
    </button>

</nav>
<?php 
if(( !empty( $username_err )) )
echo '<div class="alert alert-danger" role="alert">
Toto uživateľské meno je už obsadené.
    </div>';
   
    if(( !empty( $password_err )) )
echo '<div class="alert alert-danger" role="alert">
Heslo musí mať aspoň 6 znakov.
    </div>';

    if(( !empty( $confirm_password_err )) )
echo '<div class="alert alert-danger" role="alert">
Heslo sa nezhoduje.
    </div>';

    if(( !empty( $login_err)) )
echo '<div class="alert alert-danger" role="alert">
"Nesprávne uživateľské meno alebo heslo."
    </div>';
    if(( !empty( $username_err_log)) )
echo '<div class="alert alert-danger" role="alert">
"Zadajte použivateľské meno."
    </div>';
    if(( !empty( $password_err_log)) )
echo '<div class="alert alert-danger" role="alert">
"Zadajte Heslo."
    </div>';
    ?>

<div class="container">
    <h1 class="font-monospace mt-4 mb-3 text-primary">Projekt DSD</h1>

    <h2 class="text-info">Databáza o filmoch </h2>

    <article>
        <p>
            V našej databáze sa nachádzaju údaje o filmoch. Do databázy sa môžte registrovať a následne prihlásiť, kde
            môžte rozšíriť databázu a ďalšie filmy. Tak isto môžte filmy upravovať a mazať.
        </p>

        <h2 class="text-info">DSD - požiadavky na projekt</h2>
        <p>
            Cieľom projektu je vytvorenie distribuovaného informačného systému aspoň z 3 uzlov. Každý uzol bude fyzický
            počítač v konfigurácii servera webového aj databázového. Odporúčaný je Wamp, Xampp, Lamp, ale nie je
            podmienkou.
            Uzly sú navzájom prepojené buď priamo cez switch, alebo cez net pomocou VPN napr. Log MeIn Hamachi.
            Na každom uzle beží tá istá aplikácia (webová aplikácia) a databáza s rovnakou štruktúrou.
            Transakcia vytvorená na hociktorom uzle sa prejaví na domácom uzle (kde vznikla) a zároveň sa prejaví aj na
            ostatných uzloch. Takto sa zabezpečí konzistentnosť databáz. Odporúčame používať rovnaký SRBD a rovnaký typ
            DB, ale nie je to podmienka. Referenčná integrita môže byť porušená len na dobu, kým sa údaje z
            inicializačného uzla replikujú na ďalšie uzly. Spúšťanie replikácie môže byť automatické (pri vzniku
            transakcie), alebo manuálne (na stlačenie tlačidla).
            Minimálna požiadavka na udelenie zápočtu je 20b a sú splnené vyššie opísané kritériá.
            Na dosiahnutie vyššieho počtu bodov až do 40b je potrebné vyriešiť replikáciu dát po tom ako došlo k výpadku
            komunikácie medzi uzlami.
            Pri výpadku každý uzol pracuje autonómne ďalej, ale len tie záznamy môže upravovať, ktoré vznikli na danom
            uzle (napr. uzol 1 len záznamy vytvorené uzlom 1). Vytváraním nových Query vznikajú nové záznamy v
            tabuľkách. DDB sa dostáva do stavu nekonzistentnosti. Po obnovení spojenia sa replikujú záznamy, ktoré ešte
            replikované neboli. Automatickým alebo manuálnym spustením. Po replikácii sa nachádzajú v DB na každom uzle
            rovnaké záznamy a konzistentnosť údajov je obnovená.
            Max. počet bodov získajú riešenia ktoré sú funkčné, prehľadné a elegantné.

        </p>
    </article>

    <footer class="bg-dark text-center text-white mt-5">

        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2021 DSD-Project:
            <p>Martin Jankech, Samuel Veštúr</p>
        </div>

    </footer>



</div>

<!-- Modal Login -->


<div class="modal fade float-right" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginLabel">Prihlásiť sa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlspecialchars( $_SERVER[ "PHP_SELF" ]); ?>" method="post">
                    <div class="form-group">
                        <p>Ak sa chcete prihlásiť vyplnte tento formulár.</p>
                        <label>Použivateľské meno</label>
                        <input type="text" name="username_log"
                            class="form-control <?php echo ( !empty( $username_err_log )) ? 'is-invalid' : ''; ?>"
                            value="<?php echo $username_log; ?>">
                        <span class="invalid-feedback"><?php echo $username_err_log; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Heslo</label>
                        <input type="password" name="password_log"
                            class="form-control <?php echo ( !empty( $password_err_log )) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err_log; ?></span>
                    </div>
                    <div class="form-group">
                        <input  type="submit" name="submit_log" class="btn btn-primary" value="Prihlásiť sa">
                    </div>
                    <p>Nemáte účet ? <a href="register.php">Zaregistrujte sa teraz</a>.</p>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal register -->
<div class="modal fade float-right" id="register" tabindex="-1" role="dialog" aria-labelledby="registerLabel"  
    aria-hidden="true" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal head -->
            <div class="modal-header">
                <h5 class="modal-title" id="registerLabel">Vytvor si účet </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
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
                        <!-- <input type="submit" class="btn btn-primary" value="Registrovat sa "> -->
                        <button type="submit"  name="submit_reg" class="btn btn-success" >Registrovať</button>
                        <input type="reset" class="btn btn-secondary ml-2" value="Resetovať">
                    </div>
                    <p>Úspešne ste vytvorili účet ?<a href="login.php"> Prihláste sa tu.</a></p>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- <script src="./assets/js//script.js"></script> -->
<!-- <script>
     function openpopup() {
            $("#register").modal('show');
        }
</script> -->

</body>

</html>