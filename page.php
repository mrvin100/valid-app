
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
$select_book = $conn->prepare("SELECT * FROM `book` WHERE id = ? AND status = ? LIMIT 1");
    $select_book->execute([$get_id, 'active']);
    if($select_book->rowCount() > 0){
    $fetch_book = $select_book->fetch(PDO::FETCH_ASSOC);
    $pdf = $fetch_book['doc']/*'lmd2.pdf'*/;
    $book_title = $fetch_book['title'];
    $path = 'uploaded_files/'/*'books/'*/;
    $date = $fetch_book['date'];
    $filename = $path.$pdf;
    // The location of the PDF file
    // on the server
    //$filename = "books/lmd2.pdf";

    // Header content type
    header("Content-type: application/pdf");

    header("Content-Length: " . filesize($filename));

    // Send the file to the browser.
    readfile($filename);
        
    /*echo '<h1>here is the pdf informations</h1>';
    echo '<strong>Created Date : </strong>'.$date;
    echo '<strong>filename : </strong>'.$book_title;
    echo '<br/><strong>path-file : </strong>'.$path;
    echo '<br/><strong>pdf : </strong>'.$file;*/
    }else{
        echo '<p class="empty">livre indisponible !</p>';
    }
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1.0">
<title>reader for docs</title>
    
<!-- font awesome cdn link -->
<link rel="stylesheet" href="fonts/css/all.min.css">
    
<!-- custom css link -->
<link rel="stylesheet" href="css/reader.css">
    
</head>

<body>
    
    
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
        
    /*echo '<h1>here is the pdf informations</h1>';
    echo '<strong>Created Date : </strong>'.$date;
    echo '<strong>filename : </strong>'.$book_title;
    echo '<br/><strong>path-file : </strong>'.$path;
    echo '<br/><strong>pdf : </strong>'.$file;*/
    }else{
        echo '<p class="empty">livre indisponible !</p>';
    }
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="".$file.""');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    @readfile($file);
    ?>
    <br/>
    <br/>
    <iframe src="uploaded_files/<?= $pdf; ?>" width="100%" height="100%">
    </iframe>
</div>
</section>

</body>
</html>
