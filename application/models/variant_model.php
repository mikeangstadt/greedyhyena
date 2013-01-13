<?php
/* 
 * written by: Michael Angstadt
 * date: 12/12/11
 * variant_model 
 */
require_once 'base_model.php';

class variant_model extends base_model
{
    public function variant_model()
    {
        $this->table_name = "variant_table";     
    }
	
	//increment the specified variant's conversion count
	public function addConversion($variant)
	{
		$updateVariant;
		
		$updateVariant["id"] = $variant->id;
		$updateVariant["conversions"] = intval($variant->conversions) + 1;
		
		$this->UpdateOrInsert($updateVariant);
	}
	
	//increment the specified variant's impression count
	public function addView($variant)
	{
		$updateVariant;

		$updateVariant["id"] = $variant->id;
		$updateVariant["views"] = intval($variant->views) + 1;
		$this->UpdateOrInsert($updateVariant);
	}
	
	//increment the specific variant's earned revenue value by
	//the supplied value
	public function addRevenue($variant, $revenue)
	{
		$updateVariant;
		
		$updateVariant["id"] = $variant->id;
		$updateVariant["revenue"] = floatval($variant->revenue) + $revenue;
		
		$this->UpdateOrInsert($updateVariant);
	}
}

//a container class to house
//variant records fetched & passed
//to the database
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
	
	//compute the conversion rate for
	//this specific instance
	public function ConversionRate()
	{
		if(isset($views) && $views > 0)
			return $conversions / $views;
		else
			return "N/A";
	}
  }	
?>
