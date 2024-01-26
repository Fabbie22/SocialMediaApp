<?php
require_once('./connection.php');

$dbh = dbcon();

$data = $_POST;

$emailnumber = $data['emailnumber'];
$name = $data['name'];
$username = $data['username'];

$password = password_hash($data['password'], PASSWORD_DEFAULT);

$stmt = $dbh->prepare("INSERT INTO profile (fullname, user_name, emailnumber, password) VALUES (:fullname, :user_name, :emailnumber, :password)");

$result = $stmt->execute(array(
  'fullname' => $name,
  'user_name' => $username,
  'emailnumber' => $emailnumber,
  'password' => $password
));

if ($result) {
  header('location: login.html');
  exit;
} else {
  echo "Database update failed.";
  exit;
}