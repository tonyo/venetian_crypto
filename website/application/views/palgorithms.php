<link href="<?php echo CSS ?>palgo_style.css" rel="stylesheet">
<link href="<?php echo CSS ?>common.css" rel="stylesheet">

<script src="<?php echo JS ?>exercises.js"></script>
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
        resetEncryption();
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

currentIndex = null;
function resetEncryption() {
    currentIndex = -1;
    $('#cipher-label').text('');
    var $keyLab = $('#key-label');
    $keyLab.html($keyLab.text());
    var $ptLab = $('#plain-label');
    $ptLab.html($ptLab.text());
}

function initFields(ciphername) {
    var plaintext =  'The East and Midrealm are at war';
    $('#plain-label').text(plaintext);
    var key = '';
    
    switch(ciphername) {
        case 'alberti':
            key = 'BBB BBBB CCC CCCCCCCC DDD DD EEE';
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

function showLetterEncryption() {
    if (currentIndex == -1) return;
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
    return newLet;
}

function splitLabel(label) {
    var $lab = $(label);
    var text = $lab.text();
    var encPart = text.substr(0, currentIndex);
    var curLet = text[currentIndex];
    var nonEncPart = text.substr(currentIndex+1);
    $lab.html('<span class="enc">'+encPart+'</span>'
                    + '<span class="cur">'+curLet+'</span>'
                    + '<span class="nonenc">'+nonEncPart+'</span>');
}

function showProgress() {
    splitLabel('#plain-label');
    splitLabel('#key-label');
}

// Exercise stuff
exIds = [ <?php echo $task_ids ?> ];
get_url_base = "<?php echo site_url() ?>/exercise/get/";
check_url_base = "<?php echo site_url() ?>/exercise/check/";


$(document).ready(function() {
    drawTable();
    
    // Click handlers
    $("#hide_show_tabula_btn").click(function() {
        $('#tabula_outer').slideToggle(500);
    });

    $("#arrow-back").click(function() {
        if (currentIndex == -1) return;
        currentIndex -= 1;
        showLetterEncryption();
        var curCt = $('#cipher-label').text();
        curCt = curCt.substr(0, currentIndex + 1);
        $('#cipher-label').text(curCt);
        showProgress();
    });

    $("#arrow-forward").click(function() {
        currentIndex += 1;
        var curCt = $('#cipher-label').text();
        var newLet = showLetterEncryption();
        $('#cipher-label').text(curCt + newLet);
        showProgress();
    });
    
    $('#tabula_recta div div').click(function(e) {
        var ar = getCoord($(e.target));
        if (ar[0] != 0) {
            highlightRow(ar[0]);
        }
        if (ar[1] != 0) {
            highlightColumn(ar[1]);
        }
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
    <div id="ciphertext" class="col-md-7">
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


        <h3>Description</h3>
        <div class="tab-content">
            <div class="tab-pane active" id="alberti">
                <p>
                Leon Batista Alberti came up with the idea of using multiple cipher alpahbets. These require switching the key every few words (in our example, every two words). He owned the first european publication on frequency analysis in the 15th century and he later developed the cipher disk.
                </p>
            </div>
            <div class="tab-pane" id="trithemius">
                <p>
                Johannes Trithemius introduced the idea of changing alphabets with each letter of plaintext. This means that the key does not repeat for two consecutive letters. The key can start from a previously given letter and then iterates through the rest of the alphabet.
                </p>
            </div>
            <div class="tab-pane" id="belaso">
                <p>
                Giovan Batista Belaso originally described the method in his 1553 book "La cifra", but the scheme was later misattributed to Blaise de Vigenère in the 19th century, and is now widely known as the "Vigenère cipher".
                </p>
                <p>
                A passphrase is used ("PENNSIC WAR" in the example) to generate several alphabets with different shifts.
                The cipher was considered unbreakable for a long time, and was broken only in the middle of 19 century.
                </p>

            </div>
            <div class="tab-pane" id="vigenere">
                <p>
                Blaise de Vigenère introduced the idea of "autokey ciphers" in 1585, which use the text itself as the passphrase. This technique has been forgotten until the 19th century, when it has been reinvented. In order  to use it, the first letter of the key has to be given (in our example, the letter "F").
                </p>
            </div>
        </div>
        <br />

        <!-- Exercises -->
        <div id="exercise_block" class="col-xs-10">
            <h3>Test yourself</h3>
            <div id="exercise_text"></div>
            <form id="exercise_form" class="form-horizontal" role="form" method="post">
                <div class="form-group">
                    <label for="inputAnswer" class="col-sm-2" style="padding-top: 2px">
                        Answer
                    </label>
                    <div class="col-sm-12">
                        <input type="text" id="inputAnswer" style="width: 90%;" placeholder="Answer" required />
                    </div>
                </div>
                <button id="submit_answer" type="Submit" class="btn btn-default">Check</button> 
                <span id="arrow-group">
                    <span id="arrow-prev" class="glyphicon glyphicon-circle-arrow-left"></span>
                    <span id="arrow-next" class="glyphicon glyphicon-circle-arrow-right"></span>
                </span>
                <br /> <br />
                <div id="result"></div>
            </form>
        </div> <!-- exercises -->

    </div>
    
    <!-- Tabula recta -->
    <div class="col-md-5">
        <button type="button" id="hide_show_tabula_btn" class="btn btn-info btn-xs">Hide/show</button>
        <br /> <br />
        
        <div class="container" id="tabula_outer">
            <div class="row">
                <div class="col-md-1" id="row-header">
                    P<br>L<br>A<br>I<br>N<br>T<br>E<br>X<br>T
                </div>
                <div class="col-md-11" id="tabula_header">
                    <div id="col-header">KEY</div>
                    <div id="tabula_recta"></div>
                </div>
            </div>
        </div>
    </div>
    
</div>    
