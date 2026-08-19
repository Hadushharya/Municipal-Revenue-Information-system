<?php 
include('setting/header.php'); 
include('../db/connection.php');

if (session_status() === PHP_SESSION_NONE) session_start();
$username = htmlspecialchars($_SESSION['SESS_USER_NAME'], ENT_QUOTES, 'UTF-8'); // sanitize session data
$useridc = (int)$_SESSION['SESS_ID']; // ensure numeric
include("../logactivity.php");
$id1 = (int)$_SESSION['SESS_ID'];

// Fetching and sanitizing client_id from URL (GET request)
$client_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetching client details if client_id is provided
if ($client_id > 0) {
    $query = "SELECT fullname, phone FROM clientslanddata WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $client_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $owner_name_raw, $owner_phone_raw);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Sanitize output
    $owner_name = htmlspecialchars($owner_name_raw, ENT_QUOTES, 'UTF-8');
    $owner_phone = htmlspecialchars($owner_phone_raw, ENT_QUOTES, 'UTF-8');

} else {
    // Handle case where client_id is not provided in the URL
    echo "<div class='alert alert-danger'>Invalid Client ID.</div>";
    exit;
}

?>



<script>
 const OFFSET = 79372; // Pre-calculated offset for Ethiopian calendar
  const DAY = 1000 * 60 * 60 * 24; // Milliseconds in a day
  const months = "መስከረም,ጥቅምት,ኅዳር,ታኅሣሥ,ጥር,የካቲት,መጋቢት,ሚያዝያ,ግንቦት,ሰኔ,ሐምሌ,ነሐሴ,ጳጉሜ".split(",");

  let GC, EYear, EMonth, EDate, month, day, year;

  // Validate Gregorian date
  function isValid(dt) {
    GC = new Date(dt);
    const yearLen = dt.substr(dt.lastIndexOf("/") + 1).length;
    return yearLen === 4 && GC.getFullYear() >= 1753;
  }

  // Calculate total Ethiopian days since the offset
  function getECDays(dt) {
    const UTCVal = Date.UTC(GC.getFullYear(), GC.getMonth(), GC.getDate());
    return OFFSET + UTCVal / DAY;
  }

  // Convert Gregorian date to Ethiopian date
  function ECDate(dt) {
    GC = new Date(dt); // Ensure GC is set for the date
    const days = getECDays(dt);
    let year = 1745;
    const yearsApplied = Math.floor(days / 365.25);
    year += yearsApplied;
    let daysRemaining = days - Math.floor(yearsApplied * 365.25);

    // Adjust for leap year approximation error
    if (year % 4 === 0) {
      daysRemaining--;
    }

    if (daysRemaining === 0) {
      year--;
      month = 13;
      day = 5 + (year % 4 === 3 ? 1 : 0);
    } else {
      month = Math.ceil(daysRemaining / 30);
      day = daysRemaining % 30 === 0 ? 30 : daysRemaining % 30;
    }

    return { day, month, year };
  }

  // Get Ethiopian date in words
  function getInWords() {
    return `${months[month - 1]} ${day}, ${year}`;
  }

  // Display Ethiopian date
  function displayEthiopianDate() {
    const gregDate = new Date(); // Get the current Gregorian date
    const ethDate = ECDate(gregDate); // Convert Gregorian to Ethiopian

    // Update the page with the Ethiopian date
    document.getElementById("ethiopianDate").textContent = `${ethDate.day}/${ethDate.month}/${ethDate.year}`;
    document.getElementById("ethiopianDate1").value = `${ethDate.day}/${ethDate.month}/${ethDate.year}`;
    document.getElementById("ethiopianMonth").textContent = `${ethDate.month}`;
    document.getElementById("ethiopianMonth1").value = `${ethDate.month}`;
  }

  // Display Ethiopian date in a specific section
  function getToday() {
    const todayElement = document.getElementById("today");
    const ethDate = ECDate(new Date());
    todayElement.innerHTML = `${ethDate.day}/${ethDate.month}/${ethDate.year} ${getInWords()} E.C.<br/>`;
  }

  // Display Ethiopian date when the page loads
  window.onload = displayEthiopianDate;
</script>
<body class="no-skin">
<?php include('setting/headernav1.php'); ?>

