<?php
/**
* @version 1.0.5
* @package RSform! 1.0.5
* @copyright (C) 2007 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

ini_set('max_execution_time','300');
defined('_JEXEC') or die('Restricted access');

define( 'EL_ADMIN_PATH', dirname(__FILE__) );

global $mainframe;
require_once( JApplicationHelper::getPath( 'admin_html' ) );
require_once( JApplicationHelper::getPath( 'class' ) );

require_once(EL_ADMIN_PATH.'/../../includes/pageNavigation.php');

$cid 	= JRequest::getVar('cid', array());
$rcid 	= JRequest::getVar('rcid', array());

$limit = intval( JRequest::getVar('limit', 15 ) );
$limitstart = intval( JRequest::getVar( 'limitstart', 0 ) );
$formeConfig = buildFormeConfig();


$task 	= JRequest::getVar('task' );

switch($task){
	case "update":
	updateManage();
	break;
	
	case "forms":
	listforms($option);
	break;

	case "info":
	showInformation($option);
	break;

	case "publishfield":
	publishfield( $cid, 1, $option );
	break;

	case "unpublishfield":
	publishfield( $cid, 0, $option );
	break;

	case "orderupfield":
	orderfield( $cid[0], -1, $option );
	break;

	case "orderdownfield":
	orderfield( $cid[0], 1, $option );
	break;

	case "publishform":
	publishforms( $cid, 1, $option );
	break;

	case "unpublishform":
	publishforms( $cid, 0, $option );
	break;

	case "newform":
	editforms($option, 0);
	break;

	case "newfield":
	editfield($option, 0);
	break;

	case "editfield":
	editfield($option, $cid);
	break;

	case "deletefield":
	deletefield($option, $cid);
	break;

	case "editform":
	editforms($option, $cid);
	break;

	case "deleteform":
	deleteforms($option, $cid);
	break;

	case "saveform":
	saveforms($option);
	break;

	case "applyform":
	saveforms($option,1);
	break;

	case "savefield":
	savefield($option);
	break;

	case "applyfield":
	savefield($option,true);
	break;

	case "cancelform":
	cancelform($option);
	break;

	case "cancelfield":
	cancelfield($option);
	break;

	case 'listdata':
	listdata($option, $cid);
	break;

	case 'exportdata':
	exportdata($option, $rcid);
	break;

	case 'exportalldata':
	exportdata($option, -1);
	break;

	case 'deldata':
	deletedata($option, $rcid);
	break;

	case 'sample':
	addSampleData($option);
	break;

	case 'saveorder':
	saveOrder( $cid );
	break;

	case 'saveRegistration':
	saveRegistration($option);
	break;

	case 'backup':
	backup();
	break;

	case 'restore':
	restore($option);
	break;

	case 'restoreprocess':
	restoreProcess($option);
	break;

	case 'forms.copy':
		formsCopy($option, $cid);
	break;

	case 'fields.copy.screen':
		fieldsCopyScreen($option, $cid);
	break;

	case 'fields.copy.cancel':
		fieldsCopyCancel($option);
	break;

	case 'fields.copy':
		fieldsCopy($option, $cid);
	break;

	case 'main':
	default:
	forme_HTML::controlPanel($option);
	break;
}

function RScleanVar($string,$html=false)
{
	$db = JFactory::getDBO();
	$string = $html ? htmlentities($string,ENT_COMPAT,'UTF-8') : $string;
	//$string = JFilterOutput::cleanText($string);
	$string = get_magic_quotes_gpc() ? $db->getEscaped(stripslashes($string)) : $db->getEscaped($string);
	return $string;
}


function fieldsCopy($option, $cid){
	global $mainframe;
	$database =& JFactory::getDBO();

	$form_id = JRequest::getVar('form_id',0);
	$copy_form_id = JRequest::getVar('copy_form_id',0);

	if(!empty($cid)){
		foreach($cid as $field_id){
			$field = new forme_fields($database);
			$field->load($field_id);
			$field->form_id = $copy_form_id;
			$field->id = null;
			$field->store();
			$field->reorder('form_id='.$copy_form_id);
		}

		$form1 = new forme_forms($database);
		$form1->load($form_id);
		$form1->checkin();

		$msg = _FORME_BACKEND_FIELDSCOPY_DONE;
		$mainframe->redirect('index.php?option='.$option.'&task=editform&hidemainmenu=1&cid='.$copy_form_id,$msg);
	}else{
		$msg = _FORME_BACKEND_FIELDSCOPY_NONE;
		$mainframe->redirect('index.php?option='.$option.'&task=editform&hidemainmenu=1&cid='.$form_id,$msg);
	}
}


function formsCopy($option, $cid){
	global $mainframe;
	$database =& JFactory::getDBO();

	if(!empty($cid)){
		//first duplicate fields
		foreach($cid as $form_id){

			//add new form
			$new_form = new forme_forms($database);
			$new_form->load($form_id);
			$new_form->id = null;
			$new_form->store();

			$database->setQuery("SELECT id FROM #__forme_fields WHERE form_id = '$form_id'");
			$fields = $database->loadObjectList();
			if(!empty($fields)){

				foreach($fields as $field_id){
					$field = new forme_fields($database);
					$field->load($field_id->id);

					$field->id = null;
					$field->form_id = $new_form->id;
					$field->store();
				}
			}
		}

		$msg = _FORME_BACKEND_FORMSSCOPY_DONE;
	}else{
		$msg = _FORME_BACKEND_FORMSSCOPY_NONE;
	}
	$mainframe->redirect('index.php?option='.$option.'&task=forms',$msg);
}

function fieldsCopyCancel($option){
	global $mainframe;

	$database =& JFactory::getDBO();

	$form_id = JRequest::getVar('form_id',0);

	$mainframe->redirect('index.php?option='.$option.'&task=editform&hidemainmenu=1&cid='.$form_id);
}


function fieldsCopyScreen($option, $cid){
	global $mainframe;

	$database =& JFactory::getDBO();

	$form_id = JRequest::getVar('form_id',0);


	if(!empty($cid)){
		$database->setQuery("SELECT id as value, title as text FROM #__forme_forms WHERE published>=0");
		$forms = $database->loadObjectList();

		$forms = JHTML::_('select.genericlist',$forms,'copy_form_id','','value','text');

		$field_ids = join(',',$cid);
		$database->setQuery("SELECT * FROM #__forme_fields WHERE id IN ($field_ids)");
		$fields = $database->loadObjectList();
		forme_HTML::fieldsCopyScreen($option, $fields, $forms);
	}else{
		$msg = _FORME_BACKEND_FIELDSCOPY_NONE;
		$mainframe->redirect('index.php?option='.$option.'&task=editform&hidemainmenu=1&cid='.$form_id,$msg);
	}
}


function saveRegistration($option){
	global $mainframe;

	$database =& JFactory::getDBO();


	$formeConfigPost = JRequest::getVar('formeConfig',array(), 'POST');

	if(!isset($formeConfigPost['global.register.code']))$formeConfigPost['global.register.code']='';

	if($formeConfigPost['global.register.code']=='') $mainframe->redirect('index.php?option='.$option,_FORME_BACKEND_SAVEREG_CODE);

	$database->setQuery("UPDATE #__forme_config SET setting_value = '".trim($formeConfigPost['global.register.code'])."' WHERE setting_name = 'global.register.code'");
	$database->query();

	$mainframe->redirect('index.php?option='.$option,_FORME_BACKEND_SAVEREG_SAVED);
}

function addSampleData($option){
	global $mainframe;

	$database =& JFactory::getDBO();

	$query = "INSERT INTO `#__forme_forms` VALUES ('', 'contactForm', 'My First Contact Form', '<div align=\"left\" style=\"width:100%\" class=\"componentheading\">{formtitle}</div>\r\n<form name=\"{formname}\" id=\"{formname}\" method=\"post\" action=\"{action}\" {enctype}>\r\n	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"forme\">\r\n	{formfields}\r\n	</table>\r\n</form>', '<tr>\r\n	<td align=\"right\" valign=\"top\" width=\"33%\">{fieldtitle}{validationsign}</td>\r\n	<td valign=\"top\" width=\"34%\">{field}</td>\r\n	<td valign=\"top\" width=\"33%\">{fielddesc}</td>\r\n</tr>\r\n', '', '<p>Dear {fullname},</p><p>We received your  contact enquiry which contains the following message:</p><p>{message}</p><p>We will contact you shortly at {email}. </p>', 'me@domain.com,{email}', '', '', 'Contact Enquiry Received', 1, '', 1, 0, '0000-00-00 00:00:00','','','');";
	$database->setQuery($query);
	$database->query();

	$form_id = $database->insertid();

	$query = "INSERT INTO `#__forme_fields` VALUES ('', '$form_id', 'fullname', 'Full Name', '<tr>\r\n	<td align=\"right\" valign=\"top\">{fieldtitle}{validationsign}</td>\r\n	<td valign=\"top\">{field}</td>\r\n	<td valign=\"top\">{fielddesc}</td>\r\n</tr>\r\n', '', 'text', '', '', 'alpha', 'Fullname must contain only a-z,A-Z characters', 1, 1, 0, '0000-00-00 00:00:00');";
	$database->setQuery($query);
	$database->query();

	$query = "INSERT INTO `#__forme_fields` VALUES ('', '$form_id', 'email', 'Email Address', '<tr>\r\n	<td align=\"right\" valign=\"top\">{fieldtitle}{validationsign}</td>\r\n	<td valign=\"top\">{field}</td>\r\n	<td valign=\"top\">{fielddesc}</td>\r\n</tr>\r\n', '', 'text', '', '', 'email', 'Please add a valid e-mail address.', 2, 1, 0, '0000-00-00 00:00:00');";
	$database->setQuery($query);
	$database->query();

	$query = "INSERT INTO `#__forme_fields` VALUES ('', '$form_id', 'message', 'Message:', '<tr>\r\n	<td align=\"right\" valign=\"top\">{fieldtitle}{validationsign}</td>\r\n	<td valign=\"top\">{field}</td>\r\n	<td valign=\"top\">{fielddesc}</td>\r\n</tr>\r\n', '', 'textarea', '', '', 'mandatory', 'Please add a message.', 3, 1, 0, '0000-00-00 00:00:00');";
	$database->setQuery($query);
	$database->query();

	$query = "INSERT INTO `#__forme_fields` VALUES ('', '$form_id', 'submit', '', '<tr>\r\n	<td align=\"right\" valign=\"top\">{fieldtitle}{validationsign}</td>\r\n	<td valign=\"top\">{field}</td>\r\n	<td valign=\"top\">{fielddesc}</td>\r\n</tr>\r\n', '', 'submit button', 'Submit', '', '', '', 4, 1, 0, '0000-00-00 00:00:00');";
	$database->setQuery($query);
	$database->query();

	 $mainframe->redirect( "index.php?option=$option", "Sample data added" );
}




//Information

function showInformation($option)

{

	forme_HTML::showInformation($option);

}



function cancelform($option){
	global $mainframe;

	$database =& JFactory::getDBO();

	$row = new forme_forms( $database );
	$row->bind( $_POST );
	$row->checkin();

	$mainframe->redirect( "index.php?option=$option&task=forms" );
}



function cancelfield($option){
	global $mainframe;

	$database =& JFactory::getDBO();
	$row = new forme_fields( $database );
	$row->bind( $_POST );
	$row->checkin();

	if(!$row->form_id){
		$task = '&task=forms';
		$cid = '';
	}else{
		$task = '&task=editform';
		$cid = '&cid='.$row->form_id;
	}

	$mainframe->redirect( "index.php?option=$option".$task.$cid );
}



//List Forms
function listforms($option){
	global $mainframe, $limit, $limitstart;
	$database =& JFactory::getDBO();

	$search 		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search 		= $database->getEscaped( trim( strtolower( $search ) ) );
	$filter 		= $mainframe->getUserStateFromRequest( "filter{$option}", 'filter', '' );
	$filter 		= intval( $filter );

	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__forme_forms AS a"
						. "\nWHERE a.published 	>= 0"
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();

	$where = array(
	"a.published 	>= 0",
	);

	if ($search && $filter == 1) {
		$where[] = "LOWER(a.title) LIKE '%$search%'";
		$where[] = "LOWER(a.name) LIKE '%$search%'";

	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__forme_forms AS a"
						. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();

	}

	$pageNav = new mosPageNav( $total, $limitstart, $limit );
	$query = "SELECT a.*"
			. "\nFROM #__forme_forms AS a"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
			;
	$database->SetQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	//foreach form, get number of posts
	foreach($rows as $i=>$row){
		$database->setQuery("SELECT count(*) cnt FROM #__forme_data WHERE date_format(date_added,'%Y-%m-%d') = '".date('Y-m-d')."' AND form_id='{$row->id}'");
		$cnt_today = $database->loadResult();

		$database->setQuery("SELECT count(*) cnt FROM #__forme_data WHERE date_format(date_added,'%Y-%m') = '".date('Y-m')."' AND form_id='{$row->id}'");
		$cnt_month = $database->loadResult();

		$database->setQuery("SELECT count(*) cnt FROM #__forme_data WHERE form_id='{$row->id}'");
		$cnt_all = $database->loadResult();

		$rows[$i]->cnt_today = $cnt_today;
		$rows[$i]->cnt_month = $cnt_month;
		$rows[$i]->cnt_all = $cnt_all;
	}


	//Display
	forme_HTML::listforms($option, $rows, $pageNav, $search, $filter);
}

function deletedata($option, $rcid){
	global $mainframe;

	$database =& JFactory::getDBO();


	$total = count( $rcid );
	$data_id = join(",", $rcid);

	//get form id
	$database->setQuery("SELECT form_id FROM #__forme_data WHERE id = '{$rcid[0]}'");
	$form_id = $database->loadResult();

	//check for files
	$database->setQuery("SELECT * FROM #__forme_data WHERE id IN ($data_id)");
	$data = $database->loadObjectList();
	foreach($data as $d){
		$fields = explode("||\n",$d->params);
		if(!empty($fields)){
			foreach($fields as $field){
				$field = explode('=',$field);
				if(!isset($field[1]))$field[1] = '';

				$database->setQuery("SELECT inputtype FROM #__forme_fields WHERE name='{$field[0]}'");
				$inputtype = $database->loadResult();

				if($inputtype == 'file upload'){
					unlink(JPATH_SITE.'/components/com_forme/uploads/'.$field[1]);
				}
			}
		}
	}


	$database->SetQuery("DELETE FROM #__forme_data WHERE id IN ($data_id)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total ._FORME_BACKEND_DATA_DEL." ";
	$mainframe->redirect( 'index.php?option='. $option .'&task=listdata&cid='.$form_id, $msg );
}

//Delete Forms
function deleteforms($option, $cid){
	global $mainframe;
	$database =& JFactory::getDBO();

	$total = count( $cid );
	$forms = join(",", $cid);

	//Delete form
	$database->setQuery("DELETE FROM #__forme_forms WHERE id IN ($forms)");
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}else{
		$database->setQuery("DELETE FROM #__forme_fields WHERE form_id IN ($forms)");
		$database->query();

		$database->setQuery("DELETE FROM #__forme_data WHERE form_id IN ($forms)");
		$database->query();
	}

	$msg = $total ._FORME_BACKEND_FORMS_DEL." ";
	$mainframe->redirect( 'index.php?option='. $option .'&task=forms', $msg );
}

//Delete Fields
function deletefield($option, $cid){
	global $mainframe;
	$database =& JFactory::getDBO();
	$total = count( $cid );
	$fields = join(",", $cid);

	//get form_id
	$database->setQuery("SELECT form_id FROM #__forme_fields WHERE id = '{$cid[0]}'");
	$form_id = $database->loadResult();

	//Delete field

	$database->SetQuery("DELETE FROM #__forme_fields WHERE id IN ($fields)");
	$database->Query();

	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = $total ._FORME_BACKEND_FIELDS_DEL." ";
	$mainframe->redirect( 'index.php?option='. $option .'&task=editform&cid='.$form_id, $msg );
}

//Save Forms
function saveforms($option,$apply=0)
{
	global $mainframe;
	$db =& JFactory::getDBO();

	$post = JRequest::get('post',JREQUEST_ALLOWRAW);
	
	if (empty($post['formstyle']))
		$post['formstyle'] = _FORME_BACKEND_EDITFORMS_STYLE_DEFAULT;
	
	if (empty($post['fieldstyle']))
		$post['fieldstyle'] = _FORME_BACKEND_EDITFORMS_FIELDSTYLE_DEFAULT;
	
	$post['name'] = preg_replace("/[^a-zA-Z0-9s]/", "", $post['name']);
	$post['title'] = RScleanVar($post['title']);
	$post['return_url'] = RScleanVar($post['return_url']);
	$post['lang'] = RScleanVar($post['lang']);
	$post['emailsubject'] = RScleanVar($post['emailsubject']);
	$post['emailmode'] = RScleanVar($post['emailmode']);
	$post['id'] = (int) $post['id'];
	
	if (empty($post['id']))
	{
		$db->setQuery("INSERT INTO #__forme_forms SET
		`name`='".$post['name']."' ,
		`title`='".$post['title']."' ,
		`formstyle`='".RScleanVar($post['formstyle'])."' ,
		`fieldstyle`='".RScleanVar($post['fieldstyle'])."' ,
		`thankyou`='".RScleanVar($post['thankyou'])."' ,
		`email`='".RScleanVar($post['email'])."' ,
		`script_display`='".RScleanVar($post['script_display'])."' ,
		`script_process`='".RScleanVar($post['script_process'])."' ,
		`emailto`='".RScleanVar($post['emailto'])."' ,
		`emailfrom`='".RScleanVar($post['emailfrom'])."' ,
		`emailfromname`='".RScleanVar($post['emailfromname'])."' ,
		`emailsubject`='".$post['emailsubject']."' ,
		`emailmode`='".$post['emailmode']."' ,
		`return_url`='".$post['return_url']."' ,
		`lang`='".$post['lang']."',
		`published`='1'
		");
		
		$db->query();
		
		$id = $db->insertid();
	}
	else
	{
		$db->setQuery("UPDATE #__forme_forms SET
		`name`='".$post['name']."' ,
		`title`='".$post['title']."' ,
		`formstyle`='".RScleanVar($post['formstyle'])."' ,
		`fieldstyle`='".RScleanVar($post['fieldstyle'])."' ,
		`thankyou`='".RScleanVar($post['thankyou'])."' ,
		`email`='".RScleanVar($post['email'])."' ,
		`script_display`='".RScleanVar($post['script_display'])."' ,
		`script_process`='".RScleanVar($post['script_process'])."' ,
		`emailto`='".RScleanVar($post['emailto'])."' ,
		`emailfrom`='".RScleanVar($post['emailfrom'])."' ,
		`emailfromname`='".RScleanVar($post['emailfromname'])."' ,
		`emailsubject`='".$post['emailsubject']."' ,
		`emailmode`='".$post['emailmode']."' ,
		`return_url`='".$post['return_url']."' ,
		`lang`='".$post['lang']."' WHERE `id` = '".$post['id']."' ");
		
		$db->query();
		
		$id = $post['id'];
	}
	
	if(empty($post['name'])||empty($post['title']))
        $mainframe->redirect("index.php?option=$option&task=forms", _FORME_BACKEND_FORM_NAME_EMPTY." "); 


	if(!$apply)	$mainframe->redirect("index.php?option=$option&task=forms", _FORME_BACKEND_FORMS_SAVE." ");
	else $mainframe->redirect("index.php?option=$option&task=editform&cid=".$id, _FORME_BACKEND_FORMS_SAVE." ");
}

//Save Fields
function savefield($option,$apply = false)
{
	
	global $mainframe;
	$db =& JFactory::getDBO();

	$post = JRequest::get('post',JREQUEST_ALLOWRAW);
	
	if(!isset($post['published']))
		$post['published'] = '1';

	$post['id'] = (int) $post['id'];
	$post['name'] = preg_replace("/[^a-zA-Z0-9s]/", "", $post['name']);
	$post['title'] = RScleanVar($post['title']);
	$post['fieldstyle'] = RScleanVar($post['fieldstyle']);
	$post['description'] = RScleanVar($post['description']);
	$post['inputtype'] = RScleanVar($post['inputtype']);
	$post['default_value'] = RScleanVar(html_entity_decode($post['default_value']));
	$post['params'] = RScleanVar(html_entity_decode($post['params']));
	$post['validation_rule'] = RScleanVar($post['validation_rule']);
	$post['validation_message'] = RScleanVar($post['validation_message']);
	$post['published'] = (int) $post['published'];
	$post['ordering'] = (int) $post['ordering'];
	
	if (empty($post['id']))
	{
		if(empty($post['ordering']))
		{
			$db->setQuery("SELECT ordering FROM #__forme_fields WHERE form_id = '".$post['form_id']."' ORDER BY ordering DESC");
			$ordering = (int)$db->loadResult() + 1;
		}
	
		$db->setQuery("INSERT INTO #__forme_fields SET 
		`form_id` = '".$post['form_id']."' ,
		`name` = '".$post['name']."' ,
		`title` = '".$post['title']."' ,
		`fieldstyle` = '".$post['fieldstyle']."' ,
		`description` = '".$post['description']."' ,
		`inputtype` = '".$post['inputtype']."' ,
		`default_value` = '".$post['default_value']."' ,
		`params` = '".$post['params']."' ,
		`validation_rule` = '".$post['validation_rule']."' ,
		`validation_message` = '".$post['validation_message']."' ,
		`published` = '".$post['published']."' ,
		`ordering` = '".$ordering."'");
		
		$db->query();
		$id = $db->insertid();
		
	} else {
	
		$db->setQuery("UPDATE #__forme_fields SET 
		`form_id` = '".$post['form_id']."' ,
		`name` = '".$post['name']."' ,
		`title` = '".$post['title']."' ,
		`fieldstyle` = '".$post['fieldstyle']."' ,
		`description` = '".$post['description']."' ,
		`inputtype` = '".$post['inputtype']."' ,
		`default_value` = '".$post['default_value']."' ,
		`params` = '".$post['params']."' ,
		`validation_rule` = '".$post['validation_rule']."' ,
		`validation_message` = '".$post['validation_message']."' ,
		`published` = '".$post['published']."' ,
		`ordering` = '".$post['ordering']."' WHERE `id` = '".$post['id']."'");
		
		$db->query();
		$id = $post['id'];
	}

	if(empty($post['name']))
        $mainframe->redirect("index.php?option=$option&task=editfield&cid=".$id, _FORME_BACKEND_FIELDS_NAME_EMPTY." ");


	if($apply)$mainframe->redirect("index.php?option=$option&task=editfield&hidemainmenu=1&cid=".$id, _FORME_BACKEND_FIELDS_SAVE." ");
	else $mainframe->redirect("index.php?option=$option&task=editform&cid=".$post['form_id'], _FORME_BACKEND_FIELDS_SAVE." ");
}

//Order Fields
function orderfield( $id, $inc, $option ) {
	global $mainframe;

	$database =& JFactory::getDBO();

	$row = new forme_fields( $database );
	$row->load( $id );
	$row->move( $inc, " form_id = '{$row->form_id}'" );

	$mainframe->redirect( "index.php?option=$option&task=editform&cid=".$row->form_id);
}

//Publish Form
function publishforms( $cid=null, $publishform=1,  $option ) {
	global $mainframe;

  $database =& JFactory::getDBO();

  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publishcat ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $total = count ( $cid );
  $cids = implode( ',', $cid );

  $database->setQuery( "UPDATE #__forme_forms"
  					. "\nSET published =". intval( $publishform )
					. "\nWHERE id IN ( $cids )"
					);

  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }

    switch ( $publishform ) {
		case 1:
			$msg = $total ._FORME_BACKEND_SUC_PUBL_FORM." ";
		break;
		case 0:
		default:
			$msg = $total ._FORME_BACKEND_SUC_UNPUBL_FORM." ";
		break;
	}

	if (count( $cid ) == 1) {
		$row = new forme_forms( $database );
		$row->checkin( $cid[0] );
	}

	$mainframe->redirect( 'index.php?option='.$option.'&task=forms&mosmsg='. $msg );

}

//Publish Field
function publishfield( $cid=null, $publishfield=1,  $option ) {
	global $mainframe;

  $database =& JFactory::getDBO();

  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publishcat ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $total = count ( $cid );
  $cids = implode( ',', $cid );

  $database->setQuery( "UPDATE #__forme_fields"
  					. "\nSET published =". intval( $publishfield )
					. "\nWHERE id IN ( $cids )"
					);

  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }

   	switch ( $publishfield ) {
		case 1:
			$msg = $total ._FORME_BACKEND_SUC_PUBL_FIELD." ";
		break;

		case 0:
		default:
			$msg = $total ._FORME_BACKEND_SUC_UNPUBL_FIELD." ";
		break;
	}

	if (count( $cid ) == 1) {
		$row = new forme_fields( $database );
		$row->checkin( $cid[0] );
	}

	//get form_id
	$database->setQuery("SELECT form_id FROM #__forme_fields WHERE id = '{$cid[0]}'");
	$form_id = $database->loadResult();


	$mainframe->redirect( 'index.php?option='.$option.'&task=editform&cid='.$form_id.'&mosmsg='. $msg );

}



//Edit Forms

function editforms($option, $cid){
	$my = & JFactory::getUser();

	$database =& JFactory::getDBO();

	$row = new forme_forms( $database );
	$row->load( $cid );

	$database->setQuery("SELECT * FROM #__forme_forms WHERE id = '".$cid."'");
	$database->loadObject($row);

	//if $cid, check fields
	$field_rows = array();
	if($cid){
		$database->setQuery("SELECT * FROM #__forme_fields WHERE form_id = '$cid' ORDER BY ordering");
		$field_rows = $database->loadObjectList();
	}

	if (!$cid) {
		$row->published	 = 1;
	}


	forme_HTML::editforms($option, $row, $field_rows);
}


function getLanguages() {
	$database =& JFactory::getDBO();

	$lang = array();

	// Read the language dir to find languages
	$languageBaseDir = mosPathName(mosPathName(JPATH_SITE) . "language");
	$dirName = $languageBaseDir;

	$xmlFilesInDir = mosReadDirectory($languageBaseDir,".xml");

	// XML library
	require_once( JPATH_SITE . "/includes/domit/xml_domit_lite_include.php" );

	$xmlDoc =& new DOMIT_Lite_Document();
	$xmlDoc->resolveErrors( true );
	foreach($xmlFilesInDir as $xmlfile){
		if ($xmlDoc->loadXML( $dirName . $xmlfile, false, true )) {
			$lang[] = substr($xmlfile,0,-4);
		}

	}
	return $lang;
}









//Edit Fields
function editfield($option, $cid){
	$my = & JFactory::getUser();
	$database =& JFactory::getDBO();

	$row = new forme_fields( $database );
	$row->load( $cid );

	$database->SetQuery("SELECT * FROM #__forme_fields"
						. "\nWHERE id = {$cid}");
	$database->loadObject($row);
	if (!$cid) {
		$row->published	 = 1;
	}

	$post_form = JRequest::getVar('form_id',0,'POST');
	if(!$row->form_id){
		$row->form_id = $post_form;
	}

	$form = new forme_forms( $database );
	$form->load($row->form_id);

	if($form->fieldstyle == '') $form->fieldstyle = _FORME_BACKEND_EDITFORMS_FIELDSTYLE_DEFAULT;
	if($row->fieldstyle == '') $row->fieldstyle = $form->fieldstyle;


	forme_HTML::editfield($option, $row);
}
function escapeAndEnclose( $encl, $str ) {
	switch( $encl ) {
		case '"':
			return $encl . str_replace('"', '""', $str ) . $encl;
		default:
			return $encl . str_replace($encl, '\\' .$encl, $str ) . $encl;
	}
}
//export data
function exportdata($option, $rcid){
	$cid 	= JRequest::getVar('cid', array());
	$database =& JFactory::getDBO();

	if(is_array($rcid)){
		$total = count( $rcid );
		$data_id = join(",", $rcid);
	}


	//get fields
	$database->setQuery("SELECT * FROM #__forme_fields WHERE published = 1 AND form_id = '$cid' ORDER BY ordering");
	$fields = $database->loadObjectList();

	//get data
	if(!is_array($rcid)){
		$data_id = 'form_id = '.$cid;
	}else{
		$data_id = 'id IN ('.$data_id.')';
	}

	$database->setQuery("SELECT * FROM #__forme_data WHERE $data_id");
	$data = $database->loadObjectList();

	$output = '';
	$output .= _FORME_BACKEND_LISTDATA_USERIP . ",";
	$output .= _FORME_BACKEND_LISTDATA_DADDED . ",";

	$distinct_fields = array();
	foreach($fields as $field){
		$distinct_fields[$field->name] = $field;
	}
	$fields = $distinct_fields;


	foreach($fields as $field){
		$output .= $field->name . ",";
	}
	$output .= "\n";

	foreach($data as $data_row){
		$output .= $data_row->uip . ",";
		$output .= $data_row->date_added . ",";

		//build params
		$reg_params = explode("||\n",$data_row->params);
		$custom_params = array();
		foreach ($reg_params as $each){
			$each = explode('=',$each,2);
			if(!isset($each[1]))$each[1] = '';
			$custom_params[$each[0]] = $each[1];
		}
		foreach ($fields as $field){
			if(!isset($custom_params[$field->name]))$custom_params[$field->name] = '';
			$custom_params[$field->name] = escapeAndEnclose('"',$custom_params[$field->name]);//str_replace('"', '""', $custom_params[$field->name]);
			$output .= str_replace(array("\r","\n"),"",$custom_params[$field->name]) . ",";

		}
		$output .= "\n";
	}
	if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
		$browser = "Opera";
	} elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
		$browser = "IE";
	} else {
		$browser = '';
	}
	$mime_type = ($browser == 'IE' || $browser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

	// dump anything in the buffer
			while( @ob_end_clean() );
	header ('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	//header ("Cache-Control: no-cache, must-revalidate");
	//header ("Pragma: no-cache");
	header ("Content-type: " . $mime_type);
	header('Content-Encoding: none');
	//header ("Content-Disposition: attachment; filename=\"forme.csv\"" );


	if ($browser == 'IE') {
		header('Content-Disposition: inline; filename="forme.csv"');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	} else {
		header('Content-Disposition: attachment; filename="forme.csv"');
		header('Pragma: no-cache');
	}

	print $output;
	/*
	if(function_exists('mb_convert_encoding')){
		$unicode_str_for_Excel = chr(255).chr(254).mb_convert_encoding( $output, 'UTF-16LE', 'UTF-8');
    	print $unicode_str_for_Excel;
	}else{
    	print $output;
	}*/

	exit;


}

