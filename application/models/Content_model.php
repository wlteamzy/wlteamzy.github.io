<?php

class Content_Model extends CI_Model 
{

	public function get_categories() 
	{
		return $this->db->get("content_categories");
	}

	public function get_category($id) 
	{
		return $this->db->where("ID", $id)->get("content_categories");
	}

	public function delete_category($id)
	{
		$this->db->where("ID", $id)->delete("content_categories");
	}

	public function add_category($data) 
	{
		$this->db->insert("content_categories", $data);
	}

	public function update_category($id, $data) 
	{
		$this->db->where("ID", $id)->update("content_categories", $data);
	}

	public function add_page($data) 
	{
		$this->db->insert("content_pages", $data);
		return $this->db->insert_id();
	}

	public function add_page_roles($data) 
	{
		$this->db->insert("content_page_roles", $data);
	}

	public function add_page_groups($data) 
	{
		$this->db->insert("content_page_groups", $data);
	}

	public function add_page_plans($data) 
	{
		$this->db->insert("content_page_plans", $data);
	}

	public function get_total_pages_cat_count($id) 
	{
		$s = $this->db->where("categoryid", $id)
			->select("COUNT(*) as num")->get("content_pages");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_page_user_roles($id) 
	{
		return $this->db
			->select("user_roles.ID, user_roles.name, user_roles.admin,
				user_roles.admin_members, user_roles.admin_settings,
				user_roles.admin_payment, user_roles.banned, 
				user_roles.content_manager,
				user_roles.content_worker")
			->where("content_page_roles.pageid", $id)
			->join("user_roles", "user_roles.ID = content_page_roles.roleid")
			->get("content_page_roles");
	}

	public function get_page_user_groups($id) 
	{
		return $this->db
			->select("content_page_groups.ID, content_page_groups.groupid, 
				content_page_groups.pageid,
				user_groups.name")
			->where("content_page_groups.pageid", $id)
			->join("user_groups", "user_groups.ID = content_page_groups.groupid")
			->get("content_page_groups");
	}

	public function get_page_user_plans($id) 
	{
		return $this->db
			->select("content_page_plans.ID, content_page_plans.pageid, 
				content_page_plans.planid,
				payment_plans.name")
			->where("content_page_plans.pageid", $id)
			->join("payment_plans", "payment_plans.ID = content_page_plans.planid")
			->get("content_page_plans");
	}

	public function get_content_pages_cat($id, $datatable) 
	{
		$datatable->db_order();
		$datatable->db_search(array(
			"users.username",
			"content_pages.title",
			"content_pages.summary",
			"content_categories.name"
			)
		);

		return $this->db
			->where("content_pages.categoryid", $id)
			->select("content_pages.title, content_pages.image, 
				content_pages.last_updated, content_pages.userid, 
				content_pages.summary, content_pages.categoryid,
				content_pages.comments, content_pages.ID,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				content_categories.name as catname")
			->join("users", "users.ID = content_pages.userid")
			->join("content_categories", "content_categories.ID = content_pages.categoryid")
			->limit($datatable->length, $datatable->start)
			->get("content_pages");
	}

	public function get_total_pages_count() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("content_pages");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_content_pages($datatable) 
	{
		$datatable->db_order();
		$datatable->db_search(array(
			"users.username",
			"content_pages.title",
			"content_pages.summary",
			"content_categories.name"
			)
		);

		return $this->db
			->select("content_pages.title, content_pages.image, 
				content_pages.last_updated, content_pages.userid, 
				content_pages.summary, content_pages.categoryid,
				content_pages.comments, content_pages.ID,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				content_categories.name as catname")
			->join("users", "users.ID = content_pages.userid")
			->join("content_categories", "content_categories.ID = content_pages.categoryid")
			->limit($datatable->length, $datatable->start)
			->get("content_pages");
	}

	public function get_content_page($id) 
	{
		return $this->db
			->where("content_pages.ID", $id)
			->select("content_pages.title, content_pages.image, 
				content_pages.last_updated, content_pages.userid, 
				content_pages.summary, content_pages.categoryid,
				content_pages.loggedin,
				content_pages.comments, content_pages.ID, content_pages.content,
				content_pages.type, content_pages.seo_title, 
				content_pages.seo_description,
				users.ID as userid, users.username, users.avatar, 
				users.online_timestamp,
				content_categories.name as catname")
			->join("users", "users.ID = content_pages.userid")
			->join("content_categories", "content_categories.ID = content_pages.categoryid")
			->get("content_pages");
	}

	public function delete_page($id) 
	{
		$this->db->where("ID", $id)->delete("content_pages");
	}

	public function get_page_roles($pageid) 
	{
		return $this->db
			->select("user_roles.ID, user_roles.name, content_page_roles.ID as cid")
			->join("content_page_roles", "content_page_roles.roleid = user_roles.ID AND content_page_roles.pageid = " . $pageid, "left outer")
			->get("user_roles");
	}

	public function get_page_groups($pageid) 
	{
		return $this->db
			->select("user_groups.ID, user_groups.name, content_page_groups.ID as cid")
			->join("content_page_groups", "content_page_groups.groupid = user_groups.ID AND content_page_groups.pageid = " . $pageid, "left outer")
			->get("user_groups");
	}

	public function get_page_plans($pageid) 
	{
		return $this->db
			->select("payment_plans.ID, payment_plans.name, content_page_plans.ID as cid")
			->join("content_page_plans", "content_page_plans.planid = payment_plans.ID AND content_page_plans.pageid = " . $pageid, "left outer")
			->get("payment_plans");
	}

	public function delete_page_roles($id) 
	{
		$this->db->where("pageid", $id)->delete("content_page_roles");
	}

	public function delete_page_groups($id) 
	{
		$this->db->where("pageid", $id)->delete("content_page_groups");
	}

	public function delete_page_plans($id) 
	{
		$this->db->where("pageid", $id)->delete("content_page_plans");
	}

	public function update_page($id, $data) 
	{
		$this->db->where("ID", $id)->update("content_pages", $data);
	}

	public function get_page_comments($id, $page) 
	{
		return $this->db
			->select("users.ID as userid, users.username, users.avatar,
				users.online_timestamp,
				content_page_comments.ID, content_page_comments.comment,
				content_page_comments.timestamp")
			->where("content_page_comments.pageid", $id)
			->join("users", "users.ID = content_page_comments.userid")
			->order_by("content_page_comments.ID", "DESC")
			->limit(10, $page)
			->get("content_page_comments");
	}

	public function get_comment_count($id) 
	{
		$s = $this->db
			->where("pageid", $id)
			->select("COUNT(*) as num")
			->get("content_page_comments");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function add_comment($data) 
	{
		$this->db->insert("content_page_comments", $data);
	}

	public function get_comment($id) 
	{
		return $this->db->where("ID", $id)->get("content_page_comments");
	}

	public function delete_comment($id) 
	{
		$this->db->where("ID", $id)->delete("content_page_comments");
	}

}

?>