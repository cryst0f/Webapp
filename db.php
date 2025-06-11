<?php

$dbhost = "localhost";
$dbport = "5432"; 
$dbname = "postgres";
$dbuser = "johndoe";
$dbpassword = "root";

$con = pg_connect("host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpassword");

?>