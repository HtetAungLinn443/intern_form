<?php
require 'require.php';
$form_name = '';
$form_email = '';
$form_address = '';
$form_gender = '';
$form_phone = '';
$form_date_of_birth = '';
$form_country = '';
$job_types = [];
$process_err = false;
$error = false;
$error_msg = '';
$form_job_type = '';
$image_upload = false;
require 'include/include_country.php';
require 'include/include_job_type.php';

if (isset($_POST['submit']) && $_POST['form-sub'] == 1) {

    $form_name = $mysqli->real_escape_string($_POST['userName']);
    $form_email = $mysqli->real_escape_string($_POST['email']);
    $form_address = $mysqli->real_escape_string($_POST['address']);
    $form_gender = $mysqli->real_escape_string($_POST['gender']);
    $form_phone = $mysqli->real_escape_string($_POST['phone_number']);
    $form_date_of_birth = $mysqli->real_escape_string($_POST['date_of_birth']);
    $form_country = $mysqli->real_escape_string($_POST['country']);
    $date = date("Y-m-d H:i:s");

    if ($form_name == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Name field is require!</li>";
    }
    if ($form_email == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Email field is require!</li>";
    }
    if ($form_address == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Address field is require!</li>";
    }

    if (!isset($_POST['job_type'])) {
        $process_err = true;
        $error = true;
        $error_msg = "<li class='text-danger'>Please Select Job!</li>";
    } else {
        $job_types = $_POST['job_type'];
    }
    if ($form_phone == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Phone Number field is require!</li>";
    }
    if ($form_date_of_birth == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Date of Birth field is require!</li>";
    }
    if ($form_country == '') {
        $process_err = true;
        $error = true;
        $error_msg .= "<li class='text-danger'>Please Choose Country!</li>";
    }

    // $nameCheckSql = "SELECT id FROM `user` WHERE name= '$form_name' AND deleted_at IS '0'";
    // $result = $mysqli->query($nameCheckSql);
    // if ($result->num_rows > 0) {
    //     $process_err = true;
    //     $error = true;
    //     $error_msg = "This name ( " . $form_name . ") is already exit. <br/>";
    // }

    // file data
    if ($_FILES['profile_img']['name'] != '') {

        $file = $_FILES['profile_img'];
        $fileName = $file['name'];
        $fileType = $file['type'];
        $fileTempPath = $file['tmp_name'];
        $fileError = $file['error'];
        $allowFileType = ['png', 'jpg', 'jpeg', 'gif'];
        $explode = explode('.', $fileName);
        $extension = end($explode);
        if (in_array($extension, $allowFileType)) {
            if (getimagesize($fileTempPath)) {
                $image_upload = true;
                $uniqueName = date("Y-m-d_H-i-s_") . uniqid() . '.' . $extension;
            } else {
                $error = true;
                $error_msg .= "<li class='text-danger'>Invalid Image file! </li>";
            }
        } else {
            $error = true;
            $error_msg .= "<li class='text-danger'>File allow png, jpg, jpeg and gif Files Only! </li>";
        }
    }

    if (!$process_err) {
        if (!$image_upload) {
            $sql = "INSERT INTO `user` 
                    (
                        name,
                        email,
                        address,
                        gender,
                        phone,
                        birth_of_date,
                        country_id,
                        created_at,
                        updated_at,
                        deleted_at
                    )
                    VALUE
                    (
                        '" . $form_name . "',
                        '" . $form_email . "',
                        '" . $form_address . "',
                        '" . $form_gender . "',
                        '" . $form_phone . "',
                        '" . $form_date_of_birth . "',
                        '" . $form_country . "',
                        '" . $date . "',
                        '" . $date . "',
                        '0'
                    )";

            $insert = $mysqli->query($sql);

        } else {


            $sql = "INSERT INTO `user` 
                    (
                        name,
                        email,
                        address,
                        gender,
                        phone,
                        birth_of_date,
                        country_id,
                        profile,
                        created_at,
                        updated_at,
                        deleted_at
                    )
                    VALUE
                    (
                        '" . $form_name . "',
                        '" . $form_email . "',
                        '" . $form_address . "',
                        '" . $form_gender . "',
                        '" . $form_phone . "',
                        '" . $form_date_of_birth . "',
                        '" . $form_country . "',
                        '" . $uniqueName . "',
                        '" . $date . "',
                        '" . $date . "',
                        '0'
                    )";
            $insert = $mysqli->query($sql);
        }

        if ($insert) {
            $insert_id = $mysqli->insert_id;
            foreach ($job_types as $job_type) {
                $ins_job_type_sql = "INSERT INTO `user_job_type` 
                                    (
                                        user_id,
                                        job_type_id
                                    )
                                    VALUES 
                                    (
                                        '$insert_id', 
                                        '$job_type'
                                    )";
                $mysqli->query($ins_job_type_sql);
            }
            if ($image_upload) {
                $filePath = 'upload/';
                $uniqueName = date("Y-m-d_H-i-s_") . uniqid() . '.' . $extension;
                if (!file_exists($filePath)) {
                    if (!mkdir($filePath, 0777, true)) {
                        die('Failed to create directory.');
                    }
                }
                if (file_exists($fileTempPath)) {
                    if (move_uploaded_file($fileTempPath, $filePath . $uniqueName)) {
                        $success = true;
                        $successStatus .= "Success upload file";
                    } else {
                        $error = true;
                        $error_msg .= "<li class='text-danger'>Failed to upload file.</li>";
                    }
                } else {
                    $error = true;
                    $error_msg .= "<li class='text-danger'>File does not exist.</li>";
                }
            }
            $url = $base_url . "index.php";
            header("refresh:0 ; url=$url");
        }
    }
}


