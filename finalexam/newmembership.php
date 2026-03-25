<?php
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Functions
function getBaseFee($type){
    if($type == "Monthly") return 100;
    elseif($type == "Quarterly") return 250;
    elseif($type == "Yearly") return 800;
    return 0;
}

function getFacilityFee($facility){
    if($facility == "Personal Trainer") return 100;
    elseif($facility == "Sauna") return 50;
    elseif($facility == "Yoga Class") return 40;
    elseif($facility == "Zumba Class") return 60;
    else return 0;
}

function calculateTotal($type, $facility){
    $base = getBaseFee($type);
    $extra = getFacilityFee($facility);
    return [$base, $extra, $base + $extra];
}

// Store members in session
// if(!isset($_SESSION['members'])){
//     $dataregister = $_SESSION['members'];
// }

if(isset($_POST['register'])){
    if(strlen($_POST['id']) < 6){
        $error = "Member ID must be at least 6 characters long.";
    } else {
        $name = $_POST['name'];
        $id = $_POST['id'];
        $type = $_POST['type'];
        $facility = $_POST['facility'] ?? '';

        list($base, $extra, $total) = calculateTotal($type, $facility);

        // Save into session array
        $_SESSION['members'][] = [
            'name' => $name,
            'id' => $id,
            'type' => $type,
            'facility' => $facility,
            'base' => $base,
            'extra' => $extra,
            'total' => $total
        ];
    }
}

?>
<!DOCTYPE html>
<html>
<head>
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
                <input type="radio" name="facility" value="Personal Trainer" required>Personal Trainer (RM100)<br>
                <input type="radio" name="facility" value="Sauna">Sauna (RM50)<br>
                <input type="radio" name="facility" value="Yoga Class">Yoga Class (RM40)<br>
                <input type="radio" name="facility" value="Zumba Class">Zumba (RM60)
            </td>
        </tr>
        <tr>
            <td>Membership Type: </td>
            <td>
                <select name="type" required>
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

if(!empty($_SESSION['members'])){
    ?>
    <h2>List Registered Members</h2>
    <div class="wrapper2">
    <table border='1' cellpadding='5' cellspacing='0'>
    <tr>
        <th>Name</th>
        <th>ID</th>
        <th>Membership</th>
        <th>Facility</th>
        <th>Base Fee</th>
        <th>Extra Fee</th>
        <th>Total</th>
    </tr>
    <?php
    foreach($_SESSION['members'] as $m){

        echo "<tr>
        <td>{$m['name']}</td>
        <td>{$m['id']}</td>
        <td>{$m['type']}</td>
        <td>{$m['facility']}</td>
        <td>RM{$m['base']}</td>
        <td>RM{$m['extra']}</td>
        <td><strong>RM{$m['total']}</strong></td>
        </tr>";
    }
    echo "</table>";
}
?>
</div>
<div class="wrapper2">
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
