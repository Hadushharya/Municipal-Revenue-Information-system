<?php 
include('setting/header.php'); 
include('../db/connection.php');

if(!isset($_SESSION['SESS_ID'])) {
    die("Session error: User not logged in");
}

$user_id = $_SESSION['SESS_ID'];
?>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="../assets/css/colorbox.min.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="../assets/css/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="../assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="../assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />

    <!-- ace settings handler -->
    <script src="../assets/js/ace-extra.min.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 support -->
    <!--[if lte IE 8]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="no-skin">
    <?php include('setting/headernav1.php'); ?>

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try{ace.settings.loadState('main-container')}catch(e){}
        </script>

        <div id="sidebar" class="sidebar responsive ace-save-state">
            <script type="text/javascript">
                try{ace.settings.loadState('sidebar')}catch(e){}
            </script>
            <?php include('setting/menu.php'); ?>
            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" 
                   data-icon1="ace-icon fa fa-angle-double-left" 
                   data-icon2="ace-icon fa fa-angle-double-right"></i>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content" style="padding-top:30px;">
            <!-- Dashboard Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header widget-header-flat widget-header-small">
                            <h5 class="widget-title">
                                <i class="ace-icon fa fa-users"></i>
                                Customer Dashboard
                            </h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <!-- Waiting for Decision -->
                                    <div class="col-sm-4">
                                        <div class="infobox infobox-orange">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-hourglass-half"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number">
                                                    <?php 
                                                    $waiting_combined = "SELECT (
                                                        (SELECT COUNT(*) 
                                                         FROM clientslanddatataxdecides 
                                                         WHERE forpayment_status='notapproved' 
                                                         AND forcashierstatus='notapproved')
                                                    ) AS total_waiting";
                                                    $result = mysqli_query($conn, $waiting_combined);
                                                    echo mysqli_fetch_assoc($result)['total_waiting'];
                                                    ?>
                                                </span>
                                                <div class="infobox-content">Waiting for Sending</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Daily Paid Customers -->
                                    <div class="col-sm-4">
                                        <div class="infobox infobox-green">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-money"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number">
                                                    <?php
                                                    require_once('ethiopian_date_converter.php');
                                                    $today_ethiopian_string = gregorian_to_ethiopian_string(date('Y'), date('m'), date('d'));
                                                    $income_query = "SELECT COUNT(*) as daily_income 
                                                                     FROM `clientslanddatataxdecides` 
                                                                     WHERE paidtime = '$today_ethiopian_string'
                                                                     AND forpayment_status = 'approved'";
                                                    $income_result = mysqli_query($conn, $income_query);
                                                    echo number_format(mysqli_fetch_assoc($income_result)['daily_income'] ?? 0, 2);
                                                    ?>
                                                </span>
                                                <div class="infobox-content">Paid Today</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Unpaid Customers -->
                                    <div class="col-sm-4">
                                        <div class="infobox infobox-red">
                                            <div class="infobox-icon">
                                                <i class="ace-icon fa fa-exclamation-triangle"></i>
                                            </div>
                                            <div class="infobox-data">
                                                <span class="infobox-data-number">
                                                    <?php 
                                                    $unpaid_query = "SELECT COUNT(*) as count FROM clientslanddatataxdecides 
                                                                     WHERE forcashierstatus='notapproved'";
                                                    $unpaid_result = mysqli_query($conn, $unpaid_query);
                                                    echo mysqli_fetch_assoc($unpaid_result)['count'];
                                                    ?>
                                                </span>
                                                <div class="infobox-content">Unpaid Customers</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Dashboard -->

            <!-- Page content -->
            <div class="page-content">
                <?php include('setting/settingpage.php'); ?>
            </div>
        </div>
        <!-- End of Main Content -->

        <?php include('../footerboot.php'); ?>
    </div><!-- /.main-container -->

    <!-- Scripts -->
    <script src="../assets/js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery.colorbox.min.js"></script>
    <script src="../assets/js/ace-elements.min.js"></script>
    <script src="../assets/js/ace.min.js"></script>

    <script type="text/javascript">
    jQuery(function($) {
        var $overflow = '';
        var colorbox_params = {
            rel: 'colorbox',
            reposition:true,
            scalePhotos:true,
            scrolling:false,
            previous:'<i class="ace-icon fa fa-arrow-left"></i>',
            next:'<i class="ace-icon fa fa-arrow-right"></i>',
            close:'&times;',
            current:'{current} of {total}',
            maxWidth:'100%',
            maxHeight:'100%',
            onOpen:function(){ $overflow = document.body.style.overflow; document.body.style.overflow = 'hidden'; },
            onClosed:function(){ document.body.style.overflow = $overflow; },
            onComplete:function(){ $.colorbox.resize(); }
        };
        $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
        $("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");
        $(document).one('ajaxloadstart.page', function(e){ $('#colorbox, #cboxOverlay').remove(); });
    })
    </script>

    <script>
    function show2(){
        if (!document.all&&!document.getElementById) return;
        var thelement=document.getElementById ? document.getElementById("tick2") : document.all.tick2;
        var Digital=new Date();
        var hours=Digital.getHours();
        var minutes=Digital.getMinutes();
        var seconds=Digital.getSeconds();
        var dn="PM";
        if (hours<12) dn="AM";
        if (hours>12) hours=hours-12;
        if (hours==0) hours=12;
        if (minutes<=9) minutes="0"+minutes;
        if (seconds<=9) seconds="0"+seconds;
        var ctime=hours+":"+minutes+":"+seconds+" "+dn;
        thelement.innerHTML=ctime;
        setTimeout("show2()",1000);
    }
    window.onload=show2;
    </script>
</body>
</html>
