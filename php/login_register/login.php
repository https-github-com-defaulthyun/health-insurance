<?php
    #warning메세지 제거
    ini_set('display_errors', '0');

    #세션 스타트및 세션값 체크 구문
    session_start();

    #세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것
    print_r($_SESSION);

    # php 5.3.6 버전 이하에서 password 암호화 위하여 사용
    include_once "/var/www/html/a_team/a_team1/health-insurance/password_compat.php";
    
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $encrypted_password = null;

    # 잘못된 id $& pw
    $wu = 0;
    $wp = 0;
    $db = '
    (DESCRIPTION =
      (ADDRESS_LIST=
          (
            ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
          )
            (CONNECT_DATA =
         (SID = orcl)
         )
    )';

 if (!is_null($userid)) {
    $con = oci_connect("DBA2022G1", "test1234", $db);
    $sql = "SELECT PASSWORD FROM CUSTOMERINFO WHERE userid='$userid'";
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
      foreach ($row as $item) {
        $encrypted_password = $item;
      }
    }
    if (is_null($encrypted_password)) {
      $wu = 1;
    } else {
      if (password_verify($password, $encrypted_password)) {
        session_start();
        $_SESSION['userid'] = $userid;
        header('Location: login-ok.php');
      } else {
        $wp = 1;
      }
    }
    oci_free_statement($stmt);
    oci_close($con);
  }
  
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인/회원가입</title>
    <link rel="stylesheet" id="light" href="css&js/main.css">
    <link rel="stylesheet" id="light" href="css&js/header.css">
    <link rel="stylesheet" id="light" href="css&js/healthData.css">

    <link rel="stylesheet" id="light" href="css&js/curheader.css">

    <script src="js&css/darkmode.js"></script>
    <script src="js&css/modal.js"></script>
    <script src="js&css/scrollto.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
 
    <script>
        $(document).ready(function() {
            $(".otherheader").on('mouseenter mouseleave',function () {
                $(".curheader").toggleClass('hover');
            });
        });
    </script>
</head>
<body>
 <!--modal-->

    
 <div id="modal" class="modalbackground">
    <div class="modalbox">
        <div class="content">
            <form action="loginpage.php" method="POST" id="signup-form">
                <fieldset>
                    <h1 class="loginH1">로그인</h1>
                    <!--아이디-->
                    아이디<br><input type="text" name="id" placeholder="ID를 입력하세요."><br><br>
                    <!--비밀번호-->
                    비밀번호 <br><input type="password" name="PW" id="PW" placeholder="비밀번호를 입력하세요."><br><br>
                    <hr>
                    <br>
            
                    <!--회원가입 버튼-->
                    <input type="button" onClick="offClick()" value="로그인" class="loginBtn">
                    <input type="button" onClick="location.href='signup.php'" value="회원가입">
                </fieldset>
            </form>
        </div>
    </div>
</div></body>
</html>