<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try { ace.settings.loadState('main-container'); } catch (e) {}  
    </script>

    <div id="sidebar" class="sidebar responsive ace-save-state">
        <?php include('setting/menu.php'); ?>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i id="sidebar-toggle-icon" 
               class="ace-icon fa fa-angle-double-left ace-save-state" 
               data-icon1="ace-icon fa fa-angle-double-left" 
               data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <?php include('setting/settingpage.php'); ?>

            <div class="col-xs-12">
                <h4 class="lighter"> 
                    <div class="tab-pane fade in active" id="homeowner_renter">
                        <?php
                        if (isset($_POST['register'])) {
                            // Sanitize inputs
							 //$registeredtime = $_POST('ethiopianDate1');
                             $registeredtime = $_POST['ethiopianDate1'];

                            $rooms = filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT);
                            $room_cost = filter_input(INPUT_POST, 'room_cost', FILTER_VALIDATE_FLOAT);

                            if ($rooms === false || $room_cost === false) {
                                echo '<script>
                                        alert("Invalid input values");
                                        window.location.href = "viewrent";
                                      </script>';
                                exit;
                            }

                            // Check if the client is already registered
                            $check_sql = "SELECT id FROM homeowners WHERE homeowner_id = ?";
                            $stmt_check = mysqli_prepare($conn, $check_sql);
                            mysqli_stmt_bind_param($stmt_check, "i", $client_id);
                            mysqli_stmt_execute($stmt_check);
                            mysqli_stmt_store_result($stmt_check);
                            $already_registered = mysqli_stmt_num_rows($stmt_check);
                            mysqli_stmt_close($stmt_check);

                            if ($already_registered > 0) {
                                // User already registered
                                echo '<script>
                                        alert("User already registered");
                                        window.location.href = "viewrent";
                                      </script>';
                            } else {
                                // Register the new homeowner with prepared statement
                                $sql_homeowner = "INSERT INTO homeowners (name, phone, homeowner_id, rooms, room_cost, registered_at) 
                                                  VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt_homeowner = mysqli_prepare($conn, $sql_homeowner);
                                mysqli_stmt_bind_param($stmt_homeowner, "ssiids", $owner_name, $owner_phone, $client_id, $rooms, $room_cost,$registeredtime);
                                mysqli_stmt_execute($stmt_homeowner);
                                mysqli_stmt_close($stmt_homeowner);

                                // Insert log activity
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
                                insertLog($conn, $useridc, $username, "Registered Homeowners", "Homeowner is registered successfully.", "success", $ip, $agent);

                                echo '<script>
                                        alert("ብትክክል ተመዝጊቡ ኣሎ");
                                        window.location.href = "viewrent";
                                      </script>';
                            }
                        }
                        ?>

                        <form class="form-horizontal" method="post">
                            <fieldset style="border: 2px solid #4CAF50; padding: 20px; background-color: #E8F5E9; border-radius: 10px;">
                                <legend style="font-size: 18px; font-weight: bold; color: #388E3C; padding: 0 10px; border-bottom: 2px solid #388E3C;">
                                    <i class="fa fa-home"></i> ምምዝጋብ መካረይቲ ገዛ
                                </legend>
								
										  
								 <div class="form-group">
								 <span  id="ethiopianDate" type="hidden" align="right"></span>
									<label class="col-sm-3 control-label">ዕለት:</label>
									<div class="col-sm-3">
									   <input  id="ethiopianDate1" type="text" readonly name="ethiopianDate1"  required />
									</div>
								</div> 
										  
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ሽም (ባዓል ገዛ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="owner_name" value="<?= $owner_name ?>" required class="form-control" id="owner_name" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ስልኪ (ባዓል ገዛ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="owner_phone" value="<?= $owner_phone ?>" required class="form-control" id="owner_phone" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">በዝሒ ዝካረ ገዛ:</label>
                                    <div class="col-sm-3">
                                        <input type="number" name="rooms" required class="form-control" id="rooms" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ናይ ሓደ ክራይ ክፍሊት(ብማእከላይ):</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="room_cost" required class="form-control" id="room_cost" />
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="register" class="btn btn-success" style="padding: 10px 20px; font-size: 16px; border-radius: 5px;">
                                        <i class="fa fa-user-plus"></i> መዝግብ
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </h4>
            </div>
        </div>
    </div>
</div>

<?php include('../footerboot.php'); ?>


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
</body>
</html>