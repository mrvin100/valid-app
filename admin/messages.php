<?php
session_start();

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login_admin.php');
}

if(isset($_POST['delete_message'])){

   $delete_id = $_POST['message_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `contact` WHERE id = ?");
   $verify_message->execute([$delete_id]);

   if($verify_message->rowCount() > 0){
      $delete_message = $conn->prepare("DELETE FROM `contact` WHERE id = ?");
      $delete_message->execute([$delete_id]);
      $message[] = 'message deleted successfully!';
   }else{
      $message[] = 'message already deleted!';
   }

}





?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="../fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/sup_admin_header.php'; ?>

<section class="messages">

   <h1 class="heading">user messages</h1>
   
   <div class="box-container">
   <?php
	   $select_messages = $conn->query('SELECT * FROM contact');
	  if( 2 > 1){ 
		   while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
	?>
   <div class="box">

	   <p> name : <span><?php echo $fetch_message['name']; ?></span> </p>
	   <p> number : <span><?= $fetch_message['number']; ?></span> </p>
	   <p> email : <span><?= $fetch_message['email']; ?></span> </p>
	   <p> message : <span><?= $fetch_message['message']; ?></span> </p>
	   <form action="" method="post">
   		<input type="hidden" name="message_id" value="<?= $fetch_message['id']; ?>">
   		<button type="submit" name="delete_message" class="inline-delete-btn" onclick="return confirm('delete this message?');">delete message</button>
	 </form>
   </div>
   <?php
	   };
    }else{
	   echo '<p class="empty">you have no messages!</p>';
   }
?>
	   
	   
   </div>
   
</section>








<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>