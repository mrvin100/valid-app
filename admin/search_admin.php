<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login_admin.php');
}

// ****** tutors status ******** 

if(isset($_POST['valid_tutor'])){
	// id status present in tutors table
	
   $status_id = $_POST['tutor_id'];
   $status_id = filter_var($status_id, FILTER_SANITIZE_STRING);
	
   $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
   $verify_tutor->execute([$status_id]);
	
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);

   if($verify_tutor->rowCount() > 0){
	   
	  $update_status = $conn->prepare("UPDATE `tutors` SET status = ? WHERE id = ?");
   	  $update_status->execute([$status, $status_id]);
	   
      $message[] = 'tutor status changed successfully!';
   }else{
      $message[] = 'tutor already actived!';
   }
	
}

// ****** users status ******** 

if(isset($_POST['valid_user'])){
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
   
	
<!-- playlist section tutors start -->

<section class="users">

   <h1 class="heading"> tutors results </h1>

   <div class="box-container">
	  <?php 
	   if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search'];
         $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE name LIKE '%{$search}%' ORDER BY name DESC");
         $select_tutors->execute([$admin_id]);
         if($select_tutors->rowCount() > 0){
         while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
            $tutor_id = $fetch_tutor['id'];
	  ?>
	   
	   <div class="box">
	   
		<div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_tutor['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_tutor['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_tutor['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_tutor['date']; ?></span></div>
         </div>
		   
		 
		 <span>
		 <div class="choose-btn">
			
			 <p>tutor status <span style="color:<?php  echo 'var(--red)'; ?>">*</span></p>
			 
		 <form action="" method="post" class="flex-btn">
			 
			<select name="status" class="btn" required>
				<option value="<?= $fetch_tutor['status']; ?>" selected><?= $fetch_tutor['status']; ?></option>
				<option value="active">active</option>
				<option value="deactive">deactive</option>
			  </select>
			 
			<input type="hidden" name="tutor_id" value="<?= $fetch_tutor['id']; ?>">
			<button type="submit" name="valid_tutor" class="btn" onclick="return confirm('valid this choise?');">valid</button>
		 </form>
			 
		 </div>
		  
			 
		 </span>
		   
		 <p style="display: none;"> tutor id : <span><?= $fetch_tutor['id']; ?></span> </p>
		 <p> tutorname : <span><?= $fetch_tutor['name']; ?></span> </p>
		 <p> email : <span><?= $fetch_tutor['email']; ?></span> </p>
		 <p style="display: none;"> tutor type : <span style="color:<?php  echo 'var(--oragen)'; ?>">tutor</span> </p>
		   
		 <!-- view password -->
		 
		 <p> tutor pass : <span style="color:<?php  echo 'var(--red)'; ?>"><?= $fetch_tutor['vpass']; ?></span> </p>
		   
		 <!-- choose statut -->
		 
		   
		 <form action="" method="post">
            <input type="hidden" name="tutor_id" value="<?= $fetch_tutor['id']; ?>">
            <button type="submit" name="delete_tutor" class="inline-delete-btn" onclick="return confirm('delete this tutor?');">delete tutor</button>
         </form>
		  <a href="view_tutor.php?get_id=<?= $tutor_id; ?>" class="btn">view tutor</a>
		   
	   </div>
	   <?php
         } 
      }else{
         echo '<p class="empty">no tutors found!</p>';
      }}else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>
  </div>
   
</section>
<!-- playlist section tutors end -->

<!-- playlist section users start -->

<section class="users">

   <h1 class="heading"> users results </h1>

   <div class="box-container">
	  <?php 
	   if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search'];
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE name LIKE '%{$search}%' ORDER BY name DESC");
         $select_users->execute([$admin_id]);
         if($select_users->rowCount() > 0){
         while($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)){
            $user_id = $fetch_user['id'];
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
			<button type="submit" name="valid_user" class="btn" onclick="return confirm('valid this choise?');">valid</button>
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
		 <a href="users.php?get_id=<?= $user_id; ?>" class="btn">view user</a>

	   </div>
	   <?php
         } 
      }else{
         echo '<p class="empty">no users found!</p>';
      }}else{
         echo '<p class="empty">please search something!</p>';
      }
      ?>
  </div>
   
</section>
<!-- playlist section users end -->







<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>