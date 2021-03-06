<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xim="http://www.ximdex.com/ximdex">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$title}</title>

	<link rel="icon" href="{$_URL_ROOT}/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="{$_URL_ROOT}/favicon.ico" type="image/x-icon" />
	<link href='{$_URL_ROOT}/xmd/style/fonts.css' rel='stylesheet' type='text/css'>

	<link href="{$_URL_ROOT}/xmd/style/login/login.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="{EXTENSIONS::Jquery()}"></script>
	{foreach from=$js_files item=js_file}
	<script type="text/javascript" src="{$js_file}"></script>
	{/foreach}

	{literal}
	<script type="text/javascript" language="javascript">
		$(document).ready(function() {
			$('input#user').focus();
		});
	</script>
	{/literal}
</head>

<body>
	{* IMPORTANT *}
	<xim:meta name="X-XIMDEX" content="401 Unauthorized"></xim:meta>

	<div id="contenedor">
		<h1><a href="http://www.ximdex.com" title="Access"><img src="{$_URL_ROOT}/xmd/images/login/logo_ximdex.png" alt="Ximdex logo" title="Visit our web" /></a></h1>

		<div id="acceso" class="triangle">
			<form action="{$_URL_ROOT}/?action=login&amp;method=check" method="post" name="access">
				<div class="error">{$message}</div>
				<p>
					<label for="user">{t}User{/t}</label>
					<input type="text" name="user" id="user" />
				</p>

				<p class="input-pass">
					<label for="password">{t}Password{/t}</label>
					<input type="password" name="password" id="password" onkeypress="capLock(event)" />
					<span id="capsLockAdvice" class="warning-msg">CapsLock enabled</span>
				</p>

				<p>
					<input type="submit" name="login" id="login" value="{t}Sign in{/t}" />
					<span>{t}Recommended browsers{/t}:<br/> Firefox &gt; 4, Chrome, Opera and Safari.</span>
				</p>
			</form>
		</div>

		<div id="mas_info" class="triangle">
			<h2 class="comunidad">{t}Join our community{/t}</h2>

			{*<p>Join our <a href="#">community</a>, consult your doubts, contribute with your suggestions. </p>*}

			<p>
				{t}Visit{/t} <a href="http://www.ximdex.com" target="_blank">{t}our website{/t}</a> {t}to learn more about the advantages of managing your projects with{/t} <strong>Ximdex</strong>.
			</p>

			<h2 class="siguenos">{t}Follow us{/t}</h2>

			<p>
				<a href="http://twitter.com/ximdex" target="_blank" title="{t}Visit Ximdex on Twitter{/t}" class="twit">Twitter</a>
			</p>

			<p>
				<a href="http://www.facebook.com/Ximdex" target="_blank" title="{t}Visit Ximdex on Facebook{/t}" class="face">Facebook</a>
			</p>

			<p>
				<a href="http://www.linkedin.com/companies/ximdex" target="_blank" title="{t}Visit Ximdex on LinkedIn{/t}" class="link">LinkedIn</a>
			</p>
		</div>

		<div id="news" class="news">
			{$news_content}
		</div>
</body>

</html>
