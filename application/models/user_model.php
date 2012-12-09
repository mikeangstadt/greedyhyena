<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'base_model.php';

class user_model extends base_model
{
    public function user_model()
    {
        $this->table_name = "user_table";     
    }
    // --------------------------------------------------------------------

	function getContactInfo($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			return $query->row();
		}
	}

	// --------------------------------------------------------------------

	function password_reset($email, $random_passkey)
	{
		$this->db->where('email', $email);
		$this->db->where('access_level != 0'); // they allowed to login?
		$this->db->set('password_reset', $random_passkey);
		$this->db->update($this->table_name);

		if ($this->db->affected_rows() != 0)
		{
			return $this->get_contact_id($email);
		}
		else
		{
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	function get_contact_id($email)
	{
		$this->db->where('email', $email);
		$this->db->limit(1); // nobody should have the same id... but if they do, just grab the first one
		$client = $this->db->get($this->table_name);

		if ($client->num_rows() == 1)
		{
			return $client->row()->id;
		}
		else
		{
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	function password_confirm($id, $passkey)
	{
		$this->db->where('id', $id);
		$this->db->set('password_reset', $passkey);
		$password = $this->db->get($this->table_name);

		if ($password->num_rows() == 1)
		{
			return $password;
		}
		else
		{
			return FALSE;
		}
	}

	// --------------------------------------------------------------------

	function password_change($id, $new_password)
	{
		$this->load->library('encrypt');

		$this->db->where('id', $id);
		$this->db->set('password', $this->encrypt->encode($new_password));
		$this->db->update($this->table_name);

		$this->db->where('id', $id);
		$password = $this->db->get($this->table_name);

		if ($password->num_rows() == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
class User
  {
    var $id;
    var $display_name;
    var $user_id;
    var $last_login;
    var $password;
    var $user_role;
    var $api_key;
  }	
?>
