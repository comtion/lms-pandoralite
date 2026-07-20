var emp;

$(document).ready(function() {
      $('#manage-table').DataTable({
          pageLength: 50,
          dom: 'frtip'
      });

      $('[name="remove"]').click(function() {
         emp = $(this).val();
      });

      $('#remove-form').submit(function() {
        return confirm('Are you sure you want to remove user ' + emp + '!');
      });

  });
