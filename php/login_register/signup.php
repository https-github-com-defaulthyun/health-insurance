<?php
    //클라이언트 식별자 설정 :  https://developer.oracle.com/ko/learn/technical-articles/1481916999509-81-php-web-auditing
    session_start();

    # php 버전 5.5 미만일 때 password hash 함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
    include_once("./password_compat.php");
    
    # 입력 변수 선정
    $user_id = $_POST['ID'];
    $user_password = $_POST['PW'];
    $password_confirm = $_POST['PWC'];
    $name = $_POST['name'];
    $birth = $_POST['birth'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sex = $_POST['sex'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    # DB 접속 부분 
    $db = 
    '(DESCRIPTION =
        (ADDRESS_LIST=
            (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
        )
            (CONNECT_DATA =
                (SID = orcl)
            )
        )';
        
        # Oracle DB 서버 ID/PW
        $username = "DBA2022G1";
        $password = "test1234";
        
        if (!is_null($user_id)) {

        # Oracle DB 서버 접속
        $connect = oci_connect($username, $password , $db);

        # 연결 오류 시 Oracle 오류 메시지 표시
        if (!$connect) {
            $e = oci_error();   // For oci_connect errors do not pass a handle
            trigger_error(htmlentities($e['message'],ENT_QUOTES), E_USER_ERROR);
        }                
        
        $sql = "SELECT CUSTOMER_ID FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$user_id'";

        #sql문 DB로 파싱 후 실행
        $stid = oci_parse($connect, $sql);
        oci_execute($stid);
        
        while($row = oci_fetch_array($stid, OCI_ASSOC)) {
            $user_exist  = $row['ID'];
        }
        if ( $user_id == $user_exist ) {
            $wu = 1;
        } 
        elseif ( $password != $password_confirm ) {
            $wp = 1;
        }
        else{
                # 패스워드 암호화 ( https://m.blog.naver.com/psj9102/221223524085 )
                $encrypted_password = password_hash($user_password, PASSWORD_DEFAULT);

                # 유저 추가 (INSERT 문 )
                $sql_add_user = "INSERT INTO CUSTOMERINFO VALUES (CUSTOMER_ID,CUSTOMER_PASSWORD,CUSTOMER_NAME,CUSTOMER_BIRTH,CUSTOMER_HEIGHT,CUSTOMER_WEIGHT,CUSTOMER_SEX,CUSTOMER_EMAIL,CUSTOMER_PHONENUM,REGDATE,CUSTOMNUM)

                VALUES ('$user_id', '$encrypted_password','$name',to_date('$birth', 'YYYY-MM-DD'),'$height','$weight','$sex','$email','$phone',to_date(sysdate,'YYYY-MM-DD'),NULL)";
                
                # SQL문 DB 전송
                oci_execute(oci_parse($connect, $sql_add_user));

                # js 출력
                echo "<script>alert('회원가입이 완료 되었습니다 로그인을 진행해주세요');</script>";
                echo "<script type='text/javascript'>location.href='http://software.hongik.ac.kr/a_team/a_team1/main.php'</script>";           

        }
    // DB 연결 해제 
    oci_close($connect);
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
    <div class = "signup">
        <h1>회원가입</h1>
        <form action = 'signup.php', method="POST">
            <fieldset>
            <!--아이디-->
            아이디<br>
            <input type="text" name="ID" placeholder="ID를 입력하세요." required><br><br>
            <!--비밀번호-->
            비밀번호 <br><input type="password" name="PW" id="PW" placeholder="비밀번호를 입력하세요." required><br>
            <small><a class="pw">*8자 이상의 비밀번호를 사용하는 것을 권장합니다.*</a></small><br><br>
            비밀번호 확인<br><input type="password" name="PWC" id="PWC" placeholder="한번 더 입력하세요." required><br><br>
            <!--이름-->
            이름<br><input type="text" name="name" placeholder="이름을 입력하세요." required><br><br>
            <!--생년월일-->
            생년월일<br><input type="date" name="birth" required> <br><br>
            <!-- 키 -->
            키<br><input type="number" name="height" placeholder="키를 입력하세요(cm제외)" required><br><br>
            <!-- 몸무게 -->
            몸무게<br><input type="number" name="weight" placeholder="몸무게를 입력하세요(kg제외)" required><br><br>
            <!--성별-->
            성별<br>
            <select name='sex' required>
                <option>sex</option>
                <option>male</option>
                <option>female</option>
                <option>noting</option>
            </select><br><br>
            <!-- 이메일 -->
            이메일<br>
            <div class="emailContainer">
                <input type="text" name="email" placeholder="이메일을 입력하세요" class="email" required> @ 
                    <select class="selectEmail">
                    <option>domain</option>
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
                </select>
            </div>
            <br><br>
            <!--휴대전화-->
            휴대폰<br><input type="text" name="phone" placeholder="ex) 000-0000-0000" required><br><br>
            <!--동의-->
            <input type="checkbox" name="이용약관" >만 19세 이상입니다.<br><br>
            <!--회원가입 버튼-->
            <input type="submit" value="회원가입" class="signupbtn"><br>

            <?php
            if ( $wu == 1 ) {
                echo "<p>사용자이름이 중복되었습니다.</p>";
            }
            if ( $wp == 1 ) {
                echo "<p>비밀번호가 일치하지 않습니다.</p>";
            }
            ?>       
            <br>
            <hr><br>
                <input type="button" onClick="location.href='/a_team/a_team1/main.php '" value="돌아가기" class="signupbtn">
            </fieldset>
        </form>
    </div>

</body>

</html>
