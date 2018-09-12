<?php
/**
* permissions_firstpostedit [Italian]
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
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'Può ignorare il limite massimo per la modifica del primo messaggio dei propri argomenti',
	'ACL_F_TIME_LAST_POST_EDIT'	=> 'Può ignorare il limite massimo per la modifica del ultimo risposte',
	'ACL_F_FIRST_POST_EDIT'		=> 'Può modificare il primo messaggio dei propri argomenti',
	'ACL_F_LAST_POST_EDIT'		=> 'Può modificare il ultimo risposta, se è la sua riposta',
	'ACL_F_EDIT_REPLY'		=> 'Può modificare le proprie risposte',
	'ACL_F_TIME_EDIT'		=> 'Può ignorare il limite massimo per la modifica delle proprie risposte',
));
