<?php
session_start();
if(!isset($_SESSION['loggedin'])){
   header("Location: ./login.html");
   exit;
}
require_once("./connection.php");
$dbh = dbcon();
if(isset($_GET['profile_id']))
{
  $profile_id = $_GET['profile_id'];
  
  $query = "SELECT * FROM profile WHERE profile_id = :profile_id";
  $statement = $dbh->prepare($query);
  $data = [':profile_id' => $profile_id];
  $statement->execute($data);
  
  $result = $statement->fetch(PDO::FETCH_ASSOC);
}
$profile = profiles($dbh, $result['profile_id']);
foreach($profile as $profiles){
   $name = $profiles['user_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $name; ?> • Instagram</title>
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
    <div class="h-full px-3 py-4 overflow-y-auto border-r border-instalines bg-white dark:bg-black">
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
<div class="flex items-center justify-center mt-8">
<?php
$profile = profiles($dbh, $result['profile_id']);

foreach($profile as $profiles) {
$encodedProfilePic = base64_encode($profiles['profilepicture']);

echo'
<div class="flex items-center">';
   if($profiles['profilepicture'] == NULL){
      echo '<img class="rounded-full mx-5 w-36 h-36" src="./pictures/defaultprofile.jpg" alt="image">';
   }else{
      echo '<img class="rounded-full mx-5 w-36 h-36" src="data:image/jpeg;base64,'.$encodedProfilePic.'" alt="image">';
   }
    echo '
    <div class="flex flex-col ml-6">
        <div class="flex items-start mb-5">
            <p class="text-black dark:text-white text-xl font-normal mr-3">'.$profiles['user_name'].'</p>
            <form action="connection.php" method="post">
               <input type="text" class="hidden" name="followingid" value="'.$profiles['profile_id'].'">
               <input type="text" class="hidden" name="followerid" value="'.$_SESSION['profile_id'].'">';
            if(followcheck($dbh, $_SESSION['profile_id'], $profiles['profile_id'])){
               echo'
               <button class="rounded-lg font-bold !bg-instabuttonlight dark:!bg-instabuttondark hover:!bg-instabuttonlighthover dark:hover:!bg-instabuttondarkhover p-1 px-4 text-black dark:text-white mr-3" type="submit" name="unfollow">Following</button>';
            }else{
               echo '<button class="rounded-lg font-bold !bg-instabuttonblue hover:!bg-instabuttonbluehover p-1 px-4 text-white mr-3" type="submit" name="follow">Follow</button>';
            }
            echo'
            </form>
        </div>
        <div class="flex">';
        $postcount = postcounting($dbh, $profiles['profile_id']);
        $followercount = followers($dbh, $profiles['profile_id']);
        $followingcount = following($dbh, $profiles['profile_id']);
        echo '
            <p class="text-black dark:text-white mr-3"><b>'.$postcount.'</b> posts </p>
            <p class="text-black dark:text-white mr-3"><b>'.$followercount.'</b> followers </p>
            <p class="text-black dark:text-white"><b>'.$followingcount.'</b> following </p>
        </div>
        <div class="flex">
            <p class="font-bold text-black dark:text-white mt-2">'.$profiles['fullname'].'</p>
        </div>
        <div class="flex w-[300px]">
        <p class="font-normal text-black dark:text-white mt-2">'.htmlspecialchars($profiles['biography']).'</p>
    </div>
    </div>
</div>
';
}
?>
</div>
<hr class="h-px w-[40%] ml-[32%] mt-11 bg-gray-200 border-0 dark:bg-gray-700">
<div class="flex items-center justify-center ml-3 mb-3">
   <div class="grid grid-cols-3 gap-1 mt-10 ml-[64.5px]">
<?php
$profilepost = profileposts($dbh, $result['profile_id']);

foreach($profilepost as $profileposts) {
$encodedImage = base64_encode($profileposts['picture']);
echo '<img data-modal-target="'.$profileposts['post_id'].'" data-modal-toggle="'.$profileposts['post_id'].'" src="data:image/jpeg;base64,'.$encodedImage.'" alt="Image 1" class="w-[250px] h-[250px] hover:opacity-25 group-[appel]:">';
}
?>
   </div>
</div>
<?php
foreach($profilepost as $profileposts) {
   //Modal
    $ModalImage = base64_encode($profileposts['picture']);   
    echo '<div id="'.$profileposts['post_id'].'" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-4xl max-h-full">
            <div class="relative bg-white rounded shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 rounded-t">
                    <div class="flex items-center">';
                    if($profiles['profilepicture'] == NULL){
                     echo '<img class="flex-shrink-0 rounded-full w-16 h-16" src="./pictures/defaultprofile.jpg" alt="image">';
                  }else{
                     echo '<img class="flex-shrink-0 rounded-full w-16 h-16" src="data:image/jpeg;base64,'.$encodedProfilePic.'" alt="profile-picture">';
                  }
                  echo '
                        <div class="ml-4">
                            <p class="font-bold text-black dark:text-white">'.$profileposts['user_name'].'</p>
                            <p class="text-sm text-black dark:text-white">'.$profileposts['location'].'</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="'. $profileposts['post_id'].'">
                              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                              </svg>
                              <span class="sr-only">Close modal</span>
                        </button>
                     </div>
                </div>
                <div class="flex">
                <div id="dropdown-'.$profileposts['post_id'].'" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                   <li>
                   <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                   </li>
                   <li>
                   <a href="delete.php?post_id='.$profileposts['post_id'].'&profile_id='.$_SESSION['profile_id'].'" class="block px-4 py-2 text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-red-500">Delete</a>
                   </li>
                </ul>
             </div>
             <div class="max-h-[50%] max-w-[50%] left-0 mr-3 mb-4">
             <img src="data:image/jpeg;base64,'.$ModalImage.'" alt="image">
           </div>
                    <div class="flex flex-col w-full">
                        <div class="text-base leading-relaxed text-black dark:text-white border-b border-instalines">
                            '.htmlspecialchars($profileposts['post_text']).'
                        </div>
                           <div class="p-4 md:p-5 overflow-y-auto max-h-[300px]">';
                           $comment = comments($dbh, $profileposts['post_id']);

                           foreach($comment as $comments){
                              $datetimecomment = date('d-m-Y', strtotime($comments['datetime']));
                              echo '
                              <div class="flex items-center mb-2">';
                              $commentProfile = base64_encode($comments['profilepicture']);
                              if($comments['profilepicture'] == NULL){
                                 echo '<img class="flex-shrink-0 items-center justify-center rounded-full w-8 h-8" src="./pictures/defaultprofile.jpg" alt="image">';
                              }else{
                                 echo '<img class="flex-shrink-0 items-center justify-center rounded-full w-8 h-8" src="data:image/jpeg;base64,'.$commentProfile.'" alt="profile-picture">';
                              }
                              echo '
                              <div class="block">                              
                                 <p class="text-black dark:text-white ml-2">'."<strong>".$comments['user_name']."</strong>"." ".htmlspecialchars($comments['comment_text']).'</p>
                                 <p class="text-gray-500 dark:text-gray-400 text-sm ml-3">'.$datetimecomment.'</p>
                              </div>
                              </div>';
                           }
                           echo '
                           </div>
                           <div class="flex-grow flex items-end w-full">
                            <div class="relative w-full">
                            <form action="connection.php" method="post">
                              <input type="hidden" name="profileid" value="'.$_SESSION['profile_id'].'">
                              <input type="hidden" name="postid" value="'.$profileposts['post_id'].'">
                              <input class="w-full p-5 text-black dark:text-white dark:bg-gray-700 border-t border-instalines pr-10" placeholder="Add comment..." name="comment"/>
                              <button class="absolute right-0 bottom-0 p-4 items-center text-instablue dark:instablue" type="submit" name="addcomment">Submit</button>
                            </form>
                              </div>                             
                           </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
?>
<script src="darkmode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>