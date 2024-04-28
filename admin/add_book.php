<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
	
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;
	
   $doc = $_FILES['doc']['name'];
   $doc = filter_var($doc, FILTER_SANITIZE_STRING);
   $doc_ext = pathinfo($doc, PATHINFO_EXTENSION);
   $rename_doc = unique_id().'.'.$doc_ext;
   $doc_tmp_name = $_FILES['doc']['tmp_name'];
   $doc_folder = '../uploaded_files/'.$rename_doc;
   
	if($image_size > 2000000){
      $message[] = 'taille d\'image trop grande!';
   }else{
	
   $add_book = $conn->prepare("INSERT INTO `book`(id, tutor_id, playlist_id, title, description, doc, thumb, status) VALUES(?,?,?,?,?,?,?,?)");
   $add_book->execute([$id, $tutor_id,$playlist, $title, $description, $doc, $rename, $status]);

   move_uploaded_file($image_tmp_name, $image_folder);
   move_uploaded_file($doc_tmp_name, $doc_folder);

   $message[] = 'nouveau livre chargé!';
		
	}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add book</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form book">

   <h1 class="heading">charger le livre</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>status du livre <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>titre du livre <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter book title" class="box">
      <p>description du livre <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
	  <p>playlist du livre <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>--select playlist</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
				$book_id = $fetch_playlist['id'];
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>no playlist created yet!</option>';
         }
         ?>
      </select>
      <p>couverture du livre<span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
	   
	   <!-- Upload doc -->
	   <p>choisir le doc <span>*</span></p>
       <input type="file" name="doc" accept="doc/*" required class="box">
	   
      <input type="submit" value="charger" name="submit" class="btn">
	  <a href="book.php?get_id=<?= $book_id; ?>" class="option-btn">voir le livre</a>
   </form>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>