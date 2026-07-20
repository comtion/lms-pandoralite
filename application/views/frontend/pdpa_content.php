  <div>
    <div style="width:100%">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <?php
            if (@isset($arr_welcome)) {
              if ($arr_welcome['wctitle_a'] != "") {
                echo $arr_welcome['wctitle_a'];
              } else if ($arr_welcome['wctitle_b'] != "") {
                echo $arr_welcome['wctitle_b'];
              } else {
                echo label('conditions_of_use');
              }
            } else {
              echo label('conditions_of_use');
            }
            ?>
          </h4>
        </div>
        <div class="modal-body">
          <div class="card card-body">

            <?php if (isset($arr_welcome) && $arr_welcome['wcmessage_a'] != "") { ?>
              <div class="" id="page_a">
                <!-- Content -->
                <?php echo $arr_welcome['wcmessage_a']; ?>
              </div>
            <?php } ?>
            <?php if (isset($arr_welcome) && $arr_welcome['wcmessage_b'] != "") { ?>
              <div class="" id="page_b" <?php if ($arr_welcome['wcmessage_a'] == "") { ?>style="display: none;" <?php } ?>>
                <?php echo $arr_welcome['wcmessage_b']; ?>
              </div>
            <?php } ?>
          </div>

          <?php if (isset($arr_welcome) && $arr_welcome['wcmessage_a'] != "" && $arr_welcome['wcmessage_b'] != "") { ?>
            <nav aria-label="Page navigation example float-right" style="float: right;top: -10px;">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                  </a>
                </li>
                <li class="page-item link_a"><a class="page-link" onclick="ondisplay_chk('page_a','page_b')" href="#">1</a></li>
                <li class="page-item link_b"><a class="page-link" onclick="ondisplay_chk('page_b','page_a')" href="#">2</a></li>
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                  </a>
                </li>
              </ul>
            </nav>
          <?php } ?>
          <?php if (isset($arr_msg_confirm) && countArray($arr_msg_confirm) > 0) {
            $num = 1;
            foreach ($arr_msg_confirm as $key_confirm => $value_confirm) {
          ?>
              <input type="checkbox" name="check_confirm[]" value="1" id="check-<?php echo $num; ?>" class="filled-in chk-col-red">
              <label for="check-<?php echo $num;
                                $num++; ?>">
                <?php
                if ($lang == "thai") {
                  echo $value_confirm['conmsg_title_th'];
                } else if ($lang == "english") {
                  echo $value_confirm['conmsg_title_eng'];
                } else {
                  echo $value_confirm['conmsg_title_jp'];
                }
                ?>
              </label>
              <br>
          <?php   }
          } ?>
        </div>
        <div class="modal-footer">
          <button data-dismiss="modal" type="button" title="<?php echo label('m_cancel'); ?>" class="btn waves-effect waves-light btn-outline-danger btn-danger-hover float-right" onclick="window.location.href = '<?php echo base_url() . 'dashboard/logout'; ?>';"><i class="mdi mdi-file-document-box"></i><?php echo ' ' . label('m_cancel'); ?></button>
          <button data-dismiss="modal" type="button" title="<?php echo label('m_ok'); ?>" class="btn waves-effect waves-light btn-outline-success btn-danger-hover float-right" onclick="confirmfirsttime()" id="confirm_button"><i class="mdi mdi-file-document-box"></i><?php echo ' ' . label('m_ok'); ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>