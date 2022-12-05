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
    if(!is_null($id)) {
        
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
            echo "<script>alert('아이디가 존재하지 않습니다.');location.replace('main.php');</script>";
        }
        
        # 패스워드 복호화
        else if ( password_verify( $password, $encrypted_password )) {
            session_start();
            $_SESSION['ID'] = $id;

            $sql2 = "SELECT CUSTOMER_NAME from CUSTOMERINFO WHERE CUSTOMER_ID='$id'";

            # SQL문 DB로 파싱 후 전송
            $result2 = oci_parse($connect, $sql2);
            oci_execute($result2);
            
            echo "<script>
                    localStorage.setItem('loggin', 'true');
                    location.replace('main.php');
                </script>";
            exit;
        }
        # 
        else{
            echo "<script>alert('비밀번호가 틀렸습니다');location.replace('main.php');</script>";
            exit;
        }
    }

    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($stid);
    oci_close($connect);

?>