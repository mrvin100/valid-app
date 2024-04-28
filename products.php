<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}


if(isset($_POST['add_to_cart'])){

if($user_id != ''){
    
   $id = unique_id();
   $product_id = $_POST['product_id'];
   $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   
   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");   
   $verify_cart->execute([$user_id, $product_id]);

   $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $max_cart_items->execute([$user_id]);

   if($verify_cart->rowCount() > 0){
      $message[] = 'Déjà ajouté au panier !';
   }elseif($max_cart_items->rowCount() == 10){
      $message[] = 'Le panier est plein !';
   }else{

      $select_price = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $select_price->execute([$product_id]);
      $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

      $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");
      $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
      $message[] = 'Ajouté au panier !';
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
   <title>Products</title>

   <link rel="stylesheet" href="fonts/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">tous les produits</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a><?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
		 <a href="shopping_cart.php"><i class="fas fa-shopping-bag"></i><span>panier</span><span class="span-nbr"><?= $total_cart_items; ?></span></a>
    </div>

   <div class="box-container">

   <?php 
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_prodcut = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="POST" class="box">
      <img src="admin/uploaded_files/<?= $fetch_prodcut['image']; ?>" class="image" alt="">
      <h3 class="name"><?= $fetch_prodcut['name'] ?></h3>
      <input type="hidden" name="product_id" value="<?= $fetch_prodcut['id']; ?>">
      <div class="flex">
         <p class="price"><i class="fas fa-f"></i><i class="fas fa-c"></i><i class="fas fa-f"></i><i class="fas fa-a"></i><?= $fetch_prodcut['price'] ?></p>
         <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
      </div>
      <input type="submit" name="add_to_cart" value="ajouter au panier" class="btn">
      <a href="checkout.php?get_id=<?= $fetch_prodcut['id']; ?>" class="delete-btn">acheter maintenant</a>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section>



<!-- footer section starts -->
<?php include 'components/footer.php'; ?>    
<!-- footer section ends -->


<script src="js/script.js"></script>

</body>
</html>