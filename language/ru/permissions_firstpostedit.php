<?php
/**
* permissions_firstpostedit
* Russian translation by HD321kbps. Little change by demonlibra.
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
	'ACL_F_TIME_FIRST_POST_EDIT'		=> 'fpe Может обходить максимальное время для редактирования первого сообщения во всех своих темах',
	'ACL_F_TIME_LAST_POST_EDIT'		=> 'fpe Может обходить максимальное время для редактирования последнего сообщения во всех темах',
	'ACL_F_FIRST_POST_EDIT'			=> 'fpe Может редактировать первое сообщение во всех своих темах',
	'ACL_F_LAST_POST_EDIT'			=> 'fpe Может редактировать своё последнее сообщение в любой теме',
	'ACL_F_EDIT_REPLY'			=> 'fpe Может редактировать свои сообщения',
	'ACL_F_TIME_EDIT'			=> 'fpe Может обходить максимальное время редактирования собственных сообщений',
));
