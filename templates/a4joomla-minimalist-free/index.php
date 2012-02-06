<?php // no direct access 
defined( '_JEXEC' ) or die( 'Restricted access' ); 
$showLeftColumn = (bool) $this->countModules('left');
$showRightColumn = (bool) $this->countModules('right');
$showRightColumn &= JRequest::getCmd('layout') != 'form';
$showRightColumn &= JRequest::getCmd('task') != 'edit';
$margin = 20;
$topmenuHeight = 32;
$logoType = $this->params->get("logoType", "text");
$headline	= $this->params->get("headline");
$slogan	= $this->params->get("slogan");
$pageWidth	= $this->params->get("pageWidth", "960");
$rightColumnWidth	= $this->params->get("rightColumnWidth", "200");
$leftColumnWidth	= $this->params->get("leftColumnWidth", "200");
$logoWidth	= $this->params->get("logoWidth", "250");
$logoHeight	= $this->params->get("logoHeight", "100");
if($this->countModules('user4')){
$searchWidth = 240;
$sloganWidth = $pageWidth - $logoWidth - $searchWidth - 3*$margin;
} else {
$searchWidth = 0;
$sloganWidth = $pageWidth - $logoWidth - 2*$margin;
}
$headerrightWidth = $pageWidth - $logoWidth - $margin;
if($this->countModules('top')){
$topWidth = $pageWidth - $logoWidth - $margin;
$topHeight = 24;
} else {
$topWidth = 0;
$topHeight = 0;
}
$searchHeight = $logoHeight - $topHeight;
$sloganHeight = $searchHeight;
if ($showLeftColumn && $showRightColumn) {
   $contentWidth = $pageWidth - $leftColumnWidth - $rightColumnWidth - 3*$margin;
} elseif (!$showLeftColumn && $showRightColumn) {
   $contentWidth = $pageWidth - $rightColumnWidth - 2*$margin ;
} elseif ($showLeftColumn && !$showRightColumn) {
   $contentWidth = $pageWidth - $leftColumnWidth - 2*$margin ;
} else {
   $contentWidth = $pageWidth - $margin ;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/grey.css" type="text/css" />
<!--[if IE 6]>
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/ie6.css" type="text/css" />
<style type="text/css">
img, div, a, input { behavior: url(templates/<?php echo $this->template ?>/iepngfix.htc) }
</style>
<script src="templates/<?php echo $this->template ?>/js/iepngfix_tilebg.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/ie67.css" type="text/css" />
<![endif]-->

<style type="text/css">
 #logo {
    width:<?php echo $logoWidth; ?>px;
    height:<?php echo $logoHeight; ?>px;
 }
 #search {
   width:<?php echo $searchWidth; ?>px;
   height:<?php echo $searchHeight; ?>px;
 }
 #slogan {
    width:<?php echo $sloganWidth; ?>px;
    height:<?php echo $sloganHeight; ?>px;
 }
 #top {
    width:<?php echo $topWidth; ?>px;
    height:<?php echo $topHeight; ?>px;
 } 
 #headerright {
    width:<?php echo $headerrightWidth; ?>px;
    height:<?php echo $logoHeight; ?>px;
 } 
</style>
</head>
<body>


<div id="headerwrap" class="gainlayout">
  <div id="header" class="gainlayout" style="width:<?php echo $pageWidth; ?>px;">   
      <div id="logo">
         <?php if($logoType == "text") { ?>
			<h1><a href="<?php echo JURI::base(); ?>" title="<?php echo $headline; ?>"><?php echo $headline; ?></a></h1>
         <?php } else { ?>
		    <a href="<?php echo JURI::base(); ?>"><img src="templates/<?php echo $this->template ?>/images/logo.png" border="0" alt="" /></a>
         <?php } ?> 
      </div>
	  <div id="headerright" class="gainlayout">
	  <?php if($this->countModules('top')) : ?>
        <div id="top">
          <jdoc:include type="modules" name="top" style="xhtml" /> 
		  <div class="clr"></div>
        </div>
      <?php endif; ?>
      <div id="slogan">
          <h2><?php echo $slogan; ?></h2> 
      </div>
      <?php if($this->countModules('user4')) : ?>
        <div id="search">
          <jdoc:include type="modules" name="user4" style="xhtml" /> 
        </div>
      <?php endif; ?>
      <div class="clr"></div>
	  </div>
      <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<?php if($this->countModules('user3')) : ?>
  <div id="topmenuwrap" class="gainlayout">
         <div id="topmenu" class="gainlayout" style="width:<?php echo $pageWidth-$margin; ?>px;">
           <jdoc:include type="modules" name="user3" style="xhtml" />
           <div class="clr"></div>
         </div> 
  </div> 
<?php endif; ?>
<div id="contentwrap" class="gainlayout">
<div id="wrap" style="width:<?php echo $pageWidth; ?>px;">
  <div id="cbody" class="gainlayout">
  <?php if($showLeftColumn) : ?>
  <div id="sidebar" style="width:<?php echo $leftColumnWidth; ?>px;">     
      <jdoc:include type="modules" name="left" style="xhtml" />    
  </div>
  <?php endif; ?>
  <div id="content60" style="width:<?php echo $contentWidth; ?>px;">    
      <?php if($this->countModules('breadcrumb')) : ?>
      <div id="pathway">
        <jdoc:include type="module" name="breadcrumbs" style="none" />
      </div>
      <?php endif; ?> 
      <?php if ($this->getBuffer('message')) : ?>
				<div class="error">
					<h2>
						<?php echo JText::_('Message'); ?>
					</h2>
					<jdoc:include type="message" />
				</div>
			<?php endif; ?> 
      <div id="content">
      <jdoc:include type="component" /> 
      </div>    
  </div>
  <?php if($showRightColumn) : ?>
  <div id="sidebar-2" style="width:<?php echo $rightColumnWidth; ?>px;">     
      <jdoc:include type="modules" name="right" style="xhtml" />     
  </div>
  <?php endif; ?>
  <div class="clr"></div>
  </div>
<!--end of wrap-->
</div>
<!--end of contentwrap-->
</div>
<div id="footerwrap" class="gainlayout">
  <?php if($this->countModules('footer')) : ?>
  <div id="footer" style="width:<?php echo $pageWidth; ?>px;">     
      <jdoc:include type="modules" name="footer" style="xhtml" />    
  </div>
  <?php endif; ?>
  <div id="a4j"><a href="http://a4joomla.com/">Joomla template by a4joomla</a></div>
<!--end of footerwrap-->
</div>
</body>
</html>
