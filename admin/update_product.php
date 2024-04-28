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
   header('location:products.php');
}


if(isset($_POST['update'])){
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
    
    $update_product = $conn->prepare("UPDATE `products` SET price = ? WHERE id = ?");
    $update_product->execute([$price, $get_id]);
    
    $message[] = 'Produit mis à jour !';

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
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? AND tutor_id = ?");
    $select_products->execute([$get_id, $tutor_id]);
    if($select_products->rowCount() > 0){
    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>infos du produit</h3>
      <p>nom du produit <span>*</span></p>
      <input type="text" name="name" placeholder="enter product name" required maxlength="50" class="box" value="<?= $fetch_products['name']; ?>">
      <p>prix du produit <span>*</span></p>
      <input type="number" name="price" placeholder="enter product price" required min="0" max="9999999999" maxlength="10" class="box"  value="<?= $fetch_products['price']; ?>">
      <p>image du produit <span>*</span></p>
      <div class="box">
         <img src="uploaded_files/<?= $fetch_products['image']; ?>" alt="img" class="image">
      </div>
      <!--<input type="file" name="image" accept="image/*" required class="box">-->
      <input type="submit" class="btn" name="update" value="modifier">
   </form>
   <?php
      } 
   }else{
      echo '<p class="empty">aucun produit ajouté!</p>';
   }
   ?>

</section>





<script src="../js/admin_script.js"></script>

</body>
</html>