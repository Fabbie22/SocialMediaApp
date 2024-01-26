<?php
function dbcon(){
      $host = 'localhost';
      $dbname = 'instagramdatabase';
      $user = 'root';
      $password = '';
      
      $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
  
      return $dbh;
}
$dbh = dbcon();
function post($dbh){
      
      $posts = array();

      $stmt = $dbh->prepare("SELECT post.*, post_has_profile.*, profile.* FROM post
      LEFT JOIN post_has_profile
      ON post_has_profile.post_post_id = post.post_id
      LEFT JOIN profile
      ON profile.profile_id = post_has_profile.profile_profile_id ORDER BY datetime DESC");

      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $posts[] = $row;
      }

      return $posts;
}
function profiles($dbh, $id){

      $profile = array();

      $stmt = $dbh->prepare("SELECT profile.* FROM profile WHERE profile_id = $id ");

      $stmt->execute();

      if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $profile[] = $row;
      }

      return $profile;
}
function profileposts($dbh, $id){
      $profilepost = array();

      $stmt = $dbh->prepare("SELECT * FROM post
      INNER JOIN post_has_profile
      ON post_has_profile.post_post_id = post.post_id
      INNER JOIN profile
      ON profile.profile_id = post_has_profile.profile_profile_id
      WHERE profile_profile_id = $id");

      $stmt->execute();

      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $profilepost[] = $row;
      }

      return $profilepost;
}
function comments($dbh, $id){
    $comment = array();

    $stmt = $dbh->prepare("SELECT * FROM comment
    INNER JOIN post_has_comment
    ON post_has_comment.comment_comment_id = comment.comment_id
    INNER JOIN profile 
    ON comment.profile_profile_id = profile.profile_id
    WHERE post_post_id = $id");

    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $comment[] = $row;
    }

    return $comment;
}
function postcounting($dbh, $id){
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM post_has_profile WHERE profile_profile_id = $id");

    $stmt->execute();

    $postcount = $stmt->fetchColumn();

    return $postcount;
}
function followers($dbh, $id){
    $stmt = $dbh->prepare("SELECT count(follower) FROM follower WHERE following = $id");

    $stmt->execute();

    $followercount = $stmt->fetchColumn();

    return $followercount;
}
function following($dbh, $id){
    $stmt = $dbh->prepare("SELECT count(following) FROM follower WHERE follower = $id");

    $stmt->execute();

    $followingcount = $stmt->fetchColumn();

    return $followingcount;
}
function followcheck($dbh, $followerid, $followingid){
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM follower WHERE follower = $followerid AND following = $followingid");

    $stmt->execute();

    $count = $stmt->fetchColumn();

    return $count > 0;
}
function likecount($dbh, $post_id){
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `like` WHERE post_post_id = $post_id ");

    $stmt->execute();

    $likeCount = $stmt->fetchColumn();

    return $likeCount;
}
function likecheck($dbh, $post_id, $profile_id){
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM `like` WHERE post_post_id = $post_id AND profile_profile_id = $profile_id");

    $stmt->execute();

    $likeCheck = $stmt->fetchColumn();

    return $likeCheck > 0;
}
//Upload Image to Database
if (isset($_POST["upload"])) {
    $profile_id = $_POST['profileid'];

    // Check if a file is uploaded
    if (!empty($_FILES["profilepicture"]["name"])) {
        // File upload case
        $fileName = basename($_FILES["profilepicture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('png', 'jpg');
        if (!in_array($fileType, $allowTypes)) {
            echo "Invalid file type. Only PNG files are allowed.";
            exit;
        }

        $image = $_FILES['profilepicture']['tmp_name'];
        $imgContent = file_get_contents($image);

        // Update the profilepicture column in the database
        $update = $dbh->prepare("UPDATE profile SET profilepicture = :imgContent WHERE profile_id = :profile_id");

        // Bind the parameters
        $update->bindParam(':imgContent', $imgContent, PDO::PARAM_LOB);
        $update->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);

        // Execute the query
        $result = $update->execute();
    }

    // Check if the biography is provided
    if (isset($_POST['biography'])) {
        // Update the biography column in the database
        $updateBiography = $dbh->prepare("UPDATE profile SET biography = :biographyValue WHERE profile_id = :profile_id");

        // Bind the parameters
        $updateBiography->bindParam(':biographyValue', $_POST['biography'], PDO::PARAM_STR);
        $updateBiography->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);

        // Execute the query
        $resultBiography = $updateBiography->execute();

        // Set the overall result based on the biography update result
        $result = isset($result) ? $result && $resultBiography : $resultBiography;
    }

    // Check for success
    if ($result) {
        header('location: ./index.php');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if (isset($_POST["uploadpost"])) {
      $post_text = $_POST['post_text'];
      $location = $_POST['location'];  
      // Check if a file is uploaded
      if (!empty($_FILES["postimage"]["name"])) {
          // File upload case
          $fileName = basename($_FILES["postimage"]["name"]);
          $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
  
          // Allow certain file formats
          $allowTypes = array('png', 'jpg');
          if (!in_array($fileType, $allowTypes)) {
              echo "Invalid file type. Only PNG files are allowed.";
              exit;
          }
  
          $image = $_FILES['postimage']['tmp_name'];
          $imgContent = file_get_contents($image);
  
          // Update the profilepicture column in the database
          $stmt = $dbh->prepare("INSERT INTO post (post_text, picture, location, datetime) VALUES (:post_text, :picture, :location, NOW())");
  
          // Bind the parameters
          $stmt->bindParam(':post_text', $post_text, PDO::PARAM_STR);
          $stmt->bindParam(':picture', $imgContent, PDO::PARAM_LOB);
          $stmt->bindParam(':location', $location, PDO::PARAM_STR);
          // Execute the query
          $stmt->execute();
      }
      // Check for success

      $post_id = $dbh->lastInsertId();
  }
if (isset($_POST["uploadpost"])) {
      $profile_id = $_POST['profileid'];

      $addposttoprofile = $dbh->prepare("INSERT INTO post_has_profile (post_post_id, profile_profile_id) VALUES (:post_post_id, :profile_profile_id)");

      $result = $addposttoprofile->execute(array(
            'post_post_id' => $post_id,
            'profile_profile_id' => $profile_id
      ));

      if ($result) {
            header('location: ./profile.php');
            exit;
        } else {
            echo "Database update failed.";
            exit;
        }

}
if(isset($_POST['addcomment'])){
    $comment = $_POST['comment'];
    $profileid = $_POST['profileid'];

    $addcomment = $dbh->prepare("INSERT INTO comment (comment_text, datetime, profile_profile_id) VALUES (:comment_text, NOW(), :profile_profile_id)");

    $addcomment->execute(array(
        'comment_text' => $comment,
        'profile_profile_id' => $profileid
    ));

    $commentid = $dbh->lastInsertId();
}
if(isset($_POST['addcomment'])){
    $postid = $_POST['postid'];

    $addcomment = $dbh->prepare("INSERT INTO post_has_comment (post_post_id, comment_comment_id) VALUES (:post_post_id, :comment_comment_id)");

    $result = $addcomment->execute(array(
        'post_post_id' => $postid,
        'comment_comment_id' => $commentid
    ));

    if ($result) {
        header('location: ./index.php');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if(isset($_POST['follow'])){
    $followingid = $_POST['followingid'];
    $followerid = $_POST['followerid'];

    $addfollow = $dbh->prepare("INSERT INTO follower (follower, following) VALUES (:follower, :following)");

    $result = $addfollow->execute(array(
        'follower' => $followerid,
        'following' => $followingid
    ));

    if ($result) {
        header('location: ./profilelooking.php?profile_id='.$followingid.'');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if(isset($_POST['unfollow'])){
    $followingid = $_POST['followingid'];
    $followerid = $_POST['followerid'];

    $removefollow = $dbh->prepare("DELETE FROM follower WHERE follower = :followerid AND following = :followingid");

    $result = $removefollow->execute(array(
        'followerid' => $followerid,
        'followingid' => $followingid
    ));

    if ($result) {
        header('location: ./profilelooking.php?profile_id='.$followingid.'');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if(isset($_POST['like'])){
    $postid = $_POST['post_id'];
    $profileid = $_POST['profile_id'];

    $addlike = $dbh->prepare("INSERT INTO `like` (post_post_id, profile_profile_id) VALUES (:post_post_id, :profile_profile_id)");

    $result = $addlike->execute(array(
        'post_post_id' => $postid,
        'profile_profile_id' => $profileid
    ));

    if ($result) {
        header('location: index.php');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if(isset($_POST['unlike'])){
    $postid = $_POST['post_id'];
    $profileid = $_POST['profile_id'];

    $removelike = $dbh->prepare("DELETE FROM `like` WHERE post_post_id = :post_post_id AND profile_profile_id = :profile_profile_id");

    $result = $removelike->execute(array(
        'post_post_id' => $postid,
        'profile_profile_id' => $profileid
    ));

    if ($result) {
        header('location: index.php');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}
if (isset($_POST["updatepost"])) {
    $post_id = $_POST['postid'];

    // Check if a file is uploaded
    if (!empty($_FILES["profilepicture"]["name"])) {
        // File upload case
        $fileName = basename($_FILES["profilepicture"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('png', 'jpg');
        if (!in_array($fileType, $allowTypes)) {
            echo "Invalid file type. Only PNG files are allowed.";
            exit;
        }

        $image = $_FILES['profilepicture']['tmp_name'];
        $imgContent = file_get_contents($image);

        // Update the profilepicture column in the database
        $update = $dbh->prepare("UPDATE post SET picture = :imgContent WHERE post_id = :post_id");

        // Bind the parameters
        $update->bindParam(':imgContent', $imgContent, PDO::PARAM_LOB);
        $update->bindParam(':post_id', $post_id, PDO::PARAM_INT);

        // Execute the query
        $result = $update->execute();
    }

    // Check if the biography is provided
    if (isset($_POST['posttext'])) {
        // Update the biography column in the database
        $updateBiography = $dbh->prepare("UPDATE post SET post_text = :post_text WHERE post_id = :post_id");

        // Bind the parameters
        $updateBiography->bindParam(':post_text', $_POST['posttext'], PDO::PARAM_STR);
        $updateBiography->bindParam(':post_id', $post_id, PDO::PARAM_INT);

        // Execute the query
        $resultBiography = $updateBiography->execute();

        // Set the overall result based on the biography update result
        $result = isset($result) ? $result && $resultBiography : $resultBiography;
    }

    // Check for success
    if ($result) {
        header('location: ./index.php');
        exit;
    } else {
        echo "Database update failed.";
        exit;
    }
}