function showResult() {
    if(document.getElementById('BP').value == '')
    document.getElementById('BP').value = 0;
    if(document.getElementById('BOS').value == '') 
    document.getElementById('BOS').value = 0;
    if(document.getElementById('SMM').value == '')
    document.getElementById('SMM').value = 0;
    if(document.getElementById('MBW').value == '')
    document.getElementById('MBW').value = 0;
    
    feedback = recommand();
    localStorage.setItem('isResult', 'true');
    localStorage.setItem('feedback', feedback)
    location.href = "result.php";
}

function make_feedback(){
    warning = localStorage.getItem('warning')
    danger = localStorage.getItem('danger')
    console.log('warning : ' + warning)
    console.log('danger : ' + danger)

    feedback = localStorage.getItem('feedback')
    let infoContainer = document.querySelector('.healthinfoContainer');
    let your_problem = document.createElement('p');
  
    your_problem.setAttribute('class', 'problem');
    console.log(feedback)
    your_problem.innerHTML = feedback
  
    infoContainer.appendChild(your_problem);

    let resultContainer = document.querySelector('.resultContainer');
    let queryresult = document.createElement('p');
    queryresult.setAttribute('class', 'problem');
    queryresult.innerHTML = 'warning : ' + warning + '<br>danger : ' + danger
    //이 부분에 쿼리문 추가
    //위 코드 warning에서는 '주의' 단계에 대해 product 테이블의 'kind' 컬럼 명 그대로 리스트로 들어있음 ex['highbp','brain']
    //danger에는 '위험' 단계에 대해 똑같이 들어있음.
    //danger에서 보상 가격이 높은거 우선으로 쿼리 해주시고 그다음 순서로 warning 부분에서는 가입 가격이 가장 낮은 순서대로 쿼리될 소 있게 부탁드려요
    resultContainer.appendChild(queryresult);
}

function logOut() {
    window.scrollTo({ top: 0});
    localStorage.setItem('isResult', 'false');
    localStorage.setItem('loggin', 'false');
    location.href = "./main.php";
}

function toResult(){
    if(localStorage.getItem('isResult') === 'true'){
        location.href = "./result.php";
    }
    else alert("먼저 건강 정보를 입력해주세요.")
}
