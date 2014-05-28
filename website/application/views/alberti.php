<link href="<?php echo CSS ?>alberti_style.css" rel="stylesheet">
<link href="<?php echo CSS ?>common.css" rel="stylesheet">

<script src="<?php echo JS ?>raphael-min.js"></script>
<script src="<?php echo JS ?>exercises.js"></script>

<script>

holder_id = 'holder';
holder = '#' + holder_id;
startRotation = 0;
currentRotation = 0;
wheelIsRotating = false;
totalRotations = 48;
oneRotAngle = 360.0 / totalRotations;
startAngle = 0;

function setIsRotating(isRotating) {
    wheelIsRotating = isRotating;
    if (isRotating) {
        $('#wheelinfo').html('Release to stop rotating.');
    } else {
        $('#wheelinfo').html('Click and drag the disk to rotate.');
    }
}

// Based on http://inventwithpython.com/cipherwheel/
function getAngle(e) {
    var PI = 3.1415926535;
    var wheeloffset = $(holder).offset();
    var wheelwidth = $(holder).width();
    var wheelheight = $(holder).height();
    var originx = wheeloffset.left + (wheelwidth  / 2);
    var originy = wheeloffset.top  + (wheelheight / 2);

    var x = e.pageX - originx;
    var y = e.pageY - originy;

    if (x == 0) {
        if (y <= 0) {
            return 90.0;
        }
        else {
            return 270.0;
        }
    }
    var slope = (y / x);
    var angle = Math.atan( slope ) * (180 / PI);

    if (y >= 0 && x >= 0) {
        angle = (90 - angle) + 270.0;
    }
    if (y >= 0 && x < 0) {
        angle = -angle + 180.0;
    }
    if( y < 0 && x < 0) {
        angle = (90 - angle) + 90.0;
    }
    if (y < 0 && x >= 0) {
        angle = -angle;
    }

    if (angle == 360.0) {
        return 0.0;
    }
    else {
        return angle;
    }
}

function showRotation(n) {
    // Rotate disk
    var current_angle = 360.0 / totalRotations * currentRotation;
    var angle = 360.0 / totalRotations * n;

    img.animate({transform: "r" + angle}, 0);  
    
    // Rotate letters
    var outerRow = "ABCDEFGILMNOPQRSTVXZ1234";
    var innerRow = "gklnprtvz&xysomqihfdbace";
    var rowLength = outerRow.length;
    var outerChars = outerRow.split("");
    var notLinedUp = (Math.abs(n % 2) == 1);
    var i = 0;
    var br_str = '  ';
    var s = br_str;
    
    if (!notLinedUp) {
        s = br_str + s;
    }

    n = mod(n, totalRotations);
    $('#debug').html(n);
    
    while (i < rowLength) {
        var index = mod(rowLength - Math.floor((n+1)/2) + i, rowLength);
        var schar = outerChars[index];
        
        if (index == 0) {
            s += '<b><u>' + schar + '</u></b>' + br_str;
        }        
        else {
            s += schar + br_str;
        }        
        i += 1;
    }
        
    $('#chars_outer').html('<pre>' + s + '</pre>');
    $('#chars_inner').html('<pre>' + br_str + br_str
                           + innerRow.split("").join(br_str) +
                           '</pre>');
}

function clickCipherDisk(e) {
    var angle = getAngle(e);
    
    if (!wheelIsRotating) {
        setIsRotating(false);
        adjustment = parseInt((startAngle - angle) / oneRotAngle);
        startRotation = (adjustment + startRotation) % totalRotations;
        return;
    }
    else {
        startAngle = angle;
        setIsRotating(true);        
    }
}

function rotateCipherDisk(e) {
    if (!wheelIsRotating) {
        return;
    }
    adjustment = parseInt((startAngle - getAngle(e)) / oneRotAngle);
    newRotation = (adjustment + startRotation) % totalRotations;
    showRotation(newRotation);
    currentRotation = newRotation;
}

