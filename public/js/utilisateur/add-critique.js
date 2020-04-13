$(function(){

    for(i=0;i<6;i++){
        var note = $("label[for=critique_note_"+i+"]").text();
        var temp = note+" ";
        for(j=0;j<note;j++){
            temp += "<span>★</span>";
        }
        $("label[for=critique_note_"+i+"]").html(temp);
    }
})