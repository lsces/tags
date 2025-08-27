<?php
require_once "../kernel/includes/setup_inc.php";
use Bitweaver\KernelTools;
use Bitweaver\Tags\LibertyTag;

$gBitSystem->verifyPackage( 'tags' );

$gBitSystem->verifyPermission('p_tags_view');

$tag = new LibertyTag();

$_REQUEST['max_records'] = !empty( $_REQUEST['max_records'] ) ? $_REQUEST['max_records'] : null;
$listHash = $_REQUEST;
$tagHash = $_REQUEST;

$gBitSmarty->assign( 'cloud', true );

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
	$gBitSystem->display( 'bitpackage:tags/list_content.tpl', $pageTitle, array( 'display_mode' => 'display' ));
}else{
	$listData = $tag->getList( $listHash );
	$gBitSmarty->assign( 'tagData', $listData["data"] );
	$gBitSystem->display( 'bitpackage:tags/list_tags.tpl', KernelTools::tra( 'Tags' ) , array( 'display_mode' => 'display' ));
	
}
