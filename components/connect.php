<?php

   $db_name = 'mysql:host=sql203.epizy.com;dbname=epiz_33602118_course_db';
   $user_name = 'epiz_33602118';
   $user_password = 'FXzvUmUkL5j';

   $conn = new PDO($db_name, $user_name, $user_password);

   function unique_id() {
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $rand = array();
      $length = strlen($str) - 1;
      for ($i = 0; $i < 20; $i++) {
          $n = mt_rand(0, $length);
          $rand[] = $str[$n];
      }
      return implode($rand);
   }

?>