
<?php
 function connectToDBS( $servername, $username, $password, $dbname ) {    
        try
            { 
                $timeout = 1;  
                $link = mysqli_init(); // mysqli_init() -> Inicializuje MySQLi a vráti objekt na použitie s mysqli_real_connect()
                $link->options( MYSQLI_OPT_CONNECT_TIMEOUT, $timeout );  // MYSQLI_OPT_CONNECT_TIMEOUT -> Časový limit výsledku vykonania príkazu v sekundách
                if( $link->real_connect( $servername, $username, $password, $dbname ))
                    {
                        return $link;
                    }
                    else
                    { 
                        throw new Exception( 'Unable to connect to noid '. $servername ); // Exception() -> je základná trieda pre všetky používateľské výnimky.
                    }
            }
        catch( Exception $e )
        {
            //echo $e->getMessage();
            return $servername;
        }
    }
    ?>
