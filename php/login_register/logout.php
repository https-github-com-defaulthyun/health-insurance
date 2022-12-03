<?php
  session_start();
  session_destroy();
  echo "<script>alert('로그아웃 되었습니다');</script>";
  echo "<script type='text/javascript'>
  location.href='http://software.hongik.ac.kr/a_team/a_team1/main.php'
  </script>";
  ?>
  