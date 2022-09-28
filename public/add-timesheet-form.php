<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {
        $error = array();
        $date = $db->escapeString($fn->xss_clean($_POST['date']));
        $name = $db->escapeString($fn->xss_clean($_POST['name']));

        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($date)) {
            $error['date'] = " <span class='label label-danger'>Required!</span>";
        }
        
        if ( !empty($name) && !empty($date))
        {
                $sql = "SELECT * FROM `staffs` WHERE `name` = '$name'";
                $db->sql($sql);
                $staffs_result = $db->getResult();
                if (!empty($staffs_result)) {
                    $staffs_result = 0;
                } else {
                    $staffs_result = 1;
                }
                if ($staffs_result == 1) {
                    $sql = "SELECT id FROM staffs ORDER BY id DESC LIMIT 1";
                    $db->sql($sql);
                    $res = $db->getResult();
                    for ($i = 0; $i < count($_POST['description']); $i++) {
    
                        $project_name = $db->escapeString($fn->xss_clean($_POST['project_name'][$i]));
                        $description = $db->escapeString($fn->xss_clean($_POST['description'][$i]));
                        $hours = $db->escapeString($fn->xss_clean($_POST['hours'][$i]));
                        $sql = "INSERT INTO timesheets (staff_id,date,project_name,description,hours) VALUES('$name','$date','$project_name','$description','$hours')";
                        $db->sql($sql);
                        $timesheet_result = $db->getResult();
                    }
                    if (!empty($timesheet_result)) {
                        $timesheet_result = 0;
                    } else {
                        $timesheet_result = 1;
                    }
                    $error['add_timesheet'] = "<section class='content-header'>
                                                    <span class='label label-success'>Timesheet Added Successfully</span>
                                                     </section>";
                } else {
                    $error['add_timesheet'] = " <span class='label label-danger'>Failed</span>";
                }

            }

    }

?>
<section class="content-header">
<h1>Add Timesheet <small><a href='timesheets.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Timesheets</a></small></h1>
    <?php echo isset($error['add_timesheet']) ? $error['add_timesheet'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr>

</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='add_timesheet_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1"> Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <select id='name' name="name" class='form-control' required>
                                        <?php
                                        $sql = "SELECT * FROM `staffs`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Date</label><i class="text-danger asterik">*</i><?php echo isset($error['date']) ? $error['date'] : ''; ?>
                                    <input type="date" class="form-control" name="date" required />
                               </div>
                            </div>
                        </div>
                        <br>
                        <div id="packate_div"  >
                                <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Project Name</label><i class="text-danger asterik">*</i>
                                                <select id='project_name' name="project_name[]" class='form-control' required>
                                                <option value="">Select</option>
                                                            <?php
                                                            $sql = "SELECT * FROM `projects`";
                                                            $db->sql($sql);

                                                            $result = $db->getResult();
                                                            foreach ($result as $value) {
                                                            ?>
                                                                <option value='<?= $value['name'] ?>'><?= $value['name'] ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i>
                                                <textarea type="text" rows="4" class="form-control" name="description[]" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Worked Hours</label><i class="text-danger asterik">*</i>
                                                <input type="number" class="form-control" name="hours[]" required />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <label>Variation</label>
                                            <a class="add_packate_variation" title="Add variation of timesheet" style="cursor: pointer;"><i class="fa fa-plus-square-o fa-2x"></i></a>
                                        </div>
                                        <div id="variations">
                                        </div>
                                </div>
                                <br>
                        </div>
            
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Add" name="btnAdd" />&nbsp;
                        <input type="reset" class="btn-danger btn" value="Clear" id="btnClear" />
                    </div>
                </form>
                <div class="hide" id="add_packate_div"  >
                                <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Project Name</label><i class="text-danger asterik">*</i>
                                                <select id='project_name' name="project_name[]" class='form-control' required>
                                                <option value="">Select</option>
                                                            <?php
                                                            $sql = "SELECT * FROM `projects`";
                                                            $db->sql($sql);

                                                            $result = $db->getResult();
                                                            foreach ($result as $value) {
                                                            ?>
                                                                <option value='<?= $value['name'] ?>'><?= $value['name'] ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i>
                                                <textarea type="text" rows="4" class="form-control" name="description[]" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group packate_div">
                                                <label for="exampleInputEmail1">Worked Hours</label><i class="text-danger asterik">*</i>
                                                <input type="number" class="form-control" name="hours[]" required />
                                            </div>
                                        </div>
                                        <div class="col-md-1" style="display:grid;">
                                            <label>Remove</label>
                                            <a class="remove text-danger" style="cursor:pointer;"><i class="fa fa-times fa-2x"></i></a></div>
                                        </div>
                                 </div>
                 </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_timesheet_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            project_name: "required",
            date: "required",
            description: "required",
            hours: "required",        
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var max_fields = 8;
        var wrapper = $("#packate_div");
        var add_button = $(".add_packate_variation");

        var x = 1;
        $(add_button).click(function (e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                $(wrapper).append($("#add_packate_div").html());
            } else {
                alert('You Reached the limits')
            }
        });

        $(wrapper).on("click", ".remove", function (e) {
            e.preventDefault();
            $(this).closest('.row').remove();
            x--;
        })
        $('#name').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

</script>

<?php $db->disconnect(); ?>