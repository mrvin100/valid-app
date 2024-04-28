<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}


if(isset($_POST['update_cart'])){

   $cart_id = $_POST['cart_id'];
   $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);

   $message[] = 'Quantité du panier modifiée !';

}

if(isset($_POST['delete_item'])){

   $cart_id = $_POST['cart_id'];
   $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
   
   $verify_delete_item = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
   $verify_delete_item->execute([$cart_id]);

   if($verify_delete_item->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
      $delete_cart_id->execute([$cart_id]);
      $message[] = 'Article du panier supprimé !';
   }else{
      $message[] = 'Article du panier déjà supprimé !';
   } 

}

if(isset($_POST['empty_cart'])){
    
  if($user_id != ''){
   
   $verify_empty_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_empty_cart->execute([$user_id]);

   if($verify_empty_cart->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart_id->execute([$user_id]);
      $message[] = 'Cart emptied!';
   }else{
      $message[] = 'Panier déjà vidé !';
   }

   }else{
      $message[] = 'merci de vous connecter d\'abord!';
   } 

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <link rel="stylesheet" href="fonts/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">panier</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a>
		 <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
    </div>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

         $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
         $select_products->execute([$fetch_cart['product_id']]);
         if($select_products->rowCount() > 0){
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
      
   ?>
   <form action="" method="POST" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <img src="admin/uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
      <h3 class="name"><?= $fetch_product['name']; ?></h3>
      <div class="flex">
         <p class="price"><i class="fas fa-franc-sign"></i> <?= $fetch_cart['price']; ?></p>
         <input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty">
         <button type="submit" name="update_cart" class="fas fa-edit">
         </button>
      </div>
      <p class="sub-total">sous-total : <span><i class="fas fa-franc-sign"></i> <?= $sub_total = ($fetch_cart['qty'] * $fetch_cart['price']); ?></span></p>
      <input type="submit" value="supprimer" name="delete_item" class="delete-btn" onclick="return confirm('retirer cet article?');">
   </form>
   <?php
      $grand_total += $sub_total;
      }else{
         echo '<p class="empty">le produit n\'a pas été trouvé !</p>';
      }
      }
   }else{
      echo '<p class="empty">votre panier est vide !</p>';
   }
   ?>

   </div>

   <?php if($grand_total != 0){ ?>
      <div class="cart-total">
         <p>grand total : <span><i class="fas fa-franc-sign"></i> <?= $grand_total; ?></span></p>
         <form action="" method="POST">
          <input type="submit" value="panier vide" name="empty_cart" class="delete-btn" onclick="return confirm('vider votre panier ?');">
         </form>
         <a href="checkout.php" class="btn">passer en caisse</a>
      </div>
   <?php } ?>

</section>


<!-- footer section starts -->
<?php include 'components/footer.php'; ?>    
<!-- footer section ends -->



<script src="js/script.js"></script>

</body>
</html>