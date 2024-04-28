<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login_admin.php');
}

$select_messages = $conn->prepare("SELECT * FROM `contact`");
$select_messages->execute([$admin_id]);
$total_messages = $select_messages->rowCount();

$select_users = $conn->prepare("SELECT * FROM `users`");
$select_users->execute([$admin_id]);
$total_users = $select_users->rowCount();

$select_tutors = $conn->prepare("SELECT * FROM `tutors`");
$select_tutors->execute([$admin_id]);
$total_tutors = $select_tutors->rowCount();

$select_admins = $conn->prepare("SELECT * FROM `admins`");
$select_admins->execute([$admin_id]);
$total_admins = $select_admins->rowCount();
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
   
<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="profile_admin.php" class="btn">view profile</a>
      </div>
	   
	   <div class="box">
         <h3><?= $total_messages; ?></h3>
         <p>total messages</p>
         <a href="messages.php" class="btn">view messages</a>
      </div>

      <div class="box">
         <h3><?= $total_users; ?></h3>
         <p>normal users</p>
         <a href="users.php" class="btn">view users</a>
      </div>
	  
	  <div class="box">
         <h3><?= $total_tutors; ?></h3>
         <p>tutor users</p>
         <a href="view_tutor.php" class="btn">view tutors</a>
      </div>

      <div class="box">
         <h3><?= $total_admins; ?></h3>
         <p>admin users</p>
         <a href="super_login.php" class="btn">view admins</a>
      </div>

      <div class="box">
         <h3>quick select</h3>
         <p>login or register</p>
         <div class="flex-btn">
            <a href="login_admin.php" class="option-btn">login</a>
            <a href="register_admin.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</section>















<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>