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
    const errorBox = $('#errorBox');
    const currentCalculation = result.val();

    if (!regexCalculation.test(result.val())) {
        errorBox.html('The calculation format is invalid.');
        errorBox.attr('hidden', false);
        addHistory(currentCalculation, false);
        return;
    }

    $.ajax({
        url: '/',
        type: 'POST',
        data: $('#calculationForm').serialize(),
        success: function (response) {
            result.val(response.data.result);
            errorBox.attr('hidden', true);
            addHistory(currentCalculation, response.data.result);
        },
        error: function (request) {
            errorBox.innerText(request.responseJSON.error);
            errorBox.attr('hidden', false);
            addHistory(currentCalculation, false);
        }
    });
});

function keydown(e) {
    if (!isNaN(e.key) || e.key === '+' || e.key === '-' || e.key === '*') {
        display(e.key.toString());
    } else if (e.key === 'Backspace') {
        const indexCursor = e.target.selectionStart ?? $("#result").val().length;
        pressBackspace(indexCursor);
        e.target.selectionStart = indexCursor - 1;
        e.target.selectionEnd = indexCursor - 1;
    }
    if (e.key === 'Enter') {
        $('#calculationForm').trigger('submit');
    }
}

function pressBackspace(indexCursor) {
    const result = $("#result");
    const currentCalculation = result.val();
    const newCalculation = currentCalculation.slice(0, indexCursor - 1) + currentCalculation.slice(indexCursor, currentCalculation.length);
    result.val(newCalculation);
}

function addHistory(calculation, result) {
    result = result ? ' = ' + result : ' = (Invalid format calculation)';
    const str = `<div class="hoverable" onclick="selectHistoryItem(this)" style="margin-bottom: 5px; padding: 2px">${calculation}${result}</div>`;
    $('#history').append(str);
}

function selectHistoryItem(historyItem) {
    const calculation = historyItem.innerText.slice(0, historyItem.innerText.lastIndexOf('='));
    $("#result").val(calculation);
}
