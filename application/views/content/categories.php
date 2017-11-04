<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-file"></span> <?php echo lang("ctn_399") ?></div>
    <div class="db-header-extra"> <?php if($this->common->has_permissions(array("admin", "content_manager"), $this->user)) : ?><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"><?php echo lang("ctn_429") ?></button><?php endif; ?>
    </div>
</div>

<table class="table table-hover table-bordered table-striped">
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_430") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($categories->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $r->image ?>" class="content-category"></td><td><a href="<?php echo site_url("content/edit_category/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("content/delete_category/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo lang("ctn_317") ?>')" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>

</div>


<?php if($this->common->has_permissions(array("admin", "content_manager"), $this->user)) : ?>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> <?php echo lang("ctn_429") ?></h4>
      </div>
      <div class="modal-body">
         <?php echo form_open_multipart(site_url("content/add_category"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_81") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
                    <div class="col-md-8 ui-front">
                        <input type="file" class="form-control" name="userfile">
                        <span class="help-block"><?php echo lang("ctn_431") ?></span>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
                    <div class="col-md-8">
                        <textarea name="desc" id="project-description"></textarea>
                    </div>
            </div>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang("ctn_60") ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo lang("ctn_429") ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
CKEDITOR.replace('project-description', { height: '100'});
});
</script>
<?php endif; ?>