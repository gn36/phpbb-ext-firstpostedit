<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\firstpostedit\tests\event;

class listener_post_edit_test extends listener_base
{
	public static function post_auth_data()
	{
		return parent::get_post_viewtopic_test_data(true);
	}

	/**
	 * @dataProvider post_auth_data
	 */
	public function test_post_auth($auth_data, $event, $user_id, $expected_result)
	{
		// Modify auth
		$this->auth->expects($this->any())
		->method('acl_get')
		->with($this->stringContains('_'), $this->anything())
		->will($this->returnValueMap($auth_data));

		// Modify user
		$this->user->data['user_id'] = $user_id;

		// fetch listener
		$listener = $this->get_listener();

		// Dispatch
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('gn36.def_listen', array($listener, 'post_edit'));
		$dispatcher->dispatch('gn36.def_listen', $event);

		// Check
		$this->assertEquals($expected_result, $event);

	}
}