$(document).ready(function() {
  $('#report-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print']
  });

  $('#enrolledreport-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    scrollX: true
  });

  $('#subordinate-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    fixedColumns: {
      leftColumns: 4
    },
    scrollX: true
  });

  $('#transcript-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    fixedColumns: {
      leftColumns: 3
    },
    scrollX: true
  });

  $('#allcourse-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    scrollX: true
  });
  $('#allquiz-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print']
  });
  $('#detailquiz-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    fixedColumns: {
      leftColumns: 3
    },
    scrollX: true
  });

  $('#course-table').DataTable({
    "pageLength": 50,
    dom: 'Bfrtip',
    buttons: ['excel', 'print'],
    scrollX: true
  });
});
