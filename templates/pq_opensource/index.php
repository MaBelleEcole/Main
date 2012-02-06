<?php
//Custom joomla template by www.pqsofts.com
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); ?>
<link rel="stylesheet" href="<?php echo $this->baseurl?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/css/joomla.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/css/navigation.css" type="text/css" />
<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]>
</script>
<?php
if ($this->countModules( 'left and right' )==0) $contentwidth="100";
if ($this->countModules( 'left or right' )==1) $contentwidth="80";
if ($this->countModules( 'left and right' )==1) $contentwidth="60";
?>
</head>
<body>
<!--[if lt IE 7]>
  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
      <div style='width: 75px; float: left;'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-warning.jpg' alt='Warning!'/></div>
      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>You are using an outdated browser</div>
        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>For a better experience using this site, please upgrade to a modern web browser.</div>
      </div>
      <div style='width: 75px; float: left;'><a href='http://www.firefox.com' target='_blank'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
      <div style='width: 75px; float: left;'><a href='http://www.browserforthebetter.com/download.html' target='_blank'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/></a></div>
      <div style='width: 73px; float: left;'><a href='http://www.apple.com/safari/download/' target='_blank'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
      <div style='float: left;'><a href='http://www.google.com/chrome' target='_blank'><img src='<?php echo $this->baseurl?>/templates/<?php echo $this->template?>/images/nomoreie6/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
    </div>
  </div>
<![endif]-->
<div id="wrap">
	<div id="container">
		<div id="container_t">
			<div id="container_tl">
				<div id="container_tr"></div>
			</div>
		</div>
	<div id="container_m">	
	<div id="header">
		<a id="logo" href="<?php echo $this->baseurl; ?>"></a>
		<?php if ($this->countModules ('top-menu' )) :?>
			<div id="top-menu">
				<jdoc:include type="modules" name="top-menu" style="none" />
			</div>
		<?php endif; ?>	

		<?php if ($this->countModules ('search' )) :?>
			<div id="search">
				<jdoc:include type="modules" name="search" style="none" />
			</div>
		<?php endif; ?>		
	</div>
	<div class="clear"></div>
	<?php if ($this->countModules ('nav' )) :?>
		<div id="nav">
			<jdoc:include type="modules" name="nav" style="none" />
		</div>
	<?php endif; ?>	
	<div class="clear"></div>
	<?php if($this->countModules('banner')) { ?>
	<div id="banner">
		<Jdoc:include type="modules" name="banner" style="none" />
	</div>
	<?php } ?>
	<div class="clear">&nbsp;</div>

	<?php if ($this->countModules( 'left' )) : ?>
	<div id="left">
			<jdoc:include type="modules" name="left" style="jom_rounded" />
	</div>
	<?php endif; ?>	
	<div id="content<?php echo $contentwidth; ?>">
		<div class="inside">
			<jdoc:include type="component" />
		</div>
	</div>
	<?php if ($this->countModules ( 'right' )) : ?>
	<div id="right">
		<jdoc:include type="modules" name="right" style="jom_rounded" />
	</div>
	<?php endif; ?>
	<div class="clear">&nbsp;</div>
	<div id="footer">
	<div class="padding">
		<div id="footer-nav">
			<jdoc:include type="modules" name="footer-nav" style="none" />
		</div>
			<!--You are not allowed to remove this link
			To remove this link please contact with us using "pqsofts@gmail.com" email address.
			-->
		<div class="cp">Copyright &copy; PQ Open Source 2009. All Rights Reserved. | Template by <a href="http://pqsofts.com">PQ Softs</a></div>
		<div class="clr"></div>
		<div id="validation">
		<a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo JURI::base(); ?>&amp;warning=1&amp;profile=css3&amp;usermedium=all" target="_blank" title="CSS Validity" style="text-decoration: none;">CSS Valid | </a>
		<a href="http://validator.w3.org/check/referer" target="_blank" title="XHTML Validity" style="text-decoration: none;">XHTML Valid | </a>
		<a href="#header">Top</a><br  />
		</div>
	</div>
	<div class="clear"></div>
	</div>
	<div class="clear"></div>
	</div>
			<div id="container_b">
			<div id="container_bl">
				<div id="container_br"></div>
			</div>
		</div>

	</div>
</div>
<div class="clear">&nbsp;</div>
</body>
</html>
