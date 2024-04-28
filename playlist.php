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

if(isset($_POST['save_list'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'playlist supprimée !';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `bookmark`(user_id, playlist_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'playlist sauvegardée !';
      }

   }else{
      $message[] = 'merci de vous connecter d\'abord';
   }

}

if(isset($_POST['save_book'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `bookmark_book` WHERE user_id = ? AND book_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `bookmark_book` WHERE user_id = ? AND book_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'livre supprimé !';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `bookmark_book`(user_id, book_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'livre sauvegardé !';
      }

   }else{
      $message[] = 'merci de vous connecter d\'abord';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>playlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- playlist section starts  -->

<section class="playlist">

   <h1 class="heading">détails de la playlist</h1>

   <div class="row">

      <?php
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? and status = ? LIMIT 1");
         $select_playlist->execute([$get_id, 'active']);
         if($select_playlist->rowCount() > 0){
            $fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC);

            $playlist_id = $fetch_playlist['id'];

            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_playlist['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

            $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $select_bookmark->execute([$user_id, $playlist_id]);

      ?>

      <div class="col">
         <form action="" method="post" class="save-list">
            <input type="hidden" name="list_id" value="<?= $playlist_id; ?>">
            <?php
               if($select_bookmark->rowCount() > 0){
            ?>
            <button type="submit" name="save_list"><i class="fas fa-bookmark"></i><span>saved</span></button>
            <?php
               }else{
            ?>
               <button type="submit" name="save_list"><i class="far fa-bookmark"></i><span>save playlist</span></button>
            <?php
               }
            ?>
         </form>
         <div class="thumb">
            <span><?= $total_videos; ?> vidéos</span>
            <img src="uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
      </div>

      <div class="col">
      <a href="tutor_profile.php?get_id=<?= $fetch_tutor['id']; ?>">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
          </div></a>
         <div class="details">
            <h3><?= $fetch_playlist['title']; ?></h3>
            <p><?= $fetch_playlist['description']; ?></p>
            <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
      </div>

      <?php
         }else{
            echo '<p class="empty">cette playlist n\'a pas été trouvée !</p>';
         }  
      ?>

   </div>
   
	<!-- playlist book start -->	

	<div class="row book" id="book">
	<?php
	 $select_book = $conn->prepare("SELECT * FROM `book` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
	 $select_book->execute([$get_id, 'active']);
	 if($select_book->rowCount() > 0){
		while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){  
			$book_id = $fetch_book['id'];

			$select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
			$select_tutor->execute([$fetch_book['tutor_id']]);
			$fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

			$select_bookmark = $conn->prepare("SELECT * FROM `bookmark_book` WHERE user_id = ? AND book_id = ?");
			$select_bookmark->execute([$user_id, $book_id]);
		  ?>
			  
		  <div class="col box-container">
			<div class="box">	
			
		  	<a href="reader.php?get_id=<?= $fetch_book['id']; ?>" class="box-link" title="<?= $fetch_book['title']; ?>">
			 <div class="thumb">
				<form action="" method="post" class="save-list">
					<input type="hidden" name="list_id" value="<?= $book_id; ?>">
					<?php
					   if($select_bookmark->rowCount() > 0){
					?>
					<button type="submit" name="save_book"><i class="fas fa-bookmark"></i><p>saved</p></button>
					<?php
					   }else{
					?>
					   <button type="submit" name="save_book"><i class="far fa-bookmark"></i><p>save book</p></button>
					<?php
					   }
					?>
				 </form>
				<img src="uploaded_files/<?= $fetch_book['thumb']; ?>" alt="">
			 </div>
			  
			 <div class="name">
			 	<h3><?= $fetch_book['title']; ?></h3>
			 </div>
			 </a>  
		  
			</div>
		  </div>
			
		<div class="col">
      <a href="tutor_profile.php?get_id=<?= $fetch_tutor['id']; ?>">
		 <div class="tutor">
			<img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
			<div>
			   <h3><?= $fetch_tutor['name']; ?></h3>
			   <span><?= $fetch_tutor['profession']; ?></span>
			</div>
          </div></a>
		 <div class="details">
			<h3><?= $fetch_book['title']; ?></h3>
			<p><?= $fetch_book['description']; ?></p>
			<div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_book['date']; ?></span></div>
			
		 </div>
	  </div>
	  <?php
			}
		 }else{
			echo '<p class="empty">aucun livre ajouté !</p>';
		 }
	  ?>

	</div>

</section>

<!-- playlist section ends -->

<!-- videos container section starts  -->

<section class="videos-container">

   <h1 class="heading">playlist vidéos</h1>

   <div class="box-container">

      <?php
         $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
         $select_content->execute([$get_id, 'active']);
         if($select_content->rowCount() > 0){
            while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
			$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->execute([$fetch_content['id']]);
            $total_likes = $select_likes->rowCount(); 
      ?>
      <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="box">
         <i class="fas fa-play"></i>
         <img src="uploaded_files/<?= $fetch_content['thumb']; ?>" alt="img">
         <h3><?= $fetch_content['title']; ?></h3>
      </a>
      <?php
            }
         }else{
            echo '<p class="empty">aucune vidéos ajoutée !</p>';
         }
      ?>

   </div>

   
	
</section>

<!-- videos container section ends -->











<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>