function prepareAll() {
    $(holder).mousedown(function(e) {
        wheelIsRotating = true;
        clickCipherDisk(e);
    });
    $(window).mouseup(function(e) {
        wheelIsRotating = false;
        clickCipherDisk(e);
    });

    $(holder).on('dragstart', function(event) {event.preventDefault();});
    $(holder).mousemove(rotateCipherDisk);

    Raphael.fn.disks = function (cx, cy, r) {
        var paper = this;
        var imgX = 310,
            imgY = 310;
        var img_path = "<?php echo IMG ?>";
        img = paper.image(img_path + "alberti_outer.png", cx - imgX/2, cy - imgY/2, imgX, imgY);
        img_inner = paper.image(img_path + "alberti_inner.png",cx - imgX/2, cy - imgY/2, imgX, imgY);
    }
}

// Exercise stuff
exIds = [ <?php echo $task_ids ?> ];
get_url_base = "<?php echo site_url() ?>/exercise/get/";
check_url_base = "<?php echo site_url() ?>/exercise/check/";

$(document).ready(function() {
    var width = $("#holder").width(),
        height = width,
        diskx = width / 2,
        disky = height / 2,
        diskrad = 100;
    prepareAll();
    
    // Show disk
    Raphael(holder_id, width, height).disks(diskx, disky, diskrad);
    showRotation(0);       
    setIsRotating(false);
});

</script>
<br />
<div class="row">
    <div id="cipher_block" class="col-xs-6">
        <div id="holder"></div>
        <div id="wheelinfo"></div>
        
        <div id="chars_outer"></div>
        <div id="chars_inner"></div>
    </div>

    <div id="exercise_block" class="col-xs-6">
        <h3>Check yourself</h3>
        <div id="exercise_text"></div>
        <form id="exercise_form" class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label for="inputAnswer" class="col-sm-2" style="padding-top: 2px">
                    Answer
                </label>
                <div class="col-sm-5">
                    <input type="text" id="inputAnswer" placeholder="Answer" required />
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
    </div>

    <!--
    <div id="debug"></div>
    -->

</div>

<!-- Description -->
<div id="desc">

<h2>Description</h2>

<p>
There are several methods of encipherment using the Cipher disk, that were described by Leon Battista Alberti himself in his treatise "De Cifris" in 1467.

<h3> First method </h3>

<p>
The first step of encryption is plaintext preprocessing. As you may have noticed, there are 20 letters and 4 digits on the outer disk, so letters such as U, Y and so on have to be removed or replaced in the plaintext. This design was used to additionally obfuscate the plaintext and eliminate common patterns.
<p>
Lowercase letters on the smaller ring are used as index letters. 

Let's use "m" as an initial index. We choose uppercase letter (say, "T"), and align these two letters, rotating the outer disk.

Then we start the encryption:

<pre>
_VENETIAN      Plaintext
Tblzlmrcz      Ciphertext
</pre>

<p>
After a word or two a different uppercase letter is chosen (say, 
"F"), and the encryption alphabet is changed accordingly: now our index letter "m" corresponds to "F". Then, the enciphering process continues:

<pre>
(CRYPTOGRAPHIES -> CRVPTOGRAPFIES, thanks to preprocessing)

_CRVPTOGRAPFIES     Plaintext
Fyelakbqe&amiog     Ciphertext
</pre>

<p>
Cipher index letters ("T" and "F") are included in the cryptograph. Therefore, the resulting ciphertext is:
<pre>
TblzlmrczFyelakbqe&amiog
</pre>

<h3> Second method </h3>

<p> In this method the encryption of one of the digits (1, 2, 3, 4) indicates the predefined change of index letters. For example, the encryption of digit "2" may mean the change of index letter to the opposite, consecutive digits "14" may denote moving the disk by 5 letters clockwise, and so on. Therefore, it is possible to create the codebook with several digit codes to indicate the specific change of encrypting alphabet.

</div>