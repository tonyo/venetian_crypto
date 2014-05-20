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

currentIndex = -1;
function resetEncryption() {
    currentIndex = 0;
    $('#cipher-label').text('');    
}

function initFields(ciphername) {
    var plaintext =  'The East and Midrealm are at war';
    $('#plain-label').text(plaintext);
    var key = '';
    
    switch(ciphername) {
        case 'alberti':
            key = 'BBB BBBB CCC CCCCCCCC DDD DD DDD';
            break;
        case 'trithemius':
            key = 'ABC DEFG HIJ KLMNOPQR STU VW XYZ';
            break;
        case 'belaso':
            key = 'PEN NSIC WAR PENNSICW ARP EN NSI';
            break;
        case 'vigenere':
            key = 'FTH EEAS TAN DMIDREAL MAR EA TWA';
            break;
        default:
            alert('smth wrong');
    }
    $('#key-label').text(key);
    resetEncryption();
}

function getIndex(l) {
    var ascii = l.toUpperCase().charCodeAt(0);
    var ind = ascii - "A".charCodeAt(0);
    if (ind < 0 || ind >= 26) {
        return -1;
    }
    return ind;
}

function getLetter(i) {
    var l = String.fromCharCode("A".charCodeAt(0) + i);
    return l;
}

$(document).ready(function() {
    drawTable();
    
    // Click handlers
    $("#hide_show_tabula_btn").click(function() {
        $('#tabula_recta').slideToggle(500);
    });

    $("#arrow-back").click(function() {
        //highlightColumn(col);
    });

    $("#arrow-forward").click(function() {
        var ptLet   = $('#plain-label').text()[currentIndex];
        var ptIndex = getIndex(ptLet);
        var keyLet  = $('#key-label').text()[currentIndex];
        var keyIndex = getIndex(keyLet);
        var newLet = '';
        if (ptIndex == -1 || keyIndex == -1) {
            newLet = ' ';        
        } else {
            highlightColumn(keyIndex + 1);
            highlightRow(ptIndex + 1);
            newLet = getLetter((keyIndex + ptIndex) % 26);
        }

        var curCt = $('#cipher-label').text();
        $('#cipher-label').text(curCt + newLet)
        currentIndex += 1;
    });
    
    $(".tr_0").click(function(e) {
        ar = getCoord($(e.target));
        highlightColumn(ar[1]);
    });

    $(".tc_0").click(function(e) {
        ar = getCoord($(e.target));
        highlightRow(ar[0]);
    });  

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var key = $(this).attr('data-key');
        initFields(key);
    });
    
    initFields('alberti');
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

            <!-- Plaintext -->
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Plaintext</span>
                <div id="plain-label" class="text-text">P1</div>
                <input type="text" id="plain-edit" class="form-control text-edit">
            </div>

            <!-- Key -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Key</span>
                <div id="key-label" class="text-text">K1</div>
                <input type="text" id="key-edit" class="form-control text-edit">
            </div>            

            <!-- Ciphertext -->            
            <div class="input-group text-group">
                <span class="input-group-addon text-label">Ciphertext</span>
                <div id="cipher-label" class="text-text">C1</div>                
                <input type="text" id="cipher-edit" class="form-control text-edit">
            </div>  

            <span id="arrow-back" class="glyphicon glyphicon-arrow-left arrow-large"></span>
            <span id="arrow-forward" class="glyphicon glyphicon-arrow-right arrow-large"></span>
        </div>

        <div class="tab-content">
            <div class="tab-pane active" id="alberti">
                Leon Batista Alberti - first European publication on frequency analysis (15th c.)
                developed the idea of using multiple cipher alphabets (switching every few words)
                developed the cipher disk
            </div>
            <div class="tab-pane" id="trithemius">
                Johannes Trithemius - idea of changing alphabets with each letter (but in order)
                first printed book on cryptology, Steganographia (1518)
            </div>
            <div class="tab-pane" id="belaso">
                Giovan Batista Belaso - using a passphrase for a polyalphabetic cipher
                called Vigenère, but Vigenère's true contribution was much cooler
            </div>
            <div class="tab-pane" id="vigenere">
                Blaise de Vigenère - autokey ciphers, using the text itself as the passphrase (1585)
                forgotten, reinvented in the 19th c. (need to already know the first letter, F in this example)
            </div>
        </div>

    </div>    
    <div class="col-xs-5">
        <button type="button" id="hide_show_tabula_btn" class="btn btn-info btn-xs">Hide/show</button>
        <br /> <br />
        <div id="tabula_recta"></div>    
    </div>
    
</div>    
