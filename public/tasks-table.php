
<section class="content-header">
    <h1>Tasks /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-task.php"><i class="fa fa-plus-square"></i> Add New Task</a>
    </ol>
</section>

    <!-- Main content -->
    <section class="content">
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
                                    <option value="0">Bug</option>
                                    <option value="2">Fixed</option>
                                    <option value="1">Tested</option>
                                </select>
                        </div>
                        <div class="form-group col-md-3">
                            <h4 class="box-title">Filter by Assign </h4>
                            <select id='staff_id' name="staff_id" class='form-control'>
                                            <option value="">All</option>
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
                    </div>
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=tasks" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="tasks.id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="project_name" data-sortable="true">Project Name</th>
                                    <th  data-field="description" data-sortable="true"> Description</th>
                                    <th  data-field="status" data-sortable="true"> Status</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>
    <script>
        $('#status').on('change', function() {
            id = $('#status').val();
            $('#users_table').bootstrapTable('refresh');
        });
        $('#staff_id').on('change', function() {
            id = $('#staff_id').val();
            $('#users_table').bootstrapTable('refresh');
        });
    </script>

<script>

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "staff_id": $('#staff_id').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>