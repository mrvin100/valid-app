<?php

include '../components/connect.php';

session_start();

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login_admin.php');
}

$select_users = $conn->prepare("SELECT * FROM `users`");
$select_users->execute([$admin_id]);
$total_users = $select_users->rowCount();

if(isset($_POST['delete_user'])){

   $delete_id = $_POST['user_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $verify_user->execute([$delete_id]);

   if($verify_user->rowCount() > 0){
      $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
      $delete_user->execute([$delete_id]);
      $message[] = 'user deleted successfully!';
   }else{
      $message[] = 'user already deleted!';
   }

}

if(isset($_POST['valid'])){
	// id status present in users table
	
   $status_id = $_POST['user_id'];
   $status_id = filter_var($status_id, FILTER_SANITIZE_STRING);
	
   $verify_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $verify_user->execute([$status_id]);
	
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);

   if($verify_user->rowCount() > 0){
	   
	  $update_status = $conn->prepare("UPDATE `users` SET status = ? WHERE id = ?");
   	  $update_status->execute([$status, $status_id]);
	   
      $message[] = 'user status changed successfully!';
   }else{
      $message[] = 'user already actived!';
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

   <h1 class="heading"> users accounts </h1>

   <div class="box-container">
	  <?php 
		$select_users = $conn->prepare("SELECT * FROM `users`");
		 $select_users->execute([$admin_id]);
		 if($select_users->rowCount() > 0){
			while($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)){
	  ?>
	   
	   <div class="box">
	   
		<div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_user['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_user['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_user['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_user['date']; ?></span></div>
         </div>
		   
		 
		 <span>
		 <div class="choose-btn">
			
			 <p>user status <span style="color:<?php  echo 'var(--red)'; ?>">*</span></p>
			 
		 <form action="" method="post" class="flex-btn">
			 
			<select name="status" class="btn" required>
				<option value="<?= $fetch_user['status']; ?>" selected><?= $fetch_user['status']; ?></option>
				<option value="active">active</option>
				<option value="deactive">deactive</option>
			  </select>
			 
			<input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
			<button type="submit" name="valid" class="btn" onclick="return confirm('valid this choise?');">valid</button>
		 </form>
			 
		 </div>
		  
			 
		 </span>
		   
		 <p style="display: none;"> user id : <span><?= $fetch_user['id']; ?></span> </p>
		 <p> username : <span><?= $fetch_user['name']; ?></span> </p>
		 <p> email : <span><?= $fetch_user['email']; ?></span> </p>
		 <p style="display: none;"> user type : <span style="color:<?php  echo 'var(--oragen)'; ?>">user</span> </p>
		   
		 <!-- view password -->
		 
		 <p> user pass : <span style="color:<?php  echo 'var(--red)'; ?>"><?= $fetch_user['vpass']; ?></span> </p>
		   
		 <!-- choose statut -->
		 
		   
		 <form action="" method="post">
            <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
            <button type="submit" name="delete_user" class="inline-delete-btn" onclick="return confirm('delete this user?');">delete user</button>
         </form>

	   </div>
	   <?php
		}
		}else{
			 echo'<p class="empty">no users added yet</p>';
		 }
	   ?>
  </div>
   
</section>














<?php include '../components/footer.php'; ?>
	
<script src="../js/admin_script.js"></script>

</body>
</html>