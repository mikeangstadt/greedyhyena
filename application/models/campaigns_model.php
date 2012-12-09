<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_model.php';

class campaigns_model extends base_model
{
    public function campaigns_model()
    {
        $this->table_name = "campaigns_table";     
    }
}
class Campaign
  {
    var $id;
    var $user_id;
    var $display_name;
    var $setting_blob;
    var $date_created;
  }	
?>
