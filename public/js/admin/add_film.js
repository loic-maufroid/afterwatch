$("#submitFilm").hide();
$("#submitFilm").prop('disabled',true);
$("label").hide();
$("label[for=film_titre]").show();
$("label[for=film_legislation]").show();

var films = [];
var curEntry = [];

$("#boutonPopu").click(function(event){

    try {
    
    event.preventDefault();
    $("#boutonPopu").prop('disabled',true);
    $("#infos").hide();
    $("#messageRecherche").text("");
    $("#infos thead").remove();
    $("#infos tbody").remove();
    films = [];
    curEntry = [];
    $("#infos").append("<thead class='thead-info'><tr><th scope='col'>Titre</th><th scope='col'>Date de sortie</th><th scope='col'>Genre(s)</th><th scope='col'>Affiche</th><th scope='col'>Réalisateur(s)</th><th>Actions</th></tr></thead><tbody></tbody>");
    var query = $("#film_titre").val();
    if (query.trim().length > 0){

    $.ajax({
            type : 'GET',
            url : "https://api.themoviedb.org/3/search/movie?language=fr&api_key=7024fabede73fed68005e5a632136b71&query="+query,
            dataType : 'JSON',
        success: function(response){

            console.log(response);

           if (response.results.length > 0){
            
            var resultats = response.results;

            for(let i=0;i<resultats.length;i++){
                
                titre = resultats[i].title;
                synopsis = resultats[i].overview;
                dateFilm = resultats[i].release_date;
                idFilm = resultats[i].id;
                
                films.push([idFilm,titre,synopsis,dateFilm]);
            }

           for([indice,film] of films.entries()){

           $("#infos tbody").append("<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>");
           curEntry[indice] = [$("#infos tbody tr:nth-child("+(indice+1)+")"),film];

           }

           for (i=0;i<curEntry.length;i++){

           curEntry[i][0].children("td:first-child").text(curEntry[i][1][1]);
           
           
           if (curEntry[i][1][3])
           curEntry[i][0].children("td:nth-child(2)").text(curEntry[i][1][3]);

           }
           //console.log(curEntry.children());
           //var date = dateFilm;
           
           //$("#film_date").val(new Date(date).toISOString().substring(0,10));
           
           for(i=0;i<curEntry.length;i++){
           detailsFilm(i); 
           }

           for(i=0;i<curEntry.length;i++){
               trailerFilm(i);
           }

           for(i=0;i<curEntry.length;i++){
               creditsFilm(i);     
            }
           
           for(i=0;i<curEntry.length;i++){
               curEntry[i][0].children().last().append("<button type='button' class='selection btn btn-primary' value='"+i+"'>Sélectionner</button>");
           }

           $("#messageRecherche").text("Chargement des suggestions...");
           $(".selection").prop('disabled',true);

           timer = setTimeout(showReady,3000+500*curEntry.length);
           
           $(".selection").click(function(e){
           
           var numero = $(this).val();
           
           curEntry[numero][0].css("border","solid");
           curEntry[numero][0].toggleClass("border-primary");
           $(".selection").toggle();
           curEntry[numero][0].children().last().append("<button type='button' class='deselection btn btn-danger' value='"+numero+"'>Déselectionner</button>");
           $("label").hide();
           $("input").hide();
           $("label[for=film_legislation]").show();
           $("#film_legislation").show();
           
           $("#boutonPopu").hide();
           $("#submitFilm").show();
           $("#titre").html("<h4>"+curEntry[numero][1][1]+"</h4>");
           console.log(curEntry[numero][1][6]);
           if (curEntry[numero][1][6])
           $("#affiche").html("<img src='http://image.tmdb.org/t/p/w185/"+curEntry[numero][1][6]+"'>");
           if (curEntry[numero][1][3])
           $("#date").html("<p>sorti le "+curEntry[numero][1][3]+"</p>");
           console.log(curEntry[numero][1][9]);
           if(curEntry[numero][1][9] != "")
           $("#director").html("<p> de "+curEntry[numero][1][9].split('+').join('/')+"</p>");

           $("#film_titre").val(curEntry[numero][1][1]);
           if (curEntry[numero][1][3])
           $("#film_date").val(new Date(curEntry[numero][1][3]).toISOString().substring(0,10));
           else
           $("#film_date").val(null);
           $("#film_synopsis").val(curEntry[numero][1][2]);
           $("#film_act").val(curEntry[numero][1][11]);
           $("#film_genre").val(curEntry[numero][1][4]);
           $("#film_duree").val(curEntry[numero][1][5]);
           $("#film_nationalite").val(curEntry[numero][1][7]);
           $("#film_real").val(curEntry[numero][1][9]);
           $("#film_scen").val(curEntry[numero][1][10]);
           $("#film_trailer").val(curEntry[numero][1][8]);
           $("#film_affiche").val(curEntry[numero][1][6]);

           $("#submitFilm").prop('disabled',false);
           
           $('#scrollform').animate({
                    scrollTop: 0
                }, 2000);

           $(".deselection").click(function(e){
               $("#submitFilm").prop('disabled',true);
               
               $("#selectedFilm *").html("");
               $("label[for=film_titre]").show();
               $("#film_titre").show();
               $("label[for=film_legislation]").show();
               $("#film_legislation").show();
               $("#boutonPopu").show();
               $("#submitFilm").hide();
               var index = $(this).val();
               curEntry[index][0].css("border","inherit");
               curEntry[index][0].toggleClass("border-primary");
               $(".deselection").remove();
               $(".selection").toggle();
           });
           });
           }
           else{
           $("#messageRecherche").text("Pas de résultats.Vérifiez l'expression en recherche et réessayez."); 
           $("#boutonPopu").prop('disabled',false);
           }
  
           
    }
    }         
    );
    }
    else{
    $("#messageRecherche").text("Saisissez un nom de film");
    $("#boutonPopu").prop('disabled',false);
    }
} catch (error) {
        console.log(error);
        $("#submitFilm").hide()
        $("#selectedFilm *").html("");
        $("label").hide();
        $("input").hide();
        $("#film_titre").show();
        $("#film_legislation").show();
        $("label[for='film_titre']").show();
        $("label[for='film_legislation']").show();
        $("#infos").hide();
        $("#boutonPopu").prop('disabled',false);
        $("#messageRecherche").text("La recherche a échoué : "+error);
}
});

