<?php
/**
 * Facebook Fanbox module
 * @license 		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @package    Facebook Fanbox
 * @link       http://www.khawaib.co.uk
 * @license    http://www.khawaib.co.uk
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );
$fanboxhtml = modFacebookFanboxHelper::getFanbox( $params );
require( JModuleHelper::getLayoutPath( 'mod_fbfanbox' ) );
?>