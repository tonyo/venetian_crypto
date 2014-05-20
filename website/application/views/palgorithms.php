<link href="<?php echo CSS ?>palgo_style.css" rel="stylesheet">

<script>

highlighted_row = -1;
highlighted_column = -1;

function drawTable() {
    var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var chars = alphabet.split("");
    var tableHtml = '';

    // First row
    var currentRow = '<div id="t_row0">';
    currentRow += '<div class="tr_0 tc_0"> </div>'
    for (var j = 1; j <= 26; j++) {
        currentRow += '<div class="tr_0 tc_' + j +'">' 
                      + chars[j-1] + " </div>";
    }
    tableHtml += currentRow + "</div>";
    
    
    for (var i = 1; i <= 26; i++) {
        var currentRow = '<div id="t_row' + i + '">';
        currentRow += '<div class="tr_' + i + ' tc_0">'
                      + chars[0] + '</div>';
        for (var j = 1; j <= 26; j++) {
            currentRow += '<div class="tr_' + i + ' tc_' + j +'">' 
                          + chars[j-1] + " </div>";
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

function getCoord(el) {
    var classStr = el.attr('class');
    var re_r = /tr_(\d+)/.exec(classStr);
    var re_c = /tc_(\d+)/.exec(classStr);
    if (re_r && re_c) {
        return [re_r[1], re_c[1]];
    }
    return null;
}

function prepareInputs() {
    var textInputClass = '.text-edit';
    var textLabelClass = '.text-text';
    
    $(textInputClass).hide();
    
    $(textLabelClass).click(function() {
        $(this).hide();
        var correspInput = $(this)
                           .siblings(textInputClass)
                           .first();
        correspInput.val($(this).text());
        correspInput.show();
        correspInput.focus();
    });
    
    $(textInputClass).blur(function() {
        $(this).hide();
        var correspLabel = $(this)
                           .siblings(textLabelClass)
                           .first();
        var new_text = $(this).val();
        correspLabel.text(new_text);
        correspLabel.show();
    });    
}

$(document).ready(function() {
    drawTable();
    
    // Click handlers
    $("#hide_show_tabula_btn").click(function() {
        $('#tabula_recta').slideToggle(500);
    });

    var col = 4;
    var row = 4;
    $("#arrow-back").click(function() {
        col += 1;
        highlightColumn(col);
    });

    $("#arrow-forward").click(function() {
        row += 1;
        highlightRow(row);
    });
    
    $(".tr_0").click(function(e) {
        ar = getCoord($(e.target));
        highlightColumn(ar[1]);
    });

    $(".tc_0").click(function(e) {
        ar = getCoord($(e.target));
        highlightRow(ar[0]);
    });  

    $('.nav.nav-tabs a').click(function() {
        alert("Y u so "+$(this).attr("data-key")+"?")

        return false;
    });
    
    prepareInputs();    
});

</script>

<div class="row doo-hickey">
    <div id="ciphertext" class="col-xs-7">

        <ul class="nav nav-tabs">
            <li class="active"><a href="#alberti" data-key="alberti" data-toggle="tab">Alberti</a></li>
            <li><a href="#trithemius" data-key="trithemius" data-toggle="tab">Trithemius</a></li>
            <li><a href="#belaso" data-key="belaso" data-toggle="tab">Belaso</a></li>
            <li><a href="#vigenere" data-key="vigenere" data-toggle="tab">Vigenere</a></li>
        </ul>

        <div>
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Plaintext</span>
                <div class="text-text">Meow1</div>
                <input type="text" id="plain-edit" class="form-control text-edit">
            </div>

            <!-- Key -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Key</span>
                <div class="text-text">Meow2</div>
                <input type="text" id="key-edit" class="form-control text-edit">
            </div>            

            <!-- Ciphertext -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Ciphertext</span>
                <div class="text-text">Meow3</div>                
                <input type="text" id="cipher-edit" class="form-control text-edit">
            </div>  

            <span id="arrow-back" class="glyphicon glyphicon-arrow-left arrow-large"></span>
            <span id="arrow-forward" class="glyphicon glyphicon-arrow-right arrow-large"></span>
        </div>

    </div>    
    <div class="col-xs-5">
        <button type="button" id="hide_show_tabula_btn" class="btn btn-info btn-xs">Hide/show</button>
        <br /> <br />
        <div id="tabula_recta"></div>    
    </div>
    
</div>    
