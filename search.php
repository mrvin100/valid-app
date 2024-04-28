<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">résultats de playlists</h1>

   <div class="box-container">

      <?php
         if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search_course = $_POST['search'];
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search_course}%' AND status = ?");
         $select_courses->execute(['active']);
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
		 <a href="playlist.php?get_id=<?= $course_id; ?>" title="view playlist">
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
         echo '<p class="empty">aucun cours trouvé!</p>';
      }
      }else{
         echo '<p class="empty">merci d\'éffectuer une recherche!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->

<!-- videos container section starts  -->

<section class="videos-container">

   <h1 class="heading">résultats de vidéos</h1>

   <div class="box-container">

      <?php
         if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search_video = $_POST['search'];
         $select_videos = $conn->prepare("SELECT * FROM `content` WHERE title LIKE '%{$search_video}%' AND status = ?");
         $select_videos->execute(['active']);
         if($select_videos->rowCount() > 0){
            while($fetch_video = $select_videos->fetch(PDO::FETCH_ASSOC)){
				$video_id = $fetch_video['id'];
      ?>
      <a href="watch_video.php?get_id=<?= $fetch_video['id']; ?>" class="box">
         <i class="fas fa-play"></i>
         <img src="uploaded_files/<?= $fetch_video['thumb']; ?>" alt="">
         <h3><?= $fetch_video['title']; ?></h3>
      </a>
      <?php
         }
      }else{
         echo '<p class="empty">aucune vidéo trouvée!</p>';
      }
      }else{
         echo '<p class="empty">merci d\'éffectuer une recherche!</p>';
      }
      ?>

   </div>

   
	
</section>

<!-- videos container section ends -->
	
<!-- playlist book section start -->	
<section class="playlist">
	
	<h1 class="heading">résultats de livres</h1>
		  
	<div class="row book" id="book">
	<?php
	 if(isset($_POST['search']) or isset($_POST['search_btn'])){
     $search_book = $_POST['search'];
	 $select_book = $conn->prepare("SELECT * FROM `book` WHERE title LIKE '%{$search_book}%' AND status = ?");
	 $select_book->execute(['active']);
	 if($select_book->rowCount() > 0){
		while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){  
			$book_id = $fetch_book['id'];
			
	?>
		
	  <div class="col box-container book-search" title="<?= $fetch_book['title']; ?>">
		<div class="box">	
		<a href="reader.php?get_id=<?= $fetch_book['id']; ?>" class="box-link">
		<div class="thumb">
			<img src="uploaded_files/<?= $fetch_book['thumb']; ?>" alt="">
		</div>
		<div class="name">
			<h3><?= $fetch_book['title']; ?></h3>
		</div>
		</a>
		</div>
	  </div>
	  <?php
			 }
		  }else{
			 echo '<p class="empty">aucun livre trouvé!</p>';
		  }
		  }else{
			 echo '<p class="empty">merci d\'éffectuer une recherche!</p>';
		  }
	  ?>
	</div>
</section>
<!-- playlist book section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>