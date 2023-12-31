<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2023 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<?php

$use_unicode_sqlserver_transformations = 0;

function connect_db($server, $user, $password, $name_db)
{
	global $debug_mode, $dbms_type, $db_schema, $sqlserver_conn_additional_attributes, $disable_mysql_multiple_statements;
	
		try {
			
			$temp = explode(':', $server);
			
			$server = $temp[0];
			$port_string = '';
			if (isset($temp[1])){
			    if ($dbms_type !== 'sqlserver'){
				    $port_string = ';port='.$temp[1];
				}
				else{
				    $port_string = ','.$temp[1];
				}
			}
			switch ($dbms_type){
				
				case 'sqlite':
					$conn = new PDO($dbms_type.":".$name_db, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					break;
				case 'mysql':
					
					$array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
					
					if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS') && $disable_mysql_multiple_statements === 1) {
					    $array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_MULTI_STATEMENTS => FALSE);
					}
					else{
					    $array_parameters = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
					}
					
					$conn = new PDO('mysql:host='.$server.$port_string.';dbname='.$name_db, $user, $password, $array_parameters);
					
					$res = execute_db("SET NAMES 'UTF8'", $conn);
					
					break;
				case 'sqlserver':
					
					$conn = new PDO('sqlsrv:Server='.$server.$port_string.';Database='.$name_db, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
					
					foreach($sqlserver_conn_additional_attributes as $key => $value){
					    $conn->setAttribute( $key , $value);
					}
					
					break;
				case 'postgres':
					
					$conn = new PDO('pgsql:dbname='.$name_db.$port_string.';host='.$server, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
					
					$res = execute_db("SET NAMES 'UTF8'", $conn);
					
					$res = execute_db("SET search_path TO ".$db_schema."", $conn);
					
					break;
				default:
					echo 'Error';
					exit;
			}
  		}
		catch(PDOException $e)
    	{	
    		echo '<b>[06] Error:</b> during database connection. Please check $host, $user, $pass and $db_name in your config.php';
			if ($debug_mode === 1){
				echo '<br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				echo ', set $debug_mode to 1 in your config.php to get further error information ';
			}
    	}
    	
	return $conn;
}

function execute_db($sql, $conn, $return_control_on_error = 0)
{
	global $debug_mode, $dbms_type, $unicode_sqlserver_transformations, $use_unicode_sqlserver_transformations;
	
	if ($dbms_type === 'sqlserver' && $use_unicode_sqlserver_transformations === 1){
	    
	    foreach($unicode_sqlserver_transformations as $transformation){
	        $sql = str_ireplace($transformation['input'], $transformation['output'], $sql);
	    }
	}
    	
    try {
    	$results = $conn->query($sql);
    	//$results->setFetchMode(PDO::FETCH_BOTH);
    }
    catch(PDOException $e){
    	if ($return_control_on_error === 1){
    		$res['error_message'] = $e->getMessage();
    		return $res;
    	}
    	else{
			echo '<p><b>[08] Error:</b> during query execution.';
			if ($debug_mode === 1){
				echo ' '.htmlspecialchars($sql).'<br/>The DBMS server said: '.$e->getMessage();
			}
			else{
				echo ' Set $debug_mode to 1 in your config.php to get further error information ';
			}
			exit();
		}
    }
    
	return $results;
}

require './include/config.php';

date_default_timezone_set($timezone);
                   
$min_php_version = '7.2.0';
$min_php_version_sqlserver = '7.2.0';
$min_ioncube_version = '10.1';
$min_dbms_version['mysql'] = '5';
$min_dbms_version['sqlite'] = '3';
$min_dbms_version['postgres'] = '8.2';
$min_dbms_version['sqlserver'] = '11';

$check = 1;

$content = '';

// same check in common_start
if ($dbms_type !== 'mysql' && $dbms_type !== 'postgres' && $dbms_type !== 'sqlite'  && $dbms_type !== 'sqlserver' ){
    $content .= '<p><b>[01] Error:</b> $dbms_type must be \'mysql\' or \'postgres\' or \'sqlite\' or \'sqlserver\' please check your config.php'; 
    exit;
}
elseif($host === '' && $dbms_type !== 'sqlite'){
        $content .= '<p><b>[01] Error:</b> Please specify $host in your config.php';
        exit;
}
elseif($db_name === ''){
        $content .= '<p><b>[01] Error:</b> Please specify $db_name in your config.php'; 
        exit;
}
elseif($user === '' && $dbms_type !== 'sqlite'){
        $content .= '<p><b>[01] Error:</b> Please specify $user in your config.php'; 
        exit;
}
else{
            
    $content .= '<h2>Step 1/4 - Requirements check</h2>';
    
    // CHECK PHP VERSION
    $phpversion = phpversion();
    $content .= '<p><strong>Current PHP version:</strong> '.$phpversion.' <strong>';

    $additional_errror_text = '';
    if ($dbms_type === 'sqlserver'){
        $min_php_version = $min_php_version_sqlserver;
        $additional_errror_text = ' for MS SQL Server';
    }
    if (version_compare($phpversion, $min_php_version, '>=') === true && substr($phpversion, 0, 3) !== '8.0') {
        $content .= ' <span style="color:#007700">OK<span>';
    }
    else{
        $content .= ' <span style="color:#aa0000">NO<span> (min PHP version'.$additional_errror_text.' is: '.$min_php_version.', PHP 8.0 is not supported, PHP 8.1 is supported))';
        $check = 0;
    }
    $content .= '</strong>';

    // CHECK MBSTRING
    $content .= '<p><strong>mbsgtring extension: ';

    if (extension_loaded('mbstring') === true){
        $content .= ' <span style="color:#007700">Installed<span>';
    }
    else{

        $content .= ' <span style="color:#aa0000">NOT Installed (if you need to handle multibyte characters, you need to install it)<span>';
        $check = 0;
    }
    $content .= '</strong>';

    // CHECK IONCUBE
    $content .= '<p><strong>ioncube extension: ';

    if (extension_loaded('IonCube Loader') === true){
         $content .= ' <span style="color:#007700">Installed<span></strong>';

         $ioncube_version =  ioncube_loader_version();

         $content .= '<p><strong>ioncube extension version: </strong> '.$ioncube_version;

         $temp_ar = explode('.', $ioncube_version);

         if(count($temp_ar) === 1){
            $ioncube_version_normalized .= '.0';
         }
         else{
            $ioncube_version_normalized = implode('.', array_slice($temp_ar, 0, 2));
         }

         $content .= '<strong>';
         if ( $ioncube_version_normalized >= $min_ioncube_version) {
            $content .= ' <span style="color:#007700">OK<span>';
        }
        else{
            $content .= ' <span style="color:#aa0000">NO<span> (min ioncube version is: '.$min_ioncube_version.')';

            $check = 0;
        }
        $content .= '</strong>';

    }
    else{

        $content .= ' <span style="color:#aa0000">NOT Installed<span></strong>&nbsp;&nbsp;You can download it from here <a href="https://www.ioncube.com/loaders.php" targer="_blank">ioncube.com/loaders.php</a>';
        $check = 0;
    }

    // CHECK CONNECTION    
    $content .= '<p><strong>Check DB connection:  ';

    $conn = connect_db($host, $user, $pass, $db_name);

    if ($conn === NULL){
        $content .= ' <span style="color:#aa0000">There is a connection problem, check $host, $user, $pass, $db_schema, $db_name in config.php<span>';
        $check = 0;
    }
    else{
        $content .= ' <span style="color:#007700">OK<span>';
    }
     $content .= '</strong>';


    // CHECK DBMS VERSION
    $dbms_version = $conn->getAttribute(constant('PDO::ATTR_SERVER_VERSION'));

    if ($dbms_type === 'sqlserver'){
        $temp_ar = explode('.', $dbms_version);

        $dbms_version = $temp_ar[0];
    }

    $content .= '<p><strong>'.$dbms_type.' version:</strong> '.$dbms_version.' <strong>';

    if ( $dbms_version >= $min_dbms_version[$dbms_type] || $dbms_type === 'mysql' && strpos($dbms_version, 'MariaDB') !== false) { // mariadb is always ok
        $content .= ' <span style="color:#007700">OK<span>';
    }
    else{
        $content .= ' <span style="color:#aa0000">NO<span> (min '.$dbms_type.' version is: '.$min_dbms_version[$dbms_type].'. In some conditions the DBMS version detected and parsed might be not correct, if you think your DBMS version respects the minimum requirements, you can ignore this alert).';
        $check = 0;
    }
    $content .= '</strong>';


    $content .= '<p>For the addtional requirements specific to your DBSM (engine, encoding, ...) please check the <strong>requirements</strong> chapter of the documentation.</p>';


}

if ($check !== 1){

     $content .= '<p>Please fix the requirement issues before continuing.<br>If, for some reason, you want to force the upgrade (it is not recommended), continue anyway.</p>';
}


?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Upgrade DaDaBIK</title>
  </head>
  <body>
  
    <div id="container_title" class="container mt-5"><h1>Upgrade DaDaBIK</h1></div>
    <div id="container_main" class="container mt-5">
  <?php echo $content; ?>
</div>
<div id="container_buttons" class="container mt-5">
 <div class="d-none align-items-center" id="spinner">
  <div class="spinner-border mr-3" role="status" aria-hidden="true"></div>
  
  <strong>Loading...</strong>
  
</div>

</div>

<div id="container_buttons" class="container mt-5">
  <p><a class="btn btn-primary" href="up2.php" role="button" style="background-color: #676958; border-color:#676958">CONTINUE >></a>  <a href="up.php" id="btn_restart" class="btn btn-danger" role="button">RESTART</a>
  
</div>

   <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    
  </body>
</html>