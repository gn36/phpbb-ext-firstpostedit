<?php
/**
 *
 * @package firstpostedit
 * @copyright (c) 2015 gn#36
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace gn36\firstpostedit\tests\event;

class listener_test extends listener_base
{
	/**
	 * Test the event listener actually implements the correct interface
	 */
	public function test_construct()
	{
		$listener = $this->get_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $listener);
	}

	/**
	 * Test that we are actually listening to the correct events
	 */
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.posting_modify_cannot_edit_conditions',
			'core.viewtopic_modify_post_action_conditions',
			'core.permissions',
			'core.modify_posting_auth',
			), array_keys(\gn36\firstpostedit\event\listener::getSubscribedEvents()));
	}

}