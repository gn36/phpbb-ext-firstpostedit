<?php
/**
*
* @package firstpostedit
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gn36\firstpostedit\tests\event;

class listener_base extends \phpbb_test_case
{

	protected $auth;

	/**
	 * Set up test environment
	 */
	public function setUp()
	{
		parent::setUp();
		$this->auth = $this->getMock('\phpbb\auth\auth');
	}

	/**
	 * Get the event listener
	 * @return \gn36\firstpostedit\event\listener
	 */
	protected function get_listener()
	{
		return new \gn36\firstpostedit\event\listener($this->auth);
	}

	protected static function get_auth_base_data()
	{
		return array(
			'all' => array(
				// Everything is allowed
				array('f_edit', 20, true),
				array('f_edit', '20', true),
				array('f_edit_first_post', 20, true),
				array('f_edit_first_post', '20', true),
				array('f_time_edit_first_post', 20, true),
				array('f_time_edit_first_post', '20', true),
				array('f_time_edit', 20, true),
				array('f_time_edit', '20', true),
			),

			'all_first' => array(
				// Everything allowed on first post, nothing else
				array('f_edit_first_post', 20, true),
				array('f_edit_first_post', '20', true),
				array('f_time_edit_first_post', 20, true),
				array('f_time_edit_first_post', '20', true),
			),

			'edit_first' => array(
				// Editing allowed on first post, nothing else
				array('f_edit_first_post', 20, true),
				array('f_edit_first_post', '20', true),
			),

			'time_first' => array(
				// Editing not allowed, but time is not limited
				array('f_time_edit_first_post', 20, true),
				array('f_time_edit_first_post', '20', true),
			),

			'all_reply' => array(
				// Everything is allowed on replies, not on first post
				array('f_edit', 20, true),
				array('f_edit', '20', true),
				array('f_time_edit', 20, true),
				array('f_time_edit', '20', true),
			),

			'edit_reply' => array(
				// Editing is allowed on replies, nothing else
				array('f_edit', 20, true),
				array('f_edit', '20', true),
			),

			'time_reply' => array(
				// Editing not allowed but time is not limited
				array('f_time_edit', 20, true),
				array('f_time_edit', '20', true),
			),

			'none' => array(
				// Nothing is allowed
			),
		);
	}
}