<?php 
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// ===== Facility prices =====
$facilityPrices = [
    "Personal Trainer" => 100,
    "Sauna" => 50,
    "Yoga Class" => 40,
    "Zumba Class" => 60
];

// ===== Functions =====
function getBaseFee($type){
    if($type == "Monthly") return 100;
    elseif($type == "Quarterly") return 250;
    elseif($type == "Yearly") return 800;
    return 0;
}

function getFacilityFee($facilities, $facilityPrices){
    $extra = 0;
    foreach($facilities as $f){
        $extra += $facilityPrices[$f];
    }
    return $extra;
}

function calculateTotal($type, $facility, $facilityPrices){
    $base = getBaseFee($type);
    $extra = getFacilityFee($facility, $facilityPrices);
    return [$base, $extra, $base + $extra];
}

function deleteMember(){
    unset($_SESSION['member']);
}

// ===== Process Form =====
if(isset($_POST['register'])){
    if(strlen($_POST['id']) < 6){
        $error = "Member ID must be at least 6 characters long.";
    } else {
        $name = $_POST['name'];
        $id = $_POST['id'];
        $type = $_POST['type'];
        $facility = $_POST['facilities'];

        list($base, $extra, $total) = calculateTotal($type, $facility, $facilityPrices);

        //Auto overwrite data lama
        $_SESSION['member'] = [
            'name' => $name,
            'id' => $id,
            'type' => $type,
            'facilities' => $facility,
            'base' => $base,
            'extra' => $extra,
            'total' => $total
        ];

        $success = "Registration successful. (Previous data has been replaced)";
    }
}

if(isset($_POST['delete'])){
    deleteMember();
}
?>
<!DOCTYPE html>
<html>
<head>
    <script>
        function confirmDelete(username) {
            //let username = document.querySelector('[name="deletename"]').value;
            console.log(username);
            return confirm("Are you sure you want to delete the registration for "+ username +"?");
        }
    </script>
    <title>Gym Membership Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper2">
    <div id="login-form" class="register-box">
    <form method="post">
    <h2>Welcome, <?php echo $username; ?>.</h2>
    <table>
        <tr>
            <td>Member Name: </td>
            <td><input type="text" name="name" required></td>
        </tr>
        <tr>
            <td>Member ID: </td>
            <td><input type="text" name="id" required></td>
        </tr>
        <tr>
            <td>Facility: </td>
            <td>
                <input type="checkbox" name="facilities[]" value="Personal Trainer">Personal Trainer (RM100)<br>
                <input type="checkbox" name="facilities[]" value="Sauna">Sauna (RM50)<br>
                <input type="checkbox" name="facilities[]" value="Yoga Class">Yoga Class (RM40)<br>
                <input type="checkbox" name="facilities[]" value="Zumba Class">Zumba (RM60)
            </td>
        </tr>
        <tr>
            <td>Membership Type: </td>
            <td><select name="type" required>
                <option value="Monthly">Monthly (RM100)</option>
                <option value="Quarterly">Quarterly (RM250)</option>
                <option value="Yearly">Yearly (RM800)</option>
                </select>
            </td>
        </tr>
    </table>
    <br><br>
    <div style="text-align:center">
        <input type="submit" name="register" value="Register">
    </div>
    </form>
    </div>
</div>

<?php
if(!empty($error)){
    echo "<p style='color:red; text-align:center'>$error</p>";
}
if(!empty($success)){
    echo "<p style='color:green; text-align:center'>$success</p>";
}

if(isset($_SESSION['member'])){
    $m = $_SESSION['member'];
    $facilitiesStr = !empty($m['facilities']) ? implode(", ", $m['facilities']) : "None";
?>
    <h2>Registration Detail</h2>
    <div class="wrapper2">
    <table border='1' cellpadding='5' cellspacing='0'>
    <tr>
        <th>Name</th>
        <th>ID</th>
        <th>Membership</th>
        <th>Facilities</th>
        <th>Base Fee</th>
        <th>Extra Fee</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
        <tr>
            <td><?= $m['name']; ?></td>
            <td><?= $m['id']; ?></td>
            <td><?= $m['type']; ?></td>
            <td><?= $facilitiesStr; ?></td>
            <td>RM<?= $m['base']; ?></td>
            <td>RM<?= $m['extra']; ?></td>
            <td><strong>RM<?= $m['total']; ?></strong></td>
            <td>
                <form method='POST' onsubmit="return confirmDelete('<?php echo $m['name']; ?>')">
                <center><input type='submit' name='delete' value='Delete'></center>
                </form>
            </td>
        </tr>
    </table>
    </div>
<?php } ?>

<div class="wrapper2">
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
