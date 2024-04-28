<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){
   $delete_id = $_POST['category_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_category = $conn->prepare("SELECT * FROM `category` WHERE id = ? AND tutor_id IN NOT NULL LIMIT 1");
   $verify_category->execute([$delete_id, $tutor_id]);

   if($verify_category->rowCount() > 0){

   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE category_id = ?");
   $delete_bookmark->execute([$delete_id]);
   $delete_category = $conn->prepare("DELETE FROM `category` WHERE id = ?");
   $delete_category->execute([$delete_id]);
   $message[] = 'catégorie supprimée!';
   }else{
      $message[] = 'catégorie déja supprimée!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>categories</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists categories">

   <h1 class="heading">catégories ajoutées</h1>

   <div class="box-container">
   
      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">créer une catégorie</h3>
         <a href="add_category.php" class="btn">ajouter une catégorie</a>
      </div>

      <?php
         $select_category = $conn->prepare("SELECT * FROM `category` WHERE tutor_id IS NOT NULL ORDER BY date DESC");
         $select_category->execute([$tutor_id]);
         if($select_category->rowCount() > 0){
         while($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)){
            $category_id = $fetch_category['id'];
            $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE category_id = ?");
            $count_playlists->execute([$category_id]);
            $total_playlists = $count_playlists->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_category['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_category['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_category['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_category['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_playlists; ?></span><i class="<?= $fetch_category['icon']; ?>"></i>
         </div>
         <h3 class="title"><?= $fetch_category['title']; ?></h3>
         <p class="description"><?= $fetch_category['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="category_id" value="<?= $category_id; ?>">
            <a href="update_category.php?get_id=<?= $category_id; ?>" class="option-btn">modifier</a>
            <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('supprimer cette catégorie?');" name="delete">
         </form>
         <?php /*?><a href="view_category.php?get_id=<?= $category_id; ?>" class="btn">view category</a><?php */?>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">pas encore de catégorie ajoutée!</p>';
      }
      ?>

   </div>

</section>













<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>