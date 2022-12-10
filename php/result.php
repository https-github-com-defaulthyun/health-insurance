<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>추천 받은 보험</title>
    <script type="text/javascript">
        window.onload = function(){
            getNames();
            showResult()
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
                    <a class="curheader" onclick=>추천 받은 보험</a>
                </span>
                <span>
                    <a class="otherheader" onclick="location.href='allIns.php'">전체 보험 상품</a>
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
    <!--추천-->
    <h1 class="resultH1"><a class="name" id="resultname">@@</a> 님의 건강 상태</h1>
    <div class="result">
        <div class="healthinfoContainer">
        </div>
    </div>
    <h1 class="resultH1"><a class="name" id="resultname">@@</a> 님께 추천해드리는 보험</h1>
    <div class="result">
        <div class="resultContainer">
        </div>
    </div>
    <!--footer-->
    <footer id="foot">
    데이터베이스 및 실습 1조 B789055 전성태, B789071 현동엽, B789033오현석, B789049 이현진<br>
        주제: 개인 건강정보를 통한 맞춤형 보험 상품 추천 시스템    </footer>
    <script>make_feedback()</script>
</body>

</html>    