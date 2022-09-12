
//side-bar toggle button
let toggle = false;
var side_bar = document.querySelector(".side-bar");
var side_bar_content= document.querySelector(".side-bar-content");
var side_bar_txt = document.querySelectorAll(".icon-txt");
document.querySelector("#toggle-btn").addEventListener("click", function(){
    if (toggle) {
        //side-bar properties
        side_bar.style="width: 5em;";
        side_bar.classList.replace("side-open", "side-close");

        //side-bar-content properties
        side_bar_content.style="margin-left: 5em;";
        side_bar_content.classList.replace("side-content-open", "side-content-close");
        setTimeout(() =>{
            side_bar_txt.forEach(element => {
                element.style="visibility: hidden; position: absolute;";
            });
        }, 200)
    }else{
        //side-bar properties
        side_bar.style="width: 18em;";
        side_bar.classList.replace("side-close", "side-open");

        //side-bar-content properties
        side_bar_content.style="margin-left: 18em;";
        side_bar_content.classList.replace("side-content-close", "side-content-open");

        setTimeout(() => {
            side_bar_txt.forEach(element => {
                element.style="visibility: visible; position: relative;";
            });
        }, 200);
    }
    toggle = !toggle;
})