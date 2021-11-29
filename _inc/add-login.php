<?php
/* Inicializacia relacie  */

session_start(); // session_start() -> vytvorí reláciu alebo obnoví aktuálnu na základe identifikátora relácie odovzdaného prostredníctvom požiadavky Get alebo Post v nasom priprade POST 

/* Kontrola, ci je pouzivatel prihlaseny ak ano presmeruje ho do DB -> index.php */
// if( isset( $_SESSION[ "loggedin" ]) && $_SESSION[ "loggedin" ] === true){
//     header( "location: index.php" ); // header()-> sa pouziva na odoslanie hlavicky http v nasom pripade na lacation index.php
//     exit;
// }
// $halohalo=($_POST["username"]);
// var_dump($halohalo);

/* Vlozenie config.php */
require_once "_inc/config.php";
$config=new config;
$config->connect();
 
/* Definovanie premmenych */
$username_log = $password_log = "";
$username_err_log = $password_err_log = $login_err_log = "";
//echo $_POST['submit_log'];
 
/* Spracovanie údajov formulára pri odoslaní formulára */
//if( $_SERVER[ "REQUEST_METHOD" ] == "POST"){
 if(!empty($_POST[ "submit_log" ]) ){
    /* Kontrola ci pouzivatelske meno je prazdne  */
    if( empty( trim( $_POST[ "username_log" ]))){
        $username_err_log = "Zadajte použivateľské meno.";
    } else{
        $username_log = trim( $_POST[ "username_log" ]);
    }


    /* Kontrola pouzivatelskéh hesla */
    if( empty( trim( $_POST[ "password_log" ]))){
        $password_err_log = "Prosím vložte heslo.";
    } else{
        $password_log = trim( $_POST[ "password_log" ]);
    }

    
    /* Overenie  */
    if( empty( $username_err_log ) && empty( $password_err_log )){
        
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
     
        if( $stmt = mysqli_prepare( $config->getLink(), $sql )){
            /* Naviazať premenné na pripravený príkaz ako parametre */
            mysqli_stmt_bind_param( $stmt, "s", $param_username );
            
           /* Nastavit parametre */
            $param_username = $username_log;
          
           /* Pokus o pripravu na vykonanie pripraveneho prikazu  */
            if( mysqli_stmt_execute( $stmt )){
               /* Ulozenie vysledku */
               
                mysqli_stmt_store_result( $stmt );
        
                
                /* Skontroluje, ci existuje pouzivatelske meno ak ano , overenie */
                if( mysqli_stmt_num_rows( $stmt ) == 1){                    
                    /* Zviazanie do vysledkov */
                    mysqli_stmt_bind_result( $stmt, $id, $username, $hashed_password );
                    if( mysqli_stmt_fetch( $stmt )){
                        if( password_verify( $password_log, $hashed_password)){
                            /* Ak je heslo spravne zacne session */
                            session_start();
                            
                            /* ulozi udaje do premenych */
                            $_SESSION[ "loggedin" ] = true;
                            $_SESSION[ "id" ] = $id;
                            $_SESSION[ "username" ] = $username;                            
                            
                            /* Presmerovanie pouzivatela na index.php */
                            header("location: index.php");
                        } else{
                            /* Ak je heslo nespravne zobrazo sa hlaska */
                            $login_err = "Nesprávne uživateľské meno alebo heslo.";
                        }
                    }
                } else{
                    
                    $login_err = "Nesprávne uživateľské meno alebo heslo.";
                }
            } else{
                echo "Oops! Niečo sa pokazilo, skúste to neskôr pri logine.";
            }

            /* Zatvorenie daneho vyhlasenia */
            mysqli_stmt_close( $stmt );
        }
    }
    
    /* Zatvorenie prihlasenia */
    // mysqli_close( $config->getLink() );
}

//}

//}

?>