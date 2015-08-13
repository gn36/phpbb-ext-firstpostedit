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
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'Puede pasar por alto el tiempo de edición máximo, en el primer mensaje de sus propios temas',
	'ACL_F_FIRST_POST_EDIT'			=> 'Puede editar el primer mensaje de sus propios temas',
	'ACL_F_EDIT_REPLY'						=> 'Puede editar sus propias respuestas',
	'ACL_F_TIME_EDIT'							=> 'Puede pasar por alto el tiempo máximo, para editar sus propias respuestas',
));
