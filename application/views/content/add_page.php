<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_399") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("content/add_page") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_400") ?></a>
    </div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("content/add_page_pro"), array("class" => "form-horizontal")) ?>
			<div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_401") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="title" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_402") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="text" class="form-control" name="summary" value="">
                        <span class="help-block"><?php echo lang("ctn_403") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_404") ?></label>
                    <div class="col-md-9 ui-front">
                        <input type="file" name="userfile" class="form-control">
                        <span class="help-block"><?php echo lang("ctn_405") ?></span>
                    </div>
            </div>
            
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_399") ?></label>
                    <div class="col-md-9">
                        <textarea name="content" id="project-description"></textarea>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_406") ?></label>
                    <div class="col-md-9">
                        <select name="categoryid" class="form-control">
                        <?php foreach($categories->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <h3><?php echo lang("ctn_407") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_408") ?></label>
                    <div class="col-md-9">
                        <input type="checkbox" name="loggedin" checked value="1" > <?php echo lang("ctn_409") ?>
                        <span class="help-block"><?php echo lang("ctn_410") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_411") ?></label>
                    <div class="col-md-9">
                        <select name="user_roles[]" multiple class="form-control chosen-select-no-single" id="user_roles" data-placeholder="<?php echo lang("ctn_412") ?>">
	                        <?php foreach($user_roles->result() as $r) : ?>
	                        	<option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
	                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_413") ?></label>
                    <div class="col-md-9">
                        <select name="user_groups[]" multiple class="form-control chosen-select-no-single" id="user_groups" data-placeholder="<?php echo lang("ctn_414") ?>">
                            <?php foreach($groups->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_415") ?></label>
                    <div class="col-md-9">
                        <select name="premium_plans[]" multiple class="form-control chosen-select-no-single" id="premium_plans" data-placeholder="<?php echo lang("ctn_416") ?>">
                            <?php foreach($premium_plans->result() as $r) : ?>
                                <option value="<?php echo $r->ID ?>"><?php echo $r->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_417") ?></label>
                    <div class="col-md-9">
                        <select name="type" class="form-control">
                            <option value="0"><?php echo lang("ctn_418") ?></option>
                            <option value="1"><?php echo lang("ctn_419") ?></option>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_420") ?></span>
                    </div>
            </div>
            <h3><?php echo lang("ctn_421") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_422") ?></label>
                    <div class="col-md-9">
                        <input type="checkbox" name="comments" value="1" checked> <?php echo lang("ctn_423") ?>
                    </div>
            </div>
            <h3><?php echo lang("ctn_424") ?></h3>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_425") ?></label>
                    <div class="col-md-9">
                        <input type="text" name="seo_title" class="form-control">
                        <span class="help-block"><?php echo lang("ctn_426") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-3 label-heading"><?php echo lang("ctn_427") ?></label>
                    <div class="col-md-9">
                        <input type="text" name="seo_description" class="form-control">
                        <span class="help-block"><?php echo lang("ctn_428") ?></span>
                    </div>
            </div>
<input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_400") ?>" />
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