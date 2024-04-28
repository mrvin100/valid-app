<?php

include '../components/connect.php';

session_start();

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

if(isset($_POST['delete_user'])){

   $delete_id = $_POST['user_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_user = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
   $verify_user->execute([$delete_id]);

   if($verify_user->rowCount() > 0){
      $delete_user = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
      $delete_user->execute([$delete_id]);
      $message[] = 'user deleted successfully!';
   }else{
      $message[] = 'user already deleted!';
   }

}



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

<?php include '../components/sup_admin_header.php'; ?>

<section class="users">

   <h1 class="heading"> admins accounts </h1>

   <div class="box-container">
	  <?php 
		$select_users = $conn->prepare("SELECT * FROM `admins`");
		 $select_users->execute([$admin_id]);
		 if($select_users->rowCount() > 0){
			while($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)){

	  ?>
	   <div class="box">
			  
		 <p> admins id : <span><?= $fetch_user['id']; ?></span> </p>
		 <p> username : <span><?= $fetch_user['name']; ?></span> </p>
		 <p> email : <span><?= $fetch_user['email']; ?></span> </p>
		 <p> user type : <span style="color:<?php  echo 'var(--oragen)'; ?>">admin</span> </p>
		   
		 <!-- view password admin --> 
		 <p> admin pass : <span style="color:<?php  echo 'var(--red)'; ?>"><?= $fetch_user['vpass']; ?></span> </p>
		   
		   
		 <form action="" method="post">
            <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
            <button type="submit" name="delete_user" class="inline-delete-btn" onclick="return confirm('delete this admin?');">delete admin</button>
         </form>

	   </div>
	   <?php
		}
		}else{
			 echo'<p class="empty">no admins added yet</p>';
		 }
	   ?>
  </div>
   
</section>














<?php include '../components/footer.php'; ?>
	
<script src="../js/admin_script.js"></script>

</body>
</html>