//List Data
function listdata($option, $cid){
	global $limit, $limitstart;

	$database =& JFactory::getDBO();

	$cid = intval( $cid );

	if(!$cid) {
		//get first cid
		$database->setQuery("SELECT id FROM #__forme_forms LIMIT 1");
		$cid = (int)$database->loadResult();
	}

	//build forms selectlist
	$database->setQuery("SELECT id as value, title as text FROM #__forme_forms");

	$forms = array();
	$forms[] = JHTML::_('select.option', '0', _FORME_BACKEND_LISTDATA_FORMS.' ' );
	$forms = array_merge( $forms, $database->loadObjectList() );

	$database->SetQuery( "SELECT count(*)"
						. "\nFROM #__forme_data AS d"
						. "\nWHERE d.form_id = $cid"
						);
  	$total = $database->loadResult();
	echo $database->getErrorMsg();

	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$query = "SELECT d.*"
			. "\nFROM #__forme_data AS d"
			. "\nWHERE d.form_id = $cid"
			. "\nORDER BY d.date_added DESC"
			;
	$database->SetQuery($query, $pageNav->limitstart, $pageNav->limit );
	$rows = $database->loadObjectList();

	//load form
	$form = new forme_forms($database);
	$form->load($cid);

	//select fields
	$query = "SELECT * FROM #__forme_fields WHERE form_id = '{$cid}' AND published=1 AND inputtype!='free text' ORDER BY ordering";
	$database->setQuery($query);

	$fields = $database->loadObjectList();
	$distinct_fields = array();
	foreach($fields as $field){
		$distinct_fields[$field->name] = $field;
	}

	$form->fields = $distinct_fields;

	forme_HTML::listdata($option, $rows, $form, $forms, $pageNav);
}

