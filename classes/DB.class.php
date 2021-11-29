<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(1);

require_once '_inc/config.php';






class DB{

    /* LocalHost */
    private $conn;
    /* 25.69.87.199 */
    private $conn1;


    /* Uzol */
    private $nodeip = "25.69.87.199"; 
    /* Uzivatel */

    /* Spojenia */
  

    /* Databaza Localhost */
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName    = "movies";

    /* Zdialena Databaza */
    private $dbHost1     = "25.69.87.199";
    private $dbUsername1 = "samuel";
    private $dbPassword1 = "samko123";
    private $dbName1     = "movies";
    /*
     * Vráti riadky z databázy na základe podmienok
     */
    public function getRows( $table, $conditions = array()){
        $config=new config;
        $config->connect();
        
        $sql = ' SELECT ';
        $sql .= array_key_exists( "select", $conditions ) ? $conditions[ 'select' ] : '*';
        $sql .= ' FROM '. $table;
        if( array_key_exists( "where", $conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach( $conditions[ 'where' ] as $key => $value ){
                $pre = ($i > 0) ? ' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if( array_key_exists( "order_by", $conditions )){ // Zoradenie
            $sql .= ' ORDER BY '. $conditions[ 'order_by' ]; 
        }else{
            $sql .= ' ORDER BY Id DESC '; 
        }
        
        if( array_key_exists( "start", $conditions) && array_key_exists( "limit", $conditions)){
            $sql .= ' LIMIT '. $conditions[ 'start' ]. ',' . $conditions[ 'limit' ]; 
        }elseif( !array_key_exists( "start", $conditions ) && array_key_exists( "limit", $conditions)){
            $sql .= ' LIMIT '.$conditions[ 'limit' ]; 
        }
        
        $result = $config->getLink()->query($sql);
        
        if( array_key_exists( "return_type", $conditions ) && $conditions[ 'return_type' ] != 'all'){
            switch( $conditions[ 'return_type' ]){
                case 'count':
                    $data = $result->num_rows;
                    break;
                case 'single':
                    $data = $result->fetch_assoc(); // fetch_assoc() -> Načíta výsledný riadok ako asociatívne pole
                    break;
                default:
                    $data = '';
            }
        }else{
            if( $result->num_rows > 0 ){
                while( $row = $result->fetch_assoc() ){
                    $data[] = $row;
                }
            }
        }
        return !empty( $data ) ? $data:false;
    }
    
    /*
     * Vlozenie udajov do DB
     */
    public function insert( $table, $data ){
        $config=new config;
        $config->connect();
        
        if( !empty( $data ) && is_array($data)){  //empty() -> zisti ci premenna je prazdna / is_array() -> zisti ci premenna je pole 
            $columns = '';
            $values  = '';
            $i = 0;
            if($table=="movies"){
            if( !array_key_exists( 'When_Created', $data )){ // array_key_exists() -> Skontroluje ci dany kluc alebo index existuje v poli 
                $data[ 'When_Created' ] = date( "Y-m-d H:i:s" ); // data() -> Formatovanie casu 
            }
            if( !array_key_exists ('When_Modify', $data)){
                $data[ 'When_Modify' ] = date( "Y-m-d H:i:s" );
            }
            if( !array_key_exists( 'Node', $data)) { 
                $data['Node'] = $this->nodeip;
            }
        }
            

            foreach( $data as $key=>$val ){
                $pre = ( $i > 0 ) ? ', ':'';
                $columns .= $pre.$key; // .= -> zretazenie
                $values  .= $pre. "'" . $config->getLink()->real_escape_string( $val ). "'"; // real_escape_string() -> specialne znaky v retazci pre pouzitie v SQL
            
                $i++;
            }
            $query = "INSERT INTO ". $table ." (".$columns.") VALUES (". $values .")";
            //var_dump($query);
            foreach( $config->getAviableconnection() as $value )
            {

                $insert =  $value->query( $query );   
        
            }
            if ( !empty( $config->getNotaviableconnection() )){

                $myfile = fopen( "notaviablenodes.txt", "a+" ) or die( "Unable to open file!" ); // fopen() -> Otvori subor alebo URL
            
                foreach( $config->getNotaviableconnection() as $this->value )
            
                fwrite( $myfile, $this->value.":". $query . PHP_EOL ); // fwrite() -> zapis do suboru
                fclose( $myfile ); // fclose() -> Zatvori ukazovatel a otvori subor

            }
        
            return $insert?$config->getLink()->insert_id:false;
            
        }else{
            return false;
        }
    }
    
    /*
     * Aktualizovanie udajov do DB
     */
    public function update( $table, $data, $conditions ){
        
        $config=new config;
        $config->connect();
        

        if( !empty( $data ) && is_array( $data )){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            if( !array_key_exists( 'When_Modify', $data )){
                $data[ 'When_Modify' ] = date( "Y-m-d H:i:s" );
            }
            if( !array_key_exists( 'Node', $data )){
                $data[ 'Node' ] = $this->nodeip;
            }
           

        

            foreach( $data as $key=>$val ){

                $pre = ( $i > 0 ) ?', ':'';
                $colvalSet .= $pre.$key."='".$config->getLink()->real_escape_string( $val )."'";
                $i++;
            }
            if( !empty( $conditions ) && is_array( $conditions )){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach( $conditions as $key => $value ){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $query = "UPDATE ". $table ." SET ".$colvalSet.$whereSql;
            foreach($config->getAviableconnection() as $this->value)
            {
                $update =  $this->value->query( $query );   
            }
            if (!empty($config->getNotaviableconnection())){

                $myfile = fopen( "notaviablenodes.txt", "a+") or die( "Unable to open file!" );
            
                foreach($config->getNotaviableconnection() as $this->value)
                //$current= file_get_contents($myfile);
                fwrite( $myfile, $this->value.":".$query.PHP_EOL );
                fclose( $myfile );

            }
            //$update =  $this->conn->query($query)&&$this->conn1->query($query)&&$this->conn2->query($query);
            return $update?$config->getLink()->affected_rows:false;
        }else{
            return false;
        }
    }
    
    /*
     * Vymazanie dat z DB
     */
    public function delete( $table, $conditions ){
        $config=new config;
        $config->connect();
        $whereSql = '';
        if( !empty( $conditions ) && is_array( $conditions )){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach( $conditions as $key => $value ){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $query = "DELETE FROM ".$table.$whereSql;
        foreach( $config->getAviableconnection() as $this->value )
        {
            $delete =  $this->value->query( $query );   
        }
        if ( !empty( $config->getNotaviableconnection())){

            $myfile = fopen( "notaviablenodes.txt", "a+" ) or die( "Unable to open file!" );
        
            foreach($config->getNotaviableconnection() as $this->value )
            //$current= file_get_contents($myfile);
            fwrite( $myfile, $this->value.":".$query.PHP_EOL );
            fclose( $myfile );

        }
        //$delete =  $this->conn->query($query)&&$this->conn1->query($query)&&$this->conn2->query($query);
        return $delete?true:false;
    }
       
}



	
?>