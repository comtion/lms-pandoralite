<?php
include_once "./application/libraries/AES.php";

class AES_model extends CI_Model {
        
        private $aes;
        
        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                $this->aes = new AES('artofgreenlife17');
        }
        
        public function encrypt($str) {
          return $this->aes->encrypt($str);
        }
        
        public function decrypt($str) {
          return $this->aes->decrypt($str);
        }
}
