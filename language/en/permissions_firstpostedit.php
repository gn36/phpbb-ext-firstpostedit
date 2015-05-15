<?php
/**
* permissions_firstpostedit [English]
*
* @package language
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Adding the permissions
$lang = array_merge($lang, array(
	// Forum perms,
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'Can bypass max edit time in first post of own topics',
	'ACL_F_FIRST_POST_EDIT'			=> 'Can edit first post of own topics',
	'ACL_F_EDIT_REPLY'				=> 'Can edit own replies',
	'ACL_F_TIME_EDIT'				=> 'Can bypass max edit time for own replies',
));
