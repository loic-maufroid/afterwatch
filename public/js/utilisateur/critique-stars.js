$(function(){

    $(".stars").each(function(){
        var note = $(this).attr('value');
        for(i=0;i<note;i++){
            $(this).append("<span>★</span>");
        }
        for(j=0;j<(5-note);j++){
            $(this).append("<span>☆</span>");
        }

    });

})