function saveOrder( &$cid ) {
	global $mainframe;
	$database =& JFactory::getDBO();

	$total		= count( $cid );
	$redirect 	= JRequest::getVar( 'redirect', 0, 'POST');
	$rettask	= strval( JRequest::getVar( 'returntask', '', 'POST' ) );

	$order 		= JRequest::getVar('order',array(), 'POST');

	$form_id = JRequest::getVar('form_id',0, 'POST');

	$row = new forme_fields($database);
	// update ordering values
	for( $i=0; $i < $total; $i++ ) {
		$row->load( (int) $cid[$i] );

		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}
	$row->reorder( 'form_id='.$form_id );

	$mainframe->redirect( 'index.php?option=com_forme&task=editform&cid='. $row->form_id , $msg );

} // saveOrder

function updateManage()
{
	forme_HTML::updateManage();
}


function parseRow($option,$type,$value,$dest){
	$database =& JFactory::getDBO();

	switch ($type){
		case 'mkdir':
			@mkdir(JPATH_SITE.$value);
			return sprintf(_FORME_BACKEND_UPGRADE_SUCCESS,$value);
		break;
		case 'query':
			$database->setQuery($value);
			if($database->query()){
				return sprintf(_FORME_BACKEND_UPGRADE_SUCCESS,$value);
			}else{
				return sprintf(_FORME_BACKEND_UPGRADE_FAIL,$value);
			}

		break;
		case 'copy':
			if($value!=''){

				$rfile = @fopen ('http://www.pimpmyjoomla.com/component/option,com_support/task,files.update/file,'.$value.'/sess,'.forme_HTML::genKeyCode(), "r");
				if (!$rfile) {
					return sprintf(_FORME_BACKEND_UPGRADE_ERROR_REMOTE,$value);
				}else{
					$filecontents = file_get_contents('http://www.pimpmyjoomla.com/component/option,com_support/task,files.update/file,'.$value.'/sess,'.forme_HTML::genKeyCode());
					$filename = JPATH_SITE.$dest;

					if($filecontents==''){
						return sprintf(_FORME_BACKEND_UPGRADE_ERROR_REMOTE,$value);
					}

					if (!$handle = @fopen($filename, 'w')) {
				         return sprintf(_FORME_BACKEND_UPGRADE_ERROR_LOCAL,$filename);
				         exit;
				    }

				    // Write $filecontents to our opened file.
				    if (fwrite($handle, $filecontents) === FALSE) {
				        return sprintf(_FORME_BACKEND_UPGRADE_ERROR_WRITE,$filename);
				        exit;
				    }

					return sprintf(_FORME_BACKEND_UPGRADE_STATUS_WRITE,$filename);

				    fclose($handle);
				}
			}
		break;
		case 'delete':
			$filename = JPATH_SITE.$value;
			if(file_exists($filename)){
				unlink($filename);
				return sprintf(_FORME_BACKEND_UPGRADE_STATUS_DELETE,$filename);
			}else{
				return sprintf(_FORME_BACKEND_UPGRADE_ERROR_DELETE,$filename);
			}
		break;
		case 'message':
			return $value;
		break;

	}
}


