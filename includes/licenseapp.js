function toggle(id)
{
    var elm = document.getElementById(id);
        var sp = document.getElementById("moreinfo");
    if (!elm || elm.length == 0) return;
    var d = elm.style.display;
        //alert(d);
    if (d && (d != "none" || d == ""))
    {
        elm.style.display = "none";
                sp.innerHTML = "Include more information about your work";
    } else
    {
        elm.style.display = "block";
                sp.innerHTML = "Hide these fields (All fields are optional)";
    }
}
