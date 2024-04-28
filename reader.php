
<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:index.php');
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>reader for docs</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="css/reader.css">
    
</head>

<body>
<div class="body">

<?php include 'components/reader_header.php'; ?>
    
    
<section class="page">
<div  class="embed">
<?php
    $select_book = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND status = ? LIMIT 1");
    $select_book->execute([$get_id, 'active']);
    if($select_book->rowCount() > 0){
    $fetch_book = $select_book->fetch(PDO::FETCH_ASSOC);
    $book_id = $fetch_book['id'];
    $pdf = $fetch_book['doc']/*'lmd2.pdf'*/;
    $book_title = $fetch_book['title'];
    $path = 'uploaded_files/'/*'books/'*/;
    $date = $fetch_book['date'];
    $file = $path.$pdf;
?>
<div class="icons">
    <div class="fas fa-toggle-on full" id="full-btn"></div>
</div>
<?php /*
<iframe src="page.php?get_id=<?= $fetch_book['id']; ?>" width="90%" height="100%">#toolbar=0
</iframe>
*/ ?>
<iframe src="<?= $file ?>" width="90%" height="100%">#toolbar=0
</iframe>

<?php    
}else{
    echo '<p class="empty">livre indisponible !</p>';
}

?>
</div>
</section>
    


<!-- footer section starts -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->
    
</div>
    
</body>
</html>
