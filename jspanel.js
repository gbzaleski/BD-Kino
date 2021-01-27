
function confirmTicket(idTicket)
{

    var request = $.ajax({
        url: 'confirmticket.php',
        data: {idBiletuct: idTicket},
        contentType: 'application/json; charset=utf-8'
    });
    
    $("#tr" + idTicket).remove();
}

function rejectTicket(idTicket, freedSeats, idShow)
{
    var request = $.ajax({
        url: 'rejectticket.php',
        data: {idBileturt: idTicket, noweWolneMiejscart: freedSeats, idSeansurt: idShow},
        contentType: 'application/json; charset=utf-8'
    });

    $("#tr" + idTicket).remove();
}

function deleteHall(idHall)
{
    var request = $.ajax({
        url: 'deletehall.php',
        data: {nrSali: idHall},
        contentType: 'application/json; charset=utf-8'
    });

    $("#trh" + idHall).remove();
}

function deleteFilm(idFilm)
{
    var request = $.ajax({
        url: 'deletefilm.php',
        data: {idFilmu: idFilm},
        contentType: 'application/json; charset=utf-8'
    });

    $("#trf" + idFilm).remove();
}

function deleteScreening(idScreening)
{
    var request = $.ajax({
        url: 'deletescreening.php',
        data: {idSeansu: idScreening},
        contentType: 'application/json; charset=utf-8'
    });

    $("#trs" + idScreening).remove();
}

var hidden_new_res = false;
function hide_show_new_resv()
{
    if(hidden_new_res == false)
    {
        hidden_new_res = true;
        $("#restable").hide();
    }
    else
    {
        hidden_new_res = false;
        $("#restable").show();
    }
}

var hidden_old_resv = false;
function hide_show_old_resv()
{
    if(hidden_old_resv == false)
    {
        hidden_old_resv = true;
        $("#oldrestable").hide();
    }
    else
    {
        hidden_old_resv = false;
        $("#oldrestable").show();
    }
}

var hidden_hales = false;
function hide_show_hales()
{
    if(hidden_hales == false)
    {
        hidden_hales = true;
        $("#reshales").hide();
        $("#formhall").hide();
    }
    else
    {
        hidden_hales = false;
        $("#reshales").show();
        $("#formhall").show();
    }
}

var hidden_films = false;
function hide_show_films()
{
    if(hidden_films == false)
    {
        hidden_films = true;
        $("#resfilms").hide();
        $("#formfilm").hide();
    }
    else
    {
        hidden_films = false;
        $("#resfilms").show();
        $("#formfilm").show();
    }
}

var hidden_screenings = false;
function hide_show_screenings()
{
    if(hidden_screenings == false)
    {
        hidden_screenings = true;
        $("#resscreens").hide();
        $("#formscreening").hide();
    }
    else
    {
        hidden_screenings = false;
        $("#resscreens").show();
        $("#formscreening").show();
    }
}

function startPage()
{
    hide_show_old_resv();
    hide_show_hales();
    hide_show_films();
    hide_show_screenings();
}