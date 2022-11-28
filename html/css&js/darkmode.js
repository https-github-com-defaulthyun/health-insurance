const btn = document.querySelector(".btn-toggle"); 
const theme = document.querySelector("#light"); 
const newTheme = localStorage.getItem('currentTheme');

    if (newTheme == "black") { 
        theme.href = "js&css/black.css"; 
    } 
    else{
        theme.href = "js&css/main.css"; 
    }

    function Darkmode() { 
    if (theme.getAttribute("href") == "js&css/white.css") { 
        theme.href = "js&css/black.css"; 
        localStorage.setItem('currentTheme', 'black');
        } else { 
        theme.href = "js&css/white.css"; 
        localStorage.setItem('currentTheme', 'white');
        } 
    };


    

    