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
    colbut.classList.toggle("selected");
}

function linkToCollapsible(colbutid, path){
    this.document.cookie = colbutid + "=1; expires=0; path=" + path;
}

function highlight(butid){
    var button = this.document.getElementById(butid);
    button.classList.toggle("highlighted");
}

function checkCookies(root){
    var cookies = this.document.cookie.split(";");
    let banner = true;
    for (let i = 0; i< cookies.length; i++){
        let cookie = cookies[i];
        if (cookie.endsWith("=1")){
            let name=cookie.split("=")[0].replace(" ", "");
            if (name == "cbanner"){
                banner = false;
            }else if (this.document.getElementById(name)){
                collapse(name);
            }
        }
    }
    if (banner){
        document.write("<div class='bottom screenwidth banner' id=cbanner><p class=banner>This website uses cookies - <a href=" + root +  "/.cookies.html style=color:yellow>learn more</a></p><button class=banner onclick=removeElement('cbanner')>OK</button></div>");
    }
}

function removeElement(element){
    this.document.getElementById(element).classList.add("hide");
    expiry = new Date();
    expiry.setFullYear(expiry.getFullYear()+1);
    this.document.cookie = element+"=1; expires="+expiry.toUTCString() + "; path=/";
}