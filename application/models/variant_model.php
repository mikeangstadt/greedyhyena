<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_model.php';

class variant_model extends base_model
{
    public function variant_model()
    {
        $this->table_name = "variant_table";     
    }
	
	public function addConversion($variant)
	{
		$updateVariant;
		
		$updateVariant["id"] = $variant->id;
		$updateVariant["conversions"] = intval($variant->conversions) + 1;
		
		$this->UpdateOrInsert($updateVariant);
	}
	
	public function addView($variant)
	{
		$updateVariant;

		$updateVariant["id"] = $variant->id;
		$updateVariant["views"] = intval($variant->views) + 1;
		$this->UpdateOrInsert($updateVariant);
	}
	
	public function addRevenue($variant, $revenue)
	{
		$updateVariant;
		
		$updateVariant["id"] = $variant->id;
		$updateVariant["revenue"] = floatval($variant->revenue) + $revenue;
		
		$this->UpdateOrInsert($updateVariant);
	}
}
class Variant
  {
    var $id;
    var $test_id;
    var $display_name;
    var $value;
    var $replaceCSS;
    var $views;
    var $conversions;
    var $revenue;
    var $date_created;
    var $active;
	
	public function ConversionRate()
	{
		if(isset($views) && $views > 0)
			return $conversions / $views;
		else
			return "N/A";
	}
  }	
?>
