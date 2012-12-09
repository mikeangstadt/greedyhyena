<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_model.php';

class test_model extends base_model
{
    public function test_model()
    {
        $this->table_name = "test_table";     
    }
}
class Test
  {
    var $id;
    var $user_id;
    var $display_name;
    var $date_created;
    var $active;
    var $stError;
    var $randFactor;
	
	public function GetVariants()
	{
		return $this->variant_model->GetAllBy(array("test_id"=>$this->id));
	}
	
	public function AverageConversionRate()
	{
		$sum;
		$variants = $this->GetVariants();
		
		if(sizeof($variants) <= 0)
			return "N/A";
		
		foreach($variants as $variant)
		{
			$sum += $variant->ConversionRate();
		}
		
		return $sum / sizeof($variants);
	}
  }	
?>
