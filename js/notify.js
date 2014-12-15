function sharexyHideNotifyDialog(btn, url) {
    var guid = btn.getAttribute("guid");
    var params = 'request_type=hide_notify&guid='+guid;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
            document.getElementById('sharexy_notice').style.display = 'none';
        }
    }
    xmlhttp.open('POST', url, true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');  
    xmlhttp.send(params);
}