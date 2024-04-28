<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="index.php" class="logo">valid.</a>

      <form action="search.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="A quoi pensez-vous ?" required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div><?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="shopping_cart.php"><div class="fas fa-cart-shopping cart-btn"><span><?= $total_cart_items; ?></span></div></a>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <a href="profile.php">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>étudiant(e)</span></a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">connexion</a>
            <a href="register.php" class="option-btn">s'inscrire</a>
         </div>
         <a href="components/user_logout.php" onclick="return confirm('vous déconnecter de ce site ?');" class="delete-btn">déconnexion</a>
         <?php
            }else{
         ?>
         <h3>se connecter ou s'inscrire</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">connexion</a>
            <a href="register.php" class="option-btn">s'inscrire</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <a href="profile.php">
         <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span>étudiant(e)</span></a>
         <?php
            }else{
         ?>
         <h3>se connecter ou s'inscrire</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">connexion</a>
            <a href="register.php" class="option-btn">s'inscrire</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="index.php"><i class="fas fa-home"></i><span>acceuil</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>à propos</span></a>
      <a href="courses.php"><i class="fas fa-chalkboard"></i><span>contenus</span></a><!--
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>cours</span></a>
      <a href="tutorials.php"><i class="fab fa-connectdevelop"></i><span>tutoriels</span></a>
      <a href="projects.php"><i class="fas fa-tag"></i><span>projets</span></a>-->
      <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>tuteurs</span></a>
      <a href="products.php"><i class="fas fa-shop"></i><span>boutique</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>contacter</span></a>
   </nav>

</div>

<!-- side bar section ends -->