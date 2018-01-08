function countJoueurs(nomsession){
    var baseurl = window.location.origin;
    console.log(baseurl);
    countJoueursConnectes(nomsession,baseurl);
    countJoueursAbsolute(nomsession,baseurl);
    countJoueursRelative(nomsession,baseurl);
}


function countJoueursConnectes(nomSession, baseurl ){
    $.ajax({ url: baseurl + "/api/user/count/" + nomSession }).then(
        function(data) {
            value = data['nombreUtilisateursConnectes'];
            element =$( "#compteurJoueurs" );
            if (doElementValueChange(element, value))
                 doBounceHorizontal(element,  3 , 10, 300);
            element.text(value);
         });
}


function countJoueursAbsolute(nomSession, baseurl ){
    $.ajax({ url:  baseurl + "/api/user/count/" + nomSession + "/absolute" }).then(
        function(data) {
             value = data['nombreJoueursAbsolute'];
             element = $( "#compteurJoueursAbsolute");
             if (doElementValueChange(element, value))
                 doBounceVertical($( element ),  3 , 10, 300);
             element.text(value);
        });
}

function countJoueursRelative(nomSession, baseurl ){
    $.ajax({ url:  baseurl + "/api/user/count/" + nomSession + "/relative" }).then(
        function(data) {
                value = data['nombreJoueursRelative'];
                element = $('#compteurJoueursRelative');
                if (doElementValueChange(element, value))
                    doBounceResize(element,  3 , 10, 300);
                element.text(value);
           });
}

function doElementValueChange(element, value){
    return element.html()==value?false:true;
}

function doBounceHorizontal(element, times, distance, speed) {
    for(var i = 0; i < times; i++) {
        element.animate({left: '-='+distance+'px'}, speed)
            .animate({left: '+='+distance+'px'}, speed);
    }
}

function doBounceVertical(element, times, distance, speed) {
    for(var i = 0; i < times; i++) {
        element.animate({top: '-='+distance+'px'}, speed)
            .animate({top: '+='+distance+'px'}, speed);
    }
}

function doBounceResize(element, times, distance, speed) {
    halfDistance = Math.round(distance/2);
    console.log(distance + " / " +halfDistance);
    for(var i = 0; i < times; i++) {
        element.animate({lineHeight: '+='+distance+'px', width:'+='+distance+'px', height: '+='+distance+'px', left:"-="+halfDistance+'px'}, speed)
            .animate({lineHeight: '-='+distance+'px', width:'-='+distance+'px', height: '-='+distance+'px', left:"+="+halfDistance+'px'}, speed);
    }
}


