<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ha = "localhost";
$database_ha = "ha";
$username_ha = "ha";
$password_ha = "hadmin10";
$ha = mysql_pconnect($hostname_ha, $username_ha, $password_ha) or trigger_error(mysql_error(),E_USER_ERROR); 
?>