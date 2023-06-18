<?php

require "dbclass.php";

$dbObj404 = new database("localhost","root","");
$myUrl = $dbObj404->getMy404Url();
$conn = $dbObj404->dbConnect();
$dbObj404->shortRedirect();


?>