<?php
require_once('setting/header.php');
require_once('../db/connection.php');

if (session_status() === PHP_SESSION_NONE) session_start();
$username = htmlspecialchars($_SESSION['SESS_USER_NAME'], ENT_QUOTES, 'UTF-8');
$useridc = (int)$_SESSION['SESS_ID'];
include("../logactivity.php");

// =======================
// Helper functions
// =======================
function clean_input($data){
    return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
}

// =======================
// Homeowners Filter
// =======================
$homeowner_id = isset($_GET['homeowner_id']) ? clean_input($_GET['homeowner_id']) : '';
$homeowner_name = isset($_GET['homeowner_name']) ? clean_input($_GET['homeowner_name']) : '';
$homeowner_records = [];
$homeowner_message = '';
$homeowner_per_page = 5;
$homeowner_page = isset($_GET['homeowner_page']) ? max(1,(int)$_GET['homeowner_page']) : 1;
$homeowner_offset = ($homeowner_page-1)*$homeowner_per_page;

if ($homeowner_id !== '' || $homeowner_name !== '') {
    $homeowner_id_trim = str_replace(' ','',$homeowner_id);
    $homeowner_name_trim = str_replace(' ','',$homeowner_name);

    $where = "WHERE 1=1";
    $params = [];
    $types = "";

    if ($homeowner_id !== '') { $where .= " AND REPLACE(homeowner_id,' ','')=?"; $params[]=$homeowner_id_trim; $types.="s"; }
    if ($homeowner_name !== '') { $where .= " AND REPLACE(name,' ','')=?"; $params[]=$homeowner_name_trim; $types.="s"; }

    $count_q = "SELECT COUNT(*) as total FROM (SELECT homeowner_id FROM homeowners $where GROUP BY homeowner_id) t";
    $stmt = $conn->prepare($count_q);
    if ($stmt) {
        if(!empty($params)) $stmt->bind_param($types,...$params);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();
    } else { die("Prepare failed: ".$conn->error); }
    $homeowner_total_pages = ceil($total/$homeowner_per_page);

    $query = "
        SELECT h.id,h.homeowner_id,h.name,h.phone,h.rooms,h.room_cost
        FROM homeowners h
        INNER JOIN (
            SELECT homeowner_id, MAX(id) as max_id
            FROM homeowners $where
            GROUP BY homeowner_id
        ) t ON h.homeowner_id=t.homeowner_id AND h.id=t.max_id
        ORDER BY h.id DESC
        LIMIT ? OFFSET ?
    ";
    $stmt = $conn->prepare($query);
    if($stmt){
        $bind_types = $types."ii";
        $bind_params = array_merge($params, [$homeowner_per_page, $homeowner_offset]);
        $refs=[]; foreach($bind_params as $k=>$v) $refs[$k]=&$bind_params[$k];
        array_unshift($refs,$bind_types);
        call_user_func_array([$stmt,'bind_param'],$refs);
        $stmt->execute();
        $res=$stmt->get_result();
        if($res && $res->num_rows>0){
            while($r=$res->fetch_assoc()){
                foreach($r as $key=>$val) $r[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                $homeowner_records[]=$r;
            }
        } else { $homeowner_message="No homeowner record found."; }
        $stmt->close();
    } else { die("Prepare failed: ".$conn->error); }
}

// =======================
// Clientslanddata Filter
// =======================
$filenumber = isset($_GET['filenumber']) ? clean_input($_GET['filenumber']) : '';
$fullname = isset($_GET['fullname']) ? clean_input($_GET['fullname']) : '';
$clients_records = [];
$clients_message = '';
$clients_per_page = 5;
$clients_page = isset($_GET['clients_page']) ? max(1,(int)$_GET['clients_page']) : 1;
$clients_offset = ($clients_page-1)*$clients_per_page;
$forpayment_status='approved';

if ($filenumber!=='' || $fullname!=='') {
    $filenumber_trim=str_replace(' ','',$filenumber);
    $fullname_trim=str_replace(' ','',$fullname);

    $where="WHERE forpayment_status=?";
    $params=[$forpayment_status];
    $types="s";

    if($filenumber!=='' && $fullname!==''){
        $where.=" AND REPLACE(filenumber,' ','')=? AND REPLACE(fullname,' ','')=?";
        $params[]=$filenumber_trim; $params[]=$fullname_trim; $types.="ss";
    } elseif($filenumber!==''){
        $where.=" AND REPLACE(filenumber,' ','')=?"; $params[]=$filenumber_trim; $types.="s";
    } else{
        $where.=" AND REPLACE(fullname,' ','')=?"; $params[]=$fullname_trim; $types.="s";
    }

    $count_q="SELECT COUNT(*) as total FROM clientslanddata $where";
    $stmt=$conn->prepare($count_q);
    if($stmt){
        $stmt->bind_param($types,...$params);
        $stmt->execute();
        $clients_total=$stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();
    } else{ die("Prepare failed: ".$conn->error); }
    $clients_total_pages=ceil($clients_total/$clients_per_page);

    $query="SELECT id,userid,fullname,phone,kebele,block,filenumber,levelofplace,occupiedyear,mainservice,
            kindofvolumeofland,area,beginofconstruction,endofconstruction,beginpayment,endpayment,amountofpay
            FROM clientslanddata $where ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt=$conn->prepare($query);
    if($stmt){
        $bind_types=$types."ii";
        $bind_params=array_merge($params,[$clients_per_page,$clients_offset]);
        $refs=[]; foreach($bind_params as $k=>$v) $refs[$k]=&$bind_params[$k];
        array_unshift($refs,$bind_types);
        call_user_func_array([$stmt,'bind_param'],$refs);
        $stmt->execute();
        $res=$stmt->get_result();
        if($res && $res->num_rows>0){ 
            while($r=$res->fetch_assoc()){
                foreach($r as $key=>$val) $r[$key] = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                $clients_records[]=$r; 
            }
        }
        else{ $clients_message="No land record found."; }
        $stmt->close();
    } else{ die("Prepare failed: ".$conn->error); }
}
?>

<head>
    <title>Homeowners & Land Records</title>
    <style>
        body{font-family:Arial,sans-serif;padding:20px;background:#f4f7f8;}
        .section-box{width:90%;margin:auto;margin-bottom:50px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 3px 6px rgba(0,0,0,0.1);}
        h2{text-align:center;color:#333;}
        fieldset{border:2px solid #007bff;border-radius:8px;padding:15px;margin-top:15px;}
        legend{font-weight:bold;color:#007bff;padding:0 10px;}
        form{margin-bottom:20px;text-align:center;}
        input{padding:5px 8px;border:1px solid #ccc;border-radius:4px;margin-right:10px;}
        button{padding:5px 15px;border:none;background:#007bff;color:#fff;border-radius:5px;cursor:pointer;}
        button:hover{background:#0056b3;}
        table{width:100%;border-collapse:collapse;margin-top:15px;}
        th,td{padding:10px;border:1px solid #ddd;text-align:left;}
        th{background:#007bff;color:#fff;}
        tr:nth-child(even){background:#f2f2f2;}
        tr:hover{background:#d1e7ff;}
        .message{color:red;text-align:center;font-weight:bold;margin-top:10px;}
        .pagination{text-align:center;margin-top:15px;}
        .pagination a{margin:0 5px;text-decoration:none;color:#007bff;padding:5px 10px;border:1px solid #ccc;border-radius:5px;}
        .pagination a.active{background:#007bff;color:#fff;font-weight:bold;border-color:#0056b3;}
    </style>
</head>
<body class="no-skin">
<?php require_once('setting/headernav1.php'); ?>
<div class="main-container ace-save-state" id="main-container">
    <div id="sidebar" class="sidebar responsive ace-save-state">
        <?php require_once('setting/menu.php'); ?>
    </div>
    <div class="main-content">
        <div class="page-content">

            <!-- Homeowners Section -->
            <div class="section-box">
                <fieldset>
                    <legend>ዝተመዝገቡ መካረይቲ ገዛ</legend>
                    <form method="GET">
                        <input type="text" name="homeowner_id" placeholder="መለለይ ቁፅሪ" value="<?= $homeowner_id ?>" />
                        <input type="text" name="homeowner_name" placeholder="ሙሉእ ሽም" value="<?= $homeowner_name ?>" />
                        <button type="submit">ድለ</button>
                    </form>
                    <?php if($homeowner_message):?><div class="message"><?= $homeowner_message ?></div><?php endif;?>
                    <?php if(!empty($homeowner_records)):?>
                    <table>
                        <thead>
                            <tr><th>#</th><th>መለለይ ቁፅሪ</th><th>ሙሉእ ሽም</th><th>ስልኪ ቁፅሪ</th><th>በዝሒ ዝካረ ገዛ</th><th>ናይ ሓደ ገዛ ክራይ ክፍሊት</th><th>ተግባር</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($homeowner_records as $i=>$r):?>
                            <tr>
                                <td><?= $homeowner_offset+$i+1 ?></td>
                                <td><?= $r['homeowner_id'] ?></td>
                                <td><?= $r['name'] ?></td>
                                <td><?= $r['phone'] ?></td>
                                <td><?= $r['rooms'] ?></td>
                                <td><?= $r['room_cost'] ?></td>
                                <td><a href="editrentregistered?id=<?= $r['id'] ?>" style="color:red;">ኣስተካክል</a></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php
                        $base_url="?homeowner_id=".urlencode($homeowner_id)."&homeowner_name=".urlencode($homeowner_name)."&homeowner_page=";
                        for($i=1;$i<=$homeowner_total_pages;$i++):
                            $active=$i==$homeowner_page?"active":"";
                        ?>
                            <a href="<?= $base_url.$i ?>" class="<?= $active ?>"><?= $i ?></a>
                        <?php endfor;?>
                    </div>
                    <?php endif;?>
                </fieldset>
            </div>

            <!-- Clientslanddata Section -->
            <div class="section-box">
                <fieldset>
                    <legend>ዘይተመዝገቡ መካረይቲ ገዛ</legend>
                    <form method="GET">
                        <input type="text" name="filenumber" placeholder="ፋይል ቁፅሪ" value="<?= $filenumber ?>" />
                        <input type="text" name="fullname" placeholder="ሙሉእ ሽም" value="<?= $fullname ?>" />
                        <button type="submit">ድለ</button>
                    </form>
                    <?php if($clients_message):?><div class="message"><?= $clients_message ?></div><?php endif;?>
                    <?php if(!empty($clients_records)):?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th><th>መለለይ ቁፅሪ </th><th>ሙሉእ ሽም</th><th>ስልኪ ቁፅሪ</th><th>ፋይል ቁፅሪ</th><th>ቀበሌ</th><th>ብሎክ</th><th>ደረጃ ቦታ</th><th>ዝተትሓዘሉ ዓ/ም</th> <th>ዓ/ግልጋሎት</th>
            <th>ናይቲ መሬት ዓይነት ትሕዝቶ</th>
            <th >ስፍሓት ብካ/ሜ</th><th>ተግባር</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($clients_records as $i=>$r):?>
                            <tr>
                               <td><?= htmlspecialchars($clients_offset+$i+1, ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['id'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['fullname'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['phone'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['filenumber'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['kebele'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['block'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['levelofplace'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['occupiedyear'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['mainservice'], ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($r['kindofvolumeofland'], ENT_QUOTES, 'UTF-8') ?></td>
                                   <td><?= htmlspecialchars($r['area'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><a href="addrentdetail?id=<?= htmlspecialchars($r['id'], ENT_QUOTES, 'UTF-8') ?>" style="color:red;">መዝግብ</a></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php
                        $base_url="?filenumber=".urlencode($filenumber)."&fullname=".urlencode($fullname)."&clients_page=";
                        for($i=1;$i<=$clients_total_pages;$i++):
                            $active=$i==$clients_page?"active":"";
                        ?>
                            <a href="<?= $base_url.$i ?>" class="<?= $active ?>"><?= $i ?></a>
                        <?php endfor;?>
                    </div>
                    <?php endif;?>
                </fieldset>
            </div>

        </div>
    </div>
</div>

<?php
$ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ?: '0.0.0.0';
$agent = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8') : 'unknown';
insertLog($conn,$useridc,$username,"Viewed","Viewed either homeowner or land records.","success",$ip,$agent);
?>

<?php include('../footerboot.php'); ?>


		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="../assets/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="../assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
		  <script src="assets/js/excanvas.min.js"></script>
		<![endif]-->
		<script src="../assets/js/jquery-ui.custom.min.js"></script>
		<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="../assets/js/jquery.easypiechart.min.js"></script>
		<script src="../assets/js/jquery.sparkline.index.min.js"></script>
		<script src="../assets/js/jquery.flot.min.js"></script>
		<script src="../assets/js/jquery.flot.pie.min.js"></script>
		<script src="../assets/js/jquery.flot.resize.min.js"></script>

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		<script src="../assets/js/jquery.dataTables.min.js"></script>
		<script src="../assets/js/jquery.dataTables.bootstrap.min.js"></script>
		<script src="../assets/js/dataTables.buttons.min.js"></script>
		<script src="../assets/js/buttons.flash.min.js"></script>
		<script src="../assets/js/buttons.html5.min.js"></script>
		<script src="../assets/js/buttons.print.min.js"></script>
		<script src="../assets/js/buttons.colVis.min.js"></script>
		<script src="../assets/js/dataTables.select.min.js"></script>

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<script src="lib/jquery.js" type="text/javascript"></script>-->
<script src="src/facebox.js" type="text/javascript"></script>
<link href="stylee.css" media="screen" rel="stylesheet" type="text/css" />
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<!--<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>-->
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<!--<script src="js/jquery.js"></script>-->
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: ace.vars['old_ie'] ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html',
									 {
										tagValuesAttribute:'data-values',
										type: 'bar',
										barColor: barColor ,
										chartRangeMin:$(this).data('min') || 0
									 });
				});
			
			
			  //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
			  //but sometimes it brings up errors with normal resize event handlers
			  $.resize.throttleWindow = false;
			
			  var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "<?php echo "$nrcact_id";?>",  data: <?php echo "$rcact_id";?>, color: "#68BC31"},
				{ label: "<?php echo "$ntact_id";?>",  data: <?php echo "$tact_id";?>, color: "#2091CF"},
				{ label: " <?php echo "$ncact_id";?>",  data: <?php echo "$cact_id";?>, color: "#AF4E96"}, 
			  ]
			  function drawPieChart(placeholder, data, position) {
			 	  $.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);
			
			
			  //pie chart tooltip example
			  var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
			  var previousPoint = null;
			
			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + item.series['percent']+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}
				
			 });
			
				/////////////////////////////////////
				$(document).one('ajaxloadstart.page', function(e) {
					$tooltip.remove();
				});
			
			
			
			
				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}
			
				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}
			
				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}
				
			
				var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
				$.plot("#sales-charts", [
					{ label: "Domains", data: d1 },
					{ label: "Hosting", data: d2 },
					{ label: "Sers", data: d3 }
				], {
					hoverable: true,
					shadowSize: 0,
					series: {
						lines: { show: true },
						points: { show: true }
					},
					xaxis: {
						tickLength: 0
					},
					yaxis: {
						ticks: 10,
						min: -2,
						max: 2,
						tickDecimals: 3
					},
					grid: {
						backgroundColor: { colors: [ "#fff", "#fff" ] },
						borderWidth: 1,
						borderColor:'#555'
					}
				});
			
			
				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
				$('.dialogs,.comments').ace_scroll({
					size: 300
			    });
				
				
				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if(ace.vars['touch'] && ace.vars['android']) {
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				  });
				}
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});
			
			})
		</script>
				<script src="../assets/js/jquery-ui.custom.min.js"></script>
		<script src="../assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="../assets/js/chosen.jquery.min.js"></script>
		<script src="../assets/js/spinbox.min.js"></script>
		<script src="../assets/js/bootstrap-datepicker.min.js"></script>
		<script src="../assets/js/bootstrap-timepicker.min.js"></script>
		<script src="../assets/js/moment.min.js"></script>
		<script src="../assets/js/daterangepicker.min.js"></script>
		<script src="../assets/js/bootstrap-datetimepicker.min.js"></script>
		<script src="../assets/js/bootstrap-colorpicker.min.js"></script>
		<script src="../assets/js/jquery.knob.min.js"></script>
		<script src="../assets/js/autosize.min.js"></script>
		<script src="../assets/js/jquery.inputlimiter.min.js"></script>
		<script src="../assets/js/jquery.maskedinput.min.js"></script>
		<script src="../assets/js/bootstrap-tag.min.js"></script>

		<!-- ace scripts -->
		<script src="../assets/js/ace-elements.min.js"></script>
		<script src="../assets/js/ace.min.js"></script>

		
				<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table')
				.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					bAutoWidth: false,
					"aoColumns": [
					  { "bSortable": false },
					   null, null, null,    null, null,null, null, null, null, null, null, null, null, null,
					   null, null, null,  null,
					  { "bSortable": false }
					],
					"aaSorting": [],
					
					
					//"bProcessing": true,
			        //"bServerSide": true,
			        //"sAjaxSource": "http://127.0.0.1/table.php"	,
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
			
			
					select: {
						style: 'multi'
					}
			    } );
			
				
				
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					  {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },
					  {
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },
					  {
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});
			
				////
			
				setTimeout(function() {
					$($('.tableTools-container')).find('a.dt-button').each(function() {
						var div = $(this).find(' > div').first();
						if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
						else $(this).tooltip({container: 'body', title: $(this).text()});
					});
				}, 500);
				
				
				
				
				
				myTable.on( 'select', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
					}
				} );
				myTable.on( 'deselect', function ( e, dt, type, index ) {
					if ( type === 'row' ) {
						$( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
					}
				} );
			
			
			
			
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$('#dynamic-table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) myTable.row(row).select();
						else  myTable.row(row).deselect();
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(this.checked) myTable.row(row).deselect();
					else myTable.row(row).select();
				});
			
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});
				
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if($row.is('.detail-row ')) return;
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
				
				
				
				
				/***************/
				$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				});
				/***************/
				
				
				
				
				
				/**
				//add horizontal scrollbars to a simple table
				$('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
				  {
					horizontal: true,
					styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
					size: 2000,
					mouseWheelLock: true
				  }
				).css('padding-top', '12px');
				*/
			
			
			})
		</script>
	</body>
</html>

