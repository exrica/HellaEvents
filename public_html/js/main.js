$(document).ready(function(){
    
    $('body').css("display", "none");
    $('body').fadeIn(500);

    $('.smallmenubtn').click(function(){
        $('#menucontent').slideToggle('slow');
    });
    
    $('.showmoretext').click(function(){
        $('#showonclicksmall').slideToggle('slow');
        $('#showless').toggle('slow');
        $('#showmore').toggle('slow');
        
    });
});


