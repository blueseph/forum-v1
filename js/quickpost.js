$(document).ready(function () {
   
    $('#chat-toggle').click(function() {
       
        $('#chat-box').toggle(200);
       
    });
   
});

$(window).scroll(function () {

    if ($(window).scrollTop() + $(window).height() > $('.footer').offset().top) {
        alert("footer visible");
    } else {
        alert("footer invisible");
    }
});