function backup(){
	$database =& JFactory::getDBO();

		$tables = array('#__forme_forms','#__forme_fields','#__forme_data','#__forme_config');
		$output = '<?php'."\r\n";
		$output .= '$database->setQuery("TRUNCATE TABLE `#__forme_forms`");$database->query();'."\r\n";
		$output .= '$database->setQuery("TRUNCATE TABLE `#__forme_fields`");$database->query();'."\r\n";
		$output .= '$database->setQuery("TRUNCATE TABLE `#__forme_data`");$database->query();'."\r\n";
		$output .= '$database->setQuery("TRUNCATE TABLE `#__forme_config`");$database->query();'."\r\n";

		foreach($tables as $table){
			$database->setQuery("SELECT id FROM $table");
			$fids = $database->loadObjectList();
			if(!empty($fids)){
			foreach($fids as $fid){
				switch($table){
					case '#__forme_forms':
						$object = new forme_forms($database);
					break;
					case '#__forme_fields':
						$object = new forme_fields($database);
					break;
					case '#__forme_data':
						$object = new forme_data($database);
					break;
					case '#__forme_config':
						$object = new forme_config($database);
					break;
				}

				$object->load($fid->id);

				$fmtsql = '$database->setQuery("INSERT INTO '.$table.' ( %s ) VALUES ( %s );");$database->query();$i++; '."\r\n";
				$fields = array();
				$values = array();
				foreach (get_object_vars( $object ) as $k => $v) {
					if (is_array($v) or is_object($v) or $v === NULL) {
						continue;
					}
					if ($k[0] == '_') { // internal field
						continue;
					}
					$fields[] = $database->NameQuote( $k );
					$values[] = $database->Quote( $v );
				}
				$output .= sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) );

			}
			}
		}
		$output .= "\r\n?>";
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: text/plain");
		header ("Content-Disposition: attachment; filename=\"forme_backup.txt\"" );

		print $output;

		exit;
}


