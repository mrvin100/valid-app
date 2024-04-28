<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookmarks</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses" id="bookmark">

   <h1 class="heading">mes playlists</h1>

   <div class="box-container">

      <?php
         $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
         $select_bookmark->execute([$user_id]);
         if($select_bookmark->rowCount() > 0){
            while($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)){
               $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND status = ? ORDER BY date DESC");
               $select_courses->execute([$fetch_bookmark['playlist_id'], 'active']);
               if($select_courses->rowCount() > 0){
                  while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){

                  $course_id = $fetch_course['id'];

                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <a href="playlist.php?get_id=<?= $course_id; ?>" title="voir <?= $fetch_course['title']; ?>">
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3></a>
      </div>
      <?php
               }
            }else{
               echo '<p class="empty">aucune playlist trouvée!</p>';
            }
         }
      }else{
         echo '<p class="empty">aucune playlist marquée!</p>';
      }
      ?>

   </div>

</section>

<section class="playlist" id="bookmark_book">

   <h1 class="heading">livres marqués</h1>

   <div class="row book">

      <?php
         $select_bookmark = $conn->prepare("SELECT * FROM `bookmark_book` WHERE user_id = ?");
         $select_bookmark->execute([$user_id]);
         if($select_bookmark->rowCount() > 0){
            while($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)){
               $select_courses = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND status = ? ORDER BY date DESC");
               $select_courses->execute([$fetch_bookmark['book_id'], 'active']);
               if($select_courses->rowCount() > 0){
                  while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){

                  $course_id = $fetch_course['playlist_id'];

                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
	  <div class="col box-container">
      <div class="box">
         <div class="tutor" style="display: none;">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
		  <a href="playlist.php?get_id=<?= $course_id; ?>#book">
		  <div class="thumb">
		  	<img src="uploaded_files/<?= $fetch_course['thumb']; ?>" alt="img">
		  </div>
          <div class="name">
		 	<h3 class="title"><?= $fetch_course['title']; ?></h3>
		  </div>
          </a>
      </div>
	  </div>
      <?php
               }
            }else{
               echo '<p class="empty">aucun livre trouvé!</p>';
            }
         }
      }else{
         echo '<p class="empty">aucun livre marqué!</p>';
      }
      ?>

   </div>

</section>








<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>