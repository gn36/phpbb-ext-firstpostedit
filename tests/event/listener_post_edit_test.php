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
		$acl_get_map = parent::get_auth_base_data();
		
		$post_data_base = array(
			'topic_first_post_id' => 1,
			'post_id' => 1,
			'forum_id' => 1,
		);

		$event_data_base = new \Symfony\Component\EventDispatcher\GenericEvent(null, array(
			'post_data' => $post_data_base,
			'force_edit_allowed' => false,
			's_cannot_edit' => false,
			's_cannot_edit_locked' => false,
			's_cannot_edit_time' => false,
		));

		$event_datasets = array();
		$event_datasets['f1_f_true'] = clone $event_data_base;
		$event_datasets['f1_f_false'] = clone  $event_datasets['f1_f_true'];
		$event_datasets['f1_f_false']['s_cannot_edit'] = true;
		$event_datasets['f1_f_time'] = clone $event_data_base;
		$event_datasets['f1_f_time']['s_cannot_edit_time'] = true;
		$event_datasets['f1_f_null'] = clone $event_datasets['f1_f_time'];
		$event_datasets['f1_f_null']['s_cannot_edit'] = true;
		
		// Other forum
		$post_data_base['forum_id'] = 20;
		$event_data_base['post_data'] = $post_data_base;
		
		$event_datasets['f20_f_true'] = clone $event_data_base;
		$event_datasets['f20_f_false'] = clone $event_datasets['f20_f_true'];
		$event_datasets['f20_f_false']['s_cannot_edit'] = true;
		$event_datasets['f20_f_time'] = clone $event_data_base;
		$event_datasets['f20_f_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_f_null'] = clone $event_datasets['f20_f_time'];
		$event_datasets['f20_f_null']['s_cannot_edit'] = true;
		
		// Once more with first post != post_id
		$post_data_base['post_id'] = 2;
		$event_data_base['post_data'] = $post_data_base;
		
		$event_datasets['f20_s_true'] = clone $event_data_base;
		$event_datasets['f20_s_false'] = clone $event_datasets['f20_f_true'];
		$event_datasets['f20_s_false']['s_cannot_edit'] = true;
		$event_datasets['f20_s_time'] = clone $event_data_base;
		$event_datasets['f20_s_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_s_null'] = clone $event_datasets['f20_s_time'];
		$event_datasets['f20_s_null']['s_cannot_edit'] = true;
		
		$post_data_base['forum_id'] = 1;
		$event_data_base['post_data'] = $post_data_base;
		
		$event_datasets['f1_s_true'] = clone $event_data_base;
		$event_datasets['f1_s_false'] = clone  $event_datasets['f1_f_true'];
		$event_datasets['f1_s_false']['s_cannot_edit'] = true;
		$event_datasets['f1_s_time'] = clone $event_data_base;
		$event_datasets['f1_s_time']['s_cannot_edit_time'] = true;
		$event_datasets['f1_s_null'] = clone $event_datasets['f1_s_time'];
		$event_datasets['f1_s_null']['s_cannot_edit'] = true;

		// This is a huge number of combinations...
		return array(
			// f20_s
			array($acl_get_map['all'], $event_datasets['f20_s_true'], $event_datasets['f20_s_true']),
			array($acl_get_map['all'], $event_datasets['f20_s_false'], $event_datasets['f20_s_true']),
			array($acl_get_map['all'], $event_datasets['f20_s_time'], $event_datasets['f20_s_true']),
			array($acl_get_map['all'], $event_datasets['f20_s_null'], $event_datasets['f20_s_true']), // This might be wrong?
			array($acl_get_map['all_first'], $event_datasets['f20_s_true'], $event_datasets['f20_s_false']),
			array($acl_get_map['all_first'], $event_datasets['f20_s_false'], $event_datasets['f20_s_false']),
			array($acl_get_map['all_first'], $event_datasets['f20_s_time'], $event_datasets['f20_s_false']),
			array($acl_get_map['all_first'], $event_datasets['f20_s_null'], $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], $event_datasets['f20_s_true'], $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], $event_datasets['f20_s_false'], $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], $event_datasets['f20_s_time'], $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], $event_datasets['f20_s_null'], $event_datasets['f20_s_false']),
			array($acl_get_map['time_first'], $event_datasets['f20_s_true'], $event_datasets['f20_s_false']),
			array($acl_get_map['all_reply'], $event_datasets['f20_s_true'], $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], $event_datasets['f20_s_false'], $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], $event_datasets['f20_s_time'], $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], $event_datasets['f20_s_null'], $event_datasets['f20_s_true']),
			array($acl_get_map['edit_reply'], $event_datasets['f20_s_true'], $event_datasets['f20_s_true']),
			array($acl_get_map['edit_reply'], $event_datasets['f20_s_false'], $event_datasets['f20_s_true']),
			array($acl_get_map['edit_reply'], $event_datasets['f20_s_time'], $event_datasets['f20_s_time']),
			array($acl_get_map['edit_reply'], $event_datasets['f20_s_null'], $event_datasets['f20_s_time']),
			array($acl_get_map['time_reply'], $event_datasets['f20_s_true'], $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], $event_datasets['f20_s_false'], $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], $event_datasets['f20_s_time'], $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], $event_datasets['f20_s_null'], $event_datasets['f20_s_false']),
		);

	}

	/**
	 * @dataProvider post_auth_data
	 */
	public function test_post_auth($auth_data, $event, $expected_result)
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
		$dispatcher->addListener('gn36.def_listen', array($listener, 'post_auth'));
		$dispatcher->dispatch('gn36.def_listen', $event);

		// Check
		$this->assertEquals($expected_result, $event);

	}
}