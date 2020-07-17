function collapse(colbutid){
        var colbut = this.document.getElementById(colbutid);
        var inside = colbut.nextElementSibling;
        if (inside.style.maxHeight){
            inside.style.maxHeight = null;
        } else{
            inside.style.maxHeight = inside.scrollHeight + "px";
        }
}