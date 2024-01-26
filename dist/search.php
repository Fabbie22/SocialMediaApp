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
<div class="flex flex-col items-center justify-center mt-8 ml-24">
    <form action="search.php" method="post" class="flex items-center mb-4">
        <input type="search" name="searchbar" class="w-80 dark:bg-black dark:text-white border border-gray-300 p-2">
        <input type="submit" value="Search" name="searchsubmit" class="!bg-instablue p-2 text-white font-bold cursor-pointer ml-2">
    </form>
    <div class="flex">
        <ul>
            <?php
                $searchQuery = isset($_POST['searchbar']) ? trim($_POST['searchbar']) : '';
                    if (!empty($searchQuery)) {
                        // Prepare and execute the SQL query
                        $sql = "SELECT * FROM profile WHERE user_name LIKE :searchQuery";
                        $stmt = $dbh->prepare($sql);
                        $searchParam = "%$searchQuery%";
                        $stmt->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
                        $stmt->execute();

                        // Fetch results
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Display the results or a message if no results found
                        if (!empty($results)) {
                            foreach ($results as $result) {
                                echo '
                                <div class="flex items-center mt-2">';
                                
                                $encodedProfilePic = base64_encode($result['profilepicture']);
                                
                                if ($result['profilepicture'] == NULL) {
                                    echo '<a href="profilelooking.php?profile_id='.$result['profile_id'].'"><img class="rounded-full w-16 h-16 mr-2" src="./pictures/defaultprofile.jpg" alt="image"></a>';
                                } else {
                                    echo '<a href="profilelooking.php?profile_id='.$result['profile_id'].'"><img class="rounded-full w-16 h-16 mr-2" src="data:image/jpeg;base64,'.$encodedProfilePic.'" alt="image"></a>';
                                }
                                echo '
                                <div>
                                    <a href="profilelooking.php?profile_id='.$result['profile_id'].'"><li class="text-black dark:text-white font-bold text-xl">' . $result['user_name'] . '</li></a>
                                    <p class="text-gray-400">'.$result['fullname'].'</p>
                                </div>
                                </div>';
                            }
                        } else {
                            echo '<li class="text-black dark:text-white font-bold">Nothing found</li>';
                        }
                    } else {
                        echo '<li class="text-black dark:text-white font-bold">Please enter a search</li>';
                    }
            ?>
        </ul>
    </div>
</div>


<script src="darkmode.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>