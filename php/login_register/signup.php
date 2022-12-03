<?php
    // https://developer.oracle.com/ko/learn/technical-articles/1481916999509-81-php-web-auditing

    session_start();
    
    # php 버전 5.5 미만일 때 password hash 함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php : PHP 5.4.16 )
    include_once("../dbinfo.php");
    include_once("./password_compat.php");
    
    # 입력 변수 선정
    $username = $_POST['NAME'];
    $userid = $_POST['ID'];
    $password = $_POST['PW'];
    $password_confirm = $_POST['PWC'];
    $userbirth = $_POST['birth'];
    $usersex = $_POST['sex'];
    $useremail = $_POST['email'];
    $userphonenumber = $_POST['phone'];
    $useraddr1 = $_POST['addr1'];
    $useraddr2 = $_POST['addr2'];




if (!is_null($userid)) {
    $con = oci_connect("DBA2022G1", "test1234", $db);
    $sql = "SELECT ID FROM CUSTOMERINFO WHERE ID = '$userid'";

    #sql분석 및 실행준비 구문
    $stid = oci_parse($con, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        foreach ($row as $item) {
            $userid_exist = $item;
            if ($userid_exist == $userid) {
                $checkcounter++;
            }
        }

    }
    if ($checkcounter > 0) {
        $wu = 1;
    } 
    elseif ($password != $password_confirm) {
        $wp = 1;
    } 
    else {
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql_add_user = "INSERT INTO CUSTOMERINFO VALUES (CUSTOMNUM,ID,PASSWORD,NAME,SEX,BIRTH,EMAIL,PHONENUM,ADDR,REGDATE)

        VALUES (USER_SEQ.NEXTVAL,'$userid', '$encrypted_password','$username','$usersex',TO_DATE('$userbirth', 'YYYY-MM-DD'), '$useremail' ,'$userphonenumber','$addr1'+'-'+'$addr2',NOW())";

        // SQL문 DB 전송
        oci_execute(oci_parse($con, $sql_add_user));
        

        echo "<script>alert('회원가입 완료');</script>";
        echo "<script type='text/javascript'>
        location.href='http://software.hongik.ac.kr/a_team/a_team1/health-insurance/main.php'
        </script>";
    }

    // DB 연결 해제 
    oci_free_statement($stid);
    oci_close($con);
}
?>

<!DOCTYPE html>
<html lang="kr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" id="signup" href="css&js/signup.css">
    <script src="css&js/pwdcheck.js"></script>
</head>

<body>
    <!--페이지 제목&학번-->
    <form action = 'signup.php', method="POST">
        <fieldset>
            <div>
                <h1>회원가입</h1><br>
            </div>
            <!--이름-->
            이름<br><input type="text" name="name" placeholder="이름을 입력하세요."><br><br>
            <!--아이디-->
            아이디<br><input type="text" name="id" placeholder="ID를 입력하세요."><br><br>
            <!--비밀번호-->
            비밀번호 <br><input type="password" name="PW" id="PW" placeholder="비밀번호를 입력하세요."><br>
            <small><a class="pw">*8자 이상의 비밀번호를 사용하는 것을 권장합니다.*</a></small><br><br>
             <!--성별-->
             성별<br>
            <select name='sex'>
                <option>성별</option>
                <option>남자</option>
                <option>여자</option>
                <option>선택 안함</option>
            </select><br><br>
            비밀번호 확인<br><input type="password" name="PWC" id="PWC" placeholder="한번 더 입력하세요."><br><br>
            <!--생년월일-->
            생년월일<br><input type="date" name="birth" value=""> <br><br>
            <!--이메일-->
            이메일<br>
            <div class="emailContainer"><input type="text" name="email" placeholder="이메일을 입력하세요" class="email"> @ 
            <select class="selectEmail">
                <option>도메인 선택</option>
                <option>naver.com</option>
                <option>hanmail.net</option>
                <option>daum.net</option>
                <option>nate.com</option>
                <option>gmail.com</option>
                <option>hotmail.com</option>
                <option>lycos.co.kr</option>
                <option>empal.com</option>
                <option>cyworld.com</option>
                <option>yahoo.com</option>
                <option>paran.com</option>
                <option>dreamwiz.com</option>
            </select></div><br><br>

            <!--휴대전화-->
            휴대폰<br><input type="text" name="phone" placeholder="ex) 000-0000-0000"><br><br>
            
            <!--주소-->
            주소<br>
            <input type="text" name="addr1" placeholder="주소 입력"><br>
            <input type="text" name="addr2" placeholder="상세주소 입력" class="detailaddr"><br><br>
            
            <!--동의-->
            <input type="checkbox" name="이용약관" >만 19세 이상입니다.<br><br>

            <!--회원가입 버튼-->
            <input type="button" onclick="pwdcheck()" value="회원가입" class="signupbtn"><br>
            <br>
            <hr><br>
                <input type="button" onClick="location.href='main.php'" value="돌아가기" class="signupbtn">
        </fieldset>

        <?php
            if ( $wu == 1 ) {
                echo "<p>사용자이름이 중복되었습니다.</p>";
              }
              if ( $wp == 1 ) {
                echo "<p>비밀번호가 일치하지 않습니다.</p>";
              }
        ?>
    </form>
</body>

</html>
