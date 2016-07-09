/*
 * Funzioni client-side per la Panoramica del pannello.
 */

$(document).ready(function() {
    $('#pulsante_qr').bind('click', function() {
        apriQR();
    });
    $('#stampa_qr').bind('click', function() {
        stampaQR();
    });
    $('#pulsante_stampa').bind('click', function() {
        stampaScheda();
    });
});

function apriQR() {
    document.getElementById("testo_qr").innerHTML = "";
    new QRCode(document.getElementById("testo_qr"), {
        text: document.URL
    });
    $("#modaleQR").modal('toggle');
}

function stampaQR() {
    var testo_qr = document.getElementById("testo_qr").innerHTML;
    var popupWin = window.open('', '_blank', 'width=450,height=500,scrollbars=1');
    popupWin.document.write('<!DOCTYPE html><html><head>' + '<title>Codice QR</title>' + '</head><body onload="window.print()">' + testo_qr + '</body></html>');
    popupWin.document.close();
}

function stampaScheda() {
    var nome = document.getElementById("nome").innerHTML;
    var desc = document.getElementById("desc").innerHTML;
    var nomegioco = document.getElementById("nomegioco").innerHTML;
    var regno = document.getElementById("regno").innerHTML;
    var punti = document.getElementById("punti").innerHTML;
    var punti_spesi = document.getElementById("punti_s").innerHTML;
    var note = document.getElementById("note").innerHTML;
    var popupWin = window.open('', '_blank', 'width=700,height=500,scrollbars=1');
    popupWin.document.write('<!DOCTYPE html><html><head>' + '<title>Scheda personaggio</title> <link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap.min.css" media="screen,print" /><link rel="stylesheet" href="libs-frontend/bootstrap/css/bootstrap-responsive.min.css" media="screen,print" /><link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" /><link rel="stylesheet" href="css/stampa.css" />' + '</head><body onload="window.print()"><div id="wrap"><h2>' + nomegioco + '</h2><div id="left"><b>Nome : </b>' + nome + '<br />' + '' + desc + '<br />' + '<b>Regno : </b> ' + regno + '<br /><b>Punti totali : </b>' + punti + '<br /><b>Punti spesi : </b>' + punti_spesi + '</div><div id="right"></div><div id="note_master"></div><hr /></div></body></html>');
    var div_destro = popupWin.document.getElementById("right");
    var div_note = popupWin.document.getElementById("note_master");
    try {
        var abilita = document.getElementById("abilita").cloneNode(true);
        var numero_abilita = abilita.rows.length;
        for (var i = 1; i < numero_abilita; i++) {
            var riga = abilita.rows[i];
            riga.deleteCell(1);
        }
        var count = 0;
        var nuova_tabella = popupWin.document.createElement("table");
        nuova_tabella.className = "table tabella table-bordered table-condensed"
        var thead = popupWin.document.createElement("thead");
        nuova_tabella.appendChild(thead);
        var head1 = popupWin.document.createElement("th");
        head1.innerHTML = "Abilita";
        var head2 = popupWin.document.createElement("th");
        head2.innerHTML = "Costo";
        thead.appendChild(head1);
        thead.appendChild(head2);
        var tbody = popupWin.document.createElement("tbody");
        nuova_tabella.appendChild(tbody);
        div_destro.appendChild(nuova_tabella);
        for (var i = 1; i < numero_abilita; i++) {
            if (count >= 10) {
                count = 0;
                var nuova_tabella = popupWin.document.createElement("table");
                nuova_tabella.className = "table tabella table-bordered table-condensed";
                var thead = popupWin.document.createElement("thead");
                nuova_tabella.appendChild(thead);
                var head1 = popupWin.document.createElement("th");
                head1.innerHTML = "Abilita";
                var head2 = popupWin.document.createElement("th");
                head2.innerHTML = "Costo";
                thead.appendChild(head1);
                thead.appendChild(head2);
                tbody = popupWin.document.createElement("tbody");
                nuova_tabella.appendChild(tbody);
                div_destro.appendChild(nuova_tabella);
            }
            tbody.appendChild(abilita.rows[i].cloneNode(true));
            count++;
        }
    } catch (err) {
        if (abilita == null) {
            abilita = popupWin.document.createElement("h4");
            abilita.innerHTML = "Nessuna abilità presente";
        }
    }
    var t = popupWin.document.createElement("b");
    t.innerHTML = "Note del master : " + note;
    div_note.appendChild(t);
    popupWin.document.close();
}