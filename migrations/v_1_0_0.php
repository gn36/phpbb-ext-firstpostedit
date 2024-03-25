<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gn36\firstpostedit\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array();
	}

	public function update_data()
	{
		return array(
				array('permission.add', array('f_edit_first_post', false)),
		);
	}
}
