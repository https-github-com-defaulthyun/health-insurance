function offClick() {
    document.querySelector('.modalbackground').style.display ='none';
    localStorage.setItem('loggin', 'true');
}

function loginmodal(){
    if(localStorage.getItem('loggin') === 'true'){
        document.querySelector('.modalbackground').style.display ='none';
    }
}
function getNames(){
    var name = '전성태';
    let names = document.querySelectorAll('.name');
    [...names].forEach(nm => {
        nm.innerHTML = name;
      })
}