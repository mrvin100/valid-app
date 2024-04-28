<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>teachers</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- teachers section starts  -->

<section class="teachers">

   <h1 class="heading">tuteurs experts</h1>

   <form action="search_tutor.php" method="post" class="search-tutor">
      <input type="text" name="search_tutor" maxlength="100" placeholder="chercher un tuteur..." required>
      <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
   </form>

   <div class="box-container">

      <div class="box offer">
         <h3>devenir tuteur</h3>
         <p>rejoignez notre communauté d'experts qui ont appris le mieux en partageant avec vous !</p>
         <a href="admin/login.php" class="inline-btn">commencer</a>
      </div>

      <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors` INNER JOIN `likes` ON `tutors`.id = `likes`.tutor_id GROUP BY `likes`.tutor_id ORDER BY COUNT(*) DESC LIMIT 9");
         $select_tutors->execute();
         if($select_tutors->rowCount() > 0){
            while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){

               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
				
			   $count_books = $conn->prepare("SELECT * FROM `book` WHERE tutor_id = ?");
               $count_books->execute([$tutor_id]);
               $total_books = $count_books->rowCount();
				
			   $count_subs = $conn->prepare("SELECT * FROM `subscribe` WHERE tutor_id = ?");
               $count_subs->execute([$tutor_id]);
               $total_subs = $count_subs->rowCount();
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_tutor['profession']; ?></span>
            </div>
         </div>
         <div class="flex-group">
		 <p>playlists : <span><?= $total_playlists; ?></span></p>
         <p>videos : <span><?= $total_contents ?></span></p>
         <p>likes : <span><?= $total_likes ?></span></p>
		 </div>
		 <div class="flex-group">
		 <p>livres : <span><?= $total_books; ?></span></p>
         <p>followers : <span><?= $total_subs; ?></span></p>
         <p>comments : <span><?= $total_comments ?></span></p>
		 </div>
         <form action="tutor_profile.php" method="post">
            <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
            <input type="submit" value="profil +" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">aucun tuteur trouvé!</p>';
         }
      ?>

   </div>

</section>

<!-- teachers section ends -->






























<?php include 'components/footer.php'; ?>    

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>