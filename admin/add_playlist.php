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
	
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $icon = $_POST['icon'];
   $icon = filter_var($icon, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_files/'.$rename;

   $add_playlist = $conn->prepare("INSERT INTO `playlist`(id, tutor_id, category_id, title, description, icon, thumb, type, status) VALUES(?,?,?,?,?,?,?,?,?)");
   $add_playlist->execute([$id, $tutor_id, $category, $title, $description, $icon, $rename, $type, $status]);

   move_uploaded_file($image_tmp_name, $image_folder);

   $message[] = 'nouvelle playlist crée!';  

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Playlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">créer une playlist</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>status de la playlist <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>titre de la playlist <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter playlist title" class="box">
      <p>description de la playlist <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>affiche de la playlist <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
	  <!-- select plalist category -->
	  <p>catégorie de playlist <span>*</span></p>
      <select name="category" class="box" required>
         <option value="" disabled selected>--select category</option>
         <?php
         $select_categories = $conn->prepare("SELECT * FROM `category` WHERE tutor_id IS NOT NULL");
         $select_categories->execute([$tutor_id]);
         if($select_categories->rowCount() > 0){
            while($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_category['id']; ?>"><?= $fetch_category['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>pas encore de catégorie crée!</option>';
         }
         ?>
      </select>
	  <!-- select plalist type -->
	  <p>type de playlist <span>*</span></p>
      <select name="type" class="box" required>
         <option value="" disabled selected>--select type</option>
         <option value="course">course</option>
	     <option value="tutorial">tutorial</option>
	     <option value="project">project</option>
      </select>
	  
	  <p>icone de la playlist <span>#</span></p>
      <input type="text" name="icon" max-length="30" placeholder="write faw icon" class="box" value="fas fa-clapperboard" required>
      <input type="submit" value="créer" name="submit" class="btn">
   </form>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>