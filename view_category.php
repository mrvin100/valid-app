<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:index.php');
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

// for book saved
$select_bookmark = $conn->prepare("SELECT * FROM `bookmark_book` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked_book = $select_bookmark->rowCount();

$select_category = $conn->prepare("SELECT * FROM `category` WHERE id = ? AND status = ?");
$select_category->execute([$get_id, 'active']);
$fetch_category = $select_category->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Catégorie <?= $fetch_category['title']; ?></h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE category_id = ? AND status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute([$get_id, 'active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
               $tutor_id = $fetch_tutor['id'];
      ?>
      <div class="box">
         <a href="tutor_profile.php?get_id=<?= $tutor_id ?>" title="voir <?= $fetch_tutor['name']; ?>">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
          </div></a>
		 <a href="playlist.php?get_id=<?= $course_id; ?>" title="voir <?= $fetch_course['title']; ?>">
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         </a>
		 
		 <div class="info">
		  <p><i class="fas fa-video-camera"></i><span>127 M.</span></p>
			 <?php 
			 // likes for playlist : lfp
			 $select_lfp = $conn->prepare("SELECT * FROM `likes` WHERE playlist_id = ?");
			 $select_lfp->execute([$fetch_course['id']]);
			 $total_lfp = $select_lfp->rowCount();
			
			// bookmark for playlist : bfp
			 $select_bfp = $conn->prepare("SELECT * FROM `bookmark` WHERE playlist_id = ?");
			 $select_bfp->execute([$fetch_course['id']]);
			 $total_bfp = $select_bfp->rowCount();

			 ?>
		 	<p><i class="fas fa-heart"></i><span><?= $total_lfp; ?> likes</span></p>
		  <p><i class="fas fa-bookmark"></i><span><?= $total_bfp; ?> saved</span></p>
	   	 </div>
		  
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">aucune playlist ajoutée!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->












<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>