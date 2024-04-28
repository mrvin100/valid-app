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
<title>user's profile</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="../fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="style.css">
    
</head>
    
<body>
<?php include_once "header.php"; ?>
  <div class="wrapper">
    <section class="users">
      <header>
        <div class="content">
          <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <img src="images/<?php echo $row['img']; ?>" alt="img profile" width="60" height="60">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
      </header>
      <p class="text">select an user to start chat</p>
      <div class="search">
        <input type="text" placeholder="enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>

  <script src="js/users.js"></script>

</body>
</html>
