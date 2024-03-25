<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\firstpostedit\migrations;

class v_1_1_0 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\gn36\firstpostedit\migrations\v_1_0_0');
	}

	public function update_data()
	{
		return array(
			// Rename f_edit_first_post to f_time_edit_first_post so we can use f_edit_first_post correctly
			array('permission.add', array('f_time_edit_first_post', false, 'f_edit_first_post')),
			array('permission.remove', array('f_edit_first_post', false)),

			// Add permissions for editing the first post and bypassing edit time on all posts
			array('permission.add', array('f_edit_first_post', false, 'f_edit')),
			array('permission.add', array('f_time_edit', false)),

			// Add permissions to "full access":
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_time_edit')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_edit_first_post')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_time_edit_first_post')),
		);
	}
}
