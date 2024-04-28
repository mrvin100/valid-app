<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>valid | home</title>

  <!-- favicon -->
  <link rel="shortcut icon" href="images/icons/favicon.ico" type="image/x-icon">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">options rapides</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">likes et commentaires</h3>
         <div class="flex-group">
		 <p>likes : <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">likes</a></div>
		<div class="flex-group">
         <p>comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">comments</a>
		 </div>
		 <div class="flex-group">
         <p>signets : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php#bookmark" class="inline-btn">signets</a>
		 </div>
		 <div class="flex-group"> 
		 <p>lirves : <span><?= $total_bookmarked_book; ?></span></p>
         <a href="bookmark.php#bookmark_book" class="inline-btn">livres</a>
		 </div>
		  
      </div>
      <?php
         }else{ 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">se connecter ou s'inscrire</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn" style="overflow: hidden;">connexion</a>
            <a href="register.php" class="option-btn">s'inscrire</a>
         </div>
      </div>
      <?php
      }
      ?>
	 
      <div class="box">
         <h3 class="title">principales catégories</h3>
		  <?php
	  	//for categories
			$select_categories = $conn->prepare("SELECT `category`.id, `category`.title, `category`.icon  FROM `category` INNER JOIN `playlist` ON `category`.id = `playlist`.category_id  WHERE `category`.status = ? AND `playlist`.status = ? GROUP BY `playlist`.category_id ORDER BY COUNT(*) DESC LIMIT 8");
			 $select_categories->execute(['active','active']);
			 if($select_categories->rowCount() > 0){
				while($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)){
				   $category_id = $fetch_category['id']; 

		  ?>
         <div class="flex flex-categories">
            <a href="view_category.php?get_id=<?= $category_id; ?>"><i class="<?= $fetch_category['icon'] ?>"></i><span><?= $fetch_category['title'] ?></span></a>
			 
         </div>
		  <?php
				}
			 }else{
					echo '<p class="empty">pas catégories ajoutée!</p>';
				}
		  
		  ?>
      </div>

      <div class="box">
         <h3 class="title">sujets populaires</h3>
		 <?php
	  	//for topics
			$select_topics = $conn->prepare("SELECT * FROM `playlist` INNER JOIN `likes` ON `playlist`.id = `likes`.playlist_id  WHERE `playlist`.status = ? GROUP BY `likes`.playlist_id ORDER BY COUNT(*) DESC LIMIT 8");
			 $select_topics->execute(['active']);
			 if($select_topics->rowCount() > 0){
				while($fetch_topic = $select_topics->fetch(PDO::FETCH_ASSOC)){
				   	$topic_id = $fetch_topic['id'];

		  ?>
         <div class="flex flex-categories">
			<a href="playlist.php?get_id=<?= $topic_id; ?>"><i class="<?= $fetch_topic['icon'] ?>"></i><span><?= $fetch_topic['title'] ?></span></a>
         </div>
		  <?php
				}
			 }else{
					echo '<p class="empty">pas de sujet ajouté !</p>';
				}
		  
		  ?>
      </div>

      <div class="box tutor">
         <h3 class="title">devenir tuteur</h3>
         <p>rejoignez notre communauté d'experts qui ont appris le mieux en partageant avec vous !</p>
         <a href="contact.php" class="inline-btn">commencer</a>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">dernières playlists</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
                $tutor_id = $fetch_tutor['id'];
      ?>
	  
      <div class="box">
      <a href="tutor_profile.php?get_id=<?= $tutor_id; ?>" title="voir <?= $fetch_tutor['name']; ?>">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
			
         </div></a>
		 <a href="playlist.php?get_id=<?= $course_id; ?>" title="voir <?= $fetch_course['title']; ?>">
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3></a>
		 <div class="info">
		 	<p><i class="fas fa-video-camera"></i><span>3.5 B.</span></p>
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
         echo '<p class="empty">pas de playlist ajouté !</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">Voir plus</a>
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