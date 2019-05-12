<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial;
            font-size: 14px;
        }
        table {
            border-left: solid 1px #1a1a1a;
            border-top: solid 1px #1a1a1a;
            border-collapse: collapse;
        }
        td {
            border-right: solid 1px #1a1a1a;
            border-bottom: solid 1px #1a1a1a;
            padding: 5px;

        }
        h1 {
            font-size: 20px;
            margin-bottom: 0px;
        }
        .dates {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php echo $this->fetch('content'); ?>

</body>
</html>
