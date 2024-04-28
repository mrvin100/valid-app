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

if(isset($_POST['like_content'])){

   if($user_id != ''){

      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];
      $playlist_id = $fetch_content['playlist_id'];

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
      $select_likes->execute([$user_id, $content_id]);

      if($select_likes->rowCount() > 0){
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
         $remove_likes->execute([$user_id, $content_id]);
         $message[] = 'supprimé des likes !';
      }else{
         $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, tutor_id, playlist_id, content_id) VALUES(?,?,?,?)");
         $insert_likes->execute([$user_id, $tutor_id, $playlist_id, $content_id]);
         $message[] = 'ajouté aux likes !';
      }

   }else{
      $message[] = 'merci de vous connecter d\'abord!';
   }

}

if(isset($_POST['add_comment'])){

   if($user_id != ''){

      $id = unique_id();
      $comment_box = $_POST['comment_box'];
      $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      if($select_content->rowCount() > 0){

         $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND tutor_id = ? AND comment = ?");
         $select_comment->execute([$content_id, $user_id, $tutor_id, $comment_box]);

         if($select_comment->rowCount() > 0){
            $message[] = 'commentaire déjà ajouté !';
         }else{
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES(?,?,?,?,?)");
            $insert_comment->execute([$id, $content_id, $user_id, $tutor_id, $comment_box]);
            $message[] = 'nouveau commentaire ajouté !';
         }

      }else{
         $message[] = 'quelque chose s\'est mal passé !';
      }

   }else{
      $message[] = 'merci de vous connecter d\'abord !';
   }

}

if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'commentaire supprimé avec succès !';
   }else{
      $message[] = 'commentaire déjà supprimé !';
   }

}

if(isset($_POST['update_now'])){

   $update_id = $_POST['update_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
   $update_box = $_POST['update_box'];
   $update_box = filter_var($update_box, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
   $verify_comment->execute([$update_id, $update_box]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'commentaire déjà ajouté !';
   }else{
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$update_box, $update_id]);
      $message[] = 'commentaire édité avec succès !';
   }

}

