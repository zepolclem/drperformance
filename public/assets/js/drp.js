
$(document).ready(function () {
    // Add smooth scrolling to all links
    $("a").on('click', function (event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top - $('#drp_navbar').height() - 20
            }, 800, function () {

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        } // End if
    });
});


// var heightNavbar = document.getElementById("drp_navbar").offsetHeight;

// window.onscroll = function () { fixedNavbar() };

// function fixedNavbar() {
//     if (document.body.scrollTop > window.innerHeight || document.documentElement.scrollTop > window.innerHeight) {
//         document.getElementById("logo_navbar").style.height = "30px";
//         document.getElementById("drp_navbar").classList.add("bg-carbon-alt");
//         document.getElementById("drp_navbar").classList.add("border-bottom");
//         document.getElementById("drp_navbar").classList.add("border-success");
//     } else {
//         document.getElementById("logo_navbar").style.height = "70px";
//         document.getElementById("drp_navbar").classList.remove("bg-carbon-alt");
//         document.getElementById("drp_navbar").classList.remove("border-bottom");
//         document.getElementById("drp_navbar").classList.remove("border-success");
//     }
// }