?>

<?php require('template/header_temp.php') ?>
<div class="d-flex justify-content-center">

    <div class="col-md-8">
        <div class="col-md-6 mb-4">
            <a href="<?php echo $base_url ?>" class="btn btn-dark">Back</a>
        </div>
        <?php
        if ($error) {
            ?>
            <div class="alert alert-danger">
                <ul>
                    <strong>
                        <?php echo $error_msg; ?>
                    </strong>
                </ul>
            </div>
            <?php
        }
        ?>
        <div class="card">
            <div class="card-body">
                <h1 class="text-center">Intern Test</h1>
                <form action="<?php $base_url; ?>create.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name" class=" form-label"><b>Name</b></label>
                        <input type="text" name="userName" id="name" class="form-control" placeholder="Enter Your Name"
                            value="<?php echo $form_name; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class=" form-label"><b>Email</b></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email"
                            value="<?php echo $form_email ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="address" class=" form-label"><b>Address</b></label>
                        <textarea name="address" id="address" class="form-control" cols="30" rows="5"
                            placeholder="Enter Your Address"><?php echo $form_address; ?></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="" class=" form-label"><b>Gender</b></label>
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="radio" name="gender" id="male" class="form-check-input" checked
                                    value="male" <?php if ($form_gender == 'male') {
                                        echo "checked";
                                    } ?>>
                                <label for="male">Male</label>
                            </div>

                            <div class="col-sm-2">
                                <input type="radio" name="gender" id="female" class="form-check-input" value="female"
                                    <?php if ($form_gender == 'female') {
                                        echo "checked";
                                    } ?>>
                                <label for="female">Female</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="job_type" class="form-label"><b>Job Type</b></label>
                        <div class="row">
                            <?php
                            foreach ($db_job_types as $key => $db_job_type) {
                                ?>
                                <div class="col-md-4 col-sm-12">
                                    <input type="checkbox" name="job_type[]" id="job-<?php echo $db_job_type['id']; ?>"
                                        value="<?php echo $db_job_type['id']; ?>" <?php if (in_array($db_job_type['id'], $job_types)) {
                                               echo "checked";
                                           } ?>>
                                    <label for="job-<?php echo $db_job_type['id']; ?>"><?php echo $db_job_type['name']; ?></label>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone_number" class=" form-label"><b>Phone</b></label>
                        <input type="number" name="phone_number" id="phone_number" class="form-control"
                            placeholder="Enter Your Phone Number" value="<?php echo $form_phone; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_of_birth" class=" form-label"><b>Birth Of Date</b></label>
                        <input type="text" name="date_of_birth" id="date_of_birth" class="form-control"
                            placeholder="Birth Of Date" value="<?php echo $form_date_of_birth; ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="country" class=" form-label"><b>Country</b></label>
                        <select name="country" id="country" class="form-select">
                            <option value="">Please Choose Country</option>
                            <?php
                            foreach ($db_countryName as $key => $country) {
                                ?>
                                <option value="<?php echo $country['id']; ?>" <?php if ($form_country == $country['id']) {
                                       echo "selected";
                                   } ?>>
                                    <?php echo $country['name']; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="profile">User Profile</label>
                        <input type="file" name="profile_img" class="form-control" id="profile">
                    </div>
                    <input type="hidden" value="1" name="form-sub">
                    <div class="">
                        <button type="submit" name="submit" class="btn btn-block btn-info"> Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require('template/footer_temp.php') ?>
<script>
    $(function () {

        $("#date_of_birth").datepicker({
            maxDate: 0,
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
        });
    });
</script>