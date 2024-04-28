<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

// for book
$select_bookmark = $conn->prepare("SELECT * FROM `bookmark_book` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked_book = $select_bookmark->rowCount();

// for tutor subscripted
$select_tutor_subs = $conn->prepare("SELECT * FROM `subscribe` WHERE user_id = ?");
$select_tutor_subs->execute([$user_id]);
$total_tutor_subs = $select_tutor_subs->rowCount();


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>profile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="profile">

   <h1 class="heading">détails du profil</h1>

   <div class="details">

      <div class="user">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <p>étudiant(e)</p>
         <a href="update.php" class="inline-btn">modifier mon profil</a>
      </div>

      <div class="box-container">

         <div class="box">
            <div class="flex">
               <i class="fas fa-bookmark"></i>
               <div>
                  <h3><?= $total_bookmarked; ?></h3>
                  <span>saved playlists</span>
               </div>
            </div>
            <a href="bookmark.php#bookmark" class="inline-btn">mes playlists</a>
         </div>
		  
		  <div class="box">
            <div class="flex">
               <i class="fas fa-book-bookmark"></i>
               <div>
                  <h3><?= $total_bookmarked_book; ?></h3>
                  <span>saved books</span>
               </div>
            </div>
            <a href="bookmark.php#bookmark_book" class="inline-btn">bibliothèque</a>
         </div>

         <div class="box">
            <div class="flex">
               <i class="fas fa-heart"></i>
               <div>
                  <h3><?= $total_likes; ?></h3>
                  <span>vidéos aimés</span>
               </div>
            </div>
            <a href="likes.php" class="inline-btn">mes likes</a>
         </div>

         <div class="box">
            <div class="flex">
               <i class="fas fa-comment"></i>
               <div>
                  <h3><?= $total_comments; ?></h3>
                  <span>vidéos commentées</span>
               </div>
            </div>
            <a href="comments.php" class="inline-btn">commentaires</a>
         </div>
		 <div class="box">
            <div class="flex">
               <i class="fas fa-users-cog"></i>
               <div>
                  <h3><?= $total_tutor_subs; ?></h3>
                  <span>tuteurs suivis</span>
               </div>
            </div>
            <a href="tutors_sub.php" class="inline-btn">mes tuteurs</a>
         </div>

      </div>

   </div>

</section>

<!-- profile section ends -->












<!-- footer section starts  -->

<?php include 'components/footer.php'; ?>

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>