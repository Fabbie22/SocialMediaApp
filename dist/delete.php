<?php

require_once("./connection.php");

$dbh = dbcon();

if(isset($_GET['post_id'])){

    $postverwijder = $_GET['post_id'];
    $profileverwijder = $_GET['profile_id'];

    $query = "DELETE FROM post_has_profile WHERE profile_profile_id = :profile_id AND post_post_id = :post_id;
    DELETE FROM post WHERE post_id = :post_id";

    $stmt = $dbh->prepare($query);

    $data = [
        ':post_id' => $postverwijder,
        ':profile_id' => $profileverwijder
    ];

    $stmt->execute($data);
    
    header("Location: profile.php");
}
