<?php
session_start();
if(!isset($_SESSION['loggedin'])){
   header("Location: ./login.html");
   exit;
}
require_once("./connection.php");
$dbh = dbcon();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instagram</title>
  <link rel="icon" href="./pictures/instalogo.png" type="image/x-icon">
  <link href="./output.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/382a0b3e8b.js" crossorigin="anonymous"></script>
  <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
</head>
<body class="bg-white dark:bg-black">
<nav>
  <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
 </button>
 <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-[340px] h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-black border-r border-instalines">
     <img id="instalogoimage" class="w-[140px] pt-7 pb-7" src="./pictures/instalogotekst.png" alt="instalogotekst">
     <ul class="space-y-2 font-medium text-xl">
        <li>
           <a href="index.php" class="flex items-center p-2 text-black rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <i class="fa-solid fa-house text-black dark:text-white"></i>
               <span class="ms-3">Home</span>
           </a>
        </li>
        <li>
           <a href="search.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-solid fa-magnifying-glass text-black dark:text-white"></i>
              <span class="flex-1 ms-3 whitespace-nowrap">Search</span>
           </a>
        </li>
        <li>
           <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
           <i class="fa-regular fa-compass text-black dark:text-white"></i>
              <span class="flex-1 ms-3 whitespace-nowrap">Explore</span>
           </a>
        </li>
        <li>
           <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <i class="fa-solid fa-inbox text-black dark:text-white"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">Messages</span>
           </a>
        </li>
        <li>
           <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-regular fa-heart text-black dark:text-white"></i>
              <span class="flex-1 ms-3 whitespace-nowrap">Notifications</span>
           </a>
        </li>
        <li>
           <a href="create.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-solid fa-plus text-black dark:text-white"></i>
              <span class="flex-1 ms-3 whitespace-nowrap">Create</span>
           </a>
        </li>
        <li>
           <a href="profile.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                           <?php
                  $profile = profiles($dbh, $_SESSION['profile_id']);

                  foreach($profile as $profiles) {
                  $encodedProfilePic = base64_encode($profiles['profilepicture']);
                  if($profiles['profilepicture'] == NULL){
                     echo '<img class="rounded-full w-7 h-7" src="./pictures/defaultprofile.jpg" alt="image">';
                  }else{
                     echo '<img class="rounded-full w-7 h-7" src="data:image/jpeg;base64,'.$encodedProfilePic.'" alt="image">';
                  }
                  }
                  ?>
              <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
           </a>
        </li>
     </ul>
     <ul class="fixed space-y-2 bottom-0 mb-3 font-medium text-xl">
      <li>
            <a href="https://www.threads.net/" target="_blank" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
            <i class="fa-solid fa-at text-black dark:text-white"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Threads</span>
         </a>
      </li>
      <li>
         <div id="dropdownTop" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownTopButton">
              <li class="block px-4 py-2">
               <div class="flex">
               <span class="flex-1">Dark Mode</span> 
               <button id="theme-toggle" data-tooltip-target="tooltip-animation" data-tooltip-placement="bottom" type="button" class="flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm">
                 <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                 <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
               </button>
               </div>
             </li>
             <li>
               <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Log Out</a>
             </li>
            </ul>
        </div>
         <button id="dropdownTopButton" data-dropdown-toggle="dropdownTop" data-dropdown-placement="top" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group" type="button">
            <i class="fa-solid fa-bars text-black dark:text-white"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">More</span> 
            </button>
      </li>
     </ul>
  </div>
</aside>
</nav>
<div class="flex items-center justify-center mt-8 -ml-96">
<a href="./index.php"><p class="flex font-bold mr-[10px] text-lg dark:text-white">For you</p></a>
<a href="#"><p class="flex font-bold text-lg text-gray-400">Following</p></a>
</div>
<hr class="h-px w-[40%] ml-[32%] mt-3 bg-gray-200 border-0 dark:bg-gray-700">

<div class="flex flex-col items-center space-y-4 mt-4">
        <!-- Sample images (replace these with your actual image sources) -->

<?php
$posts = post($dbh);

foreach($posts as $post) {
$encodedImage = base64_encode($post['picture']);
$encodedProfilePic = base64_encode($post['profilepicture']);
echo '<div class="block">
<div class="h-[50px]">
<div class="flex">';
if($post['profilepicture'] == NULL){
   echo '<img class="rounded-full mx-1 my-1 w-11 h-11" src="./pictures/defaultprofile.jpg" alt="image">';
}else{
   echo '<img class="rounded-full mx-1 my-1 w-11 h-11" src="data:image/jpeg;base64,'.$encodedProfilePic.'" alt="image">';
}
  echo'
   <div class="flex flex-col mt-1.5">';
   if($_SESSION['profile_id'] == $post['profile_id']){
      echo '<a href="profile.php"><p class="flex items-center font-bold text-sm ml-1 text-black dark:text-white">'.$post['user_name'].'</p></a>';

   }else{
      echo '<a href="profilelooking.php?profile_id='.$post['profile_id'].'"><p class="flex items-center font-bold text-sm ml-1 text-black dark:text-white">'.$post['user_name'].'</p></a>';

   }
 echo '<p class="text-xs ml-1 text-black dark:text-white">'.$post['location'].'</p>
</div>
</div>
</div>
<img class="border border-instalines" src="data:image/jpeg;base64,'.$encodedImage.'" alt="Image 3" class="" width="390px" height="390px">
<div class="h-[10%] bg-white dark:bg-black">
   <div class="flex items-start mt-4 mb-4">
      <form action="connection.php" method="post">
         <input class="hidden" type="text" name="profile_id" value="'.$_SESSION['profile_id'].'">
         <input class="hidden" type="text" name="post_id" value="'.$post['post_id'].'">';
         if(likecheck($dbh, $post['post_id'], $_SESSION['profile_id'])){
            echo'<button class="mr-2" type="submit" name="unlike"><i class="fa-solid fa-heart text-red-600 fa-xl"></i></button>';
         }else{
            echo'<button class="mr-2" type="submit" name="like"><i class="fa-regular fa-heart text-black dark:text-white fa-xl"></i></button>';
         }
         echo'
      </form>
      <a class="mr-2" href=""><i class="fa-regular fa-comment text-black dark:text-white fa-xl"></i></a>
      <a class="" href=""><i class="fa-regular fa-share-from-square text-black dark:text-white fa-xl"></i></a>
   </div>';
   $likeCount = likecount($dbh, $post['post_id']);
   if($likeCount == ''){
      echo '<p class="text-black dark:text-white font-bold">0 likes</p>';
   }
   else{
      echo'<p class="text-black dark:text-white font-bold">'.$likeCount.' likes</p>';
   }
   echo '
   <p class="text-black dark:text-white font-bold mt-2">'.$post['user_name'].'</p>
   <div class="w-[390px]"><p class="text-black dark:text-white">'.htmlspecialchars($post['post_text']).'</p></div>
   <p class="text-instalines mt-2">View all comments</p>
</div>
<hr class="h-px w-full bg-instalines border">
</div>';
}
 
?>
</div>
<script src="darkmode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>