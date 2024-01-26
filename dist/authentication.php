<?php
session_start();

require_once("./connection.php");

$dbh = dbcon();

$data = $_POST;

if(isset($data["login"])) {  
  $query = "SELECT * FROM profile WHERE (user_name = :user_name OR emailnumber = :emailnumber)";  
  $statement = $dbh->prepare($query);  
  $statement->execute(  
      array(  
          'user_name' => $data["user_name"],
          'emailnumber' => $data["user_name"]
      )  
  );

  $row = $statement->fetch(PDO::FETCH_ASSOC);
  if($row) {
      // Vergelijk het ingevoerde wachtwoord met het gehashte wachtwoord uit de database
      if(password_verify($data['password'], $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['profile_id'] = $row['profile_id'];
        $_SESSION["username"] = $row["user_name"];
        $_SESSION['emailnumber'] = $row['emailnumber'];
        $_SESSION['loggedin'] = TRUE;

          header("Location:./index.php");
          exit();
      }
  }
  
  header("Location: ./login.html");  
  exit();
}

