
<!-- DB Base -->
<!--  include_once("./dbinfo.php");  = C언어 #include -->

<?php
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
        
        # Oracle DB 서버 접속
        $connect = oci_connect($username, $password , $db);

        # 연결 오류 시 Oracle 오류 메시지 표시
        if (!$connect) {
            $e = oci_error();   // For oci_connect errors do not pass a handle
            trigger_error(htmlentities($e['message'],ENT_QUOTES), E_USER_ERROR);
        }
?>