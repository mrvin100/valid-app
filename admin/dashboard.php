<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_categories = $conn->prepare("SELECT * FROM `category` WHERE tutor_id IS NOT NULL");
$select_categories->execute([$tutor_id]);
$total_categories = $select_categories->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_books = $conn->prepare("SELECT * FROM `book` WHERE tutor_id = ?");
$select_books->execute([$tutor_id]);
$total_books = $select_books->rowCount();

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$tutor_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();

$select_products = $conn->prepare("SELECT * FROM `products` WHERE tutor_id = ?");
$select_products->execute([$tutor_id]);
$total_products = $select_products->rowCount();

$select_tutors = $conn->prepare("SELECT * FROM `tutors`");
$select_tutors->execute([$tutor_id]);
$total_tutors = $select_tutors->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>bienvenu!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="profile.php" class="btn">mon profil</a>
      </div>

      <div class="box">
         <h3><?= $total_contents; ?></h3>
         <p>total contents</p>
         <a href="add_content.php" class="btn">ajouter un contenu</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>total playlists</p>
         <a href="add_playlist.php" class="btn">ajouter une playlist</a>
      </div>
	  <!-- view cathegories -->
	  <div class="box">
         <h3><?= $total_categories; ?></h3>
         <p>total cathegories</p>
         <a href="categories.php" class="btn">les catégories</a>
      </div>
	   
	  <div class="box">
         <h3><?= $total_books; ?></h3>
         <p>total books</p>
         <a href="book.php" class="btn">mes livres</a>
      </div>

      <div class="box">
         <h3><?= $total_likes; ?></h3>
         <p>total likes</p>
         <a href="contents.php" class="btn">mes contenus</a>
      </div>

      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>total comments</p>
         <a href="comments.php" class="btn">mes commentaires</a>
      </div>

      <div class="box">
         <h3><?= $total_products; ?></h3>
         <p>total products</p>
         <a href="products.php" class="btn">mes produits</a>
      </div>

      <div class="box">
         <h3><?= $total_tutors; ?></h3>
         <p>tutors users</p>
         <a href="login_admin.php" class="btn">les tuteurs</a>
      </div>

      <div class="box">
         <h3>quick select</h3>
         <p>se connecter ou <a href="register_admin.php" style="color: var(--light-color);">s'inscrire</a></p>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">connexion</a>
            <a href="register.php" class="option-btn">s'inscrire</a>
         </div>
      </div>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>