function showReady(){
    clearTimeout(timer);
    $("#boutonPopu").prop('disabled',false);
    $("#infos").show();
    $("#messageRecherche").text("Suggestions prêtes !");
    $(".selection").prop('disabled', false);
    setTimeout(function(){$("#messageRecherche").text("");},8000);
}

function detailsFilm(i){

    $.ajax({
               type : 'GET',
               url : "https://api.themoviedb.org/3/movie/"+curEntry[i][1][0]+"?api_key=7024fabede73fed68005e5a632136b71&language=fr",
               dataType : 'JSON',
               success: function(response){
                
                console.log(i);
                
                   if (response.genres.length > 0){
                   var genresFilm = response.genres[0].name; 
                   for (let indice = 1; indice < response.genres.length; indice++) {
                       genresFilm += "+"+response.genres[indice].name;
                   }
                   curEntry[i][1].push(genresFilm);
                   curEntry[i][0].children("td:nth-child(3)").text(genresFilm.split('+').join('-'));
               }
               else
               curEntry[i][1].push(null);

               console.log(curEntry[i][0]);
                   
                   
                   if(response.runtime){
                   //curEntry[i][0].children().last().text(Math.floor(response.runtime / 60)+"h"+(response.runtime % 60)+"mn");
                   curEntry[i][1].push(response.runtime);
                   }
                   else
                   curEntry[i][1].push(null);

                   if (response.poster_path){
                   curEntry[i][1].push(response.poster_path);
                   curEntry[i][0].children("td:nth-child(4)").append("<img src='http://image.tmdb.org/t/p/w185/"+response.poster_path+"'>");
                   }
                   else
                   curEntry[i][1].push(null);


                   if (response.original_language){
                   curEntry[i][1].push(response.original_language);
                   //curEntry[i][0].children().last().text(response.original_language);
                   }
                   else
                   curEntry[i][1].push(null);

                }
           })
}

function trailerFilm(i){
    $.ajax({
                       type : 'GET',
                       url : "https://api.themoviedb.org/3/movie/"+curEntry[i][1][0]+"/videos?api_key=7024fabede73fed68005e5a632136b71&language=fr",
                       dataType : 'JSON',
                       success: function(response){
                           //console.log(response);

                           if (response.results.length > 0){
                           curEntry[i][1].push(response.results[0].key);
                           //curEntry[i][0].children().last().append("<a href='https://youtube.com/watch/"+response.results[0].key+"'>Lien vers la bande-annonce</a>");
                           }
                           else
                           curEntry[i][1].push(null);
                       }
    });

}

function creditsFilm(i){
   

                           $.ajax({
                               type : 'GET',
                               url : 'https://api.themoviedb.org/3/movie/'+curEntry[i][1][0]+'/credits?api_key=7024fabede73fed68005e5a632136b71',
                               dataType : 'JSON',
                               success: function(response){

                                   var dirsFilm = "";
                                   
                                   var actsFilm = "";
                                   
                                   var scensFilm = "";
                                   
                                  for (let k = 0; k < response.crew.length; k++) {
                                      if (response.crew[k].job == "Director"){
                                         dirsFilm += response.crew[k].name+"+";
                                      }
                                      if (response.crew[k].department == "Writing"){
                                         scensFilm += response.crew[k].name+"+";
                                      }
                                  }
                                  for (let j = 0; j < response.cast.length; j++) {
                                      if (j == 5)
                                      break;
                                      actsFilm += response.cast[j].name+"+";
                                  }
                                  if (dirsFilm.length > 0)
                                  dirsFilm = dirsFilm.substring(0,dirsFilm.length-1);

                                  if (actsFilm.length > 0)
                                  actsFilm = actsFilm.substring(0,actsFilm.length-1);

                                  if (scensFilm.length > 0)
                                  scensFilm = scensFilm.substring(0,scensFilm.length-1);

                                  /*console.log(dirsFilm);
                                  console.log(scensFilm);
                                  console.log(actsFilm);*/

                                  curEntry[i][0].children("td:nth-child(5)").text(" "+dirsFilm.split('+').join('/'));
                                  /*curEntry[i][0].append("<td></td>");
                                  curEntry[i][0].children().last().text(" "+scensFilm.split('+').join('/'));
                                  curEntry[i][0].append("<td></td>");
                                  curEntry[i][0].children().last().text(" "+actsFilm.split('+').join('/'));*/

                                  //console.log(curEntry);
                                  
                                  curEntry[i][1].push(dirsFilm);
                                  curEntry[i][1].push(scensFilm);
                                  curEntry[i][1].push(actsFilm);

                               }
                           });
}
