<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:book.php');
}

if(isset($_POST['delete_book'])){
   $delete_id = $_POST['book_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $delete_book_thumb = $conn->prepare("SELECT * FROM `book` WHERE id = ? LIMIT 1");
   $delete_book_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_book_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE book_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_book = $conn->prepare("DELETE FROM `book` WHERE id = ?");
   $delete_book->execute([$delete_id]);
   header('locatin:books.php');
}

if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);
   if($verify_video->rowCount() > 0){
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_video['video']);
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);
      $message[] = 'vidéo supprimée!';
   }else{
      $message[] = 'vidéo déja supprimée!';
   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>book Details</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
  
<section class="playlist-details">

   <h1 class="heading">détails du livre</h1>

   <?php
      $select_book = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND tutor_id = ?");
      $select_book->execute([$get_id, $tutor_id]);
      if($select_book->rowCount() > 0){
         while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
            $book_id = $fetch_book['id'];
   ?>
   <div class="row row-book">
      <div class="thumb-box">
		  <div class="box">
		  <div class="thumb">
			 <span>loaded</span>
			 <img src="../uploaded_files/<?= $fetch_book['thumb']; ?>" alt="">
		  </div>
		  </div>
		  <div class="box">
		  	<iframe id="pdf" frameborder="0" scrolling="no" style="border:0px" src="../uploaded_files/<?= $fetch_book['doc']; ?>" width="100%" height="auto"></iframe>
		  </div>
      </div>
      <div class="details">
         <h3 class="title"><?= $fetch_book['title']; ?></h3>
         <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_book['date']; ?></span></div>
         <div class="description"><?= $fetch_book['description']; ?></div>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="book_id" value="<?= $book_id; ?>">
            <a href="update_book.php?get_id=<?= $book_id; ?>" class="option-btn">modifier</a>
            <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('supprimer le livre?');" name="delete">
         </form>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">aucun livre trouvé!</p>';
      }
   ?>

</section>
















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>