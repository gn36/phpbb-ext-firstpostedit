<?php
/**
*
* Customize first post edit permissions extension for the phpBB Forum Software package.
* French translation by Galixte (http://www.galixte.com)
*
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, array(
	// Forum perms,
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'Peut contourner la limite de temps autorisée pour modifier le premier message d’un sujet après l’avoir posté.',
	'ACL_F_TIME_LAST_POST_EDIT'		=> 'Peut contourner la limite de temps autorisée pour modifier le dernière résponse d’un sujet.',
	'ACL_F_FIRST_POST_EDIT'			=> 'Peut modifier le premier message de ses sujets.',
	'ACL_F_LAST_POST_EDIT'			=> 'Peut modifier la dernière résponse d’un sujet, si c’est sa propre résponse',
	'ACL_F_EDIT_REPLY'				=> 'Peut modifier ses réponses.',
	'ACL_F_TIME_EDIT'				=> 'Peut contourner la limite de temps autorisée pour modifier sa réponse à un sujet après l’avoir posté.',
));
