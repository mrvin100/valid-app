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

   <h1 class="heading">tous les cours</h1>
   <div class="sub-header">
	    <a href="tutorials.php"><i class="fab fa-connectdevelop"></i><span>tutoriels</span></a>
	    <a href="projects.php"><i class="fas fa-diagram-project"></i><span>projets</span></a>
       <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
		 <a href="shopping_cart.php"><i class="fas fa-shopping-bag"></i><span>panier</span><span style="margin-left: .7rem;"><?= $total_cart_items; ?></span></a>
    </div>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND type = ? ORDER BY date DESC");
         $select_courses->execute(['active', 'course']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <a href="tutor_profile.php?get_id=<?= $fetch_tutor['id']; ?>" title="voir <?= $fetch_tutor['name']; ?>">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
             </div>
         </div></a>
		 <a href="playlist.php?get_id=<?= $course_id; ?>" title="voir la playlist">
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         </a>
		 
		 <div class="info">
		  <p><i class="fas fa-video-camera"></i><span>869 M.</span></p>
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
		 
		 <?php /*?><div class="flex-icons">
		 <div class="icon">
		 <form action="" method="post" class="save-list">
			<input type="hidden" name="list_id" value="<?= $fetch_tutor['id']; ?>">
			<?php
			$select_subscribe = $conn->prepare("SELECT * FROM `subscribe` WHERE user_id = ? AND tutor_id = ?");
			$select_subscribe->execute([$user_id, $fetch_tutor['id']]);
			   if($select_subscribe->rowCount() > 0){
			?>
			<button type="submit" name="subscribe" class="icon" title="subscribed to <?= $fetch_tutor['name'] ?>"><div class="fas fa-jar-wheat" style="color: limegreen;"></div></button>
			<?php
			   }else{
			?>
			   <button type="submit" name="subscribe" class="icon" title="subscribe to <?= $fetch_tutor['name'] ?>"><div class="fas fa-jar" style="color: lightcoral;"></div></button>
			<?php
			   }
			?>
		 </form>
		 </div>
		 </div> <?php */?>
		 
		 
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">pas encore de cours ajouté!</p>';
      }
      ?>

   </div>

</section>

<!-- courses section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>