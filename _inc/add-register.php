<?php
/* Vlozenie config */
require_once "./classes/DB.class.php";
require_once "_inc/config.php";
$db=new DB;
$config=new config;
$config->connect();
$table="users";
/* Definovanie premennych */

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

/*Spracovanie udajov z formulara pri odoslani */

if($_SERVER["REQUEST_METHOD"] == "POST"){ // $_Server -> Informacie o servery REQUEST_METHOD-> ktora metoda bola pouzita v nasom pripade metoda POST 
 
    /* Overenie pouzivatelskeho mena  */

    if( empty( trim( $_POST["username"] ))){ // trim()-> Odstrani medzeri (alebo ine znaky) zo zaciatku a konca daneho retazca
        $username_err = "Zadajte používateľské meno.";
    } elseif( !preg_match( '/^[a-zA-Z0-9_]+$/', trim( $_POST["username"] ))){ // preg_match()-> Vykona zhodu regularneho vyrazu 
        $username_err = "Používateľské meno môže obsahovať iba písmená, čísla a podčiarkovníky.";
    } else{
        /* Priprava na vyhlasenie */

        $sql = "SELECT id FROM users WHERE username = ?";

        
        if($stmt = mysqli_prepare( $config->getLink(), $sql )){  // mysqli_prepare () -> vrati objekt prikazu alebo hodnotu false, ak sa vyskytne chyba 
            /* Naviazať premenné na pripravený príkaz ako parametre*/
            
            mysqli_stmt_bind_param( $stmt, "s", $param_username );  // Naviažte premenné pre značky parametrov v príkaze SQL pripravenom pomocou mysqli_prepare() alebo mysqli_stmt_prepare() .
            
            /* Nastavenie Parametrov */
            $param_username = trim( $_POST["username"] );
            
            /* Pokus o pripravu pripraveneho prikazu */
            if( mysqli_stmt_execute( $stmt )){ // mysqli_stmt_execute() -> vykona pripraveny vyraz 
                /* Ulozenie vysledku */
                mysqli_stmt_store_result( $stmt ); //mysqli_stmt_store_result() -> Uloží sadu výsledkov do internej vyrovnávacej pamäte
                
                if( mysqli_stmt_num_rows( $stmt ) == 1){ //mysqli_stmt_num_rows() -> Vrati pocet riadkov stiahnutych zo servera 
                    $username_err = "Toto uživateľské meno je už obsadené.";
                } else{
                    $username = trim( $_POST["username"] );
                }
            } else{
                echo "Oops! Niečo sa pokazilo, skúste to neskôr pri logine.";
            }

            // Zatvorenie  vyhlásenia 
            // mysqli_stmt_close( $stmt );
        }
    }
    
    /* Overenie hesla */
    if( empty( trim( $_POST["password"] ))){
        $password_err = "Zadajte heslo.";     
    } elseif( strlen( trim( $_POST["password"] )) < 6){
        $password_err = "Heslo musí mať aspoň 6 znakov.";
    } else{
        $password = trim( $_POST["password"] );
    }
    
    /* Potvrdenie Hesla */
    if( empty( trim( $_POST["confirm_password"] ))){
        $confirm_password_err = "Prosím potvrdte heslo.";     
    } else{
        $confirm_password = trim( $_POST[ "confirm_password" ]);
        if( empty( $password_err ) && ( $password != $confirm_password )){
            $confirm_password_err = "Heslo sa nezhoduje.";
        }
    }
    
    /* Kontrola vstupnych chyb*/
    if( empty( $username_err ) && empty( $password_err ) or empty( $confirm_password_err )){
        $username=$_POST['username']; 
        $password=$_POST['password']; 

        $userData = array(
            'username'  => $username,
            'password' => password_hash( $password, PASSWORD_DEFAULT ),
            
        );

        $bool= $db->insert($table,$userData);

        
        if ($bool){
                /* Presmerovanie na prihlasovaciu stránku */
                echo'<div class="alert alert-success" role="alert">
                    Používateľ '. $username .' bol úspešne zaregistrovaný :)
                    </div>';
                
            } else{
                echo "Oops! Niečo sa pokazilo, skúste to znova register.";
            }
        }
    //  /* Kontrola vstupnych chyb*/
    //  if( empty( $username_err ) && empty( $password_err ) or empty( $confirm_password_err )){
        
        
    //     /* Priprava vyhlásenia  */
    //     $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        
    //     if( ($stmt = mysqli_prepare( $config->getLink(), $sql )) && ($stmt1 = mysqli_prepare( $config->getLink1(), $sql)) ){
        
    //         mysqli_stmt_bind_param( $stmt, "ss", $param_username, $param_password );
    //         mysqli_stmt_bind_param( $stmt1, "ss", $param_username, $param_password );
            

            
    //         $param_username = $username;
    //         $param_password = password_hash( $password, PASSWORD_DEFAULT ); // Vytvorí hash hesla 
            
    //         if( mysqli_stmt_execute( $stmt ) && mysqli_stmt_execute( $stmt1 )){
    //             /* Presmerovanie na prihlasovaciu stránku */
    //             echo'<div class="alert alert-success" role="alert">
    //                 Používateľ '. $username .' bol úspešne zaregistrovaný :)
    //                 </div>';
                
    //         } else{
    //             echo "Oops! Niečo sa pokazilo, skúste to znova.";
    //         }

            
    //         mysqli_stmt_close( $stmt );
    //         mysqli_stmt_close( $stmt1 );
            
    //     }
    // }
    
    /* Zatvorenie spojenia */
    mysqli_close( $config->getLink());
}
?>