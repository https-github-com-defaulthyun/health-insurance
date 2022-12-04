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
    feedback = localStorage.getItem('feedback')

    let resultContainer = document.querySelector('.healthinfoContainer');
    let your_problem = document.createElement('p');
  
    your_problem.setAttribute('class', 'problem');
    console.log(feedback)
    your_problem.innerHTML = feedback
  
    resultContainer.appendChild(your_problem);
}

function logOut() {
    window.scrollTo({ top: 0});
    localStorage.setItem('isResult', 'false');
    localStorage.setItem('loggin', 'false');
    location.href = "main.php";
}

function toResult(){
    if(localStorage.getItem('isResult') === 'true'){
        location.href = "result.php";
    }
    else alert("먼저 건강 정보를 입력해주세요.")
}
