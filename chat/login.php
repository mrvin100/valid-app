<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: users.php");
  }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
<title>chatapp | login</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>
    
<body>
<?php include_once 'header.php' ?>
<section class="form login">
  <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
  <h3>realtime chat app</h3>
    <div class="error-text"></div>
    <div class="field input">
      <label>email address <span>*</span></label>
      <input type="text" name="email" placeholder="enter your email" required class="box">
    </div>
    <div class="field input">
      <label>password <span>*</span>&nbsp;<i class="fas fa-eye"></i></label>
      <input type="password" name="password" placeholder="enter your password" required class="box">
    </div>
    <p class="link">not yet signed up? <a href="index.php">signup now</a></p>
    <div class="field button">
      <input type="submit" name="submit" value="continue to chat" class="btn">
    </div>
  </form>
</section>
  
  <script src="js/pass-show-hide.js"></script>
  <script src="js/login.js"></script>

</body>
</html>