<?php

include '../components/connect.php';


if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:orders.php');
}

if(isset($_POST['cancel'])){

   $update_orders = $conn->prepare("UPDATE `orders` SET status = ? WHERE product_id = ?");
   $update_orders->execute(['canceled', $get_id]);
   header('location:orders.php');

}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE product_id = ?");
   $update_order->execute([$update_payment, $get_id]);
   $message[] = 'statut du paiement modifié!';

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Orders</title>

   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include '../components/admin_header.php'; ?>

<section class="order-details">

   <h1 class="heading">détails de la commande</h1>
   <div class="sub-header">
	    <a href="orders.php"><i class="fas fa-handshake"></i><span>commandes</span></a>
		 <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
    </div>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE product_id = ? LIMIT 1");
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
         <img src="uploaded_files/<?= $fetch_product['image']; ?>" class="image" alt="">
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
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>">
            <select name="update_payment" class="title" required>
               <option value="" selected disabled><?= $fetch_order['status']; ?></option>
               <option value="in progress">in progress</option>
               <option value="canceled">canceled</option>
               <option value="delivered">delivered</option>
            </select>
            <input type="submit" value="modifier" name="update_order" class="option-btn">
            <input type="submit" value="annuler la commande" name="cancel" class="delete-btn" onclick="return confirm('annuler cette commande ?');">
         </form>
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
<?php include '../components/footer.php'; ?>    
<!-- footer section ends -->




<script src="../js/admin_script.js"></script>

</body>
</html>