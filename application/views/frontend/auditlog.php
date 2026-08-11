<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <link href="<?php echo REAL_PATH;?>/assets/css/report-theme.css?v=20260811-1" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border report-theme-page">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-3 align-self-center">
                        <b><?php echo html_escape($title); ?></b>
                    </div>
                    <div class="col-md-9 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo html_escape($title_main); ?></li>
                            <li class="breadcrumb-item active"><?php echo html_escape($title); ?></li>
                        </ol>
                    </div>
                </div>

                <div class="row col-12 page-titles">
                    <div class="col-md-12 card">
                        <div class="card-body">
                            <form id="audit_search_form" class="form-horizontal p-t-10" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Start Date</label>
                                        <input type="date" id="date_start" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Start Time</label>
                                        <input type="time" id="time_start" class="form-control" value="00:00">
                                    </div>
                                    <div class="col-md-2">
                                        <label>End Date</label>
                                        <input type="date" id="date_end" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label>End Time</label>
                                        <input type="time" id="time_end" class="form-control" value="23:59">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Table</label>
                                        <select id="audit_table" class="form-control">
                                            <option value="">All tables</option>
                                            <?php foreach ($audit_tables as $row) { ?>
                                                <option value="<?php echo html_escape($row['audit_table']); ?>"><?php echo html_escape($row['audit_table']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Action</label>
                                        <select id="audit_action" class="form-control">
                                            <option value="">All actions</option>
                                            <?php foreach ($audit_actions as $row) { ?>
                                                <option value="<?php echo html_escape($row['audit_action']); ?>"><?php echo html_escape($row['audit_action']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-3">
                                        <label>Row Key</label>
                                        <input type="text" id="row_key" class="form-control" placeholder="id=1, emp_id=...">
                                    </div>
                                    <div class="col-md-5">
                                        <label>Keyword</label>
                                        <input type="text" id="keyword" class="form-control" placeholder="user, URI, SQL, changed value">
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-block btn-outline-info"><i class="mdi mdi-magnify"></i> Search</button>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" id="audit_reset" class="btn btn-block btn-outline-danger"><i class="mdi mdi-autorenew"></i> Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-3 offset-md-9">
                                        <button type="button" id="audit_export" class="btn btn-block btn-outline-success"><i class="mdi mdi-file-excel"></i> Export CSV</button>
                                    </div>
                                </div>
                            </form>

                            <hr>
                            <div class="table-responsive">
                                <table id="auditTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date Time</th>
                                            <th>Action</th>
                                            <th>Table</th>
                                            <th>Row Key</th>
                                            <th>User</th>
                                            <th>Page</th>
                                            <th>IP</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="auditDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 1100px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Audit Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div id="audit_summary" class="m-b-10"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#diff_table_tab" role="tab">Changed Table</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#changed_tab" role="tab">Changed JSON</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#old_tab" role="tab">Before</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#new_tab" role="tab">After</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sql_tab" role="tab">SQL</a></li>
                    </ul>
                    <div class="tab-content p-t-15">
                        <div class="tab-pane active" id="diff_table_tab" role="tabpanel"><div id="audit_diff_table" class="table-responsive"></div></div>
                        <div class="tab-pane" id="changed_tab" role="tabpanel"><pre id="audit_changed" class="audit-json"></pre></div>
                        <div class="tab-pane" id="old_tab" role="tabpanel"><pre id="audit_old" class="audit-json"></pre></div>
                        <div class="tab-pane" id="new_tab" role="tabpanel"><pre id="audit_new" class="audit-json"></pre></div>
                        <div class="tab-pane" id="sql_tab" role="tabpanel"><pre id="audit_sql" class="audit-json"></pre></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="auditHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width: 1100px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Record History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div id="audit_history_summary" class="m-b-10"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date Time</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Changed</th>
                                </tr>
                            </thead>
                            <tbody id="audit_history_rows"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <style>
        .audit-json {
            background: #f7f9fb;
            border: 1px solid #dfe5eb;
            border-radius: 4px;
            max-height: 460px;
            overflow: auto;
            padding: 12px;
            white-space: pre-wrap;
        }
    </style>
    <script>
    $(function() {
        $.fn.dataTable.ext.errMode = "none";

        var table = $('#auditTable').on('error.dt', function(e, settings, techNote, message) {
            if (typeof notificationForDatatableError === 'function') {
                notificationForDatatableError("auditTable", message);
            }
        }).DataTable({
            processing: true,
            searching: true,
            ajax: {
                url: '<?php echo base_url(); ?>index.php/auditlog/fetch',
                type: 'GET',
                data: function(d) {
                    d.date_start = $('#date_start').val();
                    d.time_start = $('#time_start').val();
                    d.date_end = $('#date_end').val();
                    d.time_end = $('#time_end').val();
                    d.table = $('#audit_table').val();
                    d.action = $('#audit_action').val();
                    d.row_key = $('#row_key').val();
                    d.keyword = $('#keyword').val();
                }
            },
            order: [[0, 'desc']],
            lengthMenu: [[10, 25, 100, -1], [10, 25, 100, "All"]],
            pageLength: 25
        });

        $('#audit_search_form').on('submit', function(event) {
            event.preventDefault();
            table.ajax.reload();
        });

        $('#audit_reset').on('click', function() {
            $('#audit_search_form')[0].reset();
            $('#time_start').val('00:00');
            $('#time_end').val('23:59');
            table.ajax.reload();
        });

        $('#audit_export').on('click', function() {
            var params = $.param({
                date_start: $('#date_start').val(),
                time_start: $('#time_start').val(),
                date_end: $('#date_end').val(),
                time_end: $('#time_end').val(),
                table: $('#audit_table').val(),
                action: $('#audit_action').val(),
                row_key: $('#row_key').val(),
                keyword: $('#keyword').val()
            });
            window.open('<?php echo base_url(); ?>index.php/auditlog/exportCsv?' + params);
        });

        $(document).on('click', '.audit-detail', function() {
            var id = $(this).data('id');
            $.getJSON('<?php echo base_url(); ?>index.php/auditlog/detail/' + id, function(row) {
                $('#audit_summary').html(
                    '<b>ID:</b> ' + escapeHtml(row.audit_id) +
                    ' &nbsp; <b>Action:</b> ' + escapeHtml(row.audit_action) +
                    ' &nbsp; <b>Table:</b> ' + escapeHtml(row.audit_table || '') +
                    ' &nbsp; <b>User:</b> ' + escapeHtml(row.audit_user_display || row.audit_username || 'System/Unknown') +
                    '<br><b>URI:</b> ' + escapeHtml(row.audit_uri || '')
                );
                $('#audit_diff_table').html(buildDiffTable(row.audit_changed_values));
                $('#audit_changed').text(formatJson(row.audit_changed_values));
                $('#audit_old').text(formatJson(row.audit_old_values));
                $('#audit_new').text(formatJson(row.audit_new_values));
                $('#audit_sql').text(row.audit_sql || '');
                $('#auditDetailModal').modal('show');
            });
        });

        $(document).on('click', '.audit-history', function() {
            var tableName = $(this).data('table');
            var rowKey = $(this).data('row-key');
            $.getJSON('<?php echo base_url(); ?>index.php/auditlog/history', {
                table: tableName,
                row_key: rowKey
            }, function(result) {
                $('#audit_history_summary').html('<b>Table:</b> ' + escapeHtml(tableName) + ' &nbsp; <b>Row:</b> ' + escapeHtml(rowKey));
                var html = '';
                (result.data || []).forEach(function(row) {
                    html += '<tr>' +
                        '<td>' + escapeHtml(row.audit_id) + '</td>' +
                        '<td>' + escapeHtml(row.audit_created_at) + '</td>' +
                        '<td>' + escapeHtml(row.audit_action) + '</td>' +
                        '<td>' + escapeHtml(row.audit_user_display || row.audit_username || 'System/Unknown') + '</td>' +
                        '<td>' + buildDiffTable(row.audit_changed_values) + '</td>' +
                    '</tr>';
                });
                if (html === '') {
                    html = '<tr><td colspan="5" class="text-center text-muted">No history found.</td></tr>';
                }
                $('#audit_history_rows').html(html);
                $('#auditHistoryModal').modal('show');
            });
        });

        function formatJson(value) {
            if (value === null || value === '') {
                return '-';
            }
            if (typeof value === 'string') {
                return value;
            }
            return JSON.stringify(value, null, 2);
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"'`=\/]/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;',
                    '`': '&#x60;',
                    '=': '&#x3D;'
                })[s];
            });
        }

        function buildDiffTable(value) {
            if (!value || typeof value !== 'object') {
                return '<div class="text-muted">No structured field changes.</div>';
            }
            if (value.before !== undefined || value.after !== undefined) {
                return '<pre class="audit-json">' + escapeHtml(formatJson(value)) + '</pre>';
            }

            var rows = [];
            if (Array.isArray(value)) {
                value.forEach(function(rowChanges) {
                    collectDiffRows(rowChanges, rows);
                });
            } else {
                collectDiffRows(value, rows);
            }

            if (!rows.length) {
                return '<div class="text-muted">No changed fields detected.</div>';
            }

            var html = '<table class="table table-bordered table-striped"><thead><tr><th>Field</th><th>Before</th><th>After</th></tr></thead><tbody>';
            rows.forEach(function(row) {
                html += '<tr><td>' + escapeHtml(row.field) + '</td><td><pre class="m-0">' + escapeHtml(row.from) + '</pre></td><td><pre class="m-0">' + escapeHtml(row.to) + '</pre></td></tr>';
            });
            html += '</tbody></table>';
            return html;
        }

        function collectDiffRows(changes, rows, prefix) {
            prefix = prefix || '';
            if (!changes || typeof changes !== 'object') {
                return;
            }
            Object.keys(changes).forEach(function(field) {
                var value = changes[field];
                var label = prefix ? prefix + '.' + field : field;
                if (value && typeof value === 'object' && value.from !== undefined && value.to !== undefined) {
                    rows.push({
                        field: label,
                        from: formatScalar(value.from),
                        to: formatScalar(value.to)
                    });
                } else if (value && typeof value === 'object') {
                    collectDiffRows(value, rows, label);
                }
            });
        }

        function formatScalar(value) {
            if (value === null || value === undefined) {
                return '';
            }
            if (typeof value === 'object') {
                return JSON.stringify(value, null, 2);
            }
            return String(value);
        }
    });
    </script>
</body>
</html>
