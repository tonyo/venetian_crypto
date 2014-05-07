<script src="<?php echo JS ?>raphael-min.js"></script>
<link href="<?php echo CSS ?>alberti_style.css" rel="stylesheet">

<script>

holder_id = 'holder';
holder = '#' + holder_id;
startRotation = 0;
currentRotation = 0;
wheelIsRotating = false;
totalRotations = 52;
oneRotAngle = 360.0 / totalRotations;


function setIsRotating(isRotating) {
    wheelIsRotating = isRotating;
    if (isRotating) {
        $('#wheelinfo').html('Click on cipher disk to stop rotating.');
    } else {
        $('#wheelinfo').html('Click on cipher disk to rotate.');    
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

function mod(a, b) {
    return (a % b + b) % b;
}

function showRotation(n) {
    // Rotate disk
    var current_angle = 360.0 / totalRotations * currentRotation;
    var angle = 360.0 / totalRotations * n;
    $('#debug').html(n);
    img.animate({transform: "r" + angle}, 0);  
    
    // Rotate letters
    var outerRow = "ABCDEFGILMNOPQRSTVXZ1234++";
    var innerRow = "gklnprtvz&xysomqihfdbace==";
    var rowLength = outerRow.length;
    var outerChars = outerRow.split("");
    var notLinedUp = (Math.abs(n % 2) == 1);
    var i = 0;
    var s = '';
    var br_str = ' ';
    
    if (!notLinedUp) {
        s = br_str + s;
    }

    n = mod(n, totalRotations);
    
    while (i < rowLength) {
        var index = mod(Math.floor(-n/2) + i, rowLength);
        var schar = outerChars[index];
        
        if (index == 0) {
            s += br_str + '<b><u>' + schar + '</u></b>' + br_str;
        }        
        else {
            s += br_str + schar + br_str;
        }        
        i += 1;
    }
    
    $('#chars_outer').html('<pre>' + s + '</pre>');
    var dbr_str = br_str + br_str;
    $('#chars_inner').html('<pre>' + dbr_str + innerRow.split("").join(dbr_str) + '</pre>');
}

function clickCipherDisk(e) {
    var angle = getAngle(e);
    
    if (wheelIsRotating) {
        wheelIsRotating = false;
        setIsRotating(false);
        adjustment = parseInt((startAngle - angle) / oneRotAngle);
        startRotation = (adjustment + startRotation) % totalRotations;
        return;
    }
    else {
        startAngle = angle;
        wheelIsRotating = true;
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
    $(holder).click(clickCipherDisk);
    $(holder).mousemove(rotateCipherDisk);

    Raphael.fn.disks = function (cx, cy, r) {
        var paper = this;
        var imgX = 300,
            imgY = 300;
        var img_path = "<?php echo IMG ?>";
        img_inner = paper.image(img_path + "alberti_inner.png",cx - imgX/2, cy - imgY/2, imgX, imgY);
        img = paper.image(img_path + "alberti_outer.png", cx - imgX/2, cy - imgY/2, imgX, imgY);
    }
}

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
<div id="cipher_block">
    <div id="holder"></div>
    <div id="wheelinfo"></div>
    <div id="debug"></div>
    
    <div id="chars_outer"></div>
    <div id="chars_inner"></div>
</div>
