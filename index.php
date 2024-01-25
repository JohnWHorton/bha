<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BHA</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css">
    <!-- should always be last-->
    <link rel="stylesheet" href="./css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./css/bha.css" type="text/css">
    <!------ Include the above in your HEAD tag ---------->
</head>

<body>
    <div id="content" class="allcontent">
        <div id="spinner" class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div id="comingbox" class="container comingcontainer modal modal-content" style="display: none; border-radius: 0%; margin-top: 5rem; opacity: 0.7;">

        <div class="container">
<p>Click on any Race to view previous head to head results of runners in this race</p>
            <table class="table table-striped transtable">
                <thead class="sticky-top">
                    <tr style="background-color: black !important;">
                        <th>Course</th>
                        <th class="maxwidth">Race</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th><span class="close" aria-hidden="true" onclick="hideAllBoxes();">&times;</span></th>
                    </tr>
                </thead>
                <tbody id="comingbody">
                </tbody>
            </table>
        </div>
    </div>
    <div id="comparebox" class="container comparecontainer modal modal-content" style="display: none; border-radius: 0%; margin-top: 5rem; opacity: 0.7;">

        <div class="container">
            <p id="headtext">Click on any Race to view previous head to head results of runners in this race</p>
            <table class="table table-striped transtable">
                <thead class="sticky-top">
                    <tr style="background-color: black !important;">
                        <th>Name</th>
                        <th>Age</th>
                        <th>weight</th>
                        <!-- <th>Race Id</th> -->
                        <th>Year</th>
                        <th>Finnish</th>
                        <th>Jockey</th>
                        <th>Betting</th>
                        <th><span class="close" aria-hidden="true" onclick="$('#comingbox').show(); $('#comparebox').hide();">&times;</span></th>
                    </tr>
                </thead>
                <tbody id="comparebody">

                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript" src="./js/smtp.js"></script>
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript" src="./js/popper.min.js"></script>
    <script type="text/javascript" src="./js/bha.js"></script>
    <script type="text/javascript" src="./js/moment.js"></script>
    <script type="text/javascript" src="./js/bootstrapv4.3.1.min.js"></script>
</body>