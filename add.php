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

if (isset($_POST['stud_submit'])) {

    // $add_sql = "INSERT INTO students_list (`first_name`, `last_name`, `student_code`, `father_name`, `dob`, `std_img_url`, `address`, `state_id`, `city_id`, `phone_no`, `gender`, `registration_date`) VALUES ('" . $_POST['first_name'] . "," . $_POST['last_name'] . "," . $_POST['std_code'] . "," . $_POST['father_name'] . "," . $_POST['dob'] . "," . $_POST['std_img'] . "," . $_POST['address'] . "," . $_POST['state_id'] . "," . $_POST['city_id'] . "," . $_POST['phone_no'] . "," . $_POST['gender'] . "," . $_POST['regis_date'] . ")";

    // Check if Same Student Code is there
    $check_std_sql = "SELECT `id` FROM `students_list` WHERE student_code = '" . $_POST['std_code'] . "'";
    $get_std = $conn->query($check_std_sql);
    $std_rows = mysqli_num_rows($get_std);
    if ($std_rows > 0) {
        echo "Student Not Added! Student Code Already Exist";
    } else {



        // Add Data Query
        $add_sql = "INSERT INTO students_list (`first_name`, `last_name`, `student_code`, `father_name`, `dob`, `std_img_url`, `address`, `state_id`, `city_id`, `phone_no`, `gender`, `registration_date`) VALUES ('" . $_POST['first_name'] . "' , '" . $_POST['last_name'] . "' , '" . $_POST['std_code'] . "' , '" . $_POST['father_name'] . "' , '" . $_POST['dob'] . "' , '" . $_FILES['std_img']['name'] . "' , '" . $_POST['address'] . "', '" . $_POST['state_id'] . "', '" . $_POST['city_id'] . "', '" . $_POST['phone_no'] . "', '" . $_POST['gender'] . "', '" . $_POST['regis_date'] . "')";

        $target = 'std_img/' . $_FILES['std_img']['name'];
        move_uploaded_file($_FILES['std_img']['tmp_name'], $target);


        $add_res = $conn->query($add_sql);

        if ($add_res) {
            echo "Student Added";
        } else {
            echo "Something's Wrong";
        }
    }
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
    </style>

</head>

<body>


    <div class="container py-5">

        <h3>Add Student Details</h3>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype='multipart/form-data'>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="first_name" placeholder="First name">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="last_name" placeholder="Last name">
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="std_code" placeholder="Student Code">
                </div>
                <div class="col">
                    <input type="file" class="form-control" name="std_img" placeholder="Student Image">
                </div>
            </div>
            <div class="row my-3 align-items-end">
                <div class="col">
                    <input type="text" class="form-control" name="father_name" placeholder="Father Name">
                </div>
                <div class="col">
                    <label for="dob" class="form-label">Date of Birth</label> <span id="age">Age:<b></b></span>
                    <input type="date" id="dob" class="form-control" name="dob" placeholder="Date of Birth">
                    <p id="dob-error"></p>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="address" placeholder="Address">
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="text" class="form-control" name="state_id" placeholder="State" requierd>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="city_id" placeholder="City" requierd>
                </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <input type="int" class="form-control" name="phone_no" placeholder="Phone Number">
                </div>
                <div class="col">
                    <div>Select Gender</div>
                    <input type="radio" name="gender" id="male" value="male">
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="other" value="other">
                    <label for="other">Other</label>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-6">
                    <label for="regis_date" class="form-label">Registration Date</label>
                    <input type="date" id="regis_date" class="form-control" name="regis_date" placeholder="Registration Date">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="stud_submit">Submit</button>
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
    </script>
</body>

</html>