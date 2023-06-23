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
require './include/config.php'; 
require './include/db_functions_pdo.php'; 
require './include/header_install.php';

$function = 'install';
$page_name = 'check_requirements';
$check = 1;

$step = 1;
if (isset($_GET['step'])){
    $step = (int)$_GET['step'];
}

$inst_params['site_url'] = "http".(isset($_SERVER['HTTPS']) ? 's' : ''). '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';

$temp = file_get_contents('version_first_installation') or die('<p>[10] Error: the file <b>version_first_installation</b> is not available in the DaDaBIK folder.</p>');
$temp = explode(' ', $temp);
$inst_params['dadabik_version_edition'] = $temp[0].' '.$temp[1];

$inst_params['ioncube_version']  = 'none';
if (extension_loaded('IonCube Loader') === true){
     $inst_params['ioncube_version'] =  ioncube_loader_version();
}

echo '<div class="container_install_text">';

switch($step){
    case 1:
        echo '<h2>This procedue will guide you through the installation of DaDaBIK</h2>';
        
        echo "<p><b>If you continue, you will share with us the following info</b> <br/><br/>your DaDaBIK serial number: ".$serial_number."<br/>installation URL = ".$inst_params['site_url']."<br/>DBMS type = ".$dbms_type."<br/>ioncube version: ".$inst_params['ioncube_version']."<br/>installation_type (db, blank, csv, xls, ods or prepackaged)<br/>DB connection result (\"success\" or \"fail\")<br/>dadabik_version = ".$inst_params['dadabik_version_edition']."<br/>os = ".(php_uname('s'))."<br/>php_version = ".(phpversion())."<br/>date_time = ".date("Y-m-d H:i:s");
        
        echo '<p><form action="install.php?step=2" method="POST">';
        
        echo '<p>Please always check the latest online version of the license and privacy policy, which may have changed.<br>*I accept the <a href="https://dadabik.com/index.php?function=show_license" target="_blank">license</a> <input type="checkbox" name="accept_license" value="1"  required>';
        echo '<br>*I specifically accept articles 1, 4.2, 5 and 6 of the <a href="https://dadabik.com/index.php?function=show_license" target="_blank">license</a> <input type="checkbox" name="accept_license_2" value="1"  required>'; 
        echo '<br>*I accept the <a href="https://www.iubenda.com/privacy-policy/875935" target="_blank">privacy policy</a> <input type="checkbox" name="accept_privacy" value="1" required>';
    
        echo '<p><input type="submit" value="NEXT >>" style="font-size:20px;"> (please <span style="color:red;font-weight:bold">WAIT</span> after clicking)</form>';

        break;
    
    case 2:
        if (!isset($_POST['accept_license']) || !isset($_POST['accept_license_2']) || !isset($_POST['accept_privacy'])  || $_POST['accept_license'] !== '1' || $_POST['accept_license_2'] !== '1' || $_POST['accept_privacy'] !== '1' ){
            die('You have to accept privacy and license. <a href="install.php">Restart</a> the installation.');
        }
        echo '<h2>Requirements check</h2>';

        require './include/requirements_check.php';
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> true,
                "verify_peer_name"=> true
            ),
        );
        
        $connection_check = 0;
        if ($conn !== NULL){
            $connection_check = 1;
        }
        @file_get_contents('https://dadabik.com/ia.php?s='.urlencode($inst_params['site_url']).'&dbms='.$dbms_type.'&v='.urlencode($inst_params['dadabik_version_edition']).'&os='.urlencode(php_uname('s')).'&php='.urlencode(phpversion()).'&date_time='.urlencode(date("Y-m-d H:i:s")).'&s2='.urlencode($serial_number).'&i='.urlencode($inst_params['ioncube_version']).'&c='.$connection_check, false, stream_context_create($arrContextOptions));
        
        if ($check === 0){
            echo '<p style="color:#aa0000">Please fix the requirement issues and <a href="install.php">restart</a> the installation.<br><br>If, for some reason, you want to force the installation anyway (it is not recommended), run install2.php.</p>';
        }
        else{
            echo '<p><form action="install2.php" method="POST"><input type="submit" value="NEXT >>" style="font-size:20px;"></form>';
        }
        break;
}
echo '</div>';

require './include/footer_install.php';