let servResponse = document.querySelector("#shortUrl_div")
let input = document.querySelector("#baseUrl_inp_id")
document.forms.ourForm.onsubmit = function(e) {
    e.preventDefault();

    let userInput = document.forms.ourForm.baseUrl_inp.value;

    let xhr = new XMLHttpRequest();
    
    xhr.open("POST", "mainpage.php");

    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if(xhr.readyState === 4 && xhr.status === 200){
            console.log("all good")
            servResponse.innerHTML = xhr.responseText
        }
    }
    xhr.send("ourForm_inp=" + userInput)
    input.value = "";
    
}

function copyFunc() {
    shortUrl.select();
    document.execCommand("copy");
}