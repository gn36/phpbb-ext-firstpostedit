<?php
/**
* permissions_firstpostedit [Arabic]
*
* @package language
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'ACL_F_TIME_FIRST_POST_EDIT'	=> 'يستطيع تجاوز الوقت المسموح به لتعديل أول مُشاركة في المواضيع الخاصة به',
	'ACL_F_TIME_LAST_POST_EDIT'	=> 'Can bypass max edit time in last reply of all topics',
	'ACL_F_FIRST_POST_EDIT'			=> 'يستطيع تعديل أول مُشاركة في المواضيع الخاصة به',
	'ACL_F_LAST_POST_EDIT'			=> 'Can edit last reply of each topic, if own reply',
	'ACL_F_EDIT_REPLY'				=> 'يستطيع تعديل الردود الخاصة به',
	'ACL_F_TIME_EDIT'				=> 'يستطيع تجاوز الوقت المسموح به لتعديل الردود الخاصة به',
));
