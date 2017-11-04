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


<hr>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open(site_url("admin/edit_menu_pro/" . $menu->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_383") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="email-in" name="name" value="<?php echo $menu->name ?>">
        </div>
</div>
<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_384") ?></label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="email-in" name="icon" value="<?php echo $menu->icon ?>">
            <span class="help-block"><?php echo lang("ctn_385") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_386") ?></label>
        <div class="col-md-9">
            <input type="checkbox" id="email-in" name="dropdown" value="1" <?php if($menu->dropdown) echo "checked" ?>>
            <span class="help-block"><?php echo lang("ctn_387") ?></span>
        </div>
</div>
<h3><?php echo lang("ctn_388") ?></h3>
<div id="menu_links">
<?php $count = 0; ?>
<?php foreach($links->result() as $r) : ?>
    <?php $count++; ?>
    <div class="form-group" id="link-<?php echo $count ?>">
        <label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_389") ?> #<?php echo $count ?></label>
        <div class="col-md-3">
            <input type="text" class="form-control" id="email-in" name="menu_link_name_<?php echo $count ?>" placeholder="<?php echo lang("ctn_390") ?>" value="<?php echo $r->name ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="email-in" name="menu_link_url_<?php echo $count ?>" placeholder="<?php echo lang("ctn_391") ?>" value="<?php echo $r->url ?>">
        </div>
        <div class="col-md-3">
            <input type="button" class="btn btn-danger btn-xs" value="<?php echo lang("ctn_392") ?>" onclick="remove_link(<?php echo $count ?>)">
        </div>
    </div>
<?php endforeach; ?>
</div>

<input type="hidden" name="menu_link_count" id="menu_link_count" value="<?php echo $count ?>">
<input type="button" onclick="add_menu_link()" value="<?php echo lang("ctn_393") ?>" class="btn btn-info btn-xs">
<hr>


 <input type="submit" class="btn btn-primary btn-sm form-control" value="<?php echo lang("ctn_394") ?>" />
<?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">

    function remove_link(count) 
    {
        $('#link-' + count).remove();
    }

	function add_menu_link() 
	{
		var count = $('#menu_link_count').val();
		count++;
		$('#menu_link_count').val(count);
		var html = '<div class="form-group" id="link-'+count+'">'+
        		'<label for="email-in" class="col-md-3 label-heading"><?php echo lang("ctn_389") ?> #'+count+'</label>'+
        	'<div class="col-md-3">'+
            '<input type="text" class="form-control" id="email-in" name="menu_link_name_'+count+'" placeholder="<?php echo lang("ctn_390") ?>">'+
        	'</div>'+
        	'<div class="col-md-3">'+
            '<input type="text" class="form-control" id="email-in" name="menu_link_url_'+count+'" placeholder="<?php echo lang("ctn_391") ?>">'+
        '</div>'+
	'</div>';
	$('#menu_links').append(html);
	}
</script>