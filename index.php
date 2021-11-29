<?php
include 'partials/header.php';
require_once '_inc/config.php'
?>


<?php



        /* Include and initialize DB class */
        require_once 'classes/DB.class.php';
        require_once './_inc/config.php';
        $config=new config;
        $config->connect();
        session_start(); // session_start() -> vytvorí reláciu alebo obnoví aktuálnu na základe identifikátora relácie odovzdaného prostredníctvom požiadavky GET alebo POST alebo odovzdaného prostredníctvom súboru cookie.
        
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            header("location: login.php");
            exit;
        }
        
        
        
        
         $db = new DB(); // $_SESSION -> Asociatívne pole obsahujúce premenné relácie dostupné pre aktuálny skript
        //$db->connect();

        if( !empty( $config->getNotaviableconnection() )){

            foreach( $config->getNotaviableconnection() as $value ){

                echo '<div class="alert alert-danger" role="alert">
                    uzol '.$value.' je odpojený !
                    </div>';

            }


        }
        $updateip = $config->synchronize();

        if( !empty( $updateip )){ 
            foreach( $updateip as $value ){
                echo '<div class="alert alert-success" role="alert">
                uzol '.$value.' bol synchronizovany s ostatnymi uzlami ! </div>';
            }
        }

        /* Ziskanie udajov */
        $movies = $db->getRows('movies');
    ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 head">
            <h5 class="text-center p-3 mb-2 bg-secondary text-white"> <?php echo $_SERVER['SERVER_NAME'] ?></h5>
            <h1 class="text-center mt-3 mb-5 text-primary">Ahoj
                <b><?php echo htmlspecialchars( $_SESSION[ "username" ]); ?></b>. Vitaj
                v
                našej databáze na filmy.
            </h1>
            <!-- Add link -->
            <div class="float-right">
                <a href="javascript:void(0);" class="btn btn-success mb-2" data-type="add" data-toggle="modal"
                    data-target="#modalUserAddEdit"><i class="plus"></i> Pridat Film</a>
                <a href="logout.php" class="btn  btn-outline-info mb-2"
                    onclick="return confirm('Naozaj sa chcete odhlásiť ?')">Odhlásiť sa z učtu</a>
                <!-- <a href="javascript:void(1);" class="btn btn-success m-2" data-type="register" data-toggle="modal"
                    data-target="#register"><i class="plus"></i> Register</a> -->
            </div>
        </div>
        <div class="statusMsg"></div>
        <!-- List the users -->
        <table class="table table-striped table-dark">
            <thead class="thead-light">
                <tr>
                    <th>Id</th>
                    <th>Názov</th>
                    <th>Meno Režiséra</th>
                    <th>Hlavný Herec</th>
                    <th>Hodnotenie Imdb</th>
                    <th>Pridal Uživatel</th>
                    <th>Uzol</th>
                    <th>Možnosti</th>
                </tr>
            </thead>
            <tbody id="movieData">
                <?php if( !empty($movies) ) { foreach( $movies as $row ){ ?>
                <tr>
                    <td><?php echo '#'. $row['Id']; ?></td>
                    <td><?php echo $row['Name_Movie']; ?></td>
                    <td><?php echo $row['Name_Director']; ?></td>
                    <td><?php echo $row['Main_Actor']; ?></td>
                    <td><?php echo $row['Rating_Imdb']; ?></td>
                    <td><?php echo $row['Added_By_User']; ?></td>
                    <td><?php echo $row['Node']; ?></td>
                    <td>
                    <?php
                     if (!empty ($config->getNotaviableconnection()) && in_array($row['Node'], $config->getNotaviableconnection())){
                        
                         ?>
                            <a href="javascript:void(0);" class="btn btn-primary disabled"  rowID="<?php echo $row['Id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit" >Upraviť</a>
                            <a href="javascript:void(0);" class="btn btn-outline-danger disabled" onclick="return confirm('Are you sure to delete data?')?movieAction('delete', '<?php echo $row['Id']; ?>'):false;">Odstraniť</a>
                        </td>
                        <?php
                         }
                         else{?>
                            <a href="javascript:void(0);" class="btn btn-primary "  rowID="<?php echo $row['Id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit" >Upraviť</a>
                            <a href="javascript:void(0);" class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete data?')?movieAction('delete', '<?php echo $row['Id']; ?>'):false;">Odstraniť</a>
                            </td>
                            <?php

                         }
                         
                         
                        ?>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="5">No user(s) found...</td></tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
    
</div>

<!-- Modal Add and Edit Form -->
<div class="modal fade" id="modalUserAddEdit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Pridať Film do DB</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="statusMsg"></div>
                <form role="form">
                    <div class="form-group">
                        <label for="Name_Movie">Názov</label>
                        <input type="text" class="form-control" name="Name_Movie" id="Name_Movie"
                            placeholder="Vložte meno filmu.">
                    </div>
                    <div class="form-group">
                        <label for="Name_Director">Meno Režiséra</label>
                        <input type="text" class="form-control" name="Name_Director" id="Name_Director"
                            placeholder="Vložte meno režiséra.">
                    </div>
                    <div class="form-group">
                        <label for="Main_Actor">Hlavný Herec</label>
                        <input type="text" class="form-control" name="Main_Actor" id="Main_Actor"
                            placeholder="Vložte meno hlavného herca.">
                    </div>
                    <div class="form-group">
                        <label for="Rating_Imdb">Hodnotenie Imdb</label>
                        <input type="number" class="form-control" name="Rating_Imdb" id="Rating_Imdb"
                            placeholder="Vložte rating filmu podľa imdb.">
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="Node">Uzol</label>
                        <input type="text" class="form-control" name="Node" id="Node" placeholder="Vložte uzol.">
                    </div> -->
                    <input type="hidden" class="form-control" name="Id" id="Id" />
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
                <button type="button" class="btn btn-success" id="movieSubmit">Potvrdiť</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/script.js"></script>

</body>

</html>