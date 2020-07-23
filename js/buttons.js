function collapse(colbutid){
    console.log(colbutid);
    var colbut = this.document.getElementById(colbutid);
    console.log(colbut);
    var inside = colbut.nextElementSibling;
    console.log(inside);
    if (inside.style.maxHeight){
        inside.style.maxHeight = null;
        this.document.cookie = colbutid+"=0; expires=0";
    } else{
        inside.style.maxHeight = Number.MAX_SAFE_INTEGER + "px";
        this.document.cookie = colbutid+"=1; expires=0";
    }
    var show = colbut.children.namedItem("show");
    var hide = colbut.children.namedItem("hide");
    show.classList.toggle("hide");
    hide.classList.toggle("hide");
}

function highlight(butid){
    var button = this.document.getElementById(butid);
    button.classList.toggle("highlighted");
}

function checkCookies(){
    var cookies = this.document.cookie.split(";");
    for (let i = 0; i< cookies.length; i++){
        let cookie = cookies[i];
        if (cookie.endsWith("=1")){
            let name=cookie.split("=")[0].replace(" ", "");
            if (this.document.getElementById(name)){
                collapse(name);
            }
        }
    }
}