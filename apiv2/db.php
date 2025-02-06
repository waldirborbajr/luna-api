<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "users";

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
//     $pdo->exec("set names utf8");
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}    
?>
