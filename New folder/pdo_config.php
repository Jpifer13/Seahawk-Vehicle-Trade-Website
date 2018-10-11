<?php
define(DBCONNSTRING,'mysql:host=127.0.0.1;dbname=databaseName');
define(DBUSER, 'username');
define(DBPASS,'password');
try {
$conn= new PDO(DBCONNSTRING, DBUSER, DBPASS);
echo "Connection successful!!"; //Delete this line later
} catch (PDOException $e) {
echo $e->getMessage();
}
?>