<?php
    
    session_start();

    $userid = $_SESSION['ID'];
    
    $db = '
        (DESCRIPTION =
            (ADDRESS_LIST=
                    (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
            )
            (CONNECT_DATA =
                (SID = orcl)
            )
    )';

    
    if (!is_null($_SESSION['userid'])) {
        
        $con = oci_connect("DBA2022G4", "dbdb1234", $db);
        $sql = "SELECT * FROM CUSTOMER WHERE userid='$userid'";
        $stmt = oci_parse($con, $sql);
        oci_execute($stmt);

    while (($row = oci_fetch_array($stmt, OCI_NUM))) {
        $username = $row['2'];
        $usersex = $row['3'];
        $userbirth = $row['4'];
        $userphonenumber = $row['5'];
        $performanceKey = $row['6'];

    }
    $sql_2 = "SELECT PerformanceName FROM Performance WHERE PerformanceKey =$performanceKey";
    $stmt2 = oci_parse($con, $sql_2);
    oci_execute($stmt2);
    while (($row2 = oci_fetch_array($stmt2, OCI_NUM))) {

        $PerformanceName = $row2[0];

    }

    oci_close($con);
} else {
    echo "<script>alert('로그인이 필요합니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php'
    </script>";
}

?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
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

</head>

<body>
    <!--header-->
    <div>
        <!--Header Title (Left)-->
        <div class="headertitleleft">
            <h2><a href="main.php">DB설계</a></h2>
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
                    <a class="otherheader" onclick="location.href='myHealthInfo.php'">나의 건강정보</a>
                </span>
                <span>
                    <a class="curheader" onclick=>마이페이지</a>
                </span>
            </nav>
        </header>
    </div>
    </div>

    <h1 class="resultH1"><a class="name" id="resultname">@@</a> 님의 마이페이지</h1>
    <div class="result">
        <div class="resultContainer">
        <p>아이디
                <?php echo $userid; ?>
            </p>
            <p>이름
                <?php echo $username; ?>
            </p>
            <p>성별
                <?php
                if ($usersex == 'm') {
                    echo '남자';
                } else {
                    echo '여자';
                } ?>
            </p>
            <p>생일
                <?php
                $userbirth = strtotime($userbirth);
                echo date(" Y-m-d", $userbirth) ?>
            </p>
            <p>전화번호
                <?php echo $userphonenumber; ?>
            </p>
            <p>예약된 공연 목록
                <?php echo  $PerformanceName; ?>
            </p>
            <p>
                <a href="/a_team/a_team4/dbproject/code/register_and_login/changemember.php">회원정보 수정</a>
            </p>
            <p>
                <a href="/a_team/a_team4/dbproject/code/register_and_login/deletemember.php">회원 탈퇴</a>
            </p>

        </div>
    </div>

    <!--footer-->
    <footer>
    데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템    </footer>
</body>

</html>    