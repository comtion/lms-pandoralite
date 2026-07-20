<?php
class News_model extends CI_Model {

      //  public $title;
      //  public $content;
      //  public $date;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function get_link_group( $lang )
        {
              $query = "select * from tbl_link_group where status = 1 and lang = '".$lang."'";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }
        public function get_link( $lang )
        {
              $query = "select * from tbl_link_details where status = 1 and lang = '".$lang."'";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_banner( $lang )
        {
              $query = "select * from tbl_news where status = 1 and cp_slidetop = 'y' and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_sticky desc, cpid desc limit 8";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_each( $lang, $cat , $limit )
        {
              $cat_id = $this->get_cat_id( $cat );
              $query = "select * from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and status = 1 and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_sticky desc, cpid desc limit ".$limit;
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_hot( $lang, $limit )
        {
              $query = "select * from tbl_news where  status = 1 and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_postview desc limit ".$limit;
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_postview( $id = "", $lang, $cat = "" , $limit )
        {     $add_con = " and cp_onoff = 'y' and status = 1 ";
              if( $id != "" ){
                $add_con .= "and cpid <> '".$id."' ";
              }
              if( $cat != "" && $cat != "all" ){
                $cat_id = $this->get_cat_id( $cat );
                $query = "select * from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."' ".$add_con." ORDER BY cp_postview desc limit ".$limit;
                //echo $query;
                $result = $this->db->query( $query );
              }else{
                $cat_id = $this->get_cat_id( $cat );
                $query = "select * from tbl_news where lang = '".$lang."' ".$add_con." ORDER BY cp_postview desc limit ".$limit;
                $result = $this->db->query( $query );
              }

              return  $result->result_array();
        }

        public function get_relate( $id = "", $lang, $cat = "" , $limit )
        {     $add_con = " and cp_onoff = 'y' and status = 1 ";
              if( $id != "" ){
                $add_con .= "and cpid <> '".$id."' ";
              }
              if( $cat != "" ){
                $cat_id = $this->get_cat_id( $cat );
                $query = "select * from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."' ".$add_con." ORDER BY cp_sticky desc, cpid desc limit ".$limit;
                //echo $query;
                $result = $this->db->query( $query );
              }else{
                $cat_id = $this->get_cat_id( $cat );
                $query = "select * from tbl_news where lang = '".$lang."' ".$add_con." ORDER BY cp_sticky desc, cpid desc limit ".$limit;
                $result = $this->db->query( $query );
              }

              return  $result->result_array();
        }

        public function get_list( $lang, $cat )
        {
              $cat_id = $this->get_cat_id( $cat );
              //echo $cat_id[0]['id'];
              $query = "select cpid, cp_category, cp_talkby, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate, cp_tag from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and status = 1 and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_sticky desc, cpid desc limit 12";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_list_video( $lang, $cat )
        {
              $cat_id = $this->get_cat_id( $cat );
              $query = "select cpid, cp_category, cp_talkby, cp_video, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate, cp_tag from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and status = 1 and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_sticky desc, cpid desc";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_list_video_filter( $lang, $cat, $start, $end, $cat_fil, $sortby )
        {
              $cat_id = $this->get_cat_id( $cat );
              $query = "select cpid, cp_category, cp_talkby, cp_video, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate, cp_tag from tbl_news";
              $query .= " where cp_category like '%".$cat_id[0]['id']."%' and status = 1 and cp_onoff = 'y' and lang = '".$lang."' ";
              if( $start !== "" ){
                if( $end !== "" ){
                  $query .= " and (cp_lastupdate BETWEEN '".$start."' AND '".$end."')";
                }else{
                  $query .= " and cp_lastupdate >= '".$start."'";
                }
              }
              if( $cat_fil !== "" ){
                $cat_fil_id = $this->get_cat_id( $cat_fil );
                $query .= " and cp_category like '%".$cat_fil_id."%'";
              }
              if( $sortby !== "" ){
                if( $sortby == "view"){
                  $query .= " ORDER BY cp_postview desc";
                }else if( $sortby == "date"){
                  $query .= " ORDER BY cp_edittime desc";
                }else if( $sortby == "suggess"){
                  $query .= " ORDER BY cp_sticky desc";
                }

              }
              //$query .= " ORDER BY cp_sticky desc, cpid desc";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }


        public function get_load( $lang, $cat )
        {
              $cat_id = $this->get_cat_id( $cat );
              $query = "select cpid,cp_category, cp_talkby, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate from tbl_news where cp_category like '%".$cat_id[0]['id']."%' and status = 1 and cp_onoff = 'y' and lang = '".$lang."' ORDER BY cp_sticky desc, cpid desc ";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_cat_id( $cat )
        {
              $query = "select id from tbl_cat where title = '".$cat."'";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }

        public function get_details( $id, $cat, $lang )
        {
              $cat_id = $this->get_cat_id( $cat );
              $query = "select *,'default' as step from tbl_news where status = 1 and cp_onoff = 'y' and cpid = ".$id;
              $query .= " union all (select *,'prev' as step from tbl_news where status = 1 and cp_onoff = 'y' and cpid < ".$id." and cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."' order by cpid desc limit 1) ";
              $query .= " union all (select *, 'next' as step from tbl_news where status = 1 and cp_onoff = 'y' and cpid > ".$id." and cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."' order by cpid asc limit 1)";

              $result = $this->db->query( $query );
              //print_r( $result->result_array() );
              return  $result->result_array();
        }
        public function get_details_mail( $id, $lang )
        {
              //$cat_id = $this->get_cat_id( $cat );
              $query = "select * from tbl_news where status = 1 and cp_onoff = 'y' and cpid = ".$id;

              $result = $this->db->query( $query );
              //print_r( $result->result_array() );
              return  $result->result_array();
        }
        public function get_all_cat()
        {
              $query = "select * from tbl_cat ";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }
        public function get_result( $lang, $cat, $start, $end, $keyword, $search_head ){
          $cat_id = $this->get_cat_id( $cat );
          $query = "select cpid, cp_category, cp_talkby, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate, cp_tag from tbl_news";
          if( $cat == "" ){
            $query .= " where lang = '".$lang."'";
          }else{
            $query .= " where cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."'";
          }

          if( $start !== "" ){
            if( $end !== "" ){
              $query .= " and (cp_lastupdate BETWEEN '".$start."' AND '".$end."')";
            }else{
              $query .= " and cp_lastupdate >= '".$start."'";
            }
          }
          if( $keyword !== "" ){
            $query .= " and cp_titlehead like '%".$keyword."%' or cp_content like '%".$keyword."%'";
          }
          if( $search_head !== "" ){
            $query .= " and cp_titlehead like '%".$search_head."%' or cp_content like '%".$search_head."%'";
          }
          $query .= " and status = 1 and cp_onoff = 'y' ORDER BY cp_sticky desc, cpid desc";
          //echo $query;
          $result = $this->db->query( $query );
          return  $result->result_array();
        }
        public function get_result_tag( $lang, $cat, $tag ){
          $cat_id = $this->get_cat_id( $cat );
          $query = "select cpid, cp_category, cp_talkby, cp_titleimg, cp_titlehead, cp_titletext, cp_postview, cp_lastupdate, cp_tag from tbl_news";
          if( $cat == "all" ){
            $query .= " where lang = '".$lang."'";
          }else{
            $query .= " where cp_category like '%".$cat_id[0]['id']."%' and lang = '".$lang."'";
          }
          $query .= " and cp_tag like '%".$tag."%'";
          $query .= " and status = 1 and cp_onoff = 'y' ORDER BY cp_sticky desc, cpid desc";
          //echo $query;
          $result = $this->db->query( $query );
          return  $result->result_array();
        }
        public function update_postview( $id ){
              $query = "select cp_postview from tbl_news where cpid = '".$id."'";
              $result = $this->db->query( $query );

              $news = $result->result_array();
              $data = array();
              $data['cp_postview'] = intval( $news[0]['cp_postview'] )+ 1 ;
              $this->db->where('cpid', $id);
              $this->db->update('tbl_news', $data);
        }

        /*public function get_all_cat()
        {
              $query = "select * from tbl_cat where id <> 8";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }
        public function get_detail( $id, $code)
        {
          $return = array();
          $query = "select * from tbl_news where cp_code = '".$code."'";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();
          foreach( $ar_data as $data ){
            $return[$data['lang']] = $data ;
          }
          //print_r( $return );
          return $return;
        }
        public function get_code()
        {
          $return = array();
          $query = "select max(cp_code) as code from tbl_news ";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();
          $code = intval(str_replace('news','', $ar_data[0]['code'])) + 1;
          return 'news'.$code;
        }
        public function get_lang_tab( $id )
        {
          $return = array();
          $query = "select lang from tbl_news where cpid = '".$id."'";
          $result = $this->db->query( $query );
          $ar_data =  $result->result_array();
          $lang_tab = $ar_data[0]['lang'];
          return $lang_tab;
        }

        public function get_list( $cat )
        {
              $query = "select * from tbl_news where cp_category like '%".$cat."%' ORDER BY cp_sticky desc, cpid desc";
              $result = $this->db->query( $query );
              return  $result->result_array();
        }


      /*  public function insert_entry()
        {
                $this->title    = $_POST['title']; // please read the below note
                $this->content  = $_POST['content'];
                $this->date     = time();

                $this->db->insert('entries', $this);
        }

        public function update_entry()
        {
                $this->title    = $_POST['title'];
                $this->content  = $_POST['content'];
                $this->date     = time();

                $this->db->update('entries', $this, array('id' => $_POST['id']));
        } */

}
