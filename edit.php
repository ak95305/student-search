<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = 'students';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$id = $_GET['id'];



// Get Data For Edit
$edit_sql = "SELECT * FROM `students_list` WHERE id = '" . $id . "'";
$get_data = $conn->query($edit_sql);
$data = mysqli_fetch_assoc($get_data);


// Get States
$state_sql = "SELECT * FROM `states`";
$get_state = $conn->query($state_sql);
$state_list = [];
while ($row = mysqli_fetch_assoc($get_state)) {
    $state_list[] = $row;
}

// Get Cities
$city_sql = "SELECT * FROM `cities`";
$get_city = $conn->query($city_sql);
$city_list = [];
while ($row = mysqli_fetch_assoc($get_city)) {
    $city_list[] = $row;
}


if (isset($_POST['upd-submit'])) {

    if (isset($_FILES['std_img'])) {
        $file_name = rand(10, 100) . $_FILES['std_img']['name'];
        $target = 'std_img/' . $file_name;
        move_uploaded_file($_FILES['std_img']['tmp_name'], $target);
        $upd_img_sql = "UPDATE students_list SET `std_img_url` = '".$file_name."' WHERE id = '" . $id . "'";
        $conn->query($upd_img_sql);
    }
    
    $upd_sql = "UPDATE `students_list` SET `first_name`='" . $_POST['first_name'] . "',`last_name`='" . $_POST['last_name'] . "',`student_code`='" . $_POST['std_code'] . "',`father_name`='" . $_POST['father_name'] . "',`dob`='" . $_POST['dob'] . "',`address`='" . $_POST['address'] . "',`state_id`='" . $_POST['state'] . "',`city_id`='" . $_POST['city'] . "',`phone_no`='" . $_POST['phone_no'] . "',`gender`='" . $_POST['gender'] . "',`registration_date`='" . $_POST['regis_date'] . "',`modified_date`=NOW() WHERE id = '" . $id . "'";


    $upd_res = $conn->query($upd_sql);

    header("Refresh:0");
}


$conn->close();

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        form {
            max-width: 700px;
            margin-top: 10px;
        }

        #age {
            font-weight: 700;
            color: #333;
            margin-left: 10px;
        }

        #age b {
            color: #000;
        }

        #dob-error {
            color: #ff0000;
        }

        img {
            width: 100px;
        }
    </style>

</head>

<body>


    <div class="container py-5">

        <h3>Add Student Details</h3>

        <div class="alert alert-success d-none" role="alert" id="up-res">
            Updating.....
        </div>
        <div class="alert alert-danger d-none" role="alert" id="fa-res">
            Something's Wrong
        </div>

        <form action="edit.php?id=<?php echo $_GET['id'] ?>" method="post" enctype='multipart/form-data'>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="first_name" placeholder="First name" value="<?php echo $data['first_name']; ?>" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="last_name" placeholder="Last name" value="<?php echo $data['last_name']; ?>" required>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="std_code" placeholder="Student Code" value="<?php echo $data['student_code']; ?>" required>
                </div>
                <div class="col">
                    <img src="std_img/<?php echo $data['std_img_url'] ?>" alt="">
                    <p>Choose File to Change</p>
                    <input type="file" class="form-control" name="std_img" placeholder="Student Image">
                </div>
            </div>
            <div class="row my-3 align-items-end">
                <div class="col">
                    <input type="text" class="form-control" name="father_name" placeholder="Father Name" value="<?php echo $data['father_name']; ?>">
                </div>
                <div class="col">
                    <label for="dob" class="form-label">Date of Birth</label> <span id="age">Age:<b></b></span>
                    <input type="date" id="dob" class="form-control" name="dob" placeholder="Date of Birth" value="<?php echo $data['dob']; ?>" required>
                    <p id="dob-error"></p>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $data['address']; ?>" required>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <select class="form-select" name="state" onchange="drop()" required>
                        <option value="default" selected>Select State</option>

                        <?php
                        if ($state_list) {
                            for ($i = 0; $i < count($state_list); $i++) {
                        ?>
                                <option value="<?php echo $state_list[$i]['id']; ?>" <?php echo $data['state_id'] == $state_list[$i]['id'] ? "selected" : ""; ?>><?php echo $state_list[$i]['state_name']; ?></option>

                        <?php }
                        } ?>

                    </select>
                </div>
                <div class="col">
                    <select class="form-select" name="city" required>
                        <option value="default" selected>Select City</option>
                        <?php
                        if ($city_list) {
                            for ($i = 0; $i < count($city_list); $i++) {
                        ?>
                                <option data-state="<?php echo $city_list[$i]['state_id']; ?>" value="<?php echo $city_list[$i]['id']; ?>" <?php echo $data['city_id'] == $city_list[$i]['id'] ? "selected" : ""; ?>><?php echo $city_list[$i]['city_name']; ?></option>

                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="int" class="form-control" name="phone_no" placeholder="Phone Number" value="<?php echo $data['phone_no'] ?>">
                </div>
                <div class="col">
                    <div>Select Gender</div>
                    <input type="radio" name="gender" id="male" value="male" <?php echo $data['gender'] == "male" ? "checked" : ""; ?> required>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female" <?php echo $data['gender'] == "female" ? "checked" : ""; ?> required>
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="other" value="other" <?php echo $data['gender'] == "other" ? "checked" : ""; ?> required>
                    <label for="other">Other</label>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-6">
                    <label for="regis_date" class="form-label">Registration Date</label>
                    <input type="date" id="regis_date" class="form-control" name="regis_date" placeholder="Registration Date" value="<?php echo $data['registration_date'] ?>" required>
                </div>
            </div>

            <button type="submit" name="upd-submit" class="btn btn-primary">update</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script>
        $("#dob").change(function() {
            let dob = new Date($(this).val());
            let today = new Date();
            let age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));

            $('#age b').text(age);

            if (age > 20 || age < 5) {
                $("#dob-error").text("Age Should be in between 5 to 20");
                $(this).val("");
            } else {
                $("#dob-error").text("");
            }
        });


        let options = $('[name="city"] option');
        let stateDrop = $('[name = "state"]');
        function drop(){
            options.each(function(item) {
                if (stateDrop.val() == options[item].getAttribute('data-state') || options[item].getAttribute('data-state')==null) {
                    options[item].style.display = "block";
                } else {
                    options[item].style.display = "none";
                }
            });
        }
        drop();

        $("[name='state']").change(function(){
            document.querySelector('[name="city"]').value='default';
        });
    </script>
</body>

</html>