// for tutor's subscribe
if(isset($_POST['subscribe'])){

   if($user_id != ''){
      
      $list_id = $_POST['list_id'];
      $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

      $select_list = $conn->prepare("SELECT * FROM `subscribe` WHERE user_id = ? AND tutor_id = ?");
      $select_list->execute([$user_id, $list_id]);

      if($select_list->rowCount() > 0){
         $remove_bookmark = $conn->prepare("DELETE FROM `subscribe` WHERE user_id = ? AND tutor_id = ?");
         $remove_bookmark->execute([$user_id, $list_id]);
         $message[] = 'abonné supprimé !';
      }else{
         $insert_bookmark = $conn->prepare("INSERT INTO `subscribe`(user_id, tutor_id) VALUES(?,?)");
         $insert_bookmark->execute([$user_id, $list_id]);
         $message[] = 'abonné inscrit !';
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
   <title>watch video</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
      $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1");
      $verify_comment->execute([$edit_id]);
      if($verify_comment->rowCount() > 0){
         $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
?>
<section class="edit-comment">
   <h1 class="heading">écrire un commentaire</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">annuler</a>
         <input type="submit" value="modifier" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $message[] = 'commentaire non trouvé!';
   }
}
?>

<!-- watch video section starts  -->
    
<section class="watch-video">

   <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
      $select_content->execute([$get_id, 'active']);
    
          $content_id = $get_id;
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->execute([$content_id]);
            $total_likes = $select_likes->rowCount();  

            $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
            $verify_likes->execute([$user_id, $content_id]);

            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
            $select_tutor->execute([$fetch_content['tutor_id']]);
            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
			$tutor_id = $fetch_tutor['id'];
			
			 // for tutor subscripted
			$select_tutor_subs = $conn->prepare("SELECT * FROM `subscribe` WHERE tutor_id = ?");
			$select_tutor_subs->execute([$tutor_id]);
			$total_tutor_subs = $select_tutor_subs->rowCount();
   ?>
   <div class="video-details">
      <div class="container">
	  <div class="main-video-container sticky">
	  	<video src="uploaded_files/<?= $fetch_content['video']; ?>" class="main-video video" poster="uploaded_files/<?= $fetch_content['thumb']; ?>" controls autoplay></video>
        <div class="icon-group margin">
         <div id="video-list-btn" class="video-list-btn fas fa-sitemap"></div>
        <h3 class="main-vid-title title"><?= $fetch_content['title']; ?></h3>
        <?php /*?><h3 class="main-vid-description description"><?= $fetch_content['description']; ?></h3><?php */?>
        </div>
	  </div>
      <div class="video-list-container">

          
          <?php
            $playlist_id = $fetch_content['playlist_id'];
            $select_list = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? AND id != ? ORDER BY date DESC");
            $select_list->execute([$playlist_id, 'active', $get_id]);
            if($select_list->rowCount() > 0){
            ?>
          <div class="list active"  title="<?= $fetch_content['title']; ?>">
             <video src="uploaded_files/<?= $fetch_content['video']; ?>" class="list-video" poster="uploaded_files/<?= $fetch_content['thumb']; ?>"></video>
             <h3 class="list_title"><?= $fetch_content['title']; ?></h3>
             <p class="list_description" style="display: none;"><?= $fetch_content['description']; ?></p>
          </div>
          <?php
                while($fetch_list = $select_list->fetch(PDO::FETCH_ASSOC)){
          ?>
          
          <div class="list" title="<?= $fetch_list['title']; ?>" >
             <video src="uploaded_files/<?= $fetch_list['video']; ?>" class="list-video" poster="uploaded_files/<?= $fetch_list['thumb']; ?>"></video>
            
             <h3 class="list_title"><?= $fetch_list['title']; ?></h3>
             <p class="list_description" style="display: none;"><?= $fetch_list['description']; ?></p>
          </div>
         <?php
            $vid_list_id = $fetch_list['id'];
                }
             }else{
                echo '<p class="empty">pas de vidéo ajoutée !</p>';
             }
          ?>
          


     </div>
     </div>
     <div class="quick margin">
     <div class="info">
	    <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
		 <p><i class="fas fa-heart"></i><span><?= $total_likes; ?> likes</span></p>
		 <p><i class="fas fa-jar-wheat"></i><span><?= $total_tutor_subs; ?> followers</span></p>
     </div>
         
      <div class="tutor">
      <a href="tutor_profile.php?get_id=<?= $fetch_tutor['id']; ?>" title="voir <?= $fetch_tutor['name']; ?>">
         <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3></a>
            <span><?= $fetch_tutor['profession']; ?></span>
         </div>
         <div class="tutor-contact">
             <p><a href="chat/index.php"><i class="fab fa-whatsapp"></i><span> chatapp</span></a></p>
         </div>
      </div>
	  
      <form action="" method="post" class="flex">
         <input type="hidden" name="content_id" value="<?= $content_id; ?>">
         <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>" class="inline-btn">playlist</a>
         <div class="flex">
		  <?php
            if($verify_likes->rowCount() > 0){
         ?>
         <button type="submit" name="like_content"><i class="fas fa-heart"></i><span>liked</span></button>
         <?php
         }else{
         ?>
         <button type="submit" name="like_content"><i class="far fa-heart"></i><span>like</span></button>
         <?php
            }
         ?>
		<!-- for tutor subscribe -->
	  <form action="" method="post" class="save-list">
		<input type="hidden" name="list_id" value="<?= $tutor_id; ?>">
		<?php
		$select_subscribe = $conn->prepare("SELECT * FROM `subscribe` WHERE user_id = ? AND tutor_id = ?");
		$select_subscribe->execute([$user_id, $tutor_id]);
		   if($select_subscribe->rowCount() > 0){
		?>
		<button type="submit" name="subscribe"  title="je suit <?= $fetch_tutor['name'] ?>"><i class="fas fa-jar-wheat" style="color: limegreen;"></i><span>abonné(e)</span></button>
		<?php
		   }else{
		?>
		   <button type="submit" name="subscribe" title="suivre <?= $fetch_tutor['name'] ?>"><i class="fas fa-jar" style="color: lightcoral;"></i><span>s'abonner</span></button>
		<?php
		   }
		?>
	  </form> 
		 </div>
      </form>
	  <div class="description"><p class="main-vid-description description"><?= $fetch_content['description']; ?></p></div>
      </div>
	   
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">pas encore de vidéos ajoutées !</p>';
      }
   ?>

</section>
    
<!-- watch video section ends -->

<!-- comments section starts  -->

<section class="comments">

   <h1 class="heading">commenter</h1>

   <form action="" method="post" class="add-comment">
      <input type="hidden" name="content_id" value="<?= $get_id; ?>">
      <textarea name="comment_box" required placeholder="écrivez votre commentaire..." maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="ajouter" name="add_comment" class="inline-btn">
   </form>

   <h1 class="heading">commentaires</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
         <div class="user">
            <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <?php
            if($fetch_comment['user_id'] == $user_id){ 
         ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="edit_comment" class="inline-option-btn">éditer</button>
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('supprimer ce commentaire ?');">supprimer</button>
         </form>
         <?php
         }
         ?>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">aucun commantaire ajouté !</p>';
      }
      ?>
      </div>
   
</section>

<!-- comments section ends -->








<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>