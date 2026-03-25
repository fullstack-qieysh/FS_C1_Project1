<?php //code membership.php 2/9/25
session_start();

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Facility prices
$facilityPrices = [
"Personal Trainer" => 100,
"Sauna" => 50,
"Yoga Class" => 40,
"Zumba Class" => 60
];

// Functions
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

function calculateTotal($type, $facilities, $facilityPrices){
    $base = getBaseFee($type);
    $extra = getFacilityFee($facilities, $facilityPrices);
    return [$base, $extra, $base + $extra];
}

function deleteMember($index){
unset($_SESSION['members'][$index]);
    $_SESSION['members'] = array_values($_SESSION['members']); // reset index
}

// Store members in session
if(!isset($_SESSION['members'])){
    $_SESSION['members'] = [];
}

if(isset($_POST['register'])){
    if(strlen($_POST['id']) < 6){
        $error = "Member ID must be at least 6 characters long.";
    } else {
        $name = $_POST['name'];
        $id = $_POST['id'];
        $gender = $_POST['gender'];
        $type = $_POST['type'];
        $facilities = $_POST['facilities'] ?? [];
        list($base, $extra, $total) = calculateTotal($type, $facilities,
        $facilityPrices);

        // Save into session array
        $_SESSION['members'][] = [
        'name' => $name,
        'id' => $id,
        'gender' => $gender,
        'type' => $type,
        'facilities' => $facilities,
        'base' => $base,
        'extra' => $extra,
        'total' => $total
        ];
    }
}

// if(isset($_POST['delete'])){
//     //echo "test";
//     $deleteIndex = $_POST['index'];
//     deleteMember($deleteIndex);
// }

?>
<!DOCTYPE html>
<html>
    <script>
        function confirmDelete(username) {
            //let username = document.querySelector('[name="deletename"]').value;
            console.log(username);
            return confirm("Are you sure you want to delete the registration for "+ username +"?");
        }
    </script>
    <link rel="stylesheet" href="style.css">
<body>
<div class="wrapper2">
    <div id="login-form" class="register-box">
    <form method="post">
    <h2>Welcome, <?php echo $username; ?>!</h2>
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
            <td>Gender: </td>
            <td><input type="radio" name="gender" value="Male" required>Male
            <input type="radio" name="gender" value="Female">Female</td>
        </tr>
        <tr>
            <td>Facilities: </td>
            <td><input type="checkbox" name="facilities[]" value="Personal Trainer">Personal Trainer (RM100)<br>
            <input type="checkbox" name="facilities[]" value="Sauna">Sauna (RM50)<br>
            <input type="checkbox" name="facilities[]" value="Yoga Class">Yoga Class (RM40)<br>
            <input type="checkbox" name="facilities[]" value="Zumba Class">Zumba (RM60)</td>
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
    <div style = "text-align:center">
        <input style="" type="submit" name="register" value="Register">
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
        <th>Gender</th>
        <th>Membership</th>
        <th>Facilities</th>
        <th>Base Fee</th>
        <th>Extra Fee</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <?php
    foreach($_SESSION['members'] as $i => $m){

        $facilitiesStr = !empty($m['facilities']) ? implode(", ",
        $m['facilities']) : "None";
        $name = $m['name'];

        echo "<tr>
        <td>{$m['name']}</td>
        <td>{$m['id']}</td>
        <td>{$m['gender']}</td>
        <td>{$m['type']}</td>
        <td>$facilitiesStr</td>
        <td>RM{$m['base']}</td>
        <td>RM{$m['extra']}</td>
        <td><strong>RM{$m['total']}</strong></td>
        <td>
            <form method='POST' onsubmit='return confirmDelete(\"$name\");'>
            <input type='hidden' name='index' value='$i'>
            <input type='hidden' name='deletename' value='$name'>
            <center><input type='submit' name='delete' value='Delete'></center>
            </form>
        </td>
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