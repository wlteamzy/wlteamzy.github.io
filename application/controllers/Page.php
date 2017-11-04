<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("content_model");
		$this->load->model("user_model");

		//if (!$this->user->loggedin) $this->template->error(lang("error_1"));

	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("content" => array("pages" => 1)));

		$this->template->loadContent("page/index.php", array(
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
				'<a href="'.site_url("page/view/" . $r->ID).'">'.$r->title.'</a>',
				$r->summary,
				'<a href="'.site_url("page/view_cat/" . $r->categoryid).'">'.$r->catname."</a>",
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				'<a href="'.site_url("page/view/" . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_448").'"><span class="glyphicon glyphicon-list"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function view($id, $page_int =0) 
	{
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script>
			'
		);
		$page_int = intval($page_int);
		$this->template->loadData("activeLink", 
			array("content" => array("pages" => 1)));
		$id = intval($id);
		$page = $this->content_model->get_content_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_90"));
		}

		$page = $page->row();
		if($page->loggedin) {
			if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		}
		if(!empty($page->seo_title)) {
			$this->template->loadData("page_title", $page->seo_title);
		}

		if(!empty($page->seo_description)) {
			$this->template->loadData("page_desc", $page->seo_description);
		}

		if($page->loggedin) {
			// Check user roles
			$roles = $this->content_model->get_page_user_roles($id);
			// Check user has all roles
			$required = "<b>".lang("error_91")."</b>: ";
			$role_flag = false;;
			if($roles->num_rows() == 0) {
				$role_flag = true;
			} else {
				foreach($roles->result() as $r) {
					$required .= $r->name . ", ";
					if($r->ID == $this->user->info->user_role) {
						$role_flag = true;
					}
				}
			}

			$required .= "<br /><br /><b>".lang("error_92")."</b>: ";
			// Check user group
			$group_flag = false;
			$groups = $this->content_model->get_page_user_groups($id);
			$user_groups = $this->user_model->get_user_groups($this->user->info->ID);
			if($groups->num_rows() == 0) {
				$group_flag = true;
			} else {
				foreach($groups->result() as $r) {
					$required .= $r->name . ", ";
					foreach($user_groups->result() as $rr) {
						if($r->groupid == $rr->groupid) {
							$group_flag = true;
						}
					}
				}
			}

			// Check premium Plans
			$required .= "<br /><br /><b>".lang("error_93")."</b>: ";
			$plans_flag = false;
			$plans = $this->content_model->get_page_user_plans($id);
			if($plans->num_rows() == 0) {
				$plans_flag = true;
			} else {
				foreach($plans->result() as $r) {
					$required .= $r->name . ", ";
					if($r->planid == $this->user->info->premium_planid) {
						$plans_flag = true;
					}
				}
			}

			if($page->type) {
				if(!$role_flag) {
					$this->template->error(lang("error_94") . " <br /><br />" . $required);
				}
				if(!$group_flag) {
					$this->template->error(lang("error_95") . " <br /><br />" . $required);
				}
				if(!$plans_flag) {
					$this->template->error(lang("error_96") . " <br /><br />" . $required);
				}
			} else {
				if(!$role_flag && !$group_flag && !$plans_flag) {
					$this->template->error(lang("ctn_97") . " <br /><br />" . $required);
				}
			}
		}

		$comments = array();
		if($page->comments && $this->settings->info->comments) {
			$comments = $this->content_model->get_page_comments($id, $page_int);

			$this->load->library('pagination');
			$config['base_url'] = site_url("page/view/" . $id);
			$config['total_rows'] = $this->content_model
				->get_comment_count($id);
			$config['per_page'] = 10;
			$config['uri_segment'] = 4;

			include (APPPATH . "/config/page_config.php");
			$this->pagination->initialize($config); 

		
		}

		$this->template->loadContent("page/view.php", array(
			"page" => $page,
			"comments" => $comments
			)
		);
	}

	public function categories() 
	{
		$this->template->loadData("activeLink", 
			array("content" => array("cats" => 1)));

		$categories = $this->content_model->get_categories();

		$this->template->loadContent("page/cats.php", array(
			"categories" => $categories
			)
		);
	}

	public function view_cat($id) 
	{
		$this->template->loadData("activeLink", 
			array("content" => array("cats" => 1)));
		$id = intval($id);
		$category = $this->content_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_89"));
		}

		$category = $category->row();

		$this->template->loadContent("page/view_cat.php", array(
			"category" => $category
			)
		);

	}

	public function cat_page($id) 
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
				 	"content_pages.last_updated" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->content_model
				->get_total_pages_cat_count($id)
		);
		$pages = $this->content_model->get_content_pages_cat($id, $this->datatables);

		foreach($pages->result() as $r) {
			$this->datatables->data[] = array(
				'<img src="'.base_url().$this->settings->info->upload_path_relative."/".$r->image.'" class="page-image">',
				'<a href="'.site_url("page/view/" . $r->ID).'">'.$r->title.'</a>',
				$r->summary,
				date($this->settings->info->date_format, $r->last_updated),
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp))
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_comment($id) 
	{
		if(!$this->settings->info->comments) {
			$this->template->error(lang("error_98"));
		}
		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		$id = intval($id);
		$page = $this->content_model->get_content_page($id);
		if($page->num_rows() == 0) {
			$this->template->error(lang("error_90"));
		}

		$page = $page->row();
		if(!empty($page->seo_title)) {
			$this->template->loadData("page_title", $page->seo_title);
		}

		if(!empty($page->seo_description)) {
			$this->template->loadData("page_desc", $page->seo_description);
		}

		// Check user roles
		$roles = $this->content_model->get_page_user_roles($id);
		// Check user has all roles
		$required = "<b>".lang("error_91")."</b>: ";
		$role_flag = false;;
		if($roles->num_rows() == 0) {
			$role_flag = true;
		} else {
			foreach($roles->result() as $r) {
				$required .= $r->name . ", ";
				if($r->ID == $this->user->info->user_role) {
					$role_flag = true;
				}
			}
		}

		$required .= "<br /><br /><b>".lang("error_92")."</b>: ";
		// Check user group
		$group_flag = false;
		$groups = $this->content_model->get_page_user_groups($id);
		$user_groups = $this->user_model->get_user_groups($this->user->info->ID);
		if($groups->num_rows() == 0) {
			$group_flag = true;
		} else {
			foreach($groups->result() as $r) {
				$required .= $r->name . ", ";
				foreach($user_groups->result() as $rr) {
					if($r->groupid == $rr->groupid) {
						$group_flag = true;
					}
				}
			}
		}

		// Check premium Plans
		$required .= "<br /><br /><b>".lang("error_93")."</b>: ";
		$plans_flag = false;
		$plans = $this->content_model->get_page_user_plans($id);
		if($plans->num_rows() == 0) {
			$plans_flag = true;
		} else {
			foreach($plans->result() as $r) {
				$required .= $r->name . ", ";
				if($r->planid == $this->user->info->premium_planid) {
					$plans_flag = true;
				}
			}
		}

		if($page->type) {
			if(!$role_flag) {
				$this->template->error(lang("error_94") . " <br /><br />" . $required);
			}
			if(!$group_flag) {
				$this->template->error(lang("error_95") . " <br /><br />" . $required);
			}
			if(!$plans_flag) {
				$this->template->error(lang("error_96") . " <br /><br />" . $required);
			}
		} else {
			if(!$role_flag && !$group_flag && !$plans_flag) {
				$this->template->error(lang("error_97") . " <br /><br />" . $required);
			}
		}

		if(!$page->comments) {
			$this->template->error(lang("error_98"));
		}

		$message = $this->lib_filter->go($this->input->post("message"));
		if(empty($message)) {
			$this->template->error(lang("error_99"));
		}

		$this->content_model->add_comment(array(
			"userid" => $this->user->info->ID,
			"comment" => $message,
			"pageid" => $id,
			"timestamp" => time()
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_49"));
		redirect(site_url("page/view/" . $id));
			
	}

	public function delete_comment($id, $hash) {
		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$comment = $this->content_model->get_comment($id);
		if($comment->num_rows() == 0) {
			$this->template->error(lang("error_100"));
		}
		$comment = $comment->row();
		if($comment->userid != $this->user->info->ID) {
			if(!$this->common->has_permissions(array(
				"admin", "content_manager"), $this->user)) {
					$this->template->error(lang("error_101"));
			}
		}

		$this->content_model->delete_comment($id);
		$this->session->set_flashdata("globalmsg", lang("success_50"));
		redirect(site_url("page/view/" . $comment->pageid));
	}

}

?>