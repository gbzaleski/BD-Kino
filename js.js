function formHide()
{
    $("#queryform").remove();
} 

var last;
function show(opis, wolneMiejsca, trId, dlugH, dlugMin)
{
    var eleId = "#tr" + trId;
    $("#del").remove();

    if (last == trId)
    {
        last = null;
        return;
    }
    else
        last = trId;

    var content = '<tr id = "del"></tr>';
    $(eleId).after(content);
    $("#del").html('<td id = "delInside" colspan="5"></td>');
    content = "<b>Opis: </b>" + opis + "<br>";
    content += "<b>Długośc filmu: </b>";
    if (dlugH != 0)
        content += dlugH + " h ";
    if (dlugMin != 0)
        content += dlugMin + " min ";
    content += "<br>";

   // alert(trId);
    content +=
    '<div class = "form" style ="margin-top:20px;">'
        +'<form method="POST" action="taketickets.php" id = "ticketform">'
            +'<input type="number" id="showid" name="showid" value="'+trId+'"/>'
		    +'<label for="emailadd">Adres email:</label><br>'
			+'<input type="email" id="emailadd" name="emailadd" required/><br>'
			+'<label for="ticketsq">Liczba biletów (max. '+wolneMiejsca+'):</label><br>'
			+'<input type="range" id="amountRange"  name="amountRange" min="1" max="'+wolneMiejsca+'" value="1" oninput="this.form.amountInput.value=this.value"/>'
				+'<input type="number" id="amountInput" name="amountInput" min="1" max="'+wolneMiejsca+'" value="1" oninput="this.form.amountRange.value=this.value"/>'
			+'<center><div class="wrap"><button name ="submitres" TYPE="submit" VALUE="Pokaż" id = "submitres" class="buttonres button">Rezerwacja</button></div></center>'
		+'</form>'
    +'</div>';

    $("#delInside").html(content);
    $("#showid").hide();
}