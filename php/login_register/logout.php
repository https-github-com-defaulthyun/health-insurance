<?php

  #세션 종료
  session_start();
  session_destroy();
  header( 'Location: login.php' );
?>
