function collapse(colbutid){
    var colbut = this.document.getElementById(colbutid);
    var inside = colbut.nextElementSibling;
    if (inside.style.maxHeight){
        inside.style.maxHeight = null;
    } else{
        inside.style.maxHeight = screen.height + "px";

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