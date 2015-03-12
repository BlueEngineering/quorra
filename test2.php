<?php
require_once("lib/user/ec_user.php");
session_start();

echo "<pre>";
print_r( $_SESSION["user"] );
echo "</pre>";

echo "Benutzeremail ist: " . $_SESSION["user"]->get_user_name();

?>
