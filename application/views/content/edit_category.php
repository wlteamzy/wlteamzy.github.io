<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_399") ?></div>
    <div class="db-header-extra"> 
    </div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("content/edit_category_pro/" . $category->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_82") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="name" value="<?php echo $category->name ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_430") ?></label>
                    <div class="col-md-9 ui-front">
                    	<img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $category->image ?>"><br />
                        <input type="file" class="form-control" name="userfile">
                        <span class="help-block"><?php echo lang("ctn_431") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_432") ?></label>
                    <div class="col-md-9">
                        <textarea name="desc" id="project-description"><?php echo $category->description ?></textarea>
                    </div>
            </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_433") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '100'});
});
</script>