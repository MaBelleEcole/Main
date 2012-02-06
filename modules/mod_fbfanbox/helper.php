<?php
/**
 * Helper class for Facebook Fanbox module
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package		Facebook Fanbox
 * @link       	http://www.khawaib.co.uk
 * @license    	http://www.khawaib.co.uk
 */
class modFacebookFanboxHelper {
  /**
   * Retrieves the html
   *
   * @param array $params An object containing the module parameters
   * @access public
   */    
  function getFanbox( $params )   {
  	global $mainframe;
  	if ((!trim($params->get('api_key'))) || (!trim($params->get('profile_id')))) {
			$fanboxhtml = 'Please enter valid API Key from you Facebook API and your valid Profile ID.';
  	} else {
  		if (trim( $params->get( 'script_call' ) )) {
				$fanboxhtml = '<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>';
			}
			$fanboxhtml .= '<script type="text/javascript">FB.init("'.trim( $params->get( 'api_key' ) ).'");</script>';
			$fanboxhtml .= '<fb:fan profile_id="'.trim( $params->get( 'profile_id' ) ).'" stream="'.trim( $params->get( 'include_stream' ) ).'" connections="'.trim( $params->get( 'number_of_fans' ) ).'" width="'.trim( $params->get( 'boxwidth' ) ).'" height="'.trim( $params->get( 'boxheight' ) ).'"cs=';

			$fanboxhtml .= '></fb:fan>';
			if (trim( $params->get( 'link_to_page' ) )) {
				$fanboxhtml .= '<div style="font-size:8px; padding-left:10px"><a href="'.trim($params->get('link')).'"';
				if ($params->get('target')) { 
					$fanboxhtml .= 'target="_blank"';
				}
				$fanboxhtml .= '>'.trim( $params->get( 'profile_name' ) ).'</a> '.trim( $params->get( 'text_after_link' ) ).'</div>';
			}
	  }
	 
	  return $fanboxhtml;
  }
}
?>