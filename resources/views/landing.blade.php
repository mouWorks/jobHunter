<!DOCTYPE html>
<html>
<head>
    <title>FreeRider</title>

    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title"><a href="/awshack"> jobHuntr.work</a></div>
    </div>
    <h1/>by AWS FreeRider | <?php echo env('TEST_CODENAME'); ?> </h1>
    <h3>Build: <?php echo env('BUILD_NUM');?> </h3>
</div>
</body>
</html>
