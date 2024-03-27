<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2018 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\firstpostedit\migrations;

class v_1_3_0 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\gn36\firstpostedit\migrations\v_1_1_0');
	}

	public function update_data()
	{
		return array(
			// Add permissions for editing the last post
			array('permission.add', array('f_time_edit_last_post', false, 'f_time_edit')),
			array('permission.add', array('f_edit_last_post', false, 'f_edit')),

			// Add permissions to "full access":
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_time_edit_last_post')),
			array('permission.permission_set', array('ROLE_FORUM_FULL', 'f_edit_last_post')),
		);
	}
}
