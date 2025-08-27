<?php
/**
 * @version
 * @package tags
 * @subpackage modules
 */

/**
 * required setup
 */
use Bitweaver\Tags\LibertyTag;

// moduleParams contains lots of goodies: extract for easier handling
extract( $moduleParams );

$listHash = [
	'sort'		=>  ( !empty( $module_params['sort'] ) ? $module_params['sort'] : null ),
	'sort_mode'   => ( !empty( $module_params['sort_mode'] ) ? $module_params['sort_mode'] : 'tag_asc' ),
//	do not enable until getList can return max of most popular requires more sophisticated query
//	'max_records' => $module_rows,
	'user'        => ( !empty( $module_params['user'] ) ? $module_params['user'] : null ),
	'group_id'     => ( \Bitweaver\BitBase::verifyId( $module_params['group_id'] ) ? $module_params['group_id'] : null ),
	'max_popular' =>  ( !empty( $module_params['max_popular'] ) ? $module_params['max_popular'] : null ),
];

$tag = new LibertyTag();

$listData = $tag->getList( $listHash );
$gBitSmarty->assign( 'modTagData', $listData["data"] );
