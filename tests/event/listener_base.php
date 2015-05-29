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

	protected static function get_post_viewtopic_test_data($for_post_test = false)
	{
		$acl_get_map = listener_base::get_auth_base_data();

		$post_data_base = array(
			'topic_first_post_id' => 1,
			'post_id' => 1,
			'forum_id' => 1,
		);

		if($for_post_test)
		{
			$event_data_base = new \Symfony\Component\EventDispatcher\GenericEvent(null, array(
				'post_data' => $post_data_base,
				'force_edit_allowed' => false,
				's_cannot_edit' => false,
				's_cannot_edit_locked' => false,
				's_cannot_edit_time' => false,
			));
		}
		else
		{
			$event_data_base = new \Symfony\Component\EventDispatcher\GenericEvent(null, array(
				'row' => $post_data_base,
				'topic_data' => $post_data_base,
				'force_edit_allowed' => false,
				's_cannot_edit' => false,
				's_cannot_edit_locked' => false,
				's_cannot_edit_time' => false,
			));
		}
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
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if ($for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f20_f_true'] = clone $event_data_base;
		$event_datasets['f20_f_false'] = clone $event_datasets['f20_f_true'];
		$event_datasets['f20_f_false']['s_cannot_edit'] = true;
		$event_datasets['f20_f_time'] = clone $event_data_base;
		$event_datasets['f20_f_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_f_null'] = clone $event_datasets['f20_f_time'];
		$event_datasets['f20_f_null']['s_cannot_edit'] = true;

		// Once more with first post != post_id
		$post_data_base['post_id'] = 2;
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if ($for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f20_s_true'] = clone $event_data_base;
		$event_datasets['f20_s_false'] = clone $event_datasets['f20_f_true'];
		$event_datasets['f20_s_false']['s_cannot_edit'] = true;
		$event_datasets['f20_s_time'] = clone $event_data_base;
		$event_datasets['f20_s_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_s_null'] = clone $event_datasets['f20_s_time'];
		$event_datasets['f20_s_null']['s_cannot_edit'] = true;

		$post_data_base['forum_id'] = 1;
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if ($for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

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
}