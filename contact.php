<?php

include 'components/connect.php';

session_start();

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){
	
	$id = unique_id();
	
   $name = $_POST['name']; 
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email']; 
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number']; 
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg']; 
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);


   $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'");
   $select_contact->execute([$name, $email, $number, $msg]);

 if($select_contact->rowCount() > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `contact`(id, name, email, number, message) VALUES('$id', '$name', '$email', '$number', '$msg')");
      $insert_message->execute([$id, $name, $email, $number, $msg]);
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>
		
      <form action="" method="post">
         <h3>entrez en contact</h3>
         <input type="text" placeholder="entrez votre nom" required maxlength="100" name="name" class="box">
         <input type="email" placeholder="entrez votre email" required maxlength="100" name="email" class="box">
         <input type="number" min="0" max="9999999999" placeholder="entrez votre numéro" required maxlength="10" name="number" class="box">
         <textarea name="msg" class="box" placeholder="entrez votre remarque, raison et domaine dans lequel vous souhaitez former ou etudier." required cols="30" rows="10" maxlength="1000"></textarea>
         <input type="submit" value="envoyer le message" class="inline-btn" name="submit">
      </form>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-phone"></i>
         <h3>numéros de téléphone</h3>
         <a href="tel:658778110">658-778-110</a>
         <a href="tel:620347534">620-347-534</a>
      </div>

      <div class="box">
         <i class="fas fa-envelope"></i>
         <h3>adresses mail</h3>
         <a href="mailto:valideurnumerique@gmail.com">valideurnumerique@gmail.com</a>
         <a href="mailto:vincentyoumssi@gmail.com">admin@gmail.come</a>
      </div>

      <div class="box">
         <i class="fas fa-map-marker-alt"></i>
         <h3>Adresse de bureau</h3>
         <a href="#">flat no. 1, a-1 building, bandjoun, douala, cameroon - 400104</a>
      </div>


   </div>

</section>

<!-- contact section ends -->











<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>