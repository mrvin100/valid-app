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
   header('location:categories.php');
}

if(isset($_POST['submit'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
	
   $icon = $_POST['icon'];
   $icon = filter_var($icon, FILTER_SANITIZE_STRING);

   $update_category = $conn->prepare("UPDATE `category` SET title = ?, description = ?,icon = ?, status = ? WHERE id = ?");
   $update_category->execute([$title, $description, $icon, $status, $get_id]);

   $message[] = 'catégorie modifiée!';  

}

if(isset($_POST['delete'])){
   $delete_id = $_POST['category_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE category_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_category = $conn->prepare("DELETE FROM `category` WHERE id = ?");
   $delete_category->execute([$delete_id]);
   header('location:category.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form category-form">

   <h1 class="heading">modifier la catégorie</h1>

   <?php
         $select_category = $conn->prepare("SELECT * FROM `category` WHERE id = ?");
         $select_category->execute([$get_id]);
         if($select_category->rowCount() > 0){
         while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
            $category_id = $fetch_category['id'];
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE category_id = ?");
            $count_videos->execute([$category_id]);
            $total_videos = $count_videos->rowCount();
      ?>
   <form action="" method="post" enctype="multipart/form-data">
      <p>status de la catégorie <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_category['status']; ?>" selected><?= $fetch_category['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>titre de la catégorie <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter category title" value="<?= $fetch_category['title']; ?>" class="box">
      <p>description de la catégory <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"><?= $fetch_category['description']; ?></textarea>
	   
      <p>icone de la catégorie <span>*</span></p>
      <input type="text" name="icon" max-length="30" required placeholder="write faw icon" value="<?= $fetch_category['icon']; ?>" class="box">
	   
      <input type="submit" value="modifier" name="submit" class="btn">
      <div class="flex-btn">
         <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('supprimer cette catégorie?');" name="delete">
         <a href="categories.php?get_id=<?= $category_id; ?>" class="option-btn">voir la catégorie</a>
      </div>
   </form>
   <?php
      } 
   }else{
      echo '<p class="empty">pas encore de catégorie ajoutée!</p>';
   }
   ?>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>