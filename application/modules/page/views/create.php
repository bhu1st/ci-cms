<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one">Content</a></li>
		<li><a href="#two">Meta data</a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<script type="text/javascript">
function change_parent() {
	selected = document.getElementById('parent').selectedIndex;
	document.getElementById('pre_uri').innerHTML = '/'+document.getElementById('parent').options[selected].value;
}

$(document).ready(function(){
	$("#image")
	.after("<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='button' id='upload_now' value='  <?php echo __('Upload') ?>  ' />");
	$("#loading").hide();
	$("#upload_now").click(function(){
		ajaxFileUpload();
	});
	handleDeleteImage();
});

function handleDeleteImage() {
	$("a.ajaxdelete").click(function(){
		if (confirm("Delete image?"))
		{
		deleteImage(this);
		return false;
		} else {
		return false;
		}
	});
}
function deleteImage(obj) {
	var id = obj.id
	$.post('<?php echo site_url('admin/page/ajax_delete')?>',
		{ id: id},
		function(data){
			alert(data);
		}
	);
	$(obj).parent().hide();
}

function ajaxFileUpload() {
	$("#upload_now")
	.ajaxStart(function(){
		$("img#loading").show();
		$(this).hide();
	})
	.ajaxComplete(function(){
		$("img#loading").hide();
		$(this).show();
	});

	$.ajaxFileUpload
	(
		{
			url:'<?php echo site_url('admin/page/ajax_upload')?>',
			secureuri:false,
			fileElementId: 'image',
			dataType: 'json',
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}else
					{
						$("#image_list").append("<div><input type='hidden' name='image_ids[]' value='"+data.imageid+"' /><a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/m') ?>/"+data.image+"\\'></a>');return false;\">"+data.image+"</a> - <a href='#'  class=\"ajaxdelete\" id='"+data.imageid+"' ><?php echo __('Delete image')?></a></div>\n");
						$("#image").val("");
						handleDeleteImage();
						
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	return false;
}
</script>

<h1 id="edit"><?=__("Create New Page")?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/page/create')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo $this->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="Save page" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/page')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?=__("To create a new page, just fill in your content below and click 'Save page'.<br />If you want to save your progress without publishing the page, Select 'Draft' status.</p>")?>

		<label for="title"><?=__("Page Title")?>:</label>
		<input type="text" name="title" value="" id="title" class="input-text" /><br />
		

		
		<label for="uri"><?=__("SEF address")?>:</label>
		<input type="text" name="uri" value="" id="uri" class="input-text" /><br />
		
		<label for="parent_id"><?=__("Parent")?>: </label>
		<select name="parent_id" class="input-select" />
		<option value='0'/>
		<?php if ($parents = $this->pages->list_pages()) : ?>
		<?php foreach ($parents as $parent):?>

		<option value="<?=$parent['id']?>"><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		
		<?php endforeach;?>
		<?php endif; ?>
		</select>
		<br />
			
		<label for="status">Status:</label>
		<select name="status" id="status" class="input-select">
			<option value="0">Draft</option>
			<option value="1" selected>Published</option>
		</select><br />
		
		<label for="body">Page Content:</label>
		<textarea name="body" class="input-textarea"></textarea><br />

		<div id='image_list'>
		<div style="visibility: hidden">Available images:</div>
		<?php if ($images) : ?>
		<?php foreach($images as $image): ?>
		<div><input type='hidden' name='image_ids[]' value='<?php echo $image['id'] ?>' /><a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<a href=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\'><img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/m')?>/<?php echo $image['file'] ?>\' /></a>');return false;"><?php echo $image['file'] ?></a> - <a href="<?php echo site_url('admin/page/removeimg/' . $image['id']) ?>" class="ajaxdelete" id="<?php echo $image['id'] ?>"><?php echo __("Delete image") ?></a></div>
		<?php endforeach; ?>
		<?php endif;?>
		</div>
		
		<label for="image"><?=__("Image")?></label>
		<input type="file" name="image" class="input-file" id="image"/><br />
		</div>
		<div id="two">
		
			<label for="meta_keywords">Page keywords:</label>
			<input type="text" name="meta_keywords" value="" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description">Page description:</label>
			<input type="text" name="meta_description" value="" id="meta_description" class="input-text" />
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->