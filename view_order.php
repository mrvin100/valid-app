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
   header('location:orders.php');
}

if(isset($_POST['cancel'])){

   $update_orders = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
   $update_orders->execute(['canceled', $get_id]);
   header('location:orders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Orders</title>

   <link rel="stylesheet" href="fonts/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="order-details">

   <h1 class="heading">détails de la commande</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a><?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
		 <a href="shopping_cart.php"><i class="fas fa-shopping-bag"></i><span>panier</span><span class="span-nbr"><?= $total_cart_items; ?></span></a>
		 <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
    </div>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
      $select_orders->execute([$get_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $select_product->execute([$fetch_order['product_id']]);
            if($select_product->rowCount() > 0){
               while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                  $sub_total = ($fetch_order['price'] * $fetch_order['qty']);
                  $grand_total += $sub_total;
   ?>
   <div class="box">
      <div class="col">
         <p class="title"><i class="fas fa-calendar"></i><?= $fetch_order['date']; ?></p>
         <img src="admin/uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
         <p class="price"><i class="fas fa-franc-sign"></i> <?= $fetch_order['price']; ?> x <?= $fetch_order['qty']; ?></p>
         <h3 class="name"><?= $fetch_product['name']; ?></h3>
         <p class="grand-total">grand total : <span><i class="fas fa-franc-sign"></i> <?= $grand_total; ?></span></p>
      </div>
      <div class="col">
         <p class="title">adresse de facturation</p>
         <p class="user"><i class="fas fa-user"></i><?= $fetch_order['name']; ?></p>
         <p class="user"><i class="fas fa-phone"></i><?= $fetch_order['number']; ?></p>
         <p class="user"><i class="fas fa-envelope"></i><?= $fetch_order['email']; ?></p>
         <p class="user"><i class="fas fa-map-marker-alt"></i><?= $fetch_order['address']; ?></p>
         <p class="title">status</p>
         <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered'){echo 'green';}elseif($fetch_order['status'] == 'canceled'){echo 'red';}else{echo 'orange';}; ?>"><?= $fetch_order['status']; ?></p>
         <?php if($fetch_order['status'] == 'canceled'){ ?>
            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">commander à nouveau</a>
         <?php }else if($fetch_order['status'] == 'delivered'){ 
            echo '<p class="empty">merci d\'en faire bon usage !</p>';
        }else{ ?>
         <form action="" method="POST">
            <input type="submit" value="annuler la commande" name="cancel" class="delete-btn" onclick="return confirm('annuler cette commande ?');">
         </form>
       
       <?php } ?>
      </div>
   </div>
   <?php
            }
         }else{
            echo '<p class="empty">Produit non trouvé!</p>';
         }
      }
   }else{
      echo '<p class="empty">aucune commande trouvée !</p>';
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