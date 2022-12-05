<?php    
    // # 500 에러 시 확인 (디버깅용)
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');
    
    # 세션 스타트
    session_start();

    # php 버전 5.5 미만일 때 password hash함수를 사용 하지 못 할때 password 암호화 위하여 사용 ( 현재 학교 php 버전 : PHP 5.4.16 )
    include_once("./password_compat.php");
        
    // 회원가입 시 ID/PW 불러옴 
    $id = $_POST['ID']; 
    $password = $_POST['PW'];
    $encrypted_password = null;

    # id, password 중복 판별 변수 선언 및 체크값 선언 
    $wu = 0;
    $wp = 0;
    
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

    # ID 부분 NULL 아닐 시 
    if(!is_null($id) && $id != '') {
        
        # Oracle DB 서버 접속
        $connect = oci_connect($username, $userpassword , $db); 
        
        # SQL문
        $sql = "SELECT CUSTOMER_PASSWORD from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

        # SQL문 DB로 파싱 후 전송
        $result = oci_parse($connect, $sql);
        oci_execute($result);
        

        # 쿼리의 다음 결과 집합 행을 포함하는 배열을 반환
        while ($row = oci_fetch_array($result, OCI_ASSOC) ) {
            foreach ($row as $item){
                $encrypted_password = $item;
            }
        }
        # 패스워드 NULL 시
        if ( is_null( $encrypted_password ) ) {
            echo "<script>alert('아이디가 존재하지 않습니다.');location.replace('../main.php');</script>";
        }
        
        # 패스워드 복호화
        else if ( password_verify( $password, $encrypted_password )) {
            session_start();
            $_SESSION['ID'] = $id;

            #이름
            $sql2 = "SELECT CUSTOMER_NAME from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            # SQL문 DB로 파싱 후 전송
            $result2 = oci_parse($connect, $sql2);
            oci_execute($result2);

            while ($row = oci_fetch_array($result2, OCI_ASSOC) ) {
                foreach ($row as $item){
                    $name = $item;
                }
            }

            #나이
            $sql3 = "SELECT TRUNC(MONTHS_BETWEEN(TRUNC(SYSDATE), CUSTOMER_BIRTH) / 12) FROM CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            $result3 = oci_parse($connect, $sql3);
            oci_execute($result3);

            while ($row = oci_fetch_array($result3, OCI_ASSOC) ) {
                foreach ($row as $item){
                    $age = $item;
                }
            }

            #키
            $sql4 = "SELECT CUSTOMER_HEIGHT FROM CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            $result4 = oci_parse($connect, $sql4);
            oci_execute($result4);

            while ($row = oci_fetch_array($result4, OCI_ASSOC) ) {
                foreach ($row as $item){
                    $height = $item;
                }
            }

            #체중
            $sql5 = "SELECT CUSTOMER_WEIGHT FROM CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            $result5 = oci_parse($connect, $sql5);
            oci_execute($result5);

            while ($row = oci_fetch_array($result5, OCI_ASSOC) ) {
                foreach ($row as $item){
                    $weight = $item;
                }
            }

            
            echo "<script>
                    alert('로그인 되었습니다.');
                    localStorage.setItem('age', '$age');
                    localStorage.setItem('height', '$height');
                    localStorage.setItem('weight', '$weight');
                    localStorage.setItem('name', '$name');
                    localStorage.setItem('loggin', 'true');
                    location.replace('../main.php');
                </script>";
            exit;
        }
        # 
        else{
            echo "<script>alert('비밀번호가 틀렸습니다');location.replace('../main.php');</script>";
            exit;
        }
    } else {
        echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";
        exit;
    }

    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($stid);
    oci_close($connect);

?>