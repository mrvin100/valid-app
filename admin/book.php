<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){
   $delete_id = $_POST['book_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_book = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND tutor_id = ? LIMIT 1");
   $verify_book->execute([$delete_id, $tutor_id]);

   if($verify_book->rowCount() > 0){

   

   $delete_book_thumb = $conn->prepare("SELECT * FROM `book` WHERE id = ? LIMIT 1");
   $delete_book_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_book_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_files/'.$fetch_thumb['thumb']);
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE book_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_book = $conn->prepare("DELETE FROM `book` WHERE id = ?");
   $delete_book->execute([$delete_id]);
   $message[] = 'livre supprimé!';
   }else{
      $message[] = 'livre déja supprimé!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>books</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists book">

   <h1 class="heading">livres ajoutés</h1>

   <div class="box-container">
   
      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">créer un livre</h3>
         <a href="add_book.php" class="btn">ajouter un livre</a>
      </div>

      <?php
         $select_book = $conn->prepare("SELECT * FROM `book` WHERE tutor_id = ? ORDER BY date DESC");
         $select_book->execute([$tutor_id]);
         if($select_book->rowCount() > 0){
         while($fetch_book = $select_book->fetch(PDO::FETCH_ASSOC)){
            $book_id = $fetch_book['id'];
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_book['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_book['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_book['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_book['date']; ?></span></div>
         </div>
         <a href="view_book.php?get_id=<?= $book_id; ?>" title="lire le livre">
         <div class="thumb">
            <span>loaded</span>
            <img src="../uploaded_files/<?= $fetch_book['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_book['title']; ?></h3></a>
         <p class="description"><?= $fetch_book['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="book_id" value="<?= $book_id; ?>">
            <a href="update_book.php?get_id=<?= $book_id; ?>" class="option-btn">modifier</a>
            <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('supprimer ce livre?');" name="delete">
         </form>
            <a href="add_product.php?get_id=<?= $book_id; ?>&type=book" class="btn">mettre en vente</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">aucun livre ajouté!</p>';
      }
      ?>

   </div>

</section>













<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.books .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>