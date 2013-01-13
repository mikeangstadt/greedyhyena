<?php
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * campaigns_model to organize groups of tests
 * across an entire campaign
 */
require_once 'base_model.php';

class campaigns_model extends base_model
{
    public function campaigns_model()
    {
        $this->table_name = "campaigns_table";     
    }
}

//a container class used to fetch
//campaign records from the database
class Campaign
  {
    var $id;
    var $user_id;
    var $display_name;
    var $setting_blob;
    var $date_created;
  }	
?>
