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

# id가 없을 시 접근 불가하도록 하는 거임
if (!is_null($_SESSION["ID"])) {

    # Oracle DB 서버 접속
    $connect = oci_connect($username, $userpassword, $db, 'KO16MSWIN949');

    # 연결 오류 시 Oracle 오류 메시지 표시
    if (!$connect) {
        $e = oci_error(); // For oci_connect errors do not pass a handle
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    # sql문 (ID확인)
    $sql = "SELECT CUSTOMER_ID FROM CUSTOMERINFO WHERE CUSTOMER_ID = '$id'";

    # sql문 DB로 파싱 후 전송
    $send = oci_parse($connect, $sql);
    oci_execute($send);
    
}

else {
        echo "<script>alert('아이디를 입력해주세요.');location.replace('../main.php');</script>";    
    }
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전체 보험 상품</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
        }
    </script>
    <link rel="stylesheet" href="css&js/result.css">
    <link rel="stylesheet" href="css&js/header.css">
    <link rel="stylesheet" href="css&js/curheader.css">
    <link rel="stylesheet" href="css&js/allins.css">

    <script src="css&js/modal.js"></script>
    <script src="css&js/goTo.js"></script>

</head>

<body>
    <!--header-->
    <div>
        <!--Header Title (Left)-->
        <div class="headertitleleft">
            <h2><a href="main.html">보험추천</a></h2>
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
                    <a class="curheader" onclick=>전체 보험 상품</a>
                </span> 
                <span>
                    <a class="otherheader" onclick="location.href='myHealthInfo.php'">나의 건강정보</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='myPage.php'">마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    </div>
    <div class="result">
        <div class="insContainer">
            <div class="divCon">
                <span>
                <h3>뇌질환관련</h3>
                    <?php   
                        # brain(뇌)
                        $sql_brain = "SELECT INS.NAME, INS.URL, PRODUCT.PRODUCTNAME FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND IN ('brain')";

                        # sql문 DB로 파싱 후 전송
                        $send1 = oci_parse($connect, $sql_brain);
                        oci_execute($send1);

                        echo "<table border='1'>\n";
                        while (($row = oci_fetch_array($send1, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                            echo "<tr>\n";
                            foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        # DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?>
                </span> 
            </div>
            <div class = "divCon">
                <span>
                <h3>당뇨관련</h3>
                    <?php   
                    # diabetes(당뇨)
                    $sql_diabetes = "SELECT INS.NAME, INS.URL, PRODUCT.PRODUCTNAME FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND IN ('diabetes')";
                    
                    # sql문 DB로 파싱 후 전송
                    $send2 = oci_parse($connect, $sql_diabetes);
                    oci_execute($send2);
                
                    echo "<table border='1'>\n";
                    while (($row = oci_fetch_array($send2, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                                echo "<tr>\n";
                                foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        # DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?>              
                </span>
            </div>
            <div class = "divCon">
                <span>
                <h3>고혈압관련</h3>
                <?php   
                    # highbp(고혈압)
                    $sql_highbp = "SELECT INS.NAME, INS.URL, PRODUCT.PRODUCTNAME FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND IN ('highbp')";
                    
                    # sql문 DB로 파싱 후 전송
                    $send3 = oci_parse($connect, $sql_highbp);
                    oci_execute($send3);
                
                    echo "<table border='1'>\n";
                    while (($row = oci_fetch_array($send3, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                                echo "<tr>\n";
                                foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        # DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?> 
                </span>
            </div>
            <div class = "divCon">
                <span>
                <h3>심장관련</h3>
                <?php   
                    # heart(심장)
                    $sql_heart = "SELECT INS.NAME, INS.URL, PRODUCT.PRODUCTNAME FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND IN ('heart')";

                    # sql문 DB로 파싱 후 전송
                    $send4 = oci_parse($connect, $sql_heart);
                    oci_execute($send4);
                
                    echo "<table border='1'>\n";
                    while (($row = oci_fetch_array($send4, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                                echo "<tr>\n";
                                foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        # DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?> 
            </div>
            <div class = "divCon">
                <span>
                <h3>종합</h3>
                <?php   
                # expenses (종합)
                $sql_expenses = "SELECT INS.NAME, INS.URL, PRODUCT.PRODUCTNAME FROM INS JOIN PRODUCT ON INS.INSID = PRODUCT.INSID WHERE PRODUCT.KIND IN ('expenses')";

                # sql문 DB로 파싱 후 전송
                $send5 = oci_parse($connect, $sql_expenses);
                oci_execute($send5);
                
                    echo "<table border='1'>\n";
                    while (($row = oci_fetch_array($send3, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                                echo "<tr>\n";
                                foreach ($row as $item) {
                                echo "    <td>".($item !== null ? htmlentities(iconv("EUC-KR", "UTF-8", $item), ENT_QUOTES) : "&nbsp;")."</td>\n";
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";

                        # DB 메모리 할당 및 연결 해제 
                        oci_free_statement($send);
                        oci_close($connect);
                    ?> 
                </span>
        </div>
    </div>
    <!--footer-->
    <footer>
        데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033 오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 보험 상품 추천 시스템    
    </footer>
</body>

</html>    


