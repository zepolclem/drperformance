
var heightNavbar = document.getElementById("drp_navbar").offsetHeight;

window.onscroll = function () { fixedNavbar() };

function fixedNavbar() {
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        document.getElementById("logo_navbar").style.height = "30px";
    } else {
        document.getElementById("logo_navbar").style.height = "50px";
    }
}


