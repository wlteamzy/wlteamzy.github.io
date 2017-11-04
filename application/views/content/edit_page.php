<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_399") ?></div>
    <div class="db-header-extra"> 
    <?php if($this->common->has_permissions(array("admin", "content_manager", "content_worker"), $this->user)) : ?>
        <a href="<?php echo site_url("content/add_page") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_400") ?></a>
    <?php endif; ?>
    </div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("content/edit_page_pro/" . $page->ID), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_401") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="title" value="<?php echo $page->title ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_402") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="summary" value="<?php echo $page->summary ?>">
                        <span class="help-block"><?php echo lang("ctn_403") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_404") ?></label>
                    <div class="col-md-9 ui-front">
                        <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $page->image ?>"><br />
                        <input type="file" name="userfile" class="form-control">
                        <span class="help-block"><?php echo lang("ctn_405") ?></span>
                    </div>
            </div>
            
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_399") ?></label>
                    <div class="col-md-9">
                        <textarea name="content" id="project-description"><?php echo $page->content ?></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_406") ?></label>
                    <div class="col-md-9">
                        <select name="categoryid" class="form-control">
                        <?php foreach($categories->result() as $r) : ?>
                            <option value="<?php echo $r->ID ?>" <?php if($r->ID == $page->categoryid) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <h3><?php echo lang("ctn_407") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_408") ?></label>
                    <div class="col-md-9">
                        <input type="checkbox" name="loggedin" value="1" <?php if($page->loggedin) echo"checked" ?>> <?php echo lang("ctn_409") ?>
                        <span class="help-block"><?php echo lang("ctn_410") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_411") ?></label>
                    <div class="col-md-9">
                        <select name="user_roles[]" multiple class="form-control chosen-select-no-single" id="user_roles" data-placeholder="<?php echo lang("ctn_412") ?>">
                            <?php foreach($user_roles->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) : ?>selected="selected"<?php endif; ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_413") ?></label>
                    <div class="col-md-9">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="user_groups" data-placeholder="<?php echo lang("ctn_414") ?>">
                            <?php foreach($groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) : ?>selected="selected"<?php endif; ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_415") ?></label>
                    <div class="col-md-9">
                        <select name="premium_plans[]" multiple class="form-control chosen-select-no-single" id="premium_plans" data-placeholder="<?php echo lang("ctn_416") ?>">
                            <?php foreach($plans->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>" <?php if(isset($r->cid)) : ?>selected="selected"<?php endif; ?>><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_417") ?></label>
                    <div class="col-md-9">
                        <select name="type" class="form-control">
                            <option value="0"><?php echo lang("ctn_418") ?></option>
                            <option value="1" <?php if($page->type == 1) echo "selected" ?>><?php echo lang("ctn_419") ?></option>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_420") ?></span>
                    </div>
            </div>
            <h3><?php echo lang("ctn_421") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_422") ?></label>
                    <div class="col-md-9">
                        <input type="checkbox" name="comments" value="1" <?php if($page->comments) echo "checked" ?>> <?php echo lang("ctn_423") ?>
                    </div>
            </div>
            <h3><?php echo lang("ctn_424") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_425") ?></label>
                    <div class="col-md-9">
                        <input type="text" name="seo_title" class="form-control" value="<?php echo $page->seo_title ?>">
                        <span class="help-block"><?php echo lang("ctn_426") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_427") ?></label>
                    <div class="col-md-9">
                        <input type="text" name="seo_description" class="form-control" value="<?php echo $page->seo_description ?>">
                        <span class="help-block"><?php echo lang("ctn_428") ?></span>
                    </div>
            </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_434") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '250'});
$(".chosen-select-no-single").chosen({
    disable_search_threshold:10
});
});
</script>