const regexCalculation = /^(\-?[0-9]+) ?(\+|\-|\*) ?(\-?[0-9]+)$/;

// This function clear all the values
function clearScreen() {
    $("#result").val("");
}

// This function display values
function display(value) {
    const result = $("#result");
    result.val(result.val() + value);
}

// This function evaluates the expression and return result
function calculate() {
    const result = $("#result");
    const p = result.value;
    result.value = eval(p);
}

$('#calculationForm').submit(function (e) {
    e.preventDefault();
    const result = $("#result");

    if (!regexCalculation.test(result.val())) {

    }

    $.ajax({
        url: '/',
        type: 'POST',
        data: $('#calculationForm').serialize(),
        success: function (response) {
            result.val(response.data.result);
        },
        error: function (request) {
            console.log(request.responseJSON.error);
        }
    });
});
