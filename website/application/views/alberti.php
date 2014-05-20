<script src="<?php echo JS ?>raphael-min.js"></script>
<link href="<?php echo CSS ?>alberti_style.css" rel="stylesheet">

<script>

holder_id = 'holder';
holder = '#' + holder_id;
startRotation = 0;
currentRotation = 0;
wheelIsRotating = false;
totalRotations = 48;
oneRotAngle = 360.0 / totalRotations;

function setIsRotating(isRotating) {
    wheelIsRotating = isRotating;
    if (isRotating) {
        $('#wheelinfo').html('Click on the disk to stop rotating.');
    } else {
        $('#wheelinfo').html('Click on the outer disk to rotate.');
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
        var imgX = 310,
            imgY = 310;
        var img_path = "<?php echo IMG ?>";
        img = paper.image(img_path + "alberti_outer.png", cx - imgX/2, cy - imgY/2, imgX, imgY);
        img_inner = paper.image(img_path + "alberti_inner.png",cx - imgX/2, cy - imgY/2, imgX, imgY);
    }
}

function clearResults() {
    var el = $('#result').removeClass("result_correct result_incorrect");
    el.slideUp(0);    
}

function showExResult(result) {
    clearResults();
    var el = $('#result').html(result.reason);
    var new_class = (result.status == 'success' ? 'result_correct' : 'result_incorrect');
    el.addClass(new_class);
    el.slideDown(500);
}

currentExercise = -1;
exIds = [ <?php echo $task_ids ?> ];

function fetchNextExercise() {
    var ind = exIds.indexOf(currentExercise);
    currentExercise = exIds[mod(ind + 1, exIds.length)];
    fetchExercise(currentExercise);
}

function fetchPrevExercise() {
    var ind = exIds.indexOf(currentExercise);
    currentExercise = exIds[mod(ind - 1, exIds.length)];
    fetchExercise(currentExercise);
}

function fetchExercise(id) {
    clearResults();
    $("#inputAnswer").val('');
    // Show exercise
    $.ajax({
        url: "<?php echo site_url() ?>/exercise/get/" + id,
        success: 
            function(result) {
                $("#exercise_text").html(result);                
            },
        error:
            function() {
                $('#exercise_block').html("<em>Cannot fetch exercise...</em>");
                $('#exercise_block').data("id", id);
            }
    });

    // Remove previous handlers first
    $("#exercise_form").off('submit');
    // Add new handler
    $("#exercise_form").submit(
        function(ev) {
            $.ajax({ 
                dataType: "json",
                url: "<?php echo site_url() ?>/exercise/check/"+id,
                type: 'POST',
                data: "answer=" + $("#inputAnswer").val(),
                success: 
                    function(result) {
                        showExResult(result);
                    },
                error:
                    function() {
                        $('#exercise_block').html("<em>Checker error...</em>");
                    }
            });
            ev.preventDefault();
        });
        currentExercise = id;
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
    fetchNextExercise();
    
    // Handlers
    $("#arrow-prev").click(fetchPrevExercise);

    $("#arrow-next").click(fetchNextExercise);
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
    <div id="exercise_block" class="col-xs-6" style="background-color: #DBDBDB">
        <em><h3>Check yourself</h3></em>
        <div id="exercise_text"></div>
        <form id="exercise_form" class="form-horizontal" role="form" method="post">
            <div class="form-group">
                <label for="inputAnswer" class="col-sm-2" style="padding-top: 5px">Answer</label>
                <div class="col-sm-5">
                    <input type="text" id="inputAnswer" placeholder="Answer" required />
                </div>
             </div>
            <button id="submit_answer" type="Submit" class="btn btn-default">Check</button> 
            <br /> <br />
            <div id="result"></div>
            <br />

            <span id="arrow-prev" class="glyphicon glyphicon-arrow-left arrow-large"></span>
            <span id="arrow-next" class="glyphicon glyphicon-arrow-right arrow-large"></span>

        </form>
    </div>

    <!--
    <div id="debug"></div>
    -->

</div>