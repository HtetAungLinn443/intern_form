<?php
require 'require.php';

$success = false;
$success_msg = '';

$sql = "SELECT 
            T01.id, 
            T01.name, 
            T01.email, 
            T01.address, 
            T01.gender, 
            T01.phone, 
            T01.birth_of_date, 
            T01.profile, 
            T01.updated_at,
            T02.name as country_name
        FROM `user` T01 
        LEFT JOIN country_list T02
        ON T01.country_id = T02.id
        WHERE deleted_at = '0'
        ORDER BY updated_at DESC
        ";
$result = $mysqli->query($sql);
$res_row = $result->num_rows;
?>

<?php require('template/header_temp.php') ?>
<div class="col-md-6">
    <a href="<?php echo $base_url ?>create.php" class="btn btn-info">Create Form</a>
</div>

<h2 class="text-center">Table List</h2>
<table class="table table-info table-hover table-striped">
    <thead>
        <tr>
            <th>UserName</th>
            <th>Email</th>
            <th>Address</th>
            <th>Gender</th>
            <th>Job</th>
            <th>Phone</th>
            <th>Birth Of Date</th>
            <th>Country</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($res_row >= 1) {
            while ($row = $result->fetch_assoc()) {
                $tb_name            = htmlspecialchars($row['name']);
                $email              = htmlspecialchars($row['email']);
                $address            = htmlspecialchars($row['address']);
                $gender             = htmlspecialchars($row['gender']);
                $phone              = htmlspecialchars($row['phone']);
                $birth_of_date      = htmlspecialchars($row['birth_of_date']);
                $country            = htmlspecialchars($row['country_name']);
        ?>
                <tr>
                    
                    <td><?php echo $tb_name ?></td>
                    <td><?php echo $email ?></td>
                    <td><?php echo $address ?></td>
                    <td><?php echo $gender ?></td>
                    <td></td>
                    <td><?php echo $phone ?></td>
                    <td><?php echo $birth_of_date ?></td>
                    <td><?php echo $country ?></td>

                    <td class="inline">
                        <a href="<?php echo $base_url . "edit.php?id=" . $row['id'];?>" class="btn btn-sm btn-primary">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <a href="<?php echo $base_url . "delete.php?id=" . $row['id'];?>" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
        <?php
            }
        }
        ?>

    </tbody>
</table>
<?php require('template/footer_temp.php') ?>