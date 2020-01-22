<?php
session_start();
if (!isset($_SESSION['_logged_']) || $_SESSION['_logged_'] === false) {
    header('Location: login.php');
    die();
}
?>

<DOCTYPE html>
<html lang="en" ng-app="Scripta">
<head>
    <meta charset="utf-8">
    <title>Scr|pta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/shellinabox.css">

    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <link rel="stylesheet" href="css/skins/skin-blue.min.css">

    <!-- <link href="css/bootstrap.no-icons.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet"> -->
    <link href="css/theme.css" rel="stylesheet">
    <link href="css/alertify.css" rel="stylesheet">
    <link href='css/css.css' rel="stylesheet" type='text/css'>
</head>


<body class="hold-transition skin-blue layout-top-nav" ng-controller="CtrlMain">
<header class="main-header scripta-trigger">
    <nav class="navbar navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand"><b>AGPF</b>Miner</a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav">

                    <li menu-active>
                        <a ng-href="#/status">
                        <b>{{title}}</b>
                        </a>
                    </li>
                    <li menu-active><a ng-href="#/miner">Miner</a></li>
                    <li menu-active><a ng-href="#/settings">Settings</a></li>
                    <li menu-active><a ng-href="#/backup">Backup</a></li>
                    <li menu-active><a ng-href="#/terminal">Terminal</a></li>

                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <span class="navbar-text scripta-more">{{counter}}s</span>

                    <button class="btn btn-danger btn-flat navbar-btn ng-cloak" ng-show="downNow"
                        title="Hopefully it's restarting">
                    {{downTime}}s downtime
                    </button>

                    <button class="btn btn-primary btn-flat navbar-btn" ng-click="tick(1,1)"
                        title="Auto refresh in {{counter}}s">
                    <i class="fa fa-refresh " ng-class="{'fa-spin':counter<2}"></i>
                    </button>

                    <button class="btn btn-primary btn-flat navbar-btn" title="Logoout"
                        onclick="location.href='f_logout.php'">
                    Logout
                    </button>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>
<div class="container" ng-class="{down:status.minerDown}">
    <div ng-view>
        <h1 class="text-center">
        Loading angular.js the first time might take some seconds... Hang tight!
        </h1>
    </div>
</div>

<footer>
    <div class="container">
        <hr/>
        <p>
        <span class="pull-right">
        Miner {{status.uptime|duration}} &nbsp;-&nbsp; Pi {{status.pi.uptime|duration}} &nbsp;-&nbsp; Temp
        {{status.pi.temp}}°C &nbsp;-&nbsp; Load {{status.pi.load|number:2}}
        </span>
        <a href='http://www.lateralfactory.com/scripta/'>Scripta</a>, by <a href='http://www.lateralfactory.com'>Lateral
        Factory</a> under GPLv3 License
        </p>
        <!-- LTC Donations welcome : Lcb3cy5nPnh3pQWPCpa55Zg8ShZj5kUHYC -->
    </div>
</footer>
<script src="js/alertify.min.js">
</script>
<script src="js/jquery.min.js">
</script>
<script src="js/highcharts.js">
</script>
<script src="js/bootstrap.min.js">
</script>
<script src="js/angular.min.js">
</script>
<script src="js/app.min.js">
</script>

<script src="ng/app.js">
</script>
<script src="ng/services.js">
</script>
<script src="ng/controllers.js">
</script>
<script src="ng/filters.js">
</script>
<script src="ng/directives.js">
</script>
<script>
    $(document).ready(function() {

            $.ajax( {
                type: "POST",
                url: "update/ctrl.php",
                success: function(returnMessage) {
                    if (returnMessage != 0) {
                        var r = confirm(returnMessage);
                        if (r == true) {
                            $.ajax( {
                                type: "POST",
                                url: "update/start.php",
                                success: function(returnMessage) {
                                    alert(returnMessage);
                                    window.location = "index.php";
                                }
                            });
                        }
                    }
                },
                error: function(returnMessage) {
                    alert("Error");
                    window.location = "index.php";
                }
            });


        });

    function ctrl_host(cmd) {
        var retVal = prompt("Enter password : ", "");
        if (retVal == null) Alert("Password required!");
        else {
            $.get('f_hostHardCtl.php?command=' + cmd + '&pass=' + retVal, function(data) {

                    alert('msg: ' + data);
                });
        }
    }

    function open_terminal() {
	url='http://'+location.host+':4200';
	window.open(url);

    }
</script>
</body>
</html>
