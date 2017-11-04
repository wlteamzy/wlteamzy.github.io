<?php

class User_Model extends CI_Model 
{

	public function getUser($email, $pass) 
	{
		return $this->db->select("ID")
		->where("email", $email)->where("password", $pass)->get("users");
	}

	public function get_user_by_id($userid) 
	{
		return $this->db->where("ID", $userid)->get("users");
	}

	public function get_user_by_username($username) 
	{
		return $this->db->where("username", $username)->get("users");
	}

	public function delete_user($id) 
	{
		$this->db->where("ID", $id)->delete("users");
	}

	public function get_new_members($limit) 
	{
		return $this->db->select("email, username, joined, oauth_provider")
		->order_by("ID", "DESC")->limit($limit)->get("users");
	}

	public function get_registered_users_date($month, $year) 
	{
		$s= $this->db->where("joined_date", $month . "-" . $year)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_oauth_count($provider) 
	{
		$s= $this->db->where("oauth_provider", $provider)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_total_members_count() 
	{
		$s= $this->db->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_active_today_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_new_today_count() 
	{
		$s= $this->db->where("joined >", time() - 3600*24)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_online_count() 
	{
		$s= $this->db->where("online_timestamp >", time() - 60*15)->select("COUNT(*) as num")->get("users");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_members($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"user_roles.name"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_admin($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"users.username",
			"users.first_name",
			"users.last_name",
			"user_roles.name",
			"users.email"
			)
		);

		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, users.online_timestamp, users.avatar,
			user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit($datatable->length, $datatable->start)
		->get("users");
	}

	public function get_members_by_search($search) 
	{
		return $this->db->select("users.username, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_username($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.username", $search)
		->get("users");
	}

	public function search_by_email($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.email", $search)
		->get("users");
	}

	public function search_by_first_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.first_name", $search)
		->get("users");
	}

	public function search_by_last_name($search) 
	{
		return $this->db->select("users.username, users.email, users.first_name, 
			users.last_name, users.ID, users.joined, users.oauth_provider,
			users.user_role, user_roles.name as user_role_name")
		->join("user_roles", "user_roles.ID = users.user_role", 
				 	"left outer")
		->limit(20)
		->like("users.last_name", $search)
		->get("users");
	}

	public function update_user($userid, $data) {
		$this->db->where("ID", $userid)->update("users", $data);
	}

	public function check_block_ip() 
	{
		$s = $this->db->where("IP", $_SERVER['REMOTE_ADDR'])->get("ip_block");
		if($s->num_rows() == 0) return false;
		return true;
	}

	public function get_user_groups($userid) 
	{
		return $this->db->where("user_group_users.userid", $userid)
			->select("user_groups.name,user_groups.ID as groupid")
			->join("user_groups", "user_groups.ID = user_group_users.groupid")
			->get("user_group_users");
	}

	public function check_user_in_group($userid, $groupid) 
	{
		$s = $this->db->where("userid", $userid)->where("groupid", $groupid)
			->get("user_group_users");
		if($s->num_rows() == 0) return 0;
		return 1;
	}

	public function get_default_groups() 
	{
		return $this->db->where("default", 1)->get("user_groups");
	}

	public function add_user_to_group($userid, $groupid) 
	{
		$this->db->insert("user_group_users", array(
			"userid" => $userid, 
			"groupid" => $groupid
			)
		);
	}

	public function add_points($userid, $points) 
	{
        $this->db->where("ID", $userid)
        	->set("points", "points+$points", FALSE)->update("users");
    }

    public function get_verify_user($code, $username) 
    {
    	return $this->db
    		->where("activate_code", $code)
    		->where("username", $username)
    		->get("users");
    }

    public function get_user_event($request) 
    {
    	return $this->db->where("IP", $_SERVER['REMOTE_ADDR'])
    		->where("event", $request)
    		->order_by("ID", "DESC")
    		->get("user_events");
    }

    public function add_user_event($data) 
    {
    	$this->db->insert("user_events", $data);
    }

    public function get_custom_fields($data) 
	{
		if(isset($data['register'])) {
			$this->db->where("register", 1);
		}
		return $this->db->get("custom_fields");
	}

	public function add_custom_field($data) 
	{
		$this->db->insert("user_custom_fields", $data);
	}

	public function get_custom_fields_answers($data, $userid) 
	{
		if(isset($data['edit'])) {
			$this->db->where("custom_fields.edit", 1);
		}
		return $this->db
			->select("custom_fields.ID, custom_fields.name, custom_fields.type,
				custom_fields.required, custom_fields.help_text,
				custom_fields.options,
				user_custom_fields.value")
			->join("user_custom_fields", "user_custom_fields.fieldid = custom_fields.ID
			 AND user_custom_fields.userid = " . $userid, "LEFT OUTER")
			->get("custom_fields");

	}

	public function get_user_cf($fieldid, $userid)
	{
		return $this->db
			->where("fieldid", $fieldid)
			->where("userid", $userid)
			->get("user_custom_fields");
	}

	public function update_custom_field($fieldid, $userid, $value) 
	{
		$this->db->where("fieldid", $fieldid)
			->where("userid", $userid)
			->update("user_custom_fields", array("value" => $value));
	}

	public function get_user_role($id) 
	{
		return $this->db->where("ID", $id)->get("user_roles");
	}

	public function get_user_roles() 
	{
		return $this->db->get("user_roles");
	}

	public function get_user_groups_all() 
	{
		return $this->db->get("user_groups");
	}

	public function get_user_group($id) 
	{
		return $this->db->where("ID", $id)->get("user_groups");
	}


}

?>