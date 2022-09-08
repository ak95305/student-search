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



if (isset($_POST['search-submit'])) {

    $filter_type = $_POST['filter'];
    $search_value = $_POST['search-student'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    switch ($filter_type) {
        case 'name':
            $get_std_sql = "SELECT * FROM `students_list` WHERE first_name = '" . $search_value . "'";
            $get_std = $conn->query($get_std_sql);
            $data = mysqli_fetch_assoc($get_std);
            $rows = mysqli_num_rows($get_std);
            if ($rows > 0) {
                print_r($data);
            } else {
                echo "No Records Found";
            }
            break;

        case 'code':
            $get_std_sql = "SELECT * FROM `students_list` WHERE student_code = '" . $search_value . "'";
            $get_std = $conn->query($get_std_sql);
            $data = mysqli_fetch_assoc($get_std);
            $rows = mysqli_num_rows($get_std);
            if ($rows > 0) {
                print_r($data);
            } else {
                echo "No Records Found";
            }
            break;

        case 'date':
            $get_std_sql = "SELECT * FROM `students_list` WHERE registration_date > '" . $start_date . "' AND registration_date < ";
            $get_std = $conn->query($get_std_sql);
            $data = mysqli_fetch_assoc($get_std);
            $rows = mysqli_num_rows($get_std);
            if ($rows > 0) {
                print_r($data);
            } else {
                echo "No Records Found";
            }
            break;
    }

    die();
}

$conn->close();

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        #date-box {
            display: none;
        }

        #date:checked~#date-box {
            display: block;
        }
    </style>

</head>

<body>


    <div class="container py-5">
        <h4>Search Student</h4>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="text" id="search-student" name="search-student"><button type="submit" name="search-submit" class="btn btn-primary btn-sm ms-2">Submit</button>

            <div class="mt-3">Filters</div>
            <input type="radio" name="filter" id="name" value="name">
            <label for="name">By Name</label>
            <input type="radio" name="filter" id="code" value="code">
            <label for="code">By Student Code</label>
            <input type="radio" name="filter" id="date" value="date">
            <label for="date">By Date</label>
            <div id="date-box" class="mt-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date">
            </div>
        </form>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</body>

</html>