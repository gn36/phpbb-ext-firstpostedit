<?php
/**
* permissions_firstpostedit [German]
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
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'Kann max. Bearbeitungszeit beim Startbeitrag umgehen',
	'ACL_F_FIRST_POST_EDIT'			=> 'Kann eigene Startbeiträge bearbeiten',
	'ACL_F_EDIT_REPLY'				=> 'Kann eigene Antwortbeiträge bearbeiten',
	'ACL_F_TIME_EDIT'				=> 'Kann max. Bearbeitungszeit bei Antwortbeiträgen umgehen',
));
