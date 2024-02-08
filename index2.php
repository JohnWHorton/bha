<!DOCTYPE html>
<html>

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BT57DJ7228"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-BT57DJ7228');
    </script>
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

    <div class="row">
        <div id="spinner" class="spinner-border text-danger" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="col-sm-12" style="min-height: 60px; max-height: 60px;">
            <div class="msg" style="text-align: center; display: none;">
            </div>
        </div>
    </div>
    <div id="comingbox" class="container comingcontainer"
        style="display: none; border-radius: 0%; margin-top: 2rem; opacity: 0.7;">

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
    <div id="comparebox" class="container comparecontainer"
        style="display: none; border-radius: 0%; margin-top: 2rem; opacity: 0.7;">

        <div class="container" style="overflow-x:auto;">
            <div><span class="close" aria-hidden="true" style="float: left!important;"
                    onclick="$('#comingbox').show(); $('#comparebox').hide();">&times;</span>
            </div>
            <table class="table table-striped transtable">
                <caption class="caption">Head to Head results</caption>
                <thead class="sticky-top">
                    <tr style="background-color: black !important;">
                        <th>Name</th>
                        <th>Age</th>
                        <th>weight</th>
                        <th>Year</th>
                        <th>Finnish</th>
                        <th>Jockey</th>
                        <th>Betting</th>
                    </tr>
                </thead>
                <tbody id="comparebody">

                </tbody>
            </table>
        </div>
    </div>

    <div id="contactbox" class="container contactcontainer" style="display: none; border-radius: 0%; margin-top: 1rem;">
        <div id="contact-form" style="text-align: left;">
            <div><span class="close" aria-hidden="true" style="float: left!important;"
                    onclick="$('#comingbox').show(); $('#contactbox').hide();">&times;</span>
            </div>
            <div class="controls">
                <div class="row">
                    <div class="msg2">.</div>
                    <div class="col-sm-12">
                        <h4>Contact us</h4>
                        <p>If you would like information, have a complaint or suggestion, please let us know.</p>
                        <div class="form-group">
                            <label for="emailaddr">Your email address *</label>
                            <input id="emailaddr" type="email" name="emailaddr" class="form-control"
                                required="required">

                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input id="subject" type="text" name="subject" class="form-control" required="required">

                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-control" required="required"
                                rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-send" onclick="sendMessage();">Send message</button>
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
    <div id="aboutusbox" class="container aboutcontainer" style="display: none; border-radius: 0%; margin-top: 2rem;">
        <div class="row">
            <div><span class="close" aria-hidden="true" style="float: left!important; margin: 15px;"
                    onclick="$('#comingbox').show(); $('#aboutusbox').hide();">&times;</span>
            </div>
            <div id="about"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small blue pt-4">

        <!-- Footer Links -->
        <div class="container-fluid text-center">

            <!-- Grid row -->
            <div class="row" style="margin-left: 20%; margin-right: 20%;">
                <!-- Grid column -->
                <div class="col mt-3"><a href="#!" onclick="hideAllBoxes(); getAboutUs();">About</a>
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