<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("content_model");
		$this->load->model("user_model");

		if (!$this->user->loggedin) $this->template->error(lang("error_1"));


		if(!$this->common->has_permissions(array(
				"admin", "content_manager", "content_worker"), $this->user)) {
				$this->template->error(lang("error_81"));
		}
	}


	public function index() 
	{	
		$this->template->loadData("activeLink", 
			array("content" => array("general" => 1)));

		$this->template->loadContent("content/index.php", array(
			)
		);
	}

	public function content_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("content_pages.last_updated", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"content_pages.title" => 0
				 ),
				 3 => array(
				 	"content_categories.name" => 0
				 ),
				 4 => array(
				 	"users.username" => 0
				 ),
				 5 => array(
				 	"content_pages.last_updated" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->content_model
				->get_total_pages_count()
		);
		$pages = $this->content_model->get_content_pages($this->datatables);

		foreach($pages->result() as $r) {
			$this->datatables->data[] = array(
				'<img src="'.base_url().$this->settings->info->upload_path_relative."/".$r->image.'" class="page-image">',
				$r->title,
				$r->summary,
				$r->catname,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				date($this->settings->info->date_format, $r->last_updated),
				'<a href="'.site_url("page/view/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_448").'"><span class="glyphicon glyphicon-list"></span></a> <a href="'.site_url("content/edit_page/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("content/delete_page/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function delete_page($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$page = $this->content_model->get_content_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_82"));
		}
		$page = $page->row();
		if($page->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array(
				"admin", "content_manager"), $this->user)) {
				$this->template->error(lang("error_81"));
			}
		}

		$this->content_model->delete_page($id);
		$this->session->set_flashdata("globalmsg", lang("success_43"));
		redirect(site_url("content"));
	}

	public function edit_page($id) 
	{
		$id = intval($id);
		$page = $this->content_model->get_content_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_82"));
		}
		$page = $page->row();

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("content" => array("general" => 1)));


		if($page->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array(
				"admin", "content_manager"), $this->user)) {
				$this->template->error(lang("error_81"));
			}
		}

		$categories = $this->content_model->get_categories();
		$user_roles = $this->user_model->get_user_roles();

		$roles = $this->content_model->get_page_roles($id);
		$groups = $this->content_model->get_page_groups($id);
		$plans = $this->content_model->get_page_plans($id);

		$this->template->loadContent("content/edit_page.php", array(
			"categories" => $categories,
			"user_roles" => $roles,
			"groups" => $groups,
			"plans" => $plans,
			"page" => $page
			)
		);


	}

	public function edit_page_pro($id) 
	{
		$this->load->model("funds_model");
		$id = intval($id);
		$page = $this->content_model->get_content_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_82"));
		}
		$page = $page->row();

		if($page->userid != $this->user->info->ID) {
			// Check permission
			if(!$this->common->has_permissions(array(
				"admin", "content_manager"), $this->user)) {
				$this->template->error(lang("error_81"));
			}
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$summary = $this->common->nohtml($this->input->post("summary"));
		$content = $this->lib_filter->go($this->input->post("content"));
		$categoryid = intval($this->input->post("categoryid"));
		$comments = intval($this->input->post("comments"));
		$user_roles = $this->input->post("user_roles");
		$user_groups = $this->input->post("user_groups");
		$premium_plans = $this->input->post("premium_plans");
		$type = intval($this->input->post("type"));
		$seo_title = $this->common->nohtml($this->input->post("seo_title"));
		$seo_desc = $this->common->nohtml($this->input->post("seo_description"));
		$loggedin = intval($this->input->post("loggedin"));

		if(empty($title)) {
			$this->template->error(lang("error_83"));
		}

		// Get category
		$category = $this->content_model->get_category($categoryid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}

		// Check user roles
		$roles= array();
		if($user_roles) {
			foreach($user_roles as $role) {
				$role = intval($role);
					if($role > 0) {
					$user_role = $this->user_model->get_user_role($role);
					if($user_role->num_rows() == 0) {
						$this->template->error(lang("error_85"));
					}
				}
				$roles[] = $role;
			}
		}

		// Check user groups
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $group) {
				$group = intval($group);
					if($group > 0) {
					$user_group = $this->user_model->get_user_group($group);
					if($user_group->num_rows() == 0) {
						$this->template->error(lang("error_86"));
					}
				}
				$groups[] = $group;
			}
		}

		// Check Premium Plans
		$plans= array();
		if($premium_plans) {
			foreach($premium_plans as $plan) {
				$plan = intval($plan);
					if($plan > 0) {
					$plan_r = $this->funds_model->get_plan($plan);
					if($plan_r->num_rows() == 0) {
						$this->template->error(lang("error_87"));
					}
				}
				$plans[] = $plan;
			}
		}

		// Upload image
		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $page->image;
		}

		// Add page
		$this->content_model->update_page($id, array(
			"title" => $title,
			"image" => $image,
			"summary" => $summary,
			"content" => $content,
			"comments" => $comments,
			"categoryid" => $categoryid,
			"timestamp" => time(),
			"userid" => $this->user->info->ID,
			"last_updated" => time(),
			"last_updated_userid" => $this->user->info->ID,
			"type" => $type,
			"seo_title" => $seo_title,
			"seo_description" => $seo_desc,
			"loggedin" => $loggedin
			)
		);

		// Wipe old user roles
		$this->content_model->delete_page_roles($id);

		// Add user role restrictions
		foreach($roles as $roleid) {
			$this->content_model->add_page_roles(array(
				"pageid" => $id,
				"roleid" => $roleid
				)
			);
		}

		$this->content_model->delete_page_groups($id);

		// Add user role restrictions
		foreach($groups as $groupid) {
			$this->content_model->add_page_groups(array(
				"pageid" => $id,
				"groupid" => $groupid
				)
			);
		}

		$this->content_model->delete_page_plans($id);

		// Add user role restrictions
		foreach($plans as $planid) {
			$this->content_model->add_page_plans(array(
				"pageid" => $id,
				"planid" => $planid
				)
			);
		}


		$this->session->set_flashdata("globalmsg", lang("success_44"));
		redirect(site_url("content"));
	}

	public function add_page() 
	{
		$this->load->model("funds_model");
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("content" => array("general" => 1)));

		$categories = $this->content_model->get_categories();
		$user_roles = $this->user_model->get_user_roles();
		$groups = $this->user_model->get_user_groups_all();
		$premium_plans = $this->funds_model->get_plans();
		$this->template->loadContent("content/add_page.php", array(
			"categories" => $categories,
			"user_roles" => $user_roles,
			"premium_plans" => $premium_plans,
			"groups" => $groups
			)
		);
	}

	public function add_page_pro() 
	{
		$this->load->model("funds_model");
		$title = $this->common->nohtml($this->input->post("title"));
		$summary = $this->common->nohtml($this->input->post("summary"));
		$content = $this->lib_filter->go($this->input->post("content"));
		$categoryid = intval($this->input->post("categoryid"));
		$comments = intval($this->input->post("comments"));
		$user_roles = $this->input->post("user_roles");
		$user_groups = $this->input->post("user_groups");
		$premium_plans = $this->input->post("premium_plans");
		$type = intval($this->input->post("type"));
		$seo_title = $this->common->nohtml($this->input->post("seo_title"));
		$seo_desc = $this->common->nohtml($this->input->post("seo_description"));
		$loggedin = intval($this->input->post("loggedin"));

		if(empty($title)) {
			$this->template->error(lang("error_83"));
		}

		// Get category
		$category = $this->content_model->get_category($categoryid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}

		// Check user roles
		$roles= array();
		if($user_roles) {
			foreach($user_roles as $role) {
				$role = intval($role);
					if($role > 0) {
					$user_role = $this->user_model->get_user_role($role);
					if($user_role->num_rows() == 0) {
						$this->template->error(lang("error_85"));
					}
				}
				$roles[] = $role;
			}
		}

		// Check user groups
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $group) {
				$group = intval($group);
					if($group > 0) {
					$user_group = $this->user_model->get_user_group($group);
					if($user_group->num_rows() == 0) {
						$this->template->error(lang("error_86"));
					}
				}
				$groups[] = $group;
			}
		}

		// Check Premium Plans
		$plans= array();
		if($premium_plans) {
			foreach($premium_plans as $plan) {
				$plan = intval($plan);
					if($plan > 0) {
					$plan_r = $this->funds_model->get_plan($plan);
					if($plan_r->num_rows() == 0) {
						$this->template->error(lang("error_87"));
					}
				}
				$plans[] = $plan;
			}
		}

		// Upload image
		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= "page_default.png";
		}

		// Add page
		$pageid = $this->content_model->add_page(array(
			"title" => $title,
			"image" => $image,
			"summary" => $summary,
			"content" => $content,
			"comments" => $comments,
			"categoryid" => $categoryid,
			"timestamp" => time(),
			"userid" => $this->user->info->ID,
			"last_updated" => time(),
			"last_updated_userid" => $this->user->info->ID,
			"type" => $type,
			"seo_title" => $seo_title,
			"seo_description" => $seo_desc,
			"loggedin" => $loggedin
			)
		);

		// Add user role restrictions
		foreach($roles as $roleid) {
			$this->content_model->add_page_roles(array(
				"pageid" => $pageid,
				"roleid" => $roleid
				)
			);
		}

		// Add user role restrictions
		foreach($groups as $groupid) {
			$this->content_model->add_page_groups(array(
				"pageid" => $pageid,
				"groupid" => $groupid
				)
			);
		}

		// Add premium plans restrictions
		foreach($plans as $planid) {
			$this->content_model->add_page_plans(array(
				"pageid" => $pageid,
				"planid" => $planid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_45"));
		redirect(site_url("content"));
	}

	public function categories() 
	{
		$this->template->loadData("activeLink", 
			array("content" => array("categories" => 1)));
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script>'
		);
		if(!$this->common->has_permissions(array(
			"admin", "content_manager"), $this->user)) {
			$this->template->error(lang("error_81"));
		}

		$categories = $this->content_model->get_categories();

		$this->template->loadContent("content/categories.php", array(
			"categories" => $categories
			)
		);
	}

	public function add_category() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "content_manager"), $this->user)) {
			$this->template->error(lang("error_81"));
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("desc"));

		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= "default.png";
		}

		if(empty($name)) {
			$this->template->error(lang("error_88"));
		}

		$this->content_model->add_category(array(
			"name" => $name,
			"description" => $desc,
			"image" => $image
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_46"));
		redirect(site_url("content/categories"));
	}

	public function delete_category($id, $hash) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "content_manager"), $this->user)) {
			$this->template->error(lang("error_81"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->content_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_89"));
		}

		$this->content_model->delete_category($id);
		$this->session->set_flashdata("globalmsg", lang("success_47"));
		redirect(site_url("content/categories"));
	}

	public function edit_category($id) 
	{
		$this->template->loadData("activeLink", 
			array("content" => array("categories" => 1)));
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script>'
		);
		if(!$this->common->has_permissions(array(
			"admin", "content_manager"), $this->user)) {
			$this->template->error(lang("error_81"));
		}
		$id = intval($id);
		$category = $this->content_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_89"));
		}

		$category = $category->row();
		$this->template->loadContent("content/edit_category.php", array(
			"category" => $category
			)
		);
	}

	public function edit_category_pro($id) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "content_manager"), $this->user)) {
			$this->template->error(lang("error_81"));
		}
		$id = intval($id);
		$category = $this->content_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_89"));
		}

		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("desc"));

		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|gif|jpeg|jpg",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $category->image;
		}

		if(empty($name)) {
			$this->template->error(lang("error_88"));
		}

		$this->content_model->update_category($id, array(
			"name" => $name,
			"description" => $desc,
			"image" => $image
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_48"));
		redirect(site_url("content/categories"));
	}

}

?>