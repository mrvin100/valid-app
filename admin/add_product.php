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
   //header('location:products.php');
}

if(isset($_GET['type'])){
   $type = $_GET['type'];
}else{
   $type = '';
   //header('location:products.php');
}

if(isset($_POST['add'])){

   $id = unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $old_thumb = $_POST['old_thumb'];
   $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
   $src_thumb = '../uploaded_files/'.$old_thumb;
    
   $ext = pathinfo($old_thumb, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
    
   $image_folder = 'uploaded_files/'.$rename;
    
  $add_product = $conn->prepare("INSERT INTO `products`(id, tutor_id, name, price, image) VALUES(?,?,?,?,?)");
  $add_product->execute([$id, $tutor_id, $name, $price, $rename]);
  copy($src_thumb, $image_folder);
  $message[] = 'produit mise en vente!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update Product</title>

   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include '../components/admin_header.php'; ?>

   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a>
		 <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
		 <a href="book.php"><i class="fas fa-book-bookmark"></i><span>livres</span></a>
    </div>

<section class="product-form">
  <?php
    if($type == 'playlist' ){
    $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? AND status = ? LIMIT 1");
    $select_playlist->execute([$get_id, $tutor_id, 'active']);
    $verify_product= $select_playlist->rowCount();
    if($select_playlist->rowCount() > 0){
    $fetch_product = $select_playlist->fetch(PDO::FETCH_ASSOC);
    }}
    
    if($type == 'book' ){
    $select_book = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND tutor_id = ? AND status = ? LIMIT 1");
    $select_book->execute([$get_id, $tutor_id, 'active']);
    $verify_product= $select_book->rowCount();
    if($select_book->rowCount() > 0){
    $fetch_product = $select_book->fetch(PDO::FETCH_ASSOC);
    }}
    
    if($type != '' AND $verify_product != ''){
   ?>

   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="old_thumb" value="<?= $fetch_product['thumb']; ?>">
      <h3>infos du produit</h3>
      <p>nom du produit <span>*</span></p>
      <input type="text" name="name" placeholder="enter product name" required maxlength="50" class="box" value="<?= $fetch_product['title']; ?>" desabled>
      <p>prix du produit <span>*</span></p>
      <input type="number" name="price" placeholder="enter product price" required min="0" max="9999999999" maxlength="10" class="box">
      <p>image du produit <span>*</span></p>
      <div class="box">
         <img src="../uploaded_files/<?= $fetch_product['thumb']; ?>" alt="img" class="image">
      </div>
      <!--<input type="file" name="image" accept="image/*" class="box">-->
      <input type="submit" class="btn" name="add" value="ajouter">
   </form>
   <?php
    }else{
      echo '<p class="empty">le produit est désactivé!</p>';
   }
   ?>

</section>





<script src="../js/admin_script.js"></script>

</body>
</html>