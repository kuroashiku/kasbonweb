window.showSnackbar = function(message){
    var el = document.createElement("div");
    el.className = "snackbar";
    var y = document.getElementById("snackbar-container");
    el.innerHTML = message;
    y.append(el);
    el.className = "snackbar show";
    setTimeout(function(){ el.className = el.className.replace("snackbar show", "snackbar"); }, 3000);
}