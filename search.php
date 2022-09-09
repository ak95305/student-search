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


// $sql = "SELECT l.first_name, l.last_name, l.state_id, s.state_name FROM students_list AS l INNER JOIN states AS s ON l.state_id = s.id";
$com_sql = "SELECT l.id, l.student_code, l.first_name, l.last_name, l.dob, l.std_img_url, c.city_name, s.state_name, l.registration_date, l.created_date, l.modified_date FROM `students_list` AS l INNER JOIN states AS s ON l.state_id = s.id INNER JOIN cities AS c ON c.id = l.city_id ";
// $get_std = $conn->query($sql);
// $rows = [];
// while ($row = mysqli_fetch_assoc($get_std)) {
//     $rows[] = $row;
// }
// print_r("<pre>");
// print_r($rows);
// print_r("</pre>");



if (isset($_POST['search-submit'])) {

    $filter_type = $_POST['filter'];
    $search_value = $_POST['search-student'];
    $start_date = $_POST['start_date'];

    switch ($filter_type) {
        case 'name':
            // $get_std_sql = $com_sql . "WHERE first_name = '" . $search_value . "' OR last_name = '" . $search_value . "'";
            $get_std_sql = $com_sql . "WHERE LOCATE('" . $search_value . "', first_name) OR LOCATE('" . $search_value . "', last_name)";
            $get_std = $conn->query($get_std_sql);
            $rows = [];
            while ($row = mysqli_fetch_assoc($get_std)) {
                $rows[] = $row;
            }

            if (count($rows) > 0) {
                $result = $rows;
            } else {
                $result = "No Records Found with name = " . $search_value;
            }
            break;

        case 'code':
            $get_std_sql = "SELECT l.student_code, l.first_name, l.last_name, l.dob, l.std_img_url, l.city_id, s.state_name, l.registration_date, l.created_date, l.modified_date FROM `students_list` AS l INNER JOIN states AS s ON l.state_id = s.id WHERE student_code = '" . $search_value . "'";
            $get_std = $conn->query($get_std_sql);
            $rows = [];
            while ($row = mysqli_fetch_assoc($get_std)) {
                $rows[] = $row;
            }
            if (count($rows) > 0) {
                $result = $rows;
            } else {
                $result = "No Records Found with code = " . $search_value;
            }
            break;

        case 'date':
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $get_std_sql = "SELECT l.student_code, l.first_name, l.last_name, l.dob, l.std_img_url, l.city_id, s.state_name, l.registration_date, l.created_date, l.modified_date FROM `students_list` AS l INNER JOIN states AS s ON l.state_id = s.id WHERE (registration_date BETWEEN CAST('" . $start_date . "' as DATE) AND CAST('" . $end_date . "' as DATE))";
            $get_std = $conn->query($get_std_sql);
            $rows = [];
            while ($row = mysqli_fetch_assoc($get_std)) {
                $rows[] = $row;
            }
            if (count($rows) > 0) {
                $result = $rows;
            } else {
                $result = "No Records Found between date " . $start_date . "and " . $end_date;
            }
            break;
    }
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

        #date:checked~#search-student {
            display: none;
        }

        table img {
            width: 50px;
        }
    </style>

</head>

<body>


    <div class="container py-5">
        <h4>Search Student</h4>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mt-3">Filters</div>
            <input type="radio" name="filter" id="name" value="name" checked>
            <label for="name">By Name</label>
            <input type="radio" name="filter" id="code" value="code">
            <label for="code">By Student Code</label>
            <input type="radio" name="filter" id="date" value="date">
            <label for="date">By Date</label>
            <div id="date-box" class="mt-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date">
            </div>
            <br>
            <input type="text" id="search-student" name="search-student" class="mt-5"><button type="submit" name="search-submit" class="btn btn-primary btn-sm ms-2">Submit</button>

        </form>


        <div id="results">
            <?php if (isset($result)) { ?>

                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Student Code</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">DOB</th>
                            <th scope="col">Image</th>
                            <th scope="col">City Name</th>
                            <th scope="col">State Name</th>
                            <th scope="col">Registration Date</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Last Modified Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (is_array($result)) {
                            for ($i = 0; $i < count($result); $i++) {
                        ?>

                                <tr>
                                    <td><a href="edit.php?id=<?php echo $result[$i]['id'] ?>" class="btn btn-success btn-sm">Edit</a><button class="btn btn-sm btn-danger del-btn" data-id="<?php echo $result[$i]['student_code'] ?>">Delete</button></td>
                                    <td><?php echo $result[$i]['student_code'] ?></td>
                                    <td><?php echo $result[$i]['first_name'] ?></td>
                                    <td><?php echo $result[$i]['last_name'] ?></td>
                                    <td><?php echo $result[$i]['dob'] ?></td>
                                    <td><?php echo $result[$i]['std_img_url'] ? "<img src='std_img/" . $result[$i]['std_img_url'] . "'>" : "No Image" ?></td>
                                    <td><?php echo $result[$i]['city_name'] ?></td>
                                    <td><?php echo $result[$i]['state_name'] ?></td>
                                    <td><?php echo $result[$i]['registration_date'] ?></td>
                                    <td><?php echo $result[$i]['created_date'] ?></td>
                                    <td><?php echo $result[$i]['modified_date'] ?></td>
                                </tr>

                        <?php }
                        } else {
                            echo $result;
                        } ?>
                    </tbody>
                </table>


            <?php } ?>


        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>


    <script>
        $(document).ready(function(){
            $(document).on('click', '.del-btn', function() {
                var delId = $(this).data("id");
                var element = this;
                $.ajax({
                    url: "delete.php",
                    type: "POST",
                    data: {
                        id: delId
                    },
                    success: function(data){
                        if(data == 1){
                            $(element).closest("tr").fadeOut();
                        }else{
                            console.log(data);
                        }
                    }
                });
            });

        });
    </script>

</body>

</html>