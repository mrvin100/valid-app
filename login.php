<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}


if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
	
   if($select_user->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:index.php');
   }else{
      $message[] = 'email ou mot de passe incorrect!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
	
	<?php /*?><?php 

		$select_users = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND status = ? LIMIT 1");
      $select_users->execute([$user_id, 'active']);
       
	if($select_users ->rowCount() > 0){
		 
	  ?><?php */?>

   <form action="" method="post" enctype="multipart/form-data" class="login">
	   
      <h3>content de te revoir !</h3>
      <p>votre e-mail <span>*</span></p>
      <input type="email" name="email" placeholder="enter your email" maxlength="50" required class="box">
      <p>votre mot de passe <span>*</span></p>
      <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
      <p class="link">pas de compte ? <a href="register.php">s'inscrire ici</a></p>
      <input type="submit" name="submit" value="connecte-toi" class="btn">
   </form>
	<?php /*?><?php
      }else{
			 echo'<p class="empty">unactived account</p>';
		 }
	   ?><?php */?>

</section>












<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>