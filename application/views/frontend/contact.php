<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
?>
    <link href="<?php echo REAL_PATH;?>/assets/css/footers.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.css" rel="stylesheet">
    <link href="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.css" rel="stylesheet">
    <style type="text/css">
      .test[style] {
          padding-right:0 !important;
      }
      .test.modal-open {
          overflow: auto;
      }    
      .modal {
          padding-right: 0px !important;
      }
      .text-wrap{
        white-space:normal;overflow-wrap: anywhere;
      }
      .circle {
        width: 200px;
          margin: 6px 20px 20px;
          display: inline-block;
          position: relative;
          text-align: center;
        vertical-align: top;
        strong {
          position: absolute;
          top: 70px;
          left: 0;
          width: 100%;
          text-align: center;
          line-height: 45px;
          font-size: 43px;
        }
      }

      #myModal_process.modal.show .modal-dialog{
        position: fixed;
        top: 50%;
        left: 50%;
        /* bring your own prefixes */
        transform: translate(-50%, -50%);
        margin: 0;
      }
      #myModal_process .circle strong{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size:1.8em;
      }
      #myModal_process .circle canvas{
        visibility: hidden;
      }
      #myModal_process #circle-b{
        margin:0;
      }

      :root {
        --contact-navy: #13233f;
        --contact-blue: #1769e0;
        --contact-blue-dark: #1057bd;
        --contact-muted: #667085;
        --contact-border: #dce3ed;
        --contact-surface: #ffffff;
        --contact-bg: #f3f6fb;
      }
      body.contact-page {
        background: var(--contact-bg);
        color: var(--contact-navy);
      }
      .contact-page .page-wrapper {
        min-height: 100vh;
        margin-left: 0;
        background:
          radial-gradient(circle at 8% 12%, rgba(23, 105, 224, .10), transparent 28%),
          radial-gradient(circle at 92% 88%, rgba(18, 175, 142, .08), transparent 24%),
          var(--contact-bg);
      }
      .contact-page .page-wrapper > .container-fluid {
        min-height: 100vh;
        padding: 48px 24px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .contact-shell {
        width: 100%;
        max-width: 1120px;
        display: grid;
        grid-template-columns: minmax(280px, .78fr) minmax(0, 1.45fr);
        overflow: hidden;
        border: 1px solid rgba(19, 35, 63, .08);
        border-radius: 24px;
        background: var(--contact-surface);
        box-shadow: 0 24px 70px rgba(19, 35, 63, .12);
      }
      .contact-intro {
        position: relative;
        padding: 54px 44px;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(155deg, #11223e 0%, #174c94 100%);
      }
      .contact-intro::after {
        content: "";
        position: absolute;
        right: -90px;
        bottom: -110px;
        width: 270px;
        height: 270px;
        border: 48px solid rgba(255, 255, 255, .06);
        border-radius: 50%;
      }
      .contact-brand {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 64px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: rgba(255,255,255,.76);
      }
      .contact-brand .mdi {
        display: grid;
        width: 38px;
        height: 38px;
        place-items: center;
        border-radius: 12px;
        background: rgba(255,255,255,.12);
        color: #fff;
        font-size: 21px;
      }
      .contact-intro h1 {
        position: relative;
        z-index: 1;
        max-width: 340px;
        margin: 0 0 18px;
        color: #fff;
        font-size: clamp(32px, 3vw, 44px);
        font-weight: 700;
        line-height: 1.12;
        letter-spacing: -.025em;
      }
      .contact-intro > p {
        position: relative;
        z-index: 1;
        max-width: 340px;
        margin: 0;
        color: rgba(255,255,255,.72);
        font-size: 15px;
        line-height: 1.75;
      }
      .contact-assurance {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 12px;
        margin-top: 56px;
        padding-top: 24px;
        border-top: 1px solid rgba(255,255,255,.14);
        color: rgba(255,255,255,.86);
        font-size: 13px;
        line-height: 1.55;
      }
      .contact-assurance .mdi { color: #71e0c2; font-size: 22px; }
      .contact-form-panel { padding: 48px 52px; }
      .contact-form-header { margin-bottom: 32px; }
      .contact-form-header .eyebrow {
        display: block;
        margin-bottom: 8px;
        color: var(--contact-blue);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
      }
      .contact-form-header h2 {
        margin: 0 0 8px;
        color: var(--contact-navy);
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -.02em;
      }
      .contact-form-header p { margin: 0; color: var(--contact-muted); font-size: 14px; }
      .contact-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 20px;
      }
      .contact-field--full { grid-column: 1 / -1; }
      .contact-field label {
        display: block;
        margin-bottom: 8px;
        color: #344054;
        font-size: 13px;
        font-weight: 600;
      }
      .contact-field .required { margin-left: 3px; color: #e44848; }
      .contact-control-wrap { position: relative; }
      .contact-control-wrap > .mdi {
        position: absolute;
        top: 50%;
        left: 15px;
        z-index: 1;
        transform: translateY(-50%);
        color: #8b98ab;
        font-size: 20px;
        pointer-events: none;
      }
      .contact-field .form-control {
        width: 100%;
        height: 48px;
        padding: 10px 14px 10px 44px;
        border: 1px solid var(--contact-border);
        border-radius: 10px;
        background: #fbfcfe;
        color: var(--contact-navy);
        font-size: 14px;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
      }
      .contact-field textarea.form-control {
        min-height: 128px;
        height: auto;
        padding: 14px 16px;
        resize: vertical;
        line-height: 1.6;
      }
      .contact-field .form-control:focus {
        border-color: var(--contact-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(23, 105, 224, .10);
      }
      .contact-field .form-control::placeholder { color: #a2acba; }
      .contact-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 26px;
        border-top: 1px solid #edf0f4;
      }
      .contact-actions .btn {
        min-width: 112px;
        height: 46px;
        padding: 0 20px;
        border: 0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: none;
      }
      .contact-actions .btn-primary {
        color: #fff;
        background: var(--contact-blue);
        box-shadow: 0 10px 20px rgba(23, 105, 224, .2);
      }
      .contact-actions .btn-primary:hover { background: var(--contact-blue-dark); }
      .contact-actions .btn-light { color: #475467; background: #eef2f6; }
      .contact-actions .mdi { margin-right: 6px; font-size: 18px; vertical-align: -2px; }
      .contact-page .precision-footer-main {
        position: relative;
        overflow: hidden;
        margin: 0 28px;
        border: 1px solid #dfe5ec;
        border-bottom: 0;
        border-radius: 16px 16px 0 0;
        background: #fff !important;
        color: #475467 !important;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
      }
      .contact-page .precision-footer-main::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #ed1c24 0 120px, #ff656b 120px 170px, #202735 170px 100%);
      }
      .contact-page .precision-footer-main .footer-con {
        width: 100%;
        max-width: none;
        min-height: 0;
        margin: 0 auto;
        padding: 28px 32px 25px;
      }
      .contact-page .precision-footer-main .footer-con > .row {
        display: grid;
        grid-template-columns: 180px minmax(280px, 1.5fr) minmax(250px, 1fr) minmax(170px, .7fr);
        align-items: center;
        gap: 28px;
        margin: 0;
      }
      .contact-page .precision-footer-main .footer-con > .row > div {
        width: auto;
        max-width: none;
        padding: 0;
      }
      .contact-page .precision-footer-main .footer-con > .row > .col-lg-2:last-of-type { display: contents; }
      .contact-page .precision-footer-main .col-lg-2:first-child > div > div,
      .contact-page .precision-footer-main .col-lg-5 > div > div,
      .contact-page .precision-footer-main .col-lg-3 > div > div,
      .contact-page .precision-footer-main .precision-footer-links { padding-top: 0 !important; }
      .contact-page .precision-footer-main .col-lg-2:first-child > div > div {
        min-height: 78px;
        padding: 8px !important;
      }
      .contact-page .precision-footer-main img {
        width: 155px;
        max-height: 66px;
        object-fit: contain;
        object-position: left center;
      }
      .contact-page .precision-footer-main h5 {
        margin: 0 0 9px;
        color: var(--contact-navy) !important;
        font-size: 13px !important;
        line-height: 1.5;
      }
      .contact-page .precision-footer-main h6 {
        max-width: 620px;
        margin: 0;
        color: #667085 !important;
        font-size: 11px !important;
        font-weight: 400 !important;
        line-height: 1.65;
      }
      .contact-page .precision-footer-main a {
        color: #475467 !important;
        text-decoration: none;
        transition: color .18s ease, background-color .18s ease, transform .18s ease;
      }
      .contact-page .precision-footer-main a:hover { color: var(--contact-blue) !important; }
      .contact-page .precision-footer-main .col-lg-3 h5 {
        display: flex;
        align-items: center;
        gap: 9px;
      }
      .contact-page .precision-footer-main .col-lg-3 h5 > i {
        display: inline-grid;
        flex: 0 0 28px;
        width: 28px;
        height: 28px;
        place-items: center;
        border-radius: 8px;
        background: #fff0f1;
        color: #ed1c24;
        font-size: 15px;
      }
      .contact-page .precision-footer-links h5 { margin-bottom: 4px; }
      .contact-page .precision-footer-links h5 > a {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 30px;
        padding: 4px 7px;
        border-radius: 8px;
      }
      .contact-page .precision-footer-links h5 > a:hover {
        background: #fff3f4;
        color: #ed1c24 !important;
        transform: translateX(3px);
      }
      .contact-page .precision-footer-main .col-lg-3 {
        padding: 0 22px !important;
        border-right: 1px solid #e8ecf1;
        border-left: 1px solid #e8ecf1;
      }
      .contact-page .precision-footer-links h5 i {
        display: inline-grid;
        flex: 0 0 32px;
        width: 32px;
        height: 32px;
        place-items: center;
        border: 1px solid #ffd9dc;
        border-radius: 9px;
        background: #fff0f1;
        color: #ed1c24;
      }
      .contact-page .precision-footer-bottom {
        min-height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 28px 22px;
        padding: 10px 24px !important;
        border: 1px solid #dfe5ec;
        border-top: 1px solid #edf0f4;
        border-radius: 0 0 16px 16px;
        background: #f7f9fb !important;
        color: #8a94a4 !important;
        font-size: 11px;
        line-height: 1.5;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .07);
      }
      .contact-page .precision-footer-bottom b { color: #596476; font-weight: 600; }
      .contact-page #myBtn {
        right: 24px;
        bottom: 24px;
        width: 44px;
        height: 44px;
        border: 0;
        border-radius: 12px;
        background: #ed1c24;
        box-shadow: 0 10px 24px rgba(237, 28, 36, .24);
      }
      @media (max-width: 820px) {
        .contact-page .page-wrapper > .container-fluid { padding: 24px 16px; align-items: flex-start; }
        .contact-shell { grid-template-columns: 1fr; border-radius: 18px; }
        .contact-intro { padding: 34px 28px; }
        .contact-brand { margin-bottom: 30px; }
        .contact-assurance { margin-top: 30px; }
        .contact-form-panel { padding: 34px 28px; }
        .contact-page .precision-footer-main,
        .contact-page .precision-footer-bottom { margin-right: 16px; margin-left: 16px; }
        .contact-page .precision-footer-main .footer-con { padding: 28px 24px 25px; }
        .contact-page .precision-footer-main .footer-con > .row {
          grid-template-columns: 150px 1fr;
          gap: 24px 30px;
        }
        .contact-page .precision-footer-main .col-lg-3,
        .contact-page .precision-footer-main .col-lg-2:nth-of-type(4) { grid-column: 1 / -1; }
      }
      @media (max-width: 560px) {
        .contact-form-grid { grid-template-columns: 1fr; }
        .contact-field--full { grid-column: auto; }
        .contact-actions { flex-direction: column-reverse; }
        .contact-actions .btn { width: 100%; }
        .contact-page .precision-footer-main,
        .contact-page .precision-footer-bottom { margin-right: 10px; margin-left: 10px; }
        .contact-page .precision-footer-main .footer-con > .row { grid-template-columns: 1fr; gap: 22px; }
        .contact-page .precision-footer-main .footer-con > .row > div { grid-column: 1 !important; }
        .contact-page .precision-footer-main img { width: 135px; }
        .contact-page .precision-footer-main .col-lg-3 { padding: 0 !important; border: 0; }
        .contact-page .precision-footer-bottom { padding-inline: 18px !important; }
      }
    </style>
  </head>
  <body class="fix-header fix-sidebar contact-page">

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
        <div class="page-wrapper">
          <div class="container-fluid">
            <main class="contact-shell" aria-labelledby="contact-title">
              <aside class="contact-intro">
                <div class="contact-brand"><i class="mdi mdi-school"></i><span>ISUZU E-Learning</span></div>
                <h1 id="contact-title"><?php echo label('contact_locked'); ?></h1>
                <p>Share the details with our support team. We will review your request and get back to you as soon as possible.</p>
                <div class="contact-assurance">
                  <i class="mdi mdi-shield-check"></i>
                  <span>Your information is used only to help resolve this request.</span>
                </div>
              </aside>
              <section class="contact-form-panel" aria-label="Contact form">
                <div class="contact-form-header">
                  <span class="eyebrow">Support request</span>
                  <h2>Tell us how we can help</h2>
                  <p>Fields marked with an asterisk are required.</p>
                </div>
                <form id="contact_form" name="contact_form" autocomplete="off">
                  <div class="contact-form-grid">
                    <div class="contact-field">
                      <label for="contact_name"><?php echo label('com_contact'); ?><span class="required" aria-hidden="true">*</span></label>
                      <div class="contact-control-wrap">
                        <i class="mdi mdi-account-outline"></i>
                        <input type="text" class="form-control" required id="contact_name" name="contact_name" placeholder="Enter your name">
                      </div>
                    </div>
                    <div class="contact-field">
                      <label for="contact_tel"><?php echo label('com_tel'); ?><span class="required" aria-hidden="true">*</span></label>
                      <div class="contact-control-wrap">
                        <i class="mdi mdi-phone-outline"></i>
                        <input type="tel" class="form-control" required id="contact_tel" name="contact_tel" placeholder="Enter your phone number">
                      </div>
                    </div>
                    <div class="contact-field contact-field--full">
                      <label for="contact_mail"><?php echo label('com_mail'); ?><span class="required" aria-hidden="true">*</span></label>
                      <div class="contact-control-wrap">
                        <i class="mdi mdi-email-outline"></i>
                        <input type="email" class="form-control" required id="contact_mail" name="contact_mail" placeholder="name@example.com">
                      </div>
                    </div>
                    <div class="contact-field contact-field--full">
                      <label for="contact_msg"><?php echo label('contamess'); ?></label>
                      <textarea class="form-control" rows="5" id="contact_msg" name="contact_msg" placeholder="Describe the issue or question in detail..."></textarea>
                    </div>
                  </div>
                                    <input type="hidden" id="contact_about" name="contact_about" value="<?php echo $foote[0]['da_email_b']; ?>">
                                    <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $emp_id; ?>">
                  <div class="contact-actions">
                    <button type="button" class="btn btn-light close_contact"><?php echo label('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-send"></i><?php echo label('sent'); ?></button>
                  </div>
                </form>
              </section>
            </main>
          </div>

        </div>
            <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    </div>
    
      
    <div
        id="myModal_process"
        class="modal bs-example-modal-lg"
        role="dialog"
        aria-labelledby="smallModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body" align="center" style="max-height: 300px;">
              <div class="circle" id="circle-b">
                <strong></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- This is for the animation -->
    <script src="<?php echo REAL_PATH;?>/assets/plugins/aos/dist/aos.js"></script>
    <script src="<?php echo REAL_PATH;?>/assets/plugins/prism/prism.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/jquery-circle-progress-1.2.2/dist/circle-progress.js"></script>
    <script type="text/javascript">
    $('.slimtest1').perfectScrollbar();

        $(document).on('click', '.close_contact', function(){
            window.location.href = '<?php echo base_url()."dashboard/logout"; ?>';
        });
        $(document).on('submit', '#contact_form', function(event){
              var tid = $('#tid').val();
              event.preventDefault(); 
                            $("#myModal_process").modal('show');
                            $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/home/send_message",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType : "json",
                                xhr: function() {
                                  /*document.getElementById("progress_filedocument_div").style.display = "";
                                      var xhr = new window.XMLHttpRequest();
                                      xhr.upload.addEventListener("progress", function(evt) {
                                          if (evt.lengthComputable) {
                                              var percentComplete = (evt.loaded / evt.total) * 100;
                                              $('#txt_progress_filedocument').text(percentComplete.toFixed(2) + '%');

                                               $('.progress-bar-filedocument').animate({
                                                width: percentComplete + '%'
                                               }, {
                                                duration: 100
                                               });
                                              //Do something with upload progress here
                                          }
                                     }, false);
                                     return xhr;*///document.getElementById("progress_div").style.display = "";
                                      var xhr = new window.XMLHttpRequest();
                                      xhr.upload.addEventListener("progress", function(evt) {
                                          if (evt.lengthComputable) {
                                              var percentComplete = (evt.loaded / evt.total) * 100;
                                                    var progressBarOptions = {
                                                      startAngle: -1.55,
                                                      size: 200,
                                                        value: percentComplete.toFixed(0),
                                                        fill: {
                                                        color: '#ffa500'
                                                      }
                                                    }
                                                    console.log(percentComplete.toFixed(0));
                                                    $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                                      $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                                    });

                                                    $('#circle-b').circleProgress({
                                                      value : percentComplete.toFixed(0),
                                                      fill: {
                                                        color: '#FF0000'
                                                      }
                                                    });
                                          }
                                     }, false);
                                     return xhr;
                                },
                  success:function(data)
                  {
                                    $( document.body ).css( 'pointer-events', '' );
                                    $('#myModal_process').modal('hide');
                                    
                                    $("#myModal_process").removeClass("in");
                                    $("#myModal_process").css("display","none");
                    if(data.status=="2"){
                        swal(
                            '<?php echo label("sent_msg"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                          window.location.href = '<?php echo base_url()."dashboard/logout"; ?>';
                        })
                    }else if(data.status=="11"){
                        swal({
                            title: '<?php echo label("datauser_notfound"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                   
                  }
                });
            });
    </script>
  </body>
</html>
