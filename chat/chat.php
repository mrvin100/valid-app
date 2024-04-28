<?php
session_start();
include_once "php/config.php";

if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
<title>chat</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>

<body>
    
<?php include_once 'header.php'; ?>

<div class="wrapper">
<!-- chat area section start -->
    
<section class="chat-area">
<header>
<?php 
      $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
      $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
      if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
      }else{
        header("location: users.php");
      }
    ?>
    <div class="icons">
    <a href="users.php" class="back-icon"><div class="fas fa-arrows-split-up-and-left"></div></a>
    <a href="" class="setting"></a>
    <div class="fas fa-gear" id="seetting-btn"></div>
    </div>
    <img src="images/<?php echo $row['img']; ?>" alt="img profile" width="60" height="60">
    <div class="details">
        <span><?Php echo $row['fname']." ".$row['lname'] ?></span>
        <p><?php echo $row['status']; ?></p>
    </div>
</header>
<div class="chat-box">
    
</div>
<form action="#" class="typing-area">
    <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
    <input type="text" name="message" class="input-field" placeholder="type a message herre..." autocomplete="off">
    <button><i class="fab fa-telegram-plane"></i></button>
</form>
</section>

    
<!-- chat area section end -->    
</div>
<script src="js/chat.js" defer></script>
</body>
</html>