<?php 

require "dbclass.php";

$dbObj = new database("localhost","root","");
$dbObj->dbConnect();
$dbObj->dbCreate();
$dbObj->tableCreate();
$dbObj->render();


