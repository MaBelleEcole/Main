<?php
/**
* Module SlideShow For Joomla 1.5.x
* Versi			: 1.0
* Created by	: Reza Erauansyah
* Email			: old_smu17@yahoo.com
* Created on	: 22 December 2009
* Las Modified 	: -
* URL			: www.camp26.biz
* License GPLv2.0 - http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$slidewidth 		= $params->get( 'slidewidth',600 );
$slideheight		= $params->get( 'slideheight',260 );
$baseurl 			= JURI::base();

$image_status1 	= $params->get( 'image_status1',1 );
$slide_img1 		= $params->get( 'slide_img1' );
$image_url1 		= str_replace('&', '&amp;', $params->get( 'image_url1' ));
$image_title1 		= $params->get( 'image_title1' );
$image_description1 		= $params->get( 'image_description1' );

$image_status2 	= $params->get( 'image_status2',1 );
$slide_img2 		= $params->get( 'slide_img2' );
$image_url2 		= str_replace('&', '&amp;', $params->get( 'image_url2' ));
$image_title2 		= $params->get( 'image_title2' );
$image_description2 		= $params->get( 'image_description2' );

$image_status3 	= $params->get( 'image_status3',1 );
$slide_img3 		= $params->get( 'slide_img3' );
$image_url3 		= str_replace('&', '&amp;', $params->get( 'image_url3' ));
$image_title3 		= $params->get( 'image_title3' );
$image_description3 		= $params->get( 'image_description3' );

$image_status4 	= $params->get( 'image_status4',1 );
$slide_img4 		= $params->get( 'slide_img4' );
$image_url4 		= str_replace('&', '&amp;', $params->get( 'image_url4' ));
$image_title4 		= $params->get( 'image_title4' );
$image_description4 		= $params->get( 'image_description4' );

$image_status5 	= $params->get( 'image_status5',1 );
$slide_img5 		= $params->get( 'slide_img5' );
$image_url5 		= str_replace('&', '&amp;', $params->get( 'image_url5' ));
$image_title5 		= $params->get( 'image_title5' );
$image_description5 		= $params->get( 'image_description5' );

$image_status6 	= $params->get( 'image_status6',1 );
$slide_img6 		= $params->get( 'slide_img6' );
$image_url6 		= str_replace('&', '&amp;', $params->get( 'image_url6' ));
$image_title6 		= $params->get( 'image_title6' );
$image_description6 		= $params->get( 'image_description6' );

$image_status7 	= $params->get( 'image_status7',1 );
$slide_img7 		= $params->get( 'slide_img7' );
$image_url7 		= str_replace('&', '&amp;', $params->get( 'image_url7' ));
$image_title7 		= $params->get( 'image_title7' );
$image_description7 		= $params->get( 'image_description7' );

$image_status8 	= $params->get( 'image_status8',1 );
$slide_img8 		= $params->get( 'slide_img8' );
$image_url8 		= str_replace('&', '&amp;', $params->get( 'image_url8' ));
$image_title8 		= $params->get( 'image_title8' );
$image_description8 		= $params->get( 'image_description8' );

$jkuery = $params->get( 'jkuery',1 );

echo "  <link rel=\"stylesheet\" href=\"".$baseurl."modules/mod_slideshow_camp26/laskar_slide/lasykarslide.css\" type=\"text/css\" />";

if($jkuery){
echo "  <script type=\"text/javascript\" src=\"".$baseurl."modules/mod_slideshow_camp26/laskar_slide/jquery-1.3.2.min.js\"></script>";}
echo "  <script type=\"text/javascript\" src=\"".$baseurl."modules/mod_slideshow_camp26/laskar_slide/jquery.easing.1.3.js\"></script>";
echo "  <script type=\"text/javascript\" src=\"".$baseurl."modules/mod_slideshow_camp26/laskar_slide/jquery.galleryview-1.1.js\"></script>";
echo "  <script type=\"text/javascript\" src=\"".$baseurl."modules/mod_slideshow_camp26/laskar_slide/jquery.timers-1.1.2.js\"></script>";

?>
<script type="text/javascript">
 jQuery.noConflict();
</script>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#photos').galleryView({
			panel_width: <?php echo $slidewidth;?>,
			panel_height: <?php echo $slideheight;?>,
			frame_width: 100,
			frame_height: 100
		});
	});

</script>
<div>
<div id="photos" class="galleryview">
			<?php if ($image_status1==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img1; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title1 ; ?></h2>
					  <p><?php echo $image_description1; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status2==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img2; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title2 ; ?></h2>
					  <p><?php echo $image_description2; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status3==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img3; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title3 ; ?></h2>
					  <p><?php echo $image_description3; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status4==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img4; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title4 ; ?></h2>
					  <p><?php echo $image_description4; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status5==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img5; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title5 ; ?></h2>
					  <p><?php echo $image_description5; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status6==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img6; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title6 ; ?></h2>
					  <p><?php echo $image_description6; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status7==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img7; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title7 ; ?></h2>
					  <p><?php echo $image_description7; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			<?php if ($image_status8==1) { ?>
				  <div class="panel">
					 <img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img8; ?>" /> 
					<div class="panel-overlay">
					  <h2 style="font-size:1.4em; color:#FFF; margin-bottom: 3px; margin-top: 3px;"><?php echo $image_title8 ; ?></h2>
					  <p><?php echo $image_description8; ?></p>
					</div>
				  </div>
			<?php } else { ?>
			<?php } ?>
			
			
		<ul class="filmstrip">
			<?php if ($image_status1==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img1; ?>" height="100" width="100" alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status2==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img2; ?>"  height="100" width="100" alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status3==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img3; ?>" height="100" width="100"  alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status4==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img4; ?>"  height="100" width="100" alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status5==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img5; ?>" height="100" width="100"  alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status6==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img6; ?>" height="100" width="100"  alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status7==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img7; ?>" height="100" width="100"  alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
			<?php if ($image_status8==1) { ?>
				<li><img src="<?php echo $baseurl?>/media/camp26_slide/<?php echo $slide_img8; ?>"  height="100" width="100" alt="" title="" /></li>
			<?php } else { ?>
			<?php } ?>
		</ul>	
		
		
</div>
<div style="clear: both;"></div>
<span style="font-size: 10px;">modul by : <a href="http://www.camp26.biz">camp26</a></span>
</div>


