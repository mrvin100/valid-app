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

   $icon = $_POST['icon'];
   $icon = filter_var($icon, FILTER_SANITIZE_STRING);

   $add_category = $conn->prepare("INSERT INTO `category`(id, tutor_id, title, description, icon, status) VALUES(?,?,?,?,?,?)");
   $add_category->execute([$id, $tutor_id, $title, $description, $icon, $status]);


   $message[] = 'nouvelle catégorie crée!';  

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="playlist-form category-form">

   <h1 class="heading">créer une catégorie</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>status de la catégorie <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- select status</option>
         <option value="active">active</option>
         <option value="deactive">deactive</option>
      </select>
      <p>titre de la catégorie <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter category title" class="box">
      <p>description de la catégorie <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>icone de la catégorie <span>*</span></p>
      <input type="text" name="icon" max-length="30" required placeholder="write faw icon" class="box" value="fas fa-cubes-stacked">
      <input type="submit" value="créer" name="submit" class="btn">
   </form>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>