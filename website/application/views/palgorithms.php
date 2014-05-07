<link href="<?php echo CSS ?>palgo_style.css" rel="stylesheet">

<script>

highlighted_row = -1;
highlighted_column = -1;

function drawTable() {
    var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var chars = alphabet.split("");
    console.log(chars);
    var tableHtml = '';

    for (var i = 0; i < 26; i++) {
        var currentRow = '<div id="t_r' + i + '">';
        for (var j = 0; j < 26; j++) {
            // Make two classes instead of one id?
            currentRow += '<span id="el_' + i + '_' + j +'">' + chars[j] + " </span>";
        }
        tableHtml += currentRow + "</div>";
        chars.push(chars.shift());
    }
    $("#tabula_recta").html(tableHtml);
}

function highlightRow(i) {
    $("[id^=el_" + highlighted_row + "_]").removeClass('h_row');
    highlighted_row = i;
    $("[id^=el_" + i + "_]").addClass('h_row');
}

function highlightColumn(j) {
    $("[id$=_" + highlighted_column + "]").removeClass('h_column');
    highlighted_column = j;
    $("[id$=_" + j + "]").addClass('h_column');
}


$(document).ready(function() {
    drawTable();
    
    // Click handlers
    $("#hide_show_tabula_btn").click(function() {
        $('#tabula_recta').slideToggle(500);
    });    
    
});

</script>
<br />
<div class="row">
    <div id="ciphertext" class="col-xs-7">
        
    </div>    
    <div class="col-xs-5">
        <button type="button" id="hide_show_tabula_btn" class="btn btn-info btn-xs">Hide/show</button>
        <br /> <br />
        <div id="tabula_recta"></div>    
    </div>
    
</div>    
