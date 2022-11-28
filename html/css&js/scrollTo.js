function showResult() {
    var bodyOffset = document.querySelector("body").style.overflow = 'scroll'
    var location = document.querySelector(".resultH1").offsetTop;
    location = location - 100;
    window.scrollTo({ top: location, behavior: 'smooth' });
}

function goToTOp() {
    window.scrollTo({ top: 0});
}

