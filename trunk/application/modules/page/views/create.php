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
</script>

<h1 id="edit">Create New Page</h1>

<form class="edit" action="<?=site_url('admin/page/create')?>" method="post" accept-charset="utf-8">
		
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
		
		<p>To create a new page, just fill in your content below and click 'Publish'.<br />
		If you want to save your progress without publishing the page, click 'Save as Draft' button.</p>

		<label for="title">Page Title:</label>
		<input type="text" name="title" value="" id="title" class="input-text" /><br />
		
		<label for="menu_title">Navigation Title:</label>
		<input type="text" name="menu_title" value="" id="menu_title" class="input-text" /><br />
		
		<label for="uri">SEF adress:</label>
		<input type="text" name="uri" value="" id="uri" class="input-text" /><br />
		<?php /* 
		<label for="parent">Page Parent:</label>
		<select name="parent" id="parent" onchange="change_parent();" class="input-select">
			<option value="" selected="selected">/</option>
			<option value="home/">-- Home</option>
			<option value="about/">-- About</option>
			<option value="about/who/">---- Who Are We</option>
			<option value="about/history/">---- Our History</option>
		</select><br /> --> */ ?>
		
		<label for="status">Status:</label>
		<select name="status" id="status" class="input-select">
			<option value="0">Draft</option>
			<option value="1">Published</option>
		</select><br />
		
		<label for="body">Page Content:</label>
		<textarea name="body" class="input-textarea"></textarea>
		
		</div>
		<div id="two">
		
			<label for="meta_keywords">Page keywords:</label>
			<input type="text" name="meta_keywords" value="" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description">Page description:</label>
			<input type="text" name="meta_description" value="" id="meta_description" class="input-text" />
			
		</div>
	</form>
<script>
	var tabs = new Control.Tabs('tabs',{
		defaultTab: 'last'
	})
	tabs.setActiveTab('one');
</script>
</div>
<!-- [Content] end -->