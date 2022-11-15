<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}

if (isset($_POST['btnEdit'])) {

    $project_id = $db->escapeString(($_POST['project_id']));
    $amount = $db->escapeString($_POST['amount']);
    $balance = $db->escapeString($_POST['balance']);
    $date = $db->escapeString($_POST['date']);
    $error = array();


    if (empty($project_id)) {
        $error['project_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($amount)) {
        $error['amount'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($balance)) {
        $error['balance'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($date)) {
        $error['date'] = " <span class='label label-danger'>Required!</span>";
    }





    if (!empty($project_id) && !empty($amount) && !empty($balance) && !empty($date)) {
        if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
            //image isn't empty and update the image
            $old_image = $db->escapeString($_POST['old_image']);
            $extension = pathinfo($_FILES["image"]["name"])['extension'];

            $result = $fn->validate_image($_FILES["image"]);
            $target_path = 'upload/staffs/';

            $filename = microtime(true) . '.' . strtolower($extension);
            $full_path = $target_path . "" . $filename;
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
                echo '<p class="alert alert-danger">Can not upload image.</p>';
                return false;
                exit();
            }
            if (!empty($old_image)) {
                unlink($old_image);
            }
            $upload_image = 'upload/staffs/' . $filename;
            $sql = "UPDATE project_bill SET `image`='" . $upload_image . "' WHERE `id`=" . $ID;
            $db->sql($sql);
        }

        $sql_query = "UPDATE project_bill SET project_id='$project_id',amount='$amount',balance='$balance',date='$date' WHERE id =  $ID";
        $db->sql($sql_query);
        $res = $db->getResult();
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        // check update result
        if ($update_result == 1) {

            $error['update_staff'] = " <section class='content-header'><span class='label label-success'>Staff Details updated Successfully</span></section>";
        } else {
            $error['update_staff'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM project_bill WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "project_bill.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Project Bill<small><a href='project_bill.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Project Bill</a></small></h1>
    <small><?php echo isset($error['update_staff']) ? $error['update_staff'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-10">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Project Bill</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="edit_staff_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" id="old_image" name="old_image" value="<?= $res[0]['image']; ?>">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Project ID</label> <i class="text-danger asterik">*</i>
                                    <select id='project_id' name="project_id" class='form-control' required>
                                        <option value="none">Select</option>
                                        <?php
                                        $sql = "SELECT id,name FROM `project_bill`";
                                        $db->sql($sql);

                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>' <?= $value['id'] == $res[0]['name'] ? 'selected="selected"' : ''; ?>><?= $value['name'] ?></option>

                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Amount</label> <i class="text-danger asterik">*</i><?php echo isset($error['amount']) ? $error['amount'] : ''; ?>
                                    <input type="text" class="form-control" name="amount" value="<?php echo $res[0]['amount']; ?>" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['balance']) ? $error['balance'] : ''; ?>
                                    <input type="number" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>">
                                </div>

                                <div class="col-md-4">
                                    <label for="exampleInputFile">Receipt</label>

                                    <input type="file" accept="image/png,  image/jpeg" onchange="readURL(this);" name="image" id="image">
                                    <p class="help-block"><img id="blah" src="<?php echo $res[0]['image']; ?>" style="height:120px;max-width:100%" /></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['date']) ? $error['date'] : ''; ?>
                                    <input type="date" class="form-control" name="date" value="<?php echo $res[0]['date']; ?>">
                                </div>
                            </div>
                        </div>


                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>

                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>