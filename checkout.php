<?php

include 'components/connect.php';


if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
}

if(isset($_POST['place_order'])){

if($user_id != ''){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $address = $_POST['flat'].', '.$_POST['street'].', '.$_POST['city'].', '.$_POST['country'].' - '.$_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $address_type = $_POST['address_type'];
   $address_type = filter_var($address_type, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);

   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_cart->execute([$user_id]);
   
   if(isset($_GET['get_id'])){

      $get_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
      $get_product->execute([$_GET['get_id']]);
      if($get_product->rowCount() > 0){
         while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
            $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
            header('location:orders.php');
         }
      }else{
         $message[] = 'quelque chose s\'est mal passé !';
      }

   }elseif($verify_cart->rowCount() > 0){

      while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

         $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);

      }

      if($insert_order){
         $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart_id->execute([$user_id]);
         header('location:orders.php');
      }

   }else{
      $message[] = 'votre panier est vide!';
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
   <title>Checkout</title>

   <link rel="stylesheet" href="fonts/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout">

   <h1 class="heading">récapitulatif de paiement</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a><?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
		 <a href="shopping_cart.php"><i class="fas fa-shopping-bag"></i><span>panier</span><span class="span-nbr"><?= $total_cart_items; ?></span></a>
		 <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
    </div>

   <div class="row">
<?php if($user_id != ''){ ?>
      <form action="" method="POST">
         <h3>Détails de facturation</h3>
         <div class="flex">
            <div class="box">
               <p>votre nom <span>*</span></p>
               <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="input">
               <p>votre numéro <span>*</span></p>
               <input type="number" name="number" required maxlength="10" placeholder="enter your number" class="input" min="0" max="9999999999">
               <p>votre e-mail <span>*</span></p>
               <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="input">
               <p>mode de paiement <span>*</span></p>
               <select name="method" class="input" required>
                  <option value="paiement à la livraison">paiement à la livraison</option>
                  <option value="carte de crédit ou de débit">carte de crédit ou de débit</option>
                  <option value="net bancaire">net bancaire</option>
                  <option value="OM ou MoMo">OM ou MoMo</option>
               </select>
               <p>type d'adresse <span>*</span></p>
               <select name="address_type" class="input" required> 
                  <option value="maison">maison</option>
                  <option value="bureau">bureau</option>
               </select>
            </div>
            <div class="box">
               <p>adresse ligne 01 <span>*</span></p>
               <input type="text" name="flat" required maxlength="50" placeholder="e.g. numéro d'appartement et d'immeuble" class="input">
               <p>adresse ligne 02 <span>*</span></p>
               <input type="text" name="street" required maxlength="50" placeholder="e.g. nom de la rue et localité" class="input">
               <p>nom de ville <span>*</span></p>
               <input type="text" name="city" required maxlength="50" placeholder="enter your city name" class="input">
               <p>nom du pays <span>*</span></p>
               <input type="text" name="country" required maxlength="50" placeholder="enter your country name" class="input">
               <p>pin code <span>*</span></p>
               <input type="number" name="pin_code" required maxlength="6" placeholder="e.g. 123456" class="input" min="0" max="999999">
            </div>
         </div>
         <input type="submit" value="Passer la commande" name="place_order" class="btn">
      </form>

      <div class="summary">
         <h3 class="title">articles du panier</h3>
         <?php
            $grand_total = 0;
            if(isset($_GET['get_id'])){
               $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
               $select_get->execute([$_GET['get_id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
                   
               $grand_total = $fetch_get['price'];
         ?>
         <div class="flex">
            <img src="admin/uploaded_files/<?= $fetch_get['image']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_get['name']; ?></h3>
               <p class="price"><i class="fas fa-franc-sign"></i> <?= $fetch_get['price']; ?> x 1</p>
            </div>
         </div>
         <?php
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                     $select_products->execute([$fetch_cart['product_id']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);

                     $grand_total += $sub_total;
            
         ?>
         <div class="flex">
            <img src="admin/uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_product['name']; ?></h3>
               <p class="price"><i class="fas fa-franc-sign"></i> <?= $fetch_product['price']; ?> x <?= $fetch_cart['qty']; ?></p>
            </div>
         </div>
         <?php
                  }
               }else{
                  echo '<p class="empty">votre panier est vide !</p>';
               }
            }
         ?>
         <div class="grand-total"><span>grand total :</span><p><i class="fas fa-franc-sign"></i> <?= $grand_total; ?></p></div>
      </div>
<?php }else{
    echo '<p class="empty">merci de vous connecter d\'abord!</p>';
} ?>
   </div>

</section>



<!-- footer section starts -->
<?php include 'components/footer.php'; ?>    
<!-- footer section ends -->


<script src="js/script.js"></script>


</body>
</html>