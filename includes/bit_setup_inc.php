<?php
use Bitweaver\KernelTools;
global $gBitSystem, $gBitUser, $gBitThemes;

define( 'LIBERTY_SERVICE_TAGS', 'tags' );

$pRegisterHash = [
	'package_name' => 'tags',
	'package_path' => dirname( dirname( __FILE__ ) ).'/',
	'service' => LIBERTY_SERVICE_TAGS,
];

// fix to quieten down VS Code which can't see the dynamic creation of these ...
define( 'TAGS_PKG_NAME', $pRegisterHash['package_name'] );
define( 'TAGS_PKG_URL', BIT_ROOT_URL . basename( $pRegisterHash['package_path'] ) . '/' );
define( 'TAGS_PKG_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/' );
define( 'TAGS_PKG_INCLUDE_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/includes/'); 
define( 'TAGS_PKG_CLASS_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/includes/classes/');
define( 'TAGS_PKG_ADMIN_PATH', BIT_ROOT_PATH . basename( $pRegisterHash['package_path'] ) . '/admin/'); 
$gBitSystem->registerPackage( $pRegisterHash );

if( $gBitSystem->isPackageActive( 'tags' ) && $gBitUser->hasPermission( 'p_tags_view' )) {
	// load css file
	$gBitThemes->loadCss( TAGS_PKG_PATH.'templates/tags.css' );

	$menuHash = [
		'package_name'  => TAGS_PKG_NAME,
		'index_url'     => TAGS_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:tags/menu_tags.tpl',
	];
	$gBitSystem->registerAppMenu( $menuHash );

	$gLibertySystem->registerService( 
		LIBERTY_SERVICE_TAGS, 
		TAGS_PKG_NAME, 
		[
			'content_display_function' 	=> 'tags_content_display',
			'content_edit_function' 	=> 'tags_content_edit',
			'content_list_sql_function' => 'tags_content_list_sql',
			'content_store_function'  	=> 'tags_content_store',
			'content_preview_function'  => 'tags_content_preview',
			'content_expunge_function'  => 'tags_content_expunge',
			'content_edit_mini_tpl'		=> 'bitpackage:tags/edit_tags_mini_inc.tpl',
			'content_view_tpl'          => 'bitpackage:tags/view_tags_view.tpl',
			'content_nav_tpl'           => 'bitpackage:tags/view_tags_nav.tpl',
			'content_body_tpl'          => 'bitpackage:tags/view_tags_body.tpl',
			'users_expunge_function'	=> 'tags_user_expunge',
			'content_search_tpl'		=> 'bitpackage:tags/search_inc.tpl'
		],
		[ 
			'description' => KernelTools::tra( 'Enables the addition of tags to any content' ),	
		]
	);
}
