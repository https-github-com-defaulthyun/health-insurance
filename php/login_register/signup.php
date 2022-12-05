<?php
    // # 500 에러 시 확인 (디버깅용)
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');
    
    # 세션 스타트
    session_start();

    # php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
    include_once("./password_compat.php");
    
    # 입력 변수 선언
    $id = $_POST["ID"];
    $password = $_POST["PW"];
    $password_confirm = $_POST["PWC"];
    $name = $_POST["name"];
    $birth = $_POST["birth"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $sex = $_POST["sex"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    
    # id, password 중복 판별 변수 선언 및 체크값 선언 
    $wu = 0;
    $wp = 0;
    $check = 0;

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
        
    # Oracle 학교 DB 서버 ID/PW
    $username = "DBA2022G1";
    $userpassword = "test1234";
        
    if (!is_null($id)) {

        # Oracle DB 서버 접속
        $connect = oci_connect($username, $userpassword , $db); 
        
        # 연결 오류 시 Oracle 오류 메시지 표시
        if (!$connect) {
            $e = oci_error();   // For oci_connect errors do not pass a handle
            trigger_error(htmlentities($e['message'],ENT_QUOTES), E_USER_ERROR);
        }
        
        # sql문 (ID확인)
        $sql = "SELECT CUSTOMER_ID FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

        # sql문 DB로 파싱 후 전송
        $send = oci_parse($connect, $sql);
        oci_execute($send);
        
        while ($row = oci_fetch_array($send, OCI_ASSOC)) {
            # 배열의 모든 아이템(요소)을 한 번에 하나씩 참조하여 처리
            foreach ($row as $item) {
                $userid_exist = $item;
                
            # 그 중 동일 id가 있으면 체크 값 증가
                if ($userid_exist == $id) {
                    $check++;
                }
            }
        }
            if ( $check > 0 ) {
                $wu = 1;
            } 
            elseif ( $password != $password_confirm ) {
                $wp = 1;
            } 
            else {
            # 패스워드 암호화 ( https://m.blog.naver.com/psj9102/221223524085 
            $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
            
            # 유저 추가 (INSERT 문 )
            $sql_add_user = "INSERT INTO CUSTOMERINFO VALUES ('$id', '$encrypted_password','$name',to_date('$birth', 'YYYY-MM-DD'),'$height','$weight','$sex','$email','$phone',to_date(sysdate,'YYYY-MM-DD'),NULL)";
            
            # SQL문 DB 전송
            oci_execute(oci_parse($connect, $sql_add_user));
            
            # js 함수 호출 
            echo "<script>alert('회원가입이 완료 되었습니다');</script>";
            echo "<script type='text/javascript'>location.href='http://software.hongik.ac.kr/a_team/a_team1/main.php'</script>";
            }
    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($send);
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
        <!-- POST 방식으로 값을 넘겨야 됨 -->
        <form action = "signup.php" method="POST">
            <fieldset>
            <div>
            <h1>회원가입</h1><br>
            </div>
            <!--아이디-->
            아이디<br>
            <input type="text" name="ID" placeholder="ID를 입력하세요." required><br><br>
            <!--비밀번호-->
            비밀번호 <br><input type="password" name="PW"  placeholder="비밀번호를 입력하세요." required><br>
            비밀번호 확인<br><input type="password" name="PWC"  placeholder="한번 더 입력하세요." required><br><br>
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
                <option>male</option>
                <option>female</option>
            </select><br><br>
            <!-- 이메일 -->
            이메일<br><input type="text" name="email" placeholder="이메일을 입력하세요"> 
            </div><br><br>
            <!--휴대전화-->
            휴대폰<br><input type="text" name="phone" placeholder="ex) 000-0000-0000"><br><br>
            <!--회원가입 버튼-->
            <input type="submit" value="회원가입" class="signupbtn">
            <br>   
            <br>
            <hr><br>
                <input type="button" onClick="location.href='/a_team/a_team1/main.php '" value="돌아가기" class="signupbtn">
            </fieldset>
            <?php
                if ( $wu == 1 ) {
                    echo "<script>alert('아이디가 이미 존재합니다');</script>";
                }
                if ( $wp == 1 ) {
                echo "<script>alert('비밀번호가 일치하지 않습니다');</script>";
                }
            ?>
        </form>
    </body>
</>

<?php

?>


