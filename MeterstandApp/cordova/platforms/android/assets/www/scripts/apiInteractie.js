/**
 * Created by Lesuisse on 02/11/2015.
 */
"use strict";
/*jslint browser: true*/
/*jslint devel: true*/
/*global $ */
var apiAddress = "http://www.mobiel.jengarobot.be/mi3p3/api.php?";



function getApiGebruiker(){
    // een ONVEILIGE manier om gebruikersgegevens te testen
    var data = {};
    data.name = $("#login").val();
    data.password = $("#pwd").val();
    data.format = "json";


    // test de api
    $.ajax({
        type : "POST",
        url : apiAddress+"m=login",
        crossDomain : true,
        data : data,
        withCredentials : false,
        success : function(responseData, textStatus, jqXHR) {
            var list = responseData.data;
            //JSON.parse(responseData);
            console.log("resultlist :" + list + " length:" + list.length);

            if (Object.keys(list).length > 0) {
                // list bevat minstens 1 property met waarde

                list.ID = parseInt(list.ID);
                // alles wat via json komt, is standaard een string of een object.
                // hier is het omzetten naar een int wel niet nodig, omdat we er niet met gaan rekenen
                console.log("Gebruikersgevens ok : ID = " + list.ID);
                alerter("Gebruikersgevens ok : ID = " + list.ID);
                window.location.replace('home.html');

            } else {
                console.log("Login failed : 0 results");
                alerter("Login failed : this login/password combination does not exist");
            }

        },
        error : function(responseData, textStatus, errorThrown) {
            console.log("Could not login " + errorThrown);
            alerter("Login failed : " + errorThrown);
        }
    });
}
function uitloggen(){

    window.location.replace('login.html');
}



function getApiGegevens(){
    // de producten van de server opvragen en weergeven dmv de alerter functie
    var data = {};
    //data.user = "test"; // als je de authentication in de api op true zet, heb je dit hier wel nodig
    //data.password = "test"; // als je de authentication in de api op true zet, heb je dit hier wel nodig
    data.format = "json";


    // test de api
    $.ajax({
        type : "POST",
        url : apiAddress+"m=getGegevens",
        crossDomain : true,
        data : data,
        withCredentials : false,
        success : function(responseData, textStatus, jqXHR) {
            var list = responseData.data;
            console.log("resultlist :" + list + " length:" + list.length);

            if (list.length > 0 ) {

                var tLijst1 = "";
                for(var i = 0; i< list.length; i++){
                    //tLijst1 = "<span class='rij'><span>" + list[i].ID + "</span><span class='kOdd'>" + list[i].User_ID + "</span><span>" + list[i].Meterstand +"</span></span><br>";
                    tLijst1 += "<tr><td>" + list[i].Meterstand + "</td><td>" + list[i].Datum + "</td></tr>"

                }

                $("#GegevensLijst").html(tLijst1);


            } else {
                console.log("Servertime retrieval failed");
                alerter("Servertijd kon niet opgevraagd worden");
            }

        },
        error : function(responseData, textStatus, errorThrown) {
            console.log("API Failure " + errorThrown);
            alerter("<br>API Fout. Probeer later nog eens.<br>(" + errorThrown + ")");
        }
    });
}
function NieuweGebruiker(){
    var data = {};
    data.achternaam = $("#achternaam").val();
    data.voornaam =  $("#voornaam").val();
    data.wachtwoord = $("#wachtwoord").val();
    data.email = $("#email").val();
    data.format = "json";

    $.ajax({
        type : "POST",
        url : apiAddress+"m=NieuweGebruiker",
        crossDomain : true,
        data : data ,
        withCredentials : false,
        error: function(responseData, textStatus, errorThrown) {
        console.log("API Failure " + errorThrown);

    },
        success: function(){
            console.log("gebruiker toegevoegd");
            $("#notif").html("UW heeft een gebruiker toegevoegd");
        }

    });

}
function setGegevens(){

    var data = {};
    data.gegeven= $("#meterstand").val();
    data.datum = $("#datum").val();
    data.format = "json";


    // test de api
    $.ajax({
        type : "POST",
        url : apiAddress+"m=setMeterstand",
        crossDomain : true,
        data : data,
        withCredentials : false,

        error : function(responseData, textStatus, errorThrown) {
            console.log("API Failure " + errorThrown);
            alerter("<br>API Fout. Probeer later nog eens.<br>(" + errorThrown + ")");
        }
    });
}
function getApiTijd(){
    // de tijd van de server opvragen en weergeven dmv de alerter functie
    var data = {};
    //data.user = "test"; // als je de authentication in de api op true zet, heb je dit hier wel nodig
    //data.password = "test"; // als je de authentication in de api op true zet, heb je dit hier wel nodig
    data.format = "json";


    // test de api
    $.ajax({
        type : "POST",
        url : apiAddress+"m=getTime",
        crossDomain : true,
        data : data,
        withCredentials : false,
        success : function(responseData, textStatus, jqXHR) {
            var list = responseData.data;
            console.log("resultlist :" + list + " length:" + list.length);

            if (Object.keys(list).length > 0) {
                // er zit slechts 1 item in de lijst, we geven dit ook onmiddelijk weer
                console.log("Servertijd :  " + list.servertime );
                alerter("Servertijd : " + list.servertime );


            } else {
                console.log("Servertime retrieval failed");
                alerter("Servertijd kon niet opgevraagd worden");
            }

        },
        error : function(responseData, textStatus, errorThrown) {
            console.log("API Failure " + errorThrown);
            alerter("<br>API Fout. Probeer later nog eens.<br>(" + errorThrown + ")");
        }
    });
}



function alerter(message){
    $("#alert").html(message);
}
