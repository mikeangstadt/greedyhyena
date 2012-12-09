<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class base_model extends CI_Model
{
    var $table_name = "";
    var $table_pk = "id";

    //gets all of the records from the database
    function GetAll()
    {
        $query = $this->db->select('*')->from($this->table_name)->get();
        return $query->result();
    }

    function GetNextId()
    {
        $query = $this->db->select('*')->from($this->table_name);
        
        $query = $this->db->order_by("id", "desc");
        
        $query = $this->db->limit(1);
        
        $query = $this->db->get();
        
        $lastUser =  $query->result();
        
        return $lastUser[0]->id + 1;
    }

    //gets all of the records based on some condition from the database
    function GetAllBy($conditions)
    {
         return $this->FetchAll($this->table_name, $conditions);
    }

    //insert an element into the table_name table
    //PRE: $element is of type of data in the database
    function Insert($element)
    {
        $this->db->insert($this->table_name, $element);
    }

    //delete all elements that match the $conditions past
    function DeleteAll($conditionals)
    {

        $fetchArrayKeys = array_keys($conditionals);
        
        for($i = 0; $i < count($conditionals); $i+=1)
        {
            $this->db->where($fetchArrayKeys[$i], $conditionals[$fetchArrayKeys[$i]]);
        }

        $this->db->delete($this->table_name);
    }


    function Update($element)
    {
       $this->db->where($this->table_pk, $element[$this->table_pk]);
       $this->db->update($this->table_name, $element);
    }

    //if the record exists, update it
    //otherwise, insert it.
    function UpdateOrInsert($element)
    {
       if(!array_key_exists ("id", $element) || $element["id"] == null)
       {
          $this->Insert($element);
       }
       else
       {
          $this->db->select("*")->from($this->table_name)->where($this->table_pk, $element[(string)$this->table_pk]);
          $query = $this->db->get();

          if($query->result() != null)
          {
              $this->Update($element);
          }
          else
          {
              $this->Insert($element);
          }
       }
    }

    function FetchAll($table, $conditionals)
    {
        $this->db->select('*')->from($table);
        $fetchArrayKeys = array_keys($conditionals);

        for($i = 0; $i < count($conditionals); $i+=1)
        {
            $this->db->where((string)$fetchArrayKeys[$i], (string)$conditionals[$fetchArrayKeys[$i]]);
        }

        $query = $this->db->get();

        return $query->result();
    }
}
?>
