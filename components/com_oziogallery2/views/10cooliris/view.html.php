<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class OzioGalleryView10Cooliris extends JView
{
	function display( $tpl = null )
	{
	
		global $mainframe;
		$document 	= & JFactory::getDocument();
		$menus		= & JSite::getMenu();
		$menu		= $menus->getActive();

		$params = $mainframe->getParams('com_oziogallery2');
		
		$larghezza 			= $params->def('width', 640);
		$altezza 			= $params->def('height', 480);	
		$framecolor			= $params->def('framecolor');		
		$bkgndretro			= $params->def('bkgndretro');			
		$ordinamento 		= (int) $params->def('ordinamento');
		$rows 				= (int) $params->def('rows', 2);			
		$downloads 			= (int) $params->def('downloads', 0);
		$retrotext			= $params->def('retrotext');
		$flickr 			= (int) $params->def('flickr', 0);
		$xml_mode 			= (int) $params->def('xml_mode', 0);
		$user_id 			= $params->def('user_id');
		$set_id 			= $params->def('set_id');
		$group_id 			= $params->def('group_id');		
		$text				= $params->def('text', '');			
		$modifiche 			= (int) $params->def('modifiche', 0);			
		$folder				= $params->def('folder');
		$debug 				= (int) $params->def('debug');	
		$manualxmlname		= $params->def('manualxmlname', 'cooliris');
		$immaginesfondo		= $params->def('immaginesfondo');
		$larghezzaant		= $params->def('larghezzaant');
		$altezzaant			= $params->def('altezzaant');
		$distanzaoriz		= $params->def('distanzaoriz');
		$distanzavert		= $params->def('distanzavert');
		$titoloimg 			= (int) $params->def('titoloimg', 0);
		
		$framecolor 		= str_replace( '#', '', $framecolor );
		$bkgndretro 		= str_replace( '#', '', $bkgndretro );
		
		
		switch ($params->get( 'rotatoralign' ))
		{
			case '0': $float		= 'left'; 		break;
			case '1': $float		= 'right';		break;
			case '2': $float		= 'inherit';	break;			
			default:  $float		= 'inherit'; 	break;				
		}
		
		switch ($params->get( 'table' ))
		{
			case '0': $table		= 'left'; 		break;
			case '1': $table		= 'right';		break;
			case '2': $table		= 'center';		break;			
			default:  $table		= 'center'; 	break;				
		}		

		switch ($params->get( 'downloads' ))
		{
			case '0': $download		= 'false'; 		break;
			case '1': $download		= 'true';		break;
			default:  $download		= 'true'; 		break;				
		}


		switch ($params->get( 'flickr' ))
		{
			case '0': $flickrs		= 'false'; 		break;
			case '1': $flickrs		= 'true';		break;
			default:  $flickrs		= 'false'; 		break;				
		}	
/*
		switch ($params->get( 'ordinamento' ))
		{
			case '0': $ordinamento		= 'arsort($files)'; 	break;
			case '1': $ordinamento		= 'sort($files)';		break;
			case '2': $ordinamento		= 'asort($files)';		break;
			case '3': $ordinamento		= 'rsort($files)';		break;	
			case '4': $ordinamento		= 'shuffle($files)';	break;				
		}		
*/		
		$document->addScript(JURI::root(true).'/components/com_oziogallery2/assets/js/21/swfobject.js');
		$document->addCustomTag('
		<style type="text/css">
			.oziofloat {
				width: '.$larghezza.';
				height: '.$altezza.';
				margin: 0px auto;
				float:  '.$float.';
				}
			.oziotime {
                font-size: 0.8em;
				color:#ccc;	
				}
		</style>
		');		

		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );		
			
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$menu->name);
			}
			
		} else {
			$params->set('page_title',	JText::_('Ozio'));
		}

		$document->setTitle($params->get('page_title'));
		$document->setMetadata( 'keywords' , $params->get('page_title') );

		if ($mainframe->getCfg('MetaTitle') == '1') {
				$mainframe->addMetaTag('title', $params->get('page_title'));
		}

		
 if( $flickr == 0 ) :		

		jimport('joomla.filesystem.file'); 
		// creazione file xml al volo
    	$VAMBpathAssoluto = JPATH_SITE;
		$VAMBpathAssoluto = str_replace("\\", "/" , $VAMBpathAssoluto);	

		$path  = $VAMBpathAssoluto .'/'. $folder . '/';
		$dir_images = rtrim(JURI::root() . $folder) ;
		$dir_files = rtrim(JURI::root() . 'images/oziodownload') . '/';		
		
		$xmltitle = $menu->name;
		$xmltitle = str_replace( ' ', '', $xmltitle );
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		$xmltitle = preg_replace($regex, '', $xmltitle);

		
		$xmlnamesuff = $params->def('xmlnamesuff');
		$xmlnamesuff = str_replace( ' ', '', $xmlnamesuff );
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		$xmlnamesuff = preg_replace($regex, '', $xmlnamesuff);		
		
		if ($xmlnamesuff != null) :
			$xmlname = $xmltitle . '_'. $xmlnamesuff;
		else:
			$xmlname = $xmltitle;
		endif;
		
		// nome del file creato
		$filename 	= JPATH_SITE.'/components/com_oziogallery2/skin/cooliris/xml/cooliris_'. $xmlname .'.ozio';
        $foldername = $path;		
		$this->assignRef('nomexml' , 				$xmlname);

		if (JFolder::exists( $path ))
		{			

		if ( @filemtime($foldername) >= @filemtime($filename) ) 
		{	
		
		$thumb_sufix = ".th.";
// per nome file
	
		if ($hd = opendir($path)) {
		  $files = array();
			while (false !== ($file = readdir($hd))) { 
				if($file != '.' && $file != '..') {
					if (strpos($file, $thumb_sufix) === false) {
						if(is_file($path . $file) && preg_match('/\.(jpg|png|gif)$/i',$file)) {
						
						if( $ordinamento == 2 OR $ordinamento == 3 OR $ordinamento == 4) { 
							$files[] = array(filemtime($path.$file), $file);
						}
						if( $ordinamento == 0 OR $ordinamento == 1) { 
							$files[] = array(($path.$file), $file);
						}							
							
							
					}
				}
			}
		}
			closedir($hd);
	}

		
		if(count($files)) {
		
			if($ordinamento == 0 OR $ordinamento == 2 ) {  
					sort($files);  
			}else if ($ordinamento == 1 OR $ordinamento == 3 ) {  
					rsort($files);
            }else {  
					shuffle($files);			
			}	
			
					
					
			$filehandle = fopen($filename, 'w');

			$string = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n";
			$string .= '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/"'."\n";	
			$string .= 'xmlns:atom="http://www.w3.org/2005/Atom">'."\n";	
			
			$string .= '<channel>'."\n";			    	
			$n = count($files);
			
			if ($titoloimg == 1) {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
				$title = preg_replace('/\.(jpg|png|gif)$/i','',$row[1]);
						$string .= '<item>'."\n";
						$string .= '<title>'. $title . '</title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link>' . $dir_images .'/'. $row[1] . '</link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			else {
			
			for ($i=0; $i<$n; $i++)
			{
				$row 	 = &$files[$i];
						$string .= '<item>'."\n";
						$string .= '<title></title>'."\n";
						$string .= '<media:description>'.$retrotext.'</media:description>'."\n";
						$string .= '<link>' . $dir_images .'/'. $row[1] . '</link>'."\n";
						$string .= '<media:thumbnail url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";	
						$string .= '<media:content url="' . $dir_images .'/'. $row[1] . '"/>';
						$string .= "\n";						
						$string .= '</item>'."\n";						
			}	
			}
			$string .= '</channel>'."\n";			
			$string .= '</rss>'."\n";
			fwrite($filehandle, $string);
			fclose($filehandle);
		}			
}

		}	
		else//Folder  non esiste
		{
			$message = '<p><b><span style="color:#009933">'.JText::_('Attenzione').'</span><br /> ' . $folder 
						   .' <br /><span style="color:#009933">'.JText::_('Non corretto').'</span>
						   <span style="color:#009933">'.JText::_('Controlla').'</span>
						   </b></p>';
			$error[] = 0;
			
		 echo $message;			
			
		}
		


				$tempo = '<div>';
				$tempo .= '<span class="oziotime">';	
			$foldername 	= $foldername;				
			if (JFolder::exists($foldername)) {
			    $tempo .= JText::_('Ultima modifica cartella'). ": " . date("d m Y H:i:s.", filemtime($foldername));
				$tempo .= '</span>';				
			}
			$tempo .= ' | ';
			
			$filename 	= $filename;
			if (JFile::exists($filename)) {
			    $tempo .= '<span class="oziotime">';
			    $tempo .= JText::_('Ultima modifica file') . ": " . date("d m Y H:i:s.", filemtime($filename));
				$tempo .= '</span>';
				$tempo .= '</div>';				
			}			

