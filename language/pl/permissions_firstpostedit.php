<?php
/**
* permissions_firstpostedit [Polish]
* (p) Serge Victor 2019
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
        'ACL_F_TIME_FIRST_POST_EDIT'    => 'Czy może ignorować czas na edycję pierwszego posta swoich własnych wątków?',
        'ACL_F_TIME_LAST_POST_EDIT'     => 'Czy może ignorować czas na edycję ostatniej odpowiedzi we wszystkich wątkach?',
        'ACL_F_FIRST_POST_EDIT'         => 'Czy może edytować pierwszy post własnych postów?',
        'ACL_F_LAST_POST_EDIT'          => 'Czy może edytować ostatnią odpowiedź w wątku, jeśli to jest jego własna odpowiedź?',
        'ACL_F_EDIT_REPLY'              => 'Czy może edytować własne odpowiedzi w wątkach?',
        'ACL_F_TIME_EDIT'               => 'Czy może ignorować czas na edycję własnych odpowiedzi?',
));
