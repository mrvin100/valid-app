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
<title>chatapp | register</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>

<body>

<?php include_once "header.php"; ?>
<section class="form signup">
  
  <form action="php/signup.php" method="POST" enctype="multipart/form-data" autocomplete="off">
    <h3>créer un compte</h3>
    <div class="error-text"></div>
    <div class="flex">
    <div class="name-details col">
      <div class="field input">
        <label>first name <span>*</span></label>
        <input type="text" name="fname" class ="box" placeholder="enter your first name" required>
      </div>
      <div class="field input">
        <label>last name <span>*</span></label>
        <input type="text" name="lname" class ="box" placeholder="enter your last name" required>
      </div>
    </div>
    <div class="col">
    <div class="field input">
      <label>email address <span>*</span></label>
      <input type="text" name="email" class ="box" placeholder="enter your email" required>
    </div>
    <div class="field input">
      <label>password <span>*</span>&nbsp;<i class="fas fa-eye"></i></label>
      <input type="password" name="password" class ="box" placeholder="enter new password" required>
    </div>
    </div></div>
    <div class="field image">
      <label>select image <span>*</span></label>
      <input type="file" name="image" class ="box" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
    </div>
    <p class="link">already signed up? <a href="login.php">login now</a></p>
    <div class="field button">
      <input type="submit" name="submit" value="continue to chat" class="btn">
    </div>
  </form>
  
</section>
    
  <script src="js/pass-show-hide.js"></script>
  <script src="js/signup.js"></script>

</body>
</html>
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
<title>chatapp | register</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>

<body>

<?php include_once "header.php"; ?>
<section class="form signup">
  
  <form action="php/signup.php" method="POST" enctype="multipart/form-data" autocomplete="off">
    <h3>créer un compte</h3>
    <div class="error-text"></div>
    <div class="flex">
    <div class="name-details col">
      <div class="field input">
        <label>first name <span>*</span></label>
        <input type="text" name="fname" class ="box" placeholder="enter your first name" required>
      </div>
      <div class="field input">
        <label>last name <span>*</span></label>
        <input type="text" name="lname" class ="box" placeholder="enter your last name" required>
      </div>
    </div>
    <div class="col">
    <div class="field input">
      <label>email address <span>*</span></label>
      <input type="text" name="email" class ="box" placeholder="enter your email" required>
    </div>
    <div class="field input">
      <label>password <span>*</span>&nbsp;<i class="fas fa-eye"></i></label>
      <input type="password" name="password" class ="box" placeholder="enter new password" required>
    </div>
    </div></div>
    <div class="field image">
      <label>select image <span>*</span></label>
      <input type="file" name="image" class ="box" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
    </div>
    <p class="link">already signed up? <a href="login.php">login now</a></p>
    <div class="field button">
      <input type="submit" name="submit" value="continue to chat" class="btn">
    </div>
  </form>
  
</section>
    
  <script src="js/pass-show-hide.js"></script>
  <script src="js/signup.js"></script>

</body>
</html>
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
<title>chatapp | register</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>

<body>

<?php include_once "header.php"; ?>
<section class="form signup">
  
  <form action="php/signup.php" method="POST" enctype="multipart/form-data" autocomplete="off">
    <h3>créer un compte</h3>
    <div class="error-text"></div>
    <div class="flex">
    <div class="name-details col">
      <div class="field input">
        <label>first name <span>*</span></label>
        <input type="text" name="fname" class ="box" placeholder="enter your first name" required>
      </div>
      <div class="field input">
        <label>last name <span>*</span></label>
        <input type="text" name="lname" class ="box" placeholder="enter your last name" required>
      </div>
    </div>
    <div class="col">
    <div class="field input">
      <label>email address <span>*</span></label>
      <input type="text" name="email" class ="box" placeholder="enter your email" required>
    </div>
    <div class="field input">
      <label>password <span>*</span>&nbsp;<i class="fas fa-eye"></i></label>
      <input type="password" name="password" class ="box" placeholder="enter new password" required>
    </div>
    </div></div>
    <div class="field image">
      <label>select image <span>*</span></label>
      <input type="file" name="image" class ="box" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
    </div>
    <p class="link">already signed up? <a href="login.php">login now</a></p>
    <div class="field button">
      <input type="submit" name="submit" value="continue to chat" class="btn">
    </div>
  </form>
  
</section>
    
  <script src="js/pass-show-hide.js"></script>
  <script src="js/signup.js"></script>

</body>
</html>
