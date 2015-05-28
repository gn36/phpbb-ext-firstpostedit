<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\firstpostedit\tests\event;

class listener_viewtopic_edit_test extends listener_base
{
	public static function viewtopic_edit_data()
	{
		return parent::get_post_viewtopic_test_data(false);
	}

	/**
	 * @dataProvider viewtopic_edit_data
	 */
	public function test_viewtopic_edit($auth_data, $event, $expected_result)
	{
		// Modify auth
		$this->auth->expects($this->any())
		->method('acl_get')
		->with($this->stringContains('_'), $this->anything())
		->will($this->returnValueMap($auth_data));

		// fetch listener
		$listener = $this->get_listener();

		// Dispatch
		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('gn36.def_listen', array($listener, 'viewtopic_edit'));
		$dispatcher->dispatch('gn36.def_listen', $event);

		// Check
		$this->assertEquals($expected_result, $event);

	}
}