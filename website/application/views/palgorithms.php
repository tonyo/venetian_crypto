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
        var currentRow = '<div id="t_row' + i + '">';
        for (var j = 0; j < 26; j++) {
            // Make two classes instead of one id?
            currentRow += '<div class="tr_' + i + ' tc_' + j +'">' + chars[j] + " </div>";
        }
        tableHtml += currentRow + "</div>";
        chars.push(chars.shift());
    }
    $("#tabula_recta").html(tableHtml);
}

function highlightIntersection(i, j) {
    if (i != -1 && j != -1) {
        var el = ".tr_" + i + ".tc_" + j;
        $(el).addClass('h_is');
    }
}

function hideIntersection(i, j) {
    var el = ".tr_" + i + ".tc_" + j;
    $(el).removeClass('h_is');
}

function highlightRow(i) {
    hideIntersection(highlighted_row, highlighted_column);
    $(".tr_" + highlighted_row).removeClass('h_row');
    highlighted_row = i;
    $(".tr_" + i).addClass('h_row');
    highlightIntersection(i, highlighted_column);
}

function highlightColumn(j) {
    hideIntersection(highlighted_row, highlighted_column);
    $(".tc_" + highlighted_column).removeClass('h_column');    
    highlighted_column = j;
    $(".tc_" + j).addClass('h_column');
    highlightIntersection(highlighted_row, j);
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
        
        <div id="edits_outer">
        
            <!-- Plaintext -->
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Plaintext</span>            
                <input type="text" class="form-control text-edit">
            </div>
            
            <!-- Key -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Key</span>            
                <input type="text" class="form-control text-edit">
            </div>            
            
            <!-- Ciphertext -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Ciphertext</span>            
                <input type="text" class="form-control text-edit">
            </div>  
            
            
            <span class="glyphicon glyphicon-arrow-left arrow-large"></span>
            <span class="glyphicon glyphicon-arrow-right arrow-large"></span>
        
        </div>
                
    </div>    
    <div class="col-xs-5">
        <button type="button" id="hide_show_tabula_btn" class="btn btn-info btn-xs">Hide/show</button>
        <br /> <br />
        <div id="tabula_recta"></div>    
    </div>
    
</div>    
