<div class="white-area-content">
<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-user"></span> <?php echo lang("ctn_1") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("admin/add_menu") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_380") ?></a>
</div>
</div>

<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
  <li><a href="<?php echo site_url("admin") ?>"><?php echo lang("ctn_1") ?></a></li>
  <li class="active"><?php echo lang("ctn_381") ?></li>
</ol>

<p><?php echo lang("ctn_382") ?></p>

<hr>

<table class="table table-bordered table-striped table-hover">
<tr class="table-header"><td><?php echo lang("ctn_81") ?></td><td><?php echo lang("ctn_52") ?></td></tr>
<?php foreach($menus->result() as $r) : ?>
<tr><td><?php echo $r->name ?></td><td><a href="<?php echo site_url("admin/edit_menu/" . $r->ID) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_55") ?>"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("admin/delete_menu/" . $r->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_57") ?>"><span class="glyphicon glyphicon-trash"></span></a></td></tr>
<?php endforeach; ?>
</table>

</div>