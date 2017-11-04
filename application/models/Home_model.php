<?php

class Home_Model extends CI_Model 
{

	public function get_home_stats() 
	{
		return $this->db->get("home_stats");
	}

	public function update_home_stats($stats) 
	{
		$this->db->where("ID", 1)->update("home_stats", array(
			"google_members" => $stats->google_members,
			"facebook_members" => $stats->facebook_members,
			"twitter_members" => $stats->twitter_members,
			"total_members" => $stats->total_members,
			"new_members" => $stats->new_members,
			"active_today" => $stats->active_today,
			"timestamp" => time()
			)
		);
	}

	public function get_email_template($id) 
	{
		return $this->db->where("ID", $id)->get("email_templates");
	}

	public function get_recent_pages($limit) 
	{
		return $this->db->select("content_pages.ID, content_pages.summary,
			content_pages.image, content_pages.title,
			users.ID as userid, users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = content_pages.userid")
			->order_by("content_pages.ID", "DESC")
			->limit($limit)
			->get("content_pages");
	}

	public function get_site_links() 
	{
		return $this->db->select("site_menu_links.name, site_menu_links.url,
			site_menu_links.ID, 
			site_menus.ID as menuid, site_menus.name as menu_name,
			site_menus.dropdown, site_menus.icon")
			->join("site_menus", "site_menus.ID = site_menu_links.menuid")
			->order_by("site_menus.ID")
			->get("site_menu_links");
	}

}

?>