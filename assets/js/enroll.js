 $(document).ready(function(){
  
  var table = $('#student-table').DataTable({
      "pageLength": 50,
      dom: 'frtip',
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false
      }]
  });
  var table2 = $('#student2-table').DataTable({
      "pageLength": 50,
      dom: 'frtip',
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false
      }]
  });

  /*$('#resetBT').click(function(){
    $('select option[value=""]').attr("selected",true);
  });*/
  
  // Handle click on "Select all" control
  $('#student-table .checkAll').on('click', function(){
     // Get all rows with search applied
     var rows = table.rows({ 'search': 'applied' }).nodes();
     // Check/uncheck checkboxes for all rows in the table
     $('input:checkbox', rows).prop('checked', this.checked);
  });

  // Handle click on checkbox to set state of "Select all" control
  $('#student-table tbody').on('change', 'input:checkbox', function(){
     // If checkbox is not checked
     if(!this.checked){
        var el = $('#student-table .checkAll').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if(el && el.checked && ('indeterminate' in el)){
           // Set visual state of "Select all" control
           // as 'indeterminate'
           el.indeterminate = true;
        }
     }
  });
  
  // Handle form submission event
  $('#add-form').on('submit', function(e){
     var form = this;

     // Iterate over all checkboxes in the table
     table.$('input:checkbox').each(function(){
        // If checkbox doesn't exist in DOM
        if(!$.contains(document, this)){
           // If checkbox is checked
           if(this.checked){
              // Create a hidden element
              $(form).append(
                 $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', this.name)
                    .val(this.value)
              );
           }
        }
     });
  });
  
  // Handle click on "Select all" control
  $('#student2-table .checkAll').on('click', function(){
     // Get all rows with search applied
     var rows = table2.rows({ 'search': 'applied' }).nodes();
     // Check/uncheck checkboxes for all rows in the table
     $('input:checkbox', rows).prop('checked', this.checked);
  });
  
  // Handle click on checkbox to set state of "Select all" control
  $('#student2-table tbody').on('change', 'input:checkbox', function(){
     // If checkbox is not checked
     if(!this.checked){
        var el = $('#student2-table .checkAll').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if(el && el.checked && ('indeterminate' in el)){
           // Set visual state of "Select all" control
           // as 'indeterminate'
           el.indeterminate = true;
        }
     }
  });
  
  // Handle form submission event
  $('#remove-form').on('submit', function(e){
     var form = this;

     // Iterate over all checkboxes in the table
     table2.$('input:checkbox').each(function(){
        // If checkbox doesn't exist in DOM
        if(!$.contains(document, this)){
           // If checkbox is checked
           if(this.checked){
              // Create a hidden element
              $(form).append(
                 $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', this.name)
                    .val(this.value)
              );
           }
        }
     });
  });
  
  /*$("#add .checkAll").click(function(){
    $('#add input:checkbox').not(this).prop('checked', this.checked);
  });
  $("#remove .checkAll").click(function(){
    $('#remove input:checkbox').not(this).prop('checked', this.checked);
  });*/
});
