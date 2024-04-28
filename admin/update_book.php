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

if(isset($_POST['submit'])){

   $book_id = $_POST['book_id'];
   $book_id = filter_var($book_id, FILTER_SANITIZE_STRING);
	
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);

   $update_book = $conn->prepare("UPDATE `book` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_book->execute([$title, $description, $status, $get_id]);

   if(!empty($playlist)){
      $update_playlist = $conn->prepare("UPDATE `book` SET playlist_id = ? WHERE id = ?");
      $update_playlist->execute([$playlist, $book_id]);
   }

   $old_image = $_POST['old_image'];
   $old_image = filter_var($old_image, FILTER_SANITIZE_STRING);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'taille d\'image trop grande!';
      }else{
         $update_image = $conn->prepare("UPDATE `book` SET thumb = ? WHERE id = ?");
         $update_image->execute([$rename, $get_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         if($old_image != '' AND $old_image != $rename){
            unlink('../uploaded_files/'.$old_image);
         }
      }
   }
	
   $old_book = $_POST['old_book'];
   $old_book = filter_var($old_book, FILTER_SANITIZE_STRING);
   $book = $_FILES['book']['name'];
   $book = filter_var($book, FILTER_SANITIZE_STRING);
   $book_ext = pathinfo($book, PATHINFO_EXTENSION);
   $rename_book = unique_id().'.'.$book_ext;
   $book_tmp_name = $_FILES['book']['tmp_name'];
   $book_folder = '../uploaded_files/'.$rename_book;

   if(!empty($book)){
      $update_book = $conn->prepare("UPDATE `book` SET doc = ? WHERE id = ?");
      $update_book->execute([$rename_book, $book_id]);
      move_uploaded_file($book_tmp_name, $book_folder);
      if($old_book != '' AND $old_book != $rename_book){
         unlink('../uploaded_files/'.$old_book);
      }
   }

   $message[] = 'livre modifié!';  

}

if(isset($_POST['delete'])){
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
   header('location:book.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update book</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">modifier le livre</h1>

   <?php
         $select_book = $conn->prepare("SELECT * FROM `book` WHERE id = ?");
         $select_book->execute([$get_id]);
         if($select_book->rowCount() > 0){
         while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
            $book_id = $fetch_book['id'];
			
			$playlist_id = $fetch_book['playlist_id'];
			
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
      ?>
   <form action="" method="post" enctype="multipart/form-data">
	   
	  <input type="hidden" name="book_id" value="<?= $fetch_book['id']; ?>">
	  <input type="hidden" name="old_book" value="<?= $fetch_book['doc']; ?>">
	   
      <input type="hidden" name="old_image" value="<?= $fetch_book['thumb']; ?>">
      <p>status du livre <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_book['status']; ?>" selected><?= $fetch_book['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>titre du livre <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter book title" value="<?= $fetch_book['title']; ?>" class="box">
      <p>description du livre <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fetch_book['description']; ?></textarea><p>changer de playlist <span>*</span></p>
      <select name="playlist" class="box">
         <option value="<?= $fetch_book['playlist_id']; ?>" selected>--select playlist</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>aucune playlist crée!</option>';
         }
         ?>
      </select>
      <p>couverture du livre <span>*</span></p>
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded_files/<?= $fetch_book['thumb']; ?>" alt="">
      </div>
      <input type="file" name="image" accept="image/*" class="box">
	   
	   <!-- Upload doc -->
	  <?php /*?><video src="../uploaded_files/<?= $fetch_book['doc']; ?>" controls></video>
	  <embed type="application/pdf" src="../uploaded_files/<?= $fetch_book['doc']; ?>#toolbar=0" width="100%" height="600px"><embed><?php */?>
	   
	  <iframe id="pdf" frameborder="0" scrolling="no" style="border:0px" src="../uploaded_files/<?= $fetch_book['doc']; ?>" width="100%" height="auto"></iframe>
	   
      <p>changer le livre <span>*</span></p>
      <input type="file" name="book" accept="image/*, .pdf" class="box">
	   
      <input type="submit" value="modifier" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('supprimer ce livre?');" name="delete">
         <a href="view_book.php?get_id=<?= $book_id; ?>" class="option-btn">voir le livre</a>
      </div>
   </form>
   <?php
      } 
   }else{
      echo '<p class="empty">pas encore de livre ajouté!</p>';
   }
   ?>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>