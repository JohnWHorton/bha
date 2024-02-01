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
    <!------ Include the above in your HEAD tag blah ---------->
</head>

<body>
    <div id="content" class="allcontent">
        <div id="spinner" class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div id="comingbox" class="container comingcontainer modal modal-content" style="display: none; border-radius: 0%; margin-top: 5rem; opacity: 0.7;">

        <div class="container" style="overflow-x:auto;">
            <p id="headtext">Click on any Race to view previous head to head results of runners in this race</p>
            <table class="table table-striped transtable">
                <caption class="caption">Upcoming Races</caption>
                <thead class="sticky-top">
                    <tr style="background-color: black !important;">
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th class="maxwidth">Race</th>
                        <!-- <th><span class="close" aria-hidden="true" onclick="hideAllBoxes();">&times;</span></th> -->
                    </tr>
                </thead>
                <tbody id="comingbody">
                </tbody>
            </table>
        </div>
    </div>
    <div id="comparebox" class="container comparecontainer modal modal-content" style="display: none; border-radius: 0%; margin-top: 5rem; opacity: 0.7;">

        <div class="container" style="overflow-x:auto;">
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

    <div id="contactbox" class="container contactcontainer modal modal-content" style="display: none; border-radius: 0%; margin-top: 5rem;">
        <div class="close">
            <span aria-hidden="true" onclick="hideAllBoxes();$('#comingbox').show();">&times;</span>
        </div>
        <div id="contact-form">
            <div class="controls">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Contact us</h4>
                        <p>If you would like information, have a complaint or suggestion, please let us know.</p>

                        <div class="form-group">
                            <label for="form_name">Your email address *</label>
                            <input id="form_name" type="text" name="emailaddr" class="form-control" required="required">

                        </div>
                        <div class="form-group">
                            <label for="form_name">Subject *</label>
                            <input id="form_name" type="text" name="subject" class="form-control" required="required">

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="form_message">Message *</label>
                            <textarea id="form_message" name="message" class="form-control" required="required" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-send" onclick="sendEmail();">Send message</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-muted" style="margin-top: 20px;"><strong>*</strong> These fields are
                            required.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small blue pt-4">

        <!-- Footer Links -->
        <div class="container-fluid text-center">

            <!-- Grid row -->
            <div class="row" style="margin-left: 20%; margin-right: 20%;">
                <!-- Grid column -->
                <div class="col mt-3"><a href="#!" onclick="hideAllBoxes(); about();">About</a>
                </div>
                <div class="col mt-3"><a href="#" onclick="hideAllBoxes(); $('#contactbox').show();">Contact Us</a>
                </div>
                <!-- Links -->
            </div>
            <!-- Grid row -->
        </div>
        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2024 Copyright:
            <a href="/"> h2hRacing.co.uk</a>
        </div>
    </footer>
    <!-- Footer -->
    <script type="text/javascript" src="./js/smtp.js"></script>
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript" src="./js/popper.min.js"></script>
    <script type="text/javascript" src="./js/bha.js"></script>
    <script type="text/javascript" src="./js/moment.js"></script>
    <script type="text/javascript" src="./js/bootstrapv4.3.1.min.js"></script>
</body>