function restore($option){
	forme_HTML::restore($option);
}

function restoreProcess($option){
	global $formeConfig, $mainframe;

	$database =& JFactory::getDBO();
	//files
	$file = JRequest::getVar('backupfile',array('tmp_name'=>''),'FILES');

	if($file['tmp_name']!=''){
		$i = 0;
		//patch the file

		require_once($file['tmp_name']);// or die('could not include');
		$database->setQuery("SELECT * FROM #__forme_data");
		$data = $database->loadObjectList();
		if(!empty($data)){
			foreach($data as $data_row){
				$database->setQuery("UPDATE #__forme_data SET params = '".str_replace('¶','||',$data_row->params)."' WHERE id = '{$data_row->id}'");
				$database->query();

			}
		}
		$database->setQuery("SELECT count(*) cnt FROM #__forme_config WHERE setting_name = 'global.register.code'");
		$code_exists = $database->loadResult();

		if(!$code_exists){
			if(!isset($formeConfig['global.register.code'])) $formeConfig['global.register.code'] = '';
			$database->setQuery("INSERT INTO #__forme_config (setting_name,setting_value) VALUES ('global.register.code','{$formeConfig['global.register.code']}')");
			$database->query();
		}
	}else{
		$mainframe->redirect('index.php?option=com_forme&task=restore','Could not upload file','error');
	}

	$mainframe->redirect('index.php?option=com_forme',sprintf(_FORME_BACKEND_RESTORE_MSG,(int)$i));

}

/**
 * Builds configuration variable
 *
 * @return formeConfig
 */
function buildFormeConfig(){
	$database =& JFactory::getDBO();

	$formeConfig = array();
	$database->setQuery("SELECT setting_name, setting_value FROM #__forme_config");
	$formeConfigObj = $database->loadObjectList();

	foreach ($formeConfigObj as $formeConfigRow){
		$formeConfig[$formeConfigRow->setting_name] = $formeConfigRow->setting_value;
	}
	return $formeConfig;
}



?>