var tableAcm = [];
var tableAu = [];
var tableBcm = [];
var tableBu = [];
var tableCcm = [];
var tableCu = [];

var u = 100;
var countResults = 0;
var Acms = 0;
var Aus = 0;
var Bcms = 0;
var Bus  = 0;
var Ccms = 0;
var Cus  = 0;




function demanderLesResultatsReels(){
    document.getElementById('f_square').style.display = 'none';
    document.getElementById('f_resultatsReels').style.display="inline";
    afficheLeTableauDeResultats();

}

function drawAnswer(){

    drawResults();
    calculeSagesseDesFoules();
    setTimeout(drawResultatSagesseDesFoules,1000);

}

function drawResults() {

    var ctx = document.getElementById("myCanvas").getContext("2d");
    ctx.font ="18px Georgia";
    ctx.fillStyle = "rgb(200,0,0)";//rouge

    ctx.fillText("solution exacte : ",300,250);
    ctx.fillText("S(A) = " + f_resultatsReels.surfaceAcmSquareResults.value + " cm²      1 u²",320,280);
    ctx.fillText("S(B) = " + f_resultatsReels.surfaceBcmSquareResults.value + " cm²      2 u²",320,300);
    ctx.fillText("S(C) = " + f_resultatsReels.surfaceCcmSquareResults.value + " cm²      5 u²",320,320);

}

function drawResultatSagesseDesFoules(){
    var ctx = document.getElementById("myCanvas").getContext("2d");

    ctx.font = "18px Georgia";
    ctx.fillStyle = "rgb(0,0,200)";//bleu

    ctx.fillText("La sagesse des foules (moyenne) : ",300,350);
    ctx.fillText("moyenne de S(A) = " + Acms + " cm²      " + Aus + " u²",320,380);
    ctx.fillText("moyenne de S(B) = " + Bcms + " cm²      " + Bus + " u²" ,320,400);
    ctx.fillText("moyenne de S(C) = " + Ccms + " cm²      " + Cus + " u²",320,420);

}

function drawSquare() {
    var ctx = document.getElementById("myCanvas").getContext("2d");
    ctx.font = "20px Georgia";
    ctx.fillText("largeur A = 1 u",20,170);

    ctx.font = "30px Georgia";
    ctx.fillText("A",65,90);
    ctx.rect(25, 40, u, u);

    ctx.fillText("B",260,100);
    ctx.rect(210,20,Math.sqrt(2)*u,Math.sqrt(2)*u);

    ctx.fillText("C",150,330);
    ctx.rect(50,200,Math.sqrt(5)*u,Math.sqrt(5)*u);



    ctx.stroke();


}

function afficheLeTableauDeResultats(){

    var tbdy = document.getElementById('results');

    document.getElementById('table_results').style.display='inline';

    for (var i=0;i<countResults;i++){

        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var td3 = document.createElement('td');
        var td4 = document.createElement('td');
        var td5 = document.createElement('td');
        var td6 = document.createElement('td');

        td1.appendChild(document.createTextNode(tableAcm[i]));
        td3.appendChild(document.createTextNode(tableBcm[i]));
        td5.appendChild(document.createTextNode(tableCcm[i]));

        td2.appendChild(document.createTextNode(tableAu[i]));
        td4.appendChild(document.createTextNode(tableBu[i]));
        td6.appendChild(document.createTextNode(tableCu[i]));
        tr.appendChild(td1);
        tr.appendChild(td3);
        tr.appendChild(td5);

        tr.appendChild(td2);
        tr.appendChild(td4);
        tr.appendChild(td6);

        tbdy.appendChild(tr);
    }


}



function addEstimation() {

    var sacm = f_square.surfaceAcmSquare.value;
    var sbcm = f_square.surfaceBcmSquare.value;
    var sccm = f_square.surfaceCcmSquare.value;
    var sau = f_square.surfaceAuSquare.value;
    var sbu = f_square.surfaceBuSquare.value;
    var scu = f_square.surfaceCuSquare.value;

    document.getElementById("valeursSaisies").innerHTML =   "A = " + sacm +" cm²;" + " B= " + sbcm +" cm²;" + " C = " + sccm +" cm²" +
                                                            "A = " + sau +"  u²;" + " B= " + sbu +"  u²;" + " C = " + scu +"  u²" ;
    document.getElementById("valeursSaisies").style.display='inline';

    tableAcm[countResults] = parseInt(sacm);
    tableBcm[countResults] = parseInt(sbcm);
    tableCcm[countResults] = parseInt(sccm);

    tableAu[countResults] = parseInt(sau);
    tableBu[countResults] = parseInt(sbu);
    tableCu[countResults] = parseInt(scu);

    countResults++;
    setTimeout(function(){document.getElementById("valeursSaisies").style.display='none';},7000);

}


function calculeSagesseDesFoules() {
    Acms = 0;
    Aus = 0;
    Bcms = 0;
    Bus  = 0;
    Ccms = 0;
    Cus  = 0;

    for (var i=0;i<countResults;i++){
        Acms += tableAcm[i];
        Bcms += tableBcm[i];
        Ccms += tableCcm[i];

        Aus += tableAu[i];
        Bus += tableBu[i];
        Cus += tableCu[i];
    }

    Acms=Math.round(Acms/countResults);
    Bcms=Math.round(Bcms/countResults);
    Ccms=Math.round(Ccms/countResults);

    Aus=Math.round(Aus/countResults);
    Bus=Math.round(Bus/countResults);
    Cus=Math.round(Cus/countResults);


	}

