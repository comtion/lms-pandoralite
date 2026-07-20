<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
    <style type="text/css">
        .right {
            border-right: 1px solid #ccc;
        }
    </style>
  </head>
  <body class="fix-header fix-sidebar">

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css  class="fix-header card-no-border fix-sidebar" -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
      <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <div class="page-wrapper">
          <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(label('contact_us')); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/home"><?php echo ucwords(label('home')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo ucwords(label('contact_us')); ?></li>
                        </ol>
                    </div>
                </div>
                 <div class="row">
                  <div class="col-md-12 card">
                    <div class="card-body row">
                      <div class="col-md-6 right">
                                <form class="form-material m-t-40" id="contact_form" name="contact_form" autocomplete="off">
                                    <div class="form-group">
                                        <label><?php echo label('com_contact'); ?></label>
                                        <input type="text" class="form-control form-control-line" required id="contact_name" name="contact_name"> 
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo label('com_tel'); ?></span></label>
                                        <input type="text" class="form-control form-control-line" required id="contact_tel" name="contact_tel">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo label('com_mail_contact'); ?></span></label>
                                        <input type="text" class="form-control form-control-line" required id="contact_mail" name="contact_mail">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo label('contamess'); ?></label>
                                        <textarea class="form-control" rows="5" id="contact_msg" required name="contact_msg"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo label('about_con'); ?></label>
                                        <select class="form-control" id="contact_about" name="contact_about" required>
                                            <option value="<?php echo $foote[0]['da_email_a']; ?>" selected><?php echo label('price'); ?></option>
                                            <option value="<?php echo $foote[0]['da_email_b']; ?>"><?php echo label('technical_information'); ?></option>
                                            <option value="<?php echo $foote[0]['da_email_b']; ?>"><?php echo label('other'); ?></option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="user" name="user" value="">
                                    <div class="form-actions " align="center">
                                        <button type="submit" class="btn btn-success"> <i class="mdi mdi-send"></i> <?php echo label('sent'); ?></button>

                                        <button type="reset" class="btn btn-danger"><?php echo label('cancel'); ?></button>
                                    </div>
                                </form>
                      </div>
                      <div class="col-md-6">
                          <h4 class="card-title"><?php echo label('our_offices'); ?></h4>
                          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.967356000799!2d100.52830831455105!3d13.72042620171698!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29f32d8710575%3A0xed126de58e19c1b!2sVerztec+Consulting+(Thailand)+Ltd.!5e0!3m2!1sen!2sth!4v1543382025421" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                      </div>
                    </div>
                  </div>
                </div>
          </div>

        </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    </div>
    
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

        $(document).on('submit', '#contact_form', function(event){
              var tid = $('#tid').val();
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/home/send_message",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    console.log(data);
                    if(data=="2"){
                        swal(
                            '<?php echo label("sent_msg"); ?>!',
                            '',
                            'success'
                        ).then(function () {

                          $('#tid').val(tid);
                          console.log(tid);
                          $('#contact_form')[0].reset();
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("sent"); ?>'
                        })
                    }
                   
                  }
                });
            });
    </script>
  </body>
</html>
