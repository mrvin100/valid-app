<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}


if(isset($_POST['delete'])){
   $delete_id = $_POST['product_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
   $delete_product_thumb = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
   $delete_product_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_product_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_files/'.$fetch_thumb['image']);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE product_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE product_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   header('location:products.php');
   $message[] = 'produit supprimé avec succes !';
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include '../components/admin_header.php'; ?>

<section class="products">

   <h1 class="heading">mes produits</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a>
		 <!--<a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>-->
    </div>

   <div class="box-container">
   
      <div class="box" style="text-align: center;">
         <h3 class="name" style="margin-bottom: .5rem;">étiqueter un article</h3>
         <a href="book.php" class="btn">ajouter un article</a>
      </div>

   <?php 
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE tutor_id = ? ORDER BY name ASC");
      $select_products->execute([$tutor_id]);
      if($select_products->rowCount() > 0){
         while($fetch_prodcut = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="POST" class="box">
      <img src="uploaded_files/<?= $fetch_prodcut['image']; ?>" class="image" alt="img">
      <h3 class="name"><?= $fetch_prodcut['name'] ?></h3>
      <input type="hidden" name="product_id" value="<?= $fetch_prodcut['id']; ?>">
      <div class="flex">
         <p class="price"><i class="fas fa-f"></i><i class="fas fa-c"></i><i class="fas fa-f"></i><i class="fas fa-a"></i><?= $fetch_prodcut['price']; ?></p>
         <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
      </div>
      <a href="update_product.php?get_id=<?= $fetch_prodcut['id']; ?>" class="option-btn">modifier</a>
       <input type="hidden" name="product_id" value="<?= $fetch_prodcut['id']; ?>">
      <input type="submit" name="delete" value="supprimer" class="delete-btn" onclick="return confirm('supprimer ce produit?');">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">aucun produit trouvé!</p>';
   }
   ?>

   </div>

</section>

<!-- footer section starts -->
<?php include '../components/footer.php'; ?>    
<!-- footer section ends -->





<script src="../js/admin_script.js"></script>

</body>
</html>