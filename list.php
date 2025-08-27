<?php
/**
 * @version $Header$
 * @package tags
 * @subpackage functions
 * 
 * @copyright Copyright (c) 2004-2006, bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 */

/**
 * required setup
 */
require_once "../kernel/includes/setup_inc.php";
use Bitweaver\Tags\LibertyTag;
use Bitweaver\KernelTools;
use Bitweaver\HttpStatusCodes;

$gBitSystem->verifyPackage( 'tags' );

if( !empty( $_REQUEST['action'] ) ) {
	if( $_REQUEST['action'] == 'remove' && !empty( $_REQUEST['tag_id'] ) ) {
		if ( !$gBitUser->hasPermission('p_tags_moderate') ){
			$gBitSystem->fatalError( KernelTools::tra('You do not have permission to remove tags.') );
		}
		
		$tmpTag = new LibertyTag();
		$tmpTag->loadTag($_REQUEST);
		
		if( isset( $_REQUEST["confirm"] ) ) {
			if( $tmpTag->expungeTag( $tmpTag->mInfo['tag_id'] ) ) {
				KernelTools::bit_redirect( TAGS_PKG_URL.'list.php?status_id='.( !empty( $_REQUEST['status_id'] ) ? $_REQUEST['status_id'] : '' ) );
			} else {
				$feedback['error'] = $tmpTag->mErrors;
			}
		}
		$gBitSystem->setBrowserTitle( 'Confirm removal of '.$tmpTag->mInfo['tag'] );
		$formHash['remove'] = true;
		$formHash['action'] = 'remove';
		$formHash['status_id'] = !empty( $_REQUEST['status_id'] ) ? $_REQUEST['status_id'] : '';
		$formHash['tag_id'] = $_REQUEST['tag_id'];
		$msgHash = array(
			'label' => KernelTools::tra('Remove Tag'),
			'confirm_item' => $tmpTag->mInfo['tag'],
			'warning' => 'This will remove the above tag.',
			'error' => KernelTools::tra('This cannot be undone!'),
		);
		$gBitSystem->confirmDialog( $formHash, $msgHash );
	}
}

$tag = new LibertyTag();

$listHash = $_REQUEST;
$tagHash = $_REQUEST;

if( isset($_REQUEST['tags']) ){
	$pageTitle = KernelTools::tra( 'Tagged Content' );
	if( $listData = $tag->assignContentList( $listHash ) ) {
		$pageTitle .= ' '.KernelTools::tra( 'with' ).' '.$_REQUEST['tags'];
		$gBitSystem->setCanonicalLink( $tag->getDisplayUrlWithTag( $_REQUEST['tags'] ) );
	} else {
		$gBitSystem->setHttpStatus( HttpStatusCodes::HTTP_GONE );
	}
	$tagData = $tag->getList( $tagHash );
	$gBitSmarty->assign( 'tagData', $tagData["data"] );
	$gBitSmarty->assign( 'tagsReq', $_REQUEST['tags'] );
	$gBitSystem->display( 'bitpackage:tags/list_content.tpl', $pageTitle, array( 'display_mode' => 'list' ));
}else{
	$listData = $tag->getList( $listHash );
	$gBitSmarty->assign( 'tagData', $listData["data"] );
	$gBitSystem->display( 'bitpackage:tags/list_tags.tpl', KernelTools::tra( 'Tags' ) , array( 'display_mode' => 'list' ));
}