endif;	

        // Debug per test interno
		$oziodebug 	= '<h2>DEBUG OZIO - FOR HELP</h2>';
if( $flickr == 1 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  Flickr :   ' .JText::_('ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  Flickrs :   '.$flickrs  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  user_id :     '.$user_id  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  set_id :   ' .$set_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  group_id :   ' .$group_id .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  text :     '.$text  .'</pre>';	
elseif  ( $xml_mode == 0 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML automatico :   ' .JText::_('ATTIVO') .'</pre>';
elseif  ( $xml_mode == 1 ) :
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  XML manuale :   ' .JText::_('ATTIVO') .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  manualxmlname :     '.$manualxmlname  .'</pre>';
endif;
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  larghezza :     '.$larghezza  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  altezza :     '.$altezza  .'</pre>';		
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  rows :   ' .$rows .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  downloads :   ' .$downloads  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  download :   ' .$download  .'</pre>';			
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  framecolor :     #'.$framecolor  .'</pre>';
		$oziodebug .= '<pre>'.JText::_('PARAMETRO').'  bkgndretro :     #'.$bkgndretro  .'</pre>';
		

	
		if (is_writable(JPATH_SITE.DS . $folder)):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  ' . $folder . ' :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		if (is_writable(JPATH_SITE.DS.'components'.DS.'com_oziogallery2'.DS.'skin'.DS.'cooliris'.DS.'xml')):
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/cooliris/xml :     '. JText::_( 'Writable' )  .'</pre>';
        else:
			$oziodebug .= '<pre>'.JText::_('CARTELLA'). '  components/com_oziogallery2/skin/cooliris/xml :     '.  JText::_( 'Unwritable' )  .'</pre>';			
		endif;			
		//fine debug				
			
		$this->assignRef('params' , 				$params);
		$this->assignRef('altezza' , 				$altezza);
		$this->assignRef('larghezza' , 				$larghezza);
		$this->assignRef('framecolor' , 			$framecolor);
		$this->assignRef('bkgndretro' , 			$bkgndretro);
		
		$this->assignRef('rows' , 					$rows);
		$this->assignRef('downloads' , 				$downloads);
		$this->assignRef('download' , 				$download);
		$this->assignRef('titoloimg' , 				$titoloimg);

		$this->assignRef('xml_mode' , 				$xml_mode);
		$this->assignRef('flickr' , 				$flickr);
		$this->assignRef('flickrs' , 				$flickrs);	
		$this->assignRef('user_id' , 				$user_id);
		$this->assignRef('set_id' , 				$set_id);
		$this->assignRef('group_id' , 				$group_id);
		
		$this->assignRef('text' , 					$text);
		$this->assignRef('table' , 					$table);
		$this->assignRef('tempo' , 					$tempo);
		$this->assignRef('modifiche' , 				$modifiche);
		$this->assignRef('debug' , 					$debug);
		$this->assignRef('oziodebug' , 				$oziodebug);
		$this->assignRef('manualxmlname' , 			$manualxmlname);
		$this->assignRef('immaginesfondo' , 		$immaginesfondo);
		$this->assignRef('larghezzaant' , 			$larghezzaant);
		$this->assignRef('altezzaant' , 			$altezzaant);
		$this->assignRef('distanzaoriz' , 			$distanzaoriz);
		$this->assignRef('distanzavert' , 			$distanzavert);
		parent::display($tpl);
	}
}
?>