currentExercise = -1;

function mod(a, b) {
    return (a % b + b) % b;
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
        url: get_url_base + id,
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
                url: check_url_base + id,
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
    $("#arrow-prev").click(fetchPrevExercise);
    $("#arrow-next").click(fetchNextExercise);
    fetchNextExercise();
});
