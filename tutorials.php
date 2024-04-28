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
   <title>tutorials</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- tutorials section starts  -->

<section class="courses">

   <h1 class="heading">tous les tutoriels</h1>
   <div class="sub-header">
	    <a href="projects.php"><i class="fas fa-diagram-project"></i><span>projets</span></a>
	    <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>cours</span></a>
       <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
		 <a href="shopping_cart.php"><i class="fas fa-shopping-bag"></i><span>panier</span><span style="margin-left: .7rem;"><?= $total_cart_items; ?></span></a>
    </div>

   <div class="box-container">

      <?php
         $select_tutorials = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? AND type = ? ORDER BY date DESC");
         $select_tutorials->execute(['active', 'tutorial']);
         if($select_tutorials->rowCount() > 0){
            while($fetch_tutorial = $select_tutorials->fetch(PDO::FETCH_ASSOC)){
               $tutorial_id = $fetch_tutorial['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_tutorial['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <a href="tutor_profile.php?get_id=<?= $fetch_tutor['id']; ?>" title="voir <?= $fetch_tutor['name']; ?>">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutorial['date']; ?></span>
            </div>
         </div></a>
		 <a href="playlist.php?get_id=<?= $tutorial_id; ?>" title="voir la playlist">
         <img src="uploaded_files/<?= $fetch_tutorial['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_tutorial['title']; ?></h3>
         </a>
		 <div class="info">
		  <p><i class="fas fa-video-camera"></i><span>127 M.</span></p>
			 <?php 
			 // likes for playlist : lfp
			 $select_lfp = $conn->prepare("SELECT * FROM `likes` WHERE playlist_id = ?");
			 $select_lfp->execute([$fetch_tutorial['id']]);
			 $total_lfp = $select_lfp->rowCount();
			
			// bookmark for playlist : bfp
			 $select_bfp = $conn->prepare("SELECT * FROM `bookmark` WHERE playlist_id = ?");
			 $select_bfp->execute([$fetch_tutorial['id']]);
			 $total_bfp = $select_bfp->rowCount();

			 ?>
		 	<p><i class="fas fa-heart"></i><span><?= $total_lfp; ?> likes</span></p>
		  <p><i class="fas fa-bookmark"></i><span><?= $total_lfp; ?> saved</span></p>
	   	 </div>
		  
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">pas encore de tutoriel ajouté!</p>';
      }
      ?>

   </div>

</section>

<!-- tutorials section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>