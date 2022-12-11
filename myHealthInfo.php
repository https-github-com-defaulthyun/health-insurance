<?php

# 500 에러 시 확인 (디버깅용)
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

# 세션 스타트
session_start();

# 세션 로그인 값을 통해 로그인 여부 확인
$id = $_SESSION["ID"];

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

if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    $connect = oci_connect($username, $userpassword, $db);

    # 연결 오류 시 Oracle 오류 메시지 표시
    if (!$connect) {
        $e = oci_error(); // For oci_connect errors do not pass a handle
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    # sql문 (ID확인)
    $sql = "SELECT * FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = oci_parse($connect, $sql);
    oci_execute($send);
    
    while ($row = oci_fetch_array($send,OCI_ASSOC)) {
        $id = $row["CUSTOMER_ID"];
        $name = $row["CUSTOMER_NAME"];
        $birth = $row["CUSTOMER_BIRTH"];
        $weight = $row["CUSTOMER_WEIGHT"];
        $height = $row["CUSTOMER_HEIGHT"];
        $sex = $row["CUSTOMER_SEX"];
        $email = $row["CUSTOMER_EMAIL"];
        $phone = $row["CUSTOMER_PHONENUM"];
    }
    $id = $_SESSION["ID"];

    // DB 메모리 할당 및 연결 해제 
    oci_free_statement($send);
    oci_close($connect);
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>나의 건강정보</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
        }
       </script>
    <link rel="stylesheet" href="css&js/result.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>
    <script src="css&js/recommand.js"></script>

</head>

<body>
    <!--header-->
    <div>
        <!--Header Title (Left)-->
        <div class="headertitleleft">
            <h2><a href="main.php">보험추천</a></h2>
        </div>
        <!--Header Button (Right)-->
        <div class="headertitleright">
            <button type="button" onclick="logOut()"><a id="logout">로그아웃</a><img src="img/logout.png" alt="button"
                width="32px"></button>
                <a class="name"></a><a class="sla">님</a>
        </div>
        <!--header-->
        <header class="header">
            <!--header - nav-->
            <nav class="headernav">

                <span>
                    <a class="otherheader" onclick="location.href='main.php'">보험 추천받기</a>
                </span>
                <span>
                    <a class="otherheader" onclick="toResult()">추천 받은 보험</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='allIns.php'">전체 보험 상품</a>
                </span> 
                <span>
                    <a class="curheader" onclick=>나의 건강정보</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    </div>

     <!--건강정보-->
    <h1 class="resultH1"><a class="name" id="resultname"></a> 님의 건강 상태</h1>
    <div class="result">
        <div class="healthinfoContainer">
        </div>
    </div>

    <!--세부건강정보-->
    <h1 class="resultH1">세부 건강 정보</h1>
    <div class="result">
        <div class="resultContainer">
            <?php
            $connect = oci_connect($username, $userpassword, $db);
            
            $query = "SELECT TO_CHAR(CUSTOMER_BIRTH, 'YYYY/MM/DD'), HEALTH.HEALTH_BP, HEALTH.HEALTH_BOS,HEALTH.HEALTH_BFP, HEALTH.HEALTH_SMM, HEALTH.HEALTH_MBW, HEALTH.HEALTH_BM
                        FROM CUSTOMERINFO JOIN HEALTH ON CUSTOMERINFO.CUSTOMER_ID = HEALTH.CUSTOMER_ID
                        WHERE CUSTOMERINFO.CUSTOMER_ID = '$id'
                        ORDER BY HEALTH.CREATED_DATE DESC";
            $info = oci_parse($connect,$query);
            oci_execute($info);

            echo "<table class='table'>\n<th>날짜</th><th>혈압</th><th>혈중산소포화도</th><th>체지방률</th><th>골격근량</th><th>체수분</th><th>기초대사량</th>";
            while (($in = oci_fetch_array($info, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                echo "<tr>\n";
                foreach ($in as $item) {
                    echo "    <td>" . ($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;") . "</td>\n";
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
                    

            oci_free_statement ($info);
            oci_close ($connect)
            ?>
        </div>
    </div>
    
    <!--footer-->
    <footer>
    데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템    </footer>
    <script>make_feedback()</script>
</body>
    
</html>    