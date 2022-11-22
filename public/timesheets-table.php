<?php
if (isset($_POST['btnUnpaid']) && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE timesheets SET status=0 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}
if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE timesheets SET status=1 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}

?>
<section class="content-header">
    <h1>Timesheets /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-timesheet.php"><i class="fa fa-plus-square"></i> Add New Timesheet</a>
    </ol>
</section>

    <!-- Main content -->
    <section class="content">
        <form name="withdrawal_form" method="post" enctype="multipart/form-data">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                                    <div class="form-group col-md-3">
                                        <h4 class="box-title">Filter by Status </h4>
                                        <select id='status' name="status" class='form-control'>
                                                <option value="">All</option>
                                                <option value="0">Not Verified</option>
                                                <option value="1">Verified</option>
                                            </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <h4 class="box-title">Filter by Date </h4>
                                        <input type="date" class="form-control" id="date" name="date">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <h4 class="box-title">Filter by Staff </h4>
                                            <select id='staff_id' name="staff_id" class='form-control'>
                                                <option value="">select</option>
                                                    <?php
                                                    $sql = "SELECT id,name FROM `staffs`";
                                                    $db->sql($sql);
                                                    $result = $db->getResult();
                                                    foreach ($result as $value) {
                                                    ?>
                                                        <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <h4 class="box-title">Filter by Projects </h4>
                                            <select id='project_id' name="project_id" class='form-control'>
                                                <option value="">select</option>
                                                    <?php
                                                    $sql = "SELECT id,name FROM `projects`";
                                                    $db->sql($sql);
                                                    $result = $db->getResult();
                                                    foreach ($result as $value) {
                                                    ?>
                                                        <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                    </div>
                            </div>
                        
                        <div  class="box-body table-responsive">
                                <div class="row">
                                    <div class="text-left col-md-2">
                                        <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                                    </div> 
                                    <div class="col-md-3">
                                            <button type="submit" class="btn btn-danger" name="btnUnpaid">Not-verified</button>
                                            <button type="submit" class="btn btn-success" name="btnPaid">Verified</button>                                        
                                    </div>

                                </div>
                        <table id='users_table' class="table table-hover" data-toggle="table"  data-url="api-firebase/get-bootstrap-table-data.php?table=timesheets" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-show-footer="true" data-export-types='["txt","excel"]' data-export-options='{
                                "fileName": "students-list-<?= date('d-m-Y') ?>",
                                "ignoreColumn": ["operate"] 
                            }'>
                                <thead>
                                    <tr>
                                        <th data-field="column"> All</th>
                                        <th  data-field="id" data-sortable="true">ID</th>
                                        <th data-field="date" data-sortable="true">Date</th>
                                        <th  data-field="staff_name" data-sortable="true">Staff Name</th>
                                        <th  data-field="project_name" data-sortable="true"> Project Name</th>
                                        <th  data-field="description" data-sortable="true" data-visible="true" data-footer-formatter="totalFormatter">Description</th>
                                        <th  data-field="hours" data-sortable="true"  data-visible="true" data-footer-formatter="timeFormatter">Worked Hours</th>
                                        <th  data-field="status" data-sortable="true">Status</th>
                                        <th  data-field="operate" data-events="actionEvents">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="separator"> </div>
            </div>
        </form>
    </section>
<script>
 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
    
</script>
 <script>

   
        $('#date').on('change', function() {
            id = $('#date').val();
            $('#users_table').bootstrapTable('refresh');
        });
        $('#staff_id').on('change', function() {
            id = $('#staff_id').val();
            $('#users_table').bootstrapTable('refresh');
        });
        $('#project_id').on('change', function() {
            id = $('#project_id').val();
            $('#users_table').bootstrapTable('refresh');
        });
        $('#status').on('change', function() {
            id = $('#status').val();
            $('#users_table').bootstrapTable('refresh');
        });

function queryParams(p) {
    return {
        "date": $('#date').val(),
        "staff_id": $('#staff_id').val(),
        "project_id": $('#project_id').val(),
        "status": $('#status').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function totalFormatter() {
        return '<span style="color:green;font-weight:bold;font-size:large;">TOTAL HOURS</span>'
    }

    var total = 0;

    function timeFormatter(data) {
        var field = this.field
        return '<span style="color:green;font-weight:bold;font-size:large;"> ' + data.map(function(row) {
                return +row[field]
            })
            .reduce(function(sum, i) {
                return sum + i
            }, 0);
    }
</script>

