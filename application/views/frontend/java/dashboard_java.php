
    <script type="text/javascript">
        function ondisplay_chk(div_main,div_sub){
          document.getElementById(div_main).style.display = "";
          document.getElementById(div_sub).style.display = "none";
        }
        $(function() {
            $('[data-plugin="knob"]').knob();
        });
        <?php if(in_array('4', $arr_role_fd)){ ?>
        $(document).ready(
          function() {

            var chart = c3.generate({
                bindto: '#visitor',
                data: 
                {
                    columns: [
                        ['Desktop', parseInt('<?php echo $PC_log; ?>')],
                        ['Tablet', parseInt('<?php echo $Tablet_log; ?>')],
                        ['Mobile', parseInt('<?php echo $Mobile_log; ?>')],
                    ],
                    
                    type : 'donut',
                    onclick: function (d, i) { console.log("onclick", d, i); },
                    onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                    onmouseout: function (d, i) { console.log("onmouseout", d, i); }
                },
                donut: {
                    label: {
                        show: false
                      },
                    title:"",
                    width:20,
                    
                },
                
                legend: {
                  hide: true
                  //or hide: 'data1'
                  //or hide: ['data1', 'data2']
                },
                color: {
                      pattern: ['#e67e22','#9b59b6',  '#26c6da', '#1e88e5']
                }
            });

          }
        );
        <?php } ?>

        new Chart(document.getElementById("chart3"),
        {
            "type":"doughnut",
            options: {
                aspectRatio: 1,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0,
                    }
                },
                responsive: true,
                legend: {
                    position : 'bottom',
                    labels: {
                      boxWidth: 10
                    }
                },
                cutoutPercentage: 80,
            },
            "data":{"labels":["Desktop","Tablet","Mobile"],
            "datasets":[{
                "label":"My First Dataset",
                "data":[parseInt('<?php echo $PC_log; ?>'),parseInt('<?php echo $Tablet_log; ?>'),parseInt('<?php echo $Mobile_log; ?>')],
                "backgroundColor":["#005277","#e94649","#8bc652"],
              borderWidth: 1}
            ]}
        });
    </script>

    <script>
      $('#company_active_user_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#ongoing_course_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#incoming_course_table').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#ongoing_course_table_admin_learner').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $('#incoming_course_table_admin_learner').DataTable({
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "bInfo": false,
        "pageLength": 5,
        "oLanguage": {
          "oPaginate": {          
          "sPrevious": "<", // This is the link to the previous page
          "sNext": ">", // This is the link to the next page
          }
        },
        "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        '<"row"<"col-sm-12 m-t-20 m-b-20"p>>',
        "scrollX": true
      });

      $( document ).ready(function() {
        $('#show_admin_dashboard').hide();
        $('#survey_admin_learner').hide();
        $('#admin_learner_approve').hide();
        
        $('#course_admin_learner').hide();
        $('#on_going_course_admin_learner').hide();
        $('#in_coming_course_admin_learner').hide();
      });

      $('#dashboard').click(function(){
            if($(this).prop("checked") == true){
              $('#show_admin_dashboard').hide();
              $('#show_learner_dashboard').show();
              $('#log_visit').fadeIn("slow");
              $('#admin_graph').fadeIn("slow");
              $('#admin_approve').fadeIn("slow");
              $('#active_user_admin').fadeIn("slow");
              $('#ongoing_admin').fadeIn("slow");
              $('#incoming_admin').fadeIn("slow");
              $('#survey_admin_learner').fadeOut("slow");
              $('#admin_learner_approve').fadeOut("slow");
              $('#course_admin_learner').fadeOut("slow");
              $('#on_going_course_admin_learner').fadeOut("slow");
              $('#in_coming_course_admin_learner').fadeOut("slow");
            }
            else if($(this).prop("checked") == false){
              $('#show_learner_dashboard').hide();
              $('#show_admin_dashboard').show();
              $('#log_visit').fadeOut("slow");
              $('#admin_graph').fadeOut("slow");
              $('#admin_approve').fadeOut("slow");
              $('#active_user_admin').fadeOut("slow");
              $('#ongoing_admin').fadeOut("slow");
              $('#incoming_admin').fadeOut("slow");
              $('#survey_admin_learner').fadeIn("slow");
              $('#admin_learner_approve').fadeIn("slow");
              $('#course_admin_learner').fadeIn("slow");
              $('#on_going_course_admin_learner').fadeIn("slow");
              $('#in_coming_course_admin_learner').fadeIn("slow");
            }
        });
    </script>

    <script type="text/javascript">
      /*$(window).on('load',function(){
          $('#dashboard_modal').modal('show');
      });

      $('#dashboard_modal').modal({backdrop: 'static', keyboard: false});

      $("input[type=checkbox]").change(function(){

        if($('#check-1').is(':checked') && $('#check-2').is(':checked')) {
          $('#confirm_button').removeClass("disabled");
        }else{
          $('#confirm_button').addClass("disabled");
        }
      });*/
    </script>