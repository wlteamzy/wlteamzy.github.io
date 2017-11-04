<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <?php echo $page->title ?></div>
    <div class="db-header-extra form-inline">
    	<?php if($this->common->has_permissions(array("admin", "content_manager"), $this->user) || ($this->user->loggedin && $page->userid == $this->user->info->ID) ) : ?>
	    	<a href="<?php echo site_url("content/edit_page/" . $page->ID) ?>" class="btn btn-warning btn-xs" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("content/delete_page/" . $page->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" title="<?php echo lang("ctn_57") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>
	    <?php endif; ?>
    </div>
</div>

<p class="page-summary"><?php echo $page->summary ?></p>

<hr>

<?php echo $page->content ?>

</div>

<?php if($page->comments && $this->settings->info->comments) : ?>
<div class="white-area-content top-margin">
<div class="db-header clearfix">
    <div class="page-header-title"><?php echo lang("ctn_444") ?></div>
    <div class="db-header-extra form-inline">
    </div>
</div>

<?php foreach($comments->result() as $r) : ?>
<div class="media">
  <div class="media-left" style="width: 100px;">
    <?php echo $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)) ?>

  </div>
  <div class="media-body">
  	<?php if( ($this->user->loggedin && $r->ID == $this->user->info->ID) || $this->common->has_permissions(array("admin", "content_manager"), $this->user)): ?>
  	<div class="pull-right">
  	<a href="<?php echo site_url("page/delete_comment/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a>
  	</div>
  <?php endif; ?>
    <?php echo $r->comment ?>
    <p class="small-text"><?php echo date($this->settings->info->date_format, $r->timestamp) ?></p>
  </div>
</div>
<hr>
<?php endforeach; ?>

<div class="align-center">
<?php echo $this->pagination->create_links() ?>
</div>

<?php if($this->user->loggedin) : ?>
<h4><?php echo lang("ctn_445") ?></h4>
<?php echo form_open(site_url("page/add_comment/" . $page->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                <div class="col-md-12 ui-front">
                   <textarea name="message" id="msg-area"></textarea>
                </div>
        </div>
<p><input type="submit" class="form-control btn btn-primary btn-sm" value="<?php echo lang("ctn_446") ?>" /></p>
<?php echo form_close(); ?>
<?php endif; ?>

</div>
<?php endif; ?>
<script type="text/javascript">
CKEDITOR.replace('msg-area', { height: '150'});
</script>