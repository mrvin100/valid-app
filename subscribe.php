<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<!-- Coding By JY Corp.-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Form | valid.</title>
    <!-- font awesome link cdn -->
    <link rel="stylesheet" href="fonts/css/all.min.css">
    
    <!-- custom file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>
    
<section class="form-container subscribe-form">
<input type="checkbox" id="toggle">
  <label for="toggle" class="show-btn">Afficher les modalités</label>
  <div class="wrapper">
    <label for="toggle">
		<a href="index.php"></a>
        <i class="cancel-icon fas fa-times"></i>
    </label>
    <div class="icon"><i class="far fa-envelope"></i></div>
    <div class="content">
      <header>Devenez abonné</header>
      <p>Abonnez-vous à notre newsletter et recevez les dernières mises à jour directement dans votre boîte de réception.</p>
    </div>
    <form action="subscribe.php" method="POST">
    <?php 
    $userEmail = ""; //first we leave email field blank
    if(isset($_POST['subscribe'])){ //if subscribe btn clicked
      $userEmail = $_POST['email']; //getting user entered email
      if(filter_var($userEmail, FILTER_VALIDATE_EMAIL)){ //validating user email
        $subject = "Merci de vous être abonné - valid.";
        $message = "Merci de vous être abonné à notre newsletter. Vous recevrez toujours des mises à jour de notre part. Et nous ne partagerons ni ne vendrons vos informations.";
        $sender = "From:valideurnumerique@gmail.com";
        //php function to send mail
        if(mail($userEmail, $subject, $message, $sender)){
          ?>
           <!-- show sucess message once email send successfully -->
          <div class="alert success-alert">
            <?php echo "Merci de vous être abonné." ?>
          </div>
          <?php
          $userEmail = "";
        }else{
          ?>
          <!-- show error message if somehow mail can't be sent -->
          <div class="alert error-alert">
          <?php echo "échec lors de l'envoi de votre courrier!" ?>
          </div>
          <?php
        }
      }else{
        ?>
        <!-- show error message if user entered email is not valid -->
        <div class="alert error-alert">
          <?php echo "$userEmail n'est pas une adresse e-mail valide!" ?>
        </div>
        <?php
      }
    }
    ?>
      <div class="field">
        <input type="text" class="email" name="email" placeholder="Adresse e-mail" required value="<?php echo $userEmail ?>">
      </div>
      <div class="field btn">
        <div class="layer"></div>
        <a href="index.php"><button type="submit" name="subscribe">S'abonner</button></a>
      </div>
    </form>
    <div class="text">Nous ne partageons pas vos informations.</div>
  </div>
</section>
    
<!-- custom js file link -->
<script src="js/script.js"></script>
    
</body>
</html>
