<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);

require_once 'classes/DB.class.php';
require_once './_inc/config.php';
$db = new DB();
$config=new config;
$config->connect();


session_start();

// Database table name
$tblName = 'movies';

// Ak je formulár odoslaný
if( !empty( $_POST[ 'action_type' ] )){
    if( $_POST[ 'action_type' ] == 'data' ){
        // Načítajte údaje na základe ID riadka
        
        $conditions[ 'where' ] = array( 'Id' => $_POST[ 'Id' ]); // array() -> vytvori pole 
        $conditions[ 'return_type' ] = 'single';
        $movie = $db->getRows( $tblName, $conditions );

         
        
        // Vrátiť údaje vo formáte JSON
        echo json_encode( $movie ); // json_encode -> Vráti JSON reprezentáciu hodnoty
    }elseif( $_POST[ 'action_type' ] == 'view'){
        // Získajte všetky záznamy
        $movies = $db->getRows( $tblName ); // getRows() -> vrati prvy riadok 
        
        // Render data as HTML format
        if( !empty( $movies )){
            foreach($movies as $row){
                echo '<tr>';
                echo '<td>#'. $row[ 'Id' ] .'</td>';
                echo '<td>'. $row[ 'Name_Movie' ] .'</td>';
                echo '<td>'. $row[ 'Name_Director' ] .'</td>';
                echo '<td>'. $row[ 'Main_Actor' ] .'</td>';
                echo '<td>'. $row[ 'Rating_Imdb' ] .'</td>';
                echo '<td>'. $row[ 'Added_By_User' ] .'</td>';
                echo '<td>'. $row[ 'Node_Created' ] .'</td>';
                if (!empty ($config->getNotaviableconnection()) && in_array($row['Node_Created'], $config->getNotaviableconnection())){
                    echo '<td><a href="javascript:void(0);" class="btn btn-primary disabled" rowID="'.$row['Id'].'" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit">Upraviť</a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger disabled" onclick="return confirm(\'Ste si istý že chcete vymazat údaj ?\')?movieAction(\'delete\', \''.$row['Id'].'\'):false;">Odstrániť</a></td>';
                    echo '</tr>';
                }
                else{
                echo '<td><a href="javascript:void(0);" class="btn btn-primary" rowID="'. $row['Id'] .'" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit">Upraviť</a>
                            <a href="javascript:void(0);" class="btn btn-outline-danger" onclick="return confirm(\'Ste si istý že chcete vymazat údaj ?\')?movieAction(\'delete\', \''.$row['Id'].'\'):false;">Odstrániť</a></td>';
                echo '</tr>';
            }
        }
        }else{
            echo '<tr><td colspan="5">Nenašli sa žiadne filmy </td></tr>';
        }
    }elseif( $_POST[ 'action_type' ] == 'add' ){
        $msg = '';
        $status = $verr = 0;
        
        // Ziska vstup od pouzivatela 
        $name_movie = $_POST[ 'Name_Movie' ];
        $name_director = $_POST[ 'Name_Director' ];
        $main_actor = $_POST[ 'Main_Actor' ];
        $rating_imdb = $_POST[ 'Rating_Imdb' ];
       
        // $node = $_POST[ 'Node' ];
        
        // Overi sa pole formulara 
        if( empty( $name_movie )){
            $verr = 1;
            $msg .= 'Vložte meno filmu.<br/>';
        }
        if( empty( $name_director )){
            $verr = 1;
            $msg .= 'Vložte meno režiséra.<br/>';
        }
        if( empty( $main_actor )){
            $verr = 1;
            $msg .= 'Vložte meno hlavného herca.<br/>';
        }
        if( empty( $rating_imdb )) { 
            $verr = 1; 
            $msg .= 'Vložte rating filmu podľa Imdb.<br/>';
        }
        if( $verr == 0 ){
            // Vlozenie udajov do DB 
            $movieData = array(
                'Name_Movie'  => $name_movie,
                'Name_Director' => $name_director,
                'Main_Actor' => $main_actor, 
                'Rating_Imdb' => $rating_imdb,
                'Added_By_User' => $_SESSION['username']
            );
            $insert = $db->insert($tblName, $movieData);
            
            if( $insert ){
                $status = 1;
                $msg .= 'Údaje o filme boli úspešne vložene';
            }else{
                $msg .= 'Vyskytol sa problém skúste to znova.';
            }
        }
        
        // Vrati odpoved vo formate JSON
        $alertType = ( $status == 1 ) ? 'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '. $alertType .'">'. $msg .'</p>';
        $response = array(
            'status' => $status,
            'msg'    => $statusMsg
        );
        echo json_encode( $response );
    }elseif( $_POST[ 'action_type' ] == 'edit'){ //editovanie
        $msg = '';
        $status = $verr = 0;
        
        if( !empty( $_POST[ 'Id' ])){
            // Zista vstup od pouzivatela
            $name_movie = $_POST[ 'Name_Movie' ];
            $name_director = $_POST[ 'Name_Director' ];
            $main_actor = $_POST[ 'Main_Actor' ];
            $rating_imdb = $_POST[ 'Rating_Imdb' ];
           
            // $node = $_POST[ 'Node' ];
            
            // Overenie poli vo formulare
            if( empty( $name_movie )){
                $verr = 1;
                $msg .= 'Vložte meno filmu.<br/>';
            }
            if( empty( $name_director )){
                $verr = 1;
                $msg .= 'Vložte meno režiséra.<br/>';
            }
            if( empty( $main_actor )){
                $verr = 1;
                $msg .= 'Vložte meno hlavného herca.<br/>';
            }
            if( empty( $rating_imdb )){
                $verr = 1;
                $msg .= 'Vložte rating filmu podľa Imdb.<br/>';
            }
            
            // if( empty( $node )){
            //     $verr = 1;
            //     $msg .= 'Vložte uzol.<br/>';
            // }
            
            if( $verr == 0 ){
                // Vlozi data do DB
                $movieData = array(
                    'Name_Movie'  => $name_movie,
                    'Name_Director' => $name_director,
                    'Main_Actor' => $main_actor,
                    'Rating_Imdb' => $rating_imdb,
                    'Update_By_User' => $_SESSION['username']
                    // 'Node' => $node
                );
                $condition = array( 'Id' => $_POST[ 'Id' ]);
                $update = $db->update( $tblName, $movieData, $condition );
                
                if( $update ){
                    $status = 1;
                    $msg .= 'Údaje o filme boli úspešne aktualizované.';
                }else{
                    $msg .= 'Vyskytol sa problém skúste to znova.';
                }
            }
        }else{
            $msg .= 'Vyskytol sa problém skúste to znova.';
        }
        
        // Vrati odpoved vo formate JSON
        $alertType = ( $status == 1 ) ? 'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '. $alertType .'">'. $msg .'</p>';
        $response = array(
            'status' => $status,
            'msg'    => $statusMsg
        );
        echo json_encode( $response );
    }elseif( $_POST[ 'action_type' ] == 'delete'){
        $msg = '';
        $status = 0;
        
        if( !empty( $_POST[ 'Id' ] )){
            // Vymaze data z DB
            $condition = array( 'Id' => $_POST[ 'Id' ]);
            $delete = $db->delete( $tblName, $condition );
            
            if( $delete ){
                $status = 1;
                $msg .= 'Údaje o filme boli úspešne odstránené.';
            }else{
                $msg .= 'Vyskytol sa problém skúste to znova.';
            }
        }else{
            $msg .= 'Vyskytol sa problém skúste to znova.';
        }  

        // Vrati odpoved vo formate JSON
        $alertType = ( $status == 1 ) ? 'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '. $alertType .'">'. $msg .'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode( $response );
        
    }
}

exit;
?>