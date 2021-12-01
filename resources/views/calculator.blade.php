<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title> Simple Calculator using HTML, CSS and JavaScript </title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script type="text/javascript" src="assets/js/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="calculationForm" method="POST" action="/">
    <table class="calculator">
        @csrf()
        <div class="error" id="errorBox" hidden>Error message</div>
        <tr>
            <td colspan="3">
                <input class="display-box" type="text" id="result" name="calculation"
                    {{--                       pattern="^(\-?[0-9]+) ?(\+|\-|\*) ?(\-?[0-9]+)$"--}}
                />
            </td>
            <!-- clearScreen() function clear all the values -->
            <td><input class="button" type="button" value="C"
                       onclick="clearScreen()" style="background-color: #fb0066;"
                />
            </td>
        </tr>
        <tr>
            <!-- display() function display the value of clicked button -->
            <td><input class="button" type="button" value="1" onclick="display('1')"/></td>
            <td><input class="button" type="button" value="2" onclick="display('2')"/></td>
            <td><input class="button" type="button" value="3" onclick="display('3')"/></td>
            <td><input class="button" type="button" value="+" onclick="display('+')"/></td>
        </tr>
        <tr>
            <td><input class="button" type="button" value="4" onclick="display('4')"/></td>
            <td><input class="button" type="button" value="5" onclick="display('5')"/></td>
            <td><input class="button" type="button" value="6" onclick="display('6')"/></td>
            <td><input class="button" type="button" value="-" onclick="display('-')"/></td>
        </tr>
        <tr>
            <td><input class="button" type="button" value="7" onclick="display('7')"/></td>
            <td><input class="button" type="button" value="8" onclick="display('8')"/></td>
            <td><input class="button" type="button" value="9" onclick="display('9')"/></td>
            <td><input class="button" type="button" value="*" onclick="display('*')"/></td>
        </tr>
        <tr>
            <td colspan="2"><input class="button" type="button" value="0" onclick="display('0')"/></td>
            <td colspan="2"><input class="button" type="submit" value="=" onclick="calculate()"
                                   style="background-color: #fb0066;"/>
            </td>
        </tr>
    </table>
</form>

<div id="history" class="container custom-scrollbar" >

</div>

<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>
