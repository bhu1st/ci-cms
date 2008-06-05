<!-- [Content] start -->
<div class="content wide">

<h1 id="login">Login</h1>
<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<form class="login" action="<?=site_url('admin/login')?>" method="post" accept-charset="utf-8">
	<fieldset>
		<label for="username">Username:</label>
		<input type='text' name='username' id='username' class="input-text" /><br />
		<label for="password">Password:</label>
		<input type="password" name="password" value="" id="password" class="input-text" />
		<br class="clearfloat" />
		<p><input type="submit" name="submit" value="Login &raquo;" id="submit" class="input-submit" /></p>
	</fieldset>
</form>

</div>
<!-- [Content] end -->
