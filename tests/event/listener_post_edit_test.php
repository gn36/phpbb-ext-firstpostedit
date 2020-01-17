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
	public static function post_edit_data()
	{
		return parent::get_post_viewtopic_test_data(true);
	}

	/**
	 * @dataProvider post_edit_data
	 */
	public function test_post_edit($auth_data, $event_data, $user_id, $expected_result_data)
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

		// Create events
		$event = new \Symfony\Component\EventDispatcher\GenericEvent(null, $event_data);
		$expected_result = new \Symfony\Component\EventDispatcher\GenericEvent(null, $expected_result_data);

		// Dispatch
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('gn36.def_listen', array($listener, 'post_edit'));
		$dispatcher->dispatch('gn36.def_listen', $event);

		//Backwards compatibility (up to phpBB 3.2.x):
		if (method_exists($expected_result, 'setDispatcher'))
		{
			// Modify expected result event to mimic correct dispatch data
			$expected_result->setDispatcher($dispatcher);
			$expected_result->setName('gn36.def_listen');
		}

		// Check
		$this->assertEquals($expected_result, $event);

	}
}