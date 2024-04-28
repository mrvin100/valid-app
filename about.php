<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="fonts/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>pourquoi nous rejoindre ?</h3>
         <p>Rejoignez notre communauté en tant que véritable étudiant ou tuteur, dans des programmes de formation intensifs et particulièrement poussés.<br/>Ces programmes sont destinés à des personnes qui souhaitent s'offrir une compétence à réel potentiel et s'intégrer dans de véritables promotions de professionnels, le tout 100% en ligne.</p>
         <a href="courses.php" class="inline-btn">nos cours</a>
      </div>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>+1k</h3>
            <span>cours en ligne</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>+25k</h3>
            <span>étudiants brillants</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <h3>+5k</h3>
            <span>professeurs experts</span>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-briefcase"></i>
         <div>
            <h3>100%</h3>
            <span>accès à l'emploi</span>
         </div>
      </div>

   </div>

</section>

<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="heading">avis d'étudiants</h1>

   <div class="box-container">

      <div class="box">
         <p>Notre vision<br/>Nous voulons rendre l'apprentissage par ordinateur accessible à tous, avec des contenus pédagogiques d'une excellence inégalée. Le tout de manière ludique.</p>
         <div class="user">
            <img src="images/pic-9.jpg" alt="">
            <div>
               <h3>Développeur</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>
	
	  <div class="box">
         <p>Notre contenu<br/>Grâce à nos contenus et leurs efforts, un nombre important d'étudiants ont trouvé un emploi dans leur entreprise idéale.</p>
         <div class="user">
            <img src="images/pic-6.jpg" alt="">
            <div>
               <h3>journaliste</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Nos étudiants<br/>Certains des plus aventureux ont même créé leur propre startup. Nous sommes fiers d'avoir contribué à cette initiative.</p>
         <div class="user">
            <img src="images/pic-7.jpg" alt="">
            <div>
               <h3>ingénieur</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Humanité<br/>Nous aimons nos élèves, ce ne sont pas des numéros. Nous voulons le meilleur pour eux.</p>
         <div class="user">
            <img src="images/pic-3.jpg" alt="">
            <div>
               <h3>Designer</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Générosité<br/>Donnez toujours avant de recevoir et ne comptez jamais. la base de toute entreprise c'est le questionnement car c'est elle l'essence meme de l'analyse.</p>
         <div class="user">
            <img src="images/pic-4.jpg" alt="">
            <div>
               <h3>Analyste</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Excellence<br/>Le meilleur ou rien. Nous avons des normes d'excellence extraordinaires. Tous les mentors sont des professionnels confirmés, avec une vraie expertise dans le métier que vous apprenez.</p>
         <div class="user">
            <img src="images/pic-5.jpg" alt="">
            <div>
               <h3>musicien</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>


   </div>

</section>

<!-- reviews section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>