<?php

/**
 * @param	array	A named array
 * @return	array
 */
function RwcardsBuildRoute( &$query )
{
	$segments = array();
	$task = isset( $query['task'] ) ? $query['task'] : '';

	if ( isset( $query['controller'] ) ) {
		$c = $query['controller'];
		switch( $c ) {
			case 'rwcardsfilloutcard':
				$segments[] = 'compose';
				unset( $query['controller'] );

				$cat = isset( $query['category_id'] ) ? $query['category_id'] : '';
				if (!$cat) $cat = 0;
				$segments[] = $cat;
				unset( $query['category_id'] );

				$id = isset( $query['id'] ) ? $query['id'] : '';
				if (!$id) $id = 0;
				$segments[] = $id;
				unset( $query['id'] );

				break;

			case 'rwcardslistonecategory':
				$segments[] = 'cat';
				unset( $query['controller'] );
				$cat = isset( $query['category_id'] ) ? $query['category_id'] : '';
				if (!$cat) $cat = 0;
				$segments[] = $cat;
				unset( $query['category_id'] );
				
				break;
			case 'rwcardsprelookcard':
				if ( $task == 'previewrwcard' ) {
					unset( $query['task'] );
					$segments[] = 'preview';
					$id = isset( $query['id'] ) ? $query['id'] : '';
					if (!$id) $id = 0;
					$segments[] = $id;
					unset( $query['id'] );
					
				} else if ( $task == 'sendrwcard') {
					unset( $query['task'] );
					$segments[] = 'send';
				} else if ( $task == 'viewCard') {
					unset( $query['task'] );
					$segments[] = 'view';
					
					$id = isset( $query['id'] ) ? $query['id'] : '';
					if (!$id) $id = 0;
					$segments[] = $id;
					unset( $query['id'] );
					
					$sessionId = isset( $query['sessionId'] ) ? $query['sessionId'] : '';
					if ($sessionId) {
						$segments[] = $sessionId;
						unset( $query['sessionId'] );
					}
					
					
				}
				unset( $query['controller'] );
				break;
			default:

		}
	}
	if ( isset( $query['reWritetoSender'] ) and $query['reWritetoSender'] == null ) unset ( $query['reWritetoSender'] );
	if ( isset( $query['sessionId'] ) and $query['sessionId'] == null ) unset ( $query['sessionId'] );
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 */
function RwcardsParseRoute( $segments )
{
	//Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();

	// Count route segments
	$count = count($segments);

	$vars = array();

	// view is always the first element of the array
	$count = count($segments);

	if ($count)
	{
		$count--;
		$controller = array_shift( $segments );
		$segment = '';
		switch ( $controller ) {
			case 'compose':
				$segment = 'rwcardsfilloutcard';
				if ($count) {
					$vars['category_id'] = array_shift( $segments );
					$count--;
				}
				if ($count) {
					$vars['id'] = array_shift( $segments );
					$count--;
				}
				break;
				
			case 'cat':
				$segment = 'rwcardslistonecategory';
				if ($count) {
					$vars['category_id'] = array_shift( $segments );
					$count--;
				}
				break;
			case 'preview':
				$segment = 'rwcardsprelookcard';
				$vars['task'] = 'previewrwcard';
				if ($count) {
					$vars['id'] = array_shift( $segments );
					$count--;
				}
				break;

			case 'send': $segment = 'rwcardsprelookcard'; $vars['task'] = 'sendrwcard'; break;
			case 'view':
				$segment = 'rwcardsprelookcard';
				$vars['task'] = 'viewCard';
				if ($count) {
					$vars['id'] = array_shift( $segments );
					$count--;
				}
				if ($count) {
					$vars['sessionId'] = array_shift( $segments );
					$count--;
				}
				break;
		}
		if ( $segment ) $vars['controller'] = $segment;
	}

	return $vars;
}