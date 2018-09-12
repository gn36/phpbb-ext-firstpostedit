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
	protected $user;

	/**
	 * Set up test environment
	 */
	public function setUp()
	{
		parent::setUp();
		$this->auth = $this->getMock('\phpbb\auth\auth');
		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * Get the event listener
	 * @return \gn36\firstpostedit\event\listener
	 */
	protected function get_listener()
	{
		return new \gn36\firstpostedit\event\listener($this->auth, $this->user);
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
				array('f_edit_last_post', 20, true),
				array('f_edit_last_post', '20', true),
				array('f_time_edit_first_post', 20, true),
				array('f_time_edit_first_post', '20', true),
				array('f_time_edit_last_post', 20, true),
				array('f_time_edit_last_post', '20', true),
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

			'all_last' => array(
				// Everything allowed on last post, nothing else
				array('f_edit_last_post', 20, true),
				array('f_edit_last_post', '20', true),
				array('f_time_edit_last_post', 20, true),
				array('f_time_edit_last_post', '20', true),
			),

			'edit_last' => array(
				// Editing allowed on last post, nothing else
				array('f_edit_last_post', 20, true),
				array('f_edit_last_post', '20', true),
			),

			'time_last' => array(
				// Editing not allowed, but time is not limited
				array('f_time_edit_last_post', 20, true),
				array('f_time_edit_last_post', '20', true),
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
			'topic_last_post_id' => 3,
			'post_id' => 1,
			'forum_id' => 1,
		);

		// Define basic events containing all information necessary
		$event_data_base =  array(
			'force_edit_allowed' => false,
			's_cannot_edit' => false,
			's_cannot_edit_locked' => false,
			's_cannot_edit_time' => false,
		);
		if ($for_post_test)
		{
			$post_data_base['poster_id'] = 1;
			$event_data_base['post_data'] = $post_data_base;
		}
		else
		{
			$post_data_base['user_id'] = 1;
			$event_data_base = array_merge($event_data_base, array(
				'row' => $post_data_base,
				'topic_data' => $post_data_base,
			));
		}

		// Define some sets of events for specific test cases named f<nr>_[fsl]_[text]
		// f<nr> Post in Forum ID <nr>
		// [fsl] is [f]irst or [s]econd or [l]ast post to be edited
		// [text] determines what permissions are set on the event prior to execution of this extension
		//        	true - cannot_edit and cannot_edit_time are false
		//			false - cannot_edit is true, cannot_edit_time is false
		//			time - cannot_edit is false, cannot_edit_time is true
		// 			null - cannot_edit and cannot_edit_time are true
		$event_datasets = array();
		$event_datasets['f1_f_true'] = $event_data_base;
		$event_datasets['f1_f_false'] =  $event_data_base;
		$event_datasets['f1_f_false']['s_cannot_edit'] = true;
		$event_datasets['f1_f_time'] = $event_data_base;
		$event_datasets['f1_f_time']['s_cannot_edit_time'] = true;
		$event_datasets['f1_f_null'] = $event_data_base;
		$event_datasets['f1_f_null']['s_cannot_edit'] = true;
		$event_datasets['f1_f_null']['s_cannot_edit_time'] = true;

		// Once more with first post != post_id
		$post_data_base['post_id'] = 2;

		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if (!$for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f1_s_true'] = $event_data_base;
		$event_datasets['f1_s_false'] = $event_data_base;
		$event_datasets['f1_s_false']['s_cannot_edit'] = true;
		$event_datasets['f1_s_time'] = $event_data_base;
		$event_datasets['f1_s_time']['s_cannot_edit_time'] = true;
		$event_datasets['f1_s_null'] = $event_data_base;
		$event_datasets['f1_s_null']['s_cannot_edit'] = true;
		$event_datasets['f1_s_null']['s_cannot_edit_time'] = true;

		// Once more with post_id = last post
		$post_data_base['post_id'] = 3;

		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if (!$for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f1_l_true'] = $event_data_base;
		$event_datasets['f1_l_false'] = $event_data_base;
		$event_datasets['f1_l_false']['s_cannot_edit'] = true;
		$event_datasets['f1_l_time'] = $event_data_base;
		$event_datasets['f1_l_time']['s_cannot_edit_time'] = true;
		$event_datasets['f1_l_null'] = $event_data_base;
		$event_datasets['f1_l_null']['s_cannot_edit'] = true;
		$event_datasets['f1_l_null']['s_cannot_edit_time'] = true;

		// Other forum
		$post_data_base['post_id'] = 1;
		$post_data_base['forum_id'] = 20;
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if (!$for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f20_f_true'] = $event_data_base;
		$event_datasets['f20_f_false'] = $event_data_base;
		$event_datasets['f20_f_false']['s_cannot_edit'] = true;
		$event_datasets['f20_f_time'] = $event_data_base;
		$event_datasets['f20_f_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_f_null'] = $event_data_base;
		$event_datasets['f20_f_null']['s_cannot_edit'] = true;
		$event_datasets['f20_f_null']['s_cannot_edit_time'] = true;

		// Once more with first post != post_id
		$post_data_base['post_id'] = 2;
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if (!$for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f20_s_true'] = $event_data_base;
		$event_datasets['f20_s_false'] = $event_data_base;
		$event_datasets['f20_s_false']['s_cannot_edit'] = true;
		$event_datasets['f20_s_time'] = $event_data_base;
		$event_datasets['f20_s_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_s_null'] = $event_data_base;
		$event_datasets['f20_s_null']['s_cannot_edit'] = true;
		$event_datasets['f20_s_null']['s_cannot_edit_time'] = true;

		// Once more with post_id = last post
		$post_data_base['post_id'] = 3;
		$event_data_base[$for_post_test ? 'post_data' : 'row'] = $post_data_base;
		if (!$for_post_test)
		{
			$event_data_base['topic_data'] = $post_data_base;
		}

		$event_datasets['f20_l_true'] = $event_data_base;
		$event_datasets['f20_l_false'] = $event_data_base;
		$event_datasets['f20_l_false']['s_cannot_edit'] = true;
		$event_datasets['f20_l_time'] = $event_data_base;
		$event_datasets['f20_l_time']['s_cannot_edit_time'] = true;
		$event_datasets['f20_l_null'] = $event_data_base;
		$event_datasets['f20_l_null']['s_cannot_edit'] = true;
		$event_datasets['f20_l_null']['s_cannot_edit_time'] = true;

		// This is a huge number of combinations...
		return array(
			// Correct user_id ==========================================================================================
			// f20_s
			// ACL to check users permissions, Event containing start data, user_id, Event containing expected result data
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_true']), // 0
			array($acl_get_map['all'], 			$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_true']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']), // 5
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']), // 10
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']), // 15
			// Expected behavior: Allow all
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_true']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_true']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_true']), // 20
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_true']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_time']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_time']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']), // 25
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_false']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['none'],			$event_datasets['f20_s_false'],	1, $event_datasets['f20_s_false']),
			array($acl_get_map['none'],			$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']), // 30
			array($acl_get_map['none'],			$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),

			// f20_f
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_true']), // 35
			array($acl_get_map['all'], 			$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_true']),
			// Expected behavior: Allow all
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_true']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_true']), // 40
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_true']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_true']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_true']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_true']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_time']), // 45
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_time']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_false']), // 50
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']), // 55
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']), // 60
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['none'],			$event_datasets['f20_f_false'],	1, $event_datasets['f20_f_false']),
			array($acl_get_map['none'],			$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']), // 65
			array($acl_get_map['none'],			$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),

			// f1_s
			// Expected behavior for all entries: Deny edit, leave edit_time as is
			array($acl_get_map['all'], 			$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']), // 70

			array($acl_get_map['all_first'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']), // 75
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			array($acl_get_map['time_first'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_false'], 	1, $event_datasets['f1_s_false']), // 80
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']), // 85
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_false'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']), // 90

			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_false'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			array($acl_get_map['none'],			$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']), // 95
			array($acl_get_map['none'],			$event_datasets['f1_s_false'],	1, $event_datasets['f1_s_false']),
			array($acl_get_map['none'],			$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['none'],			$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),

			// f1_f
			// Expected behavior for all entries: Deny edit, leave edit_time as is
			array($acl_get_map['all'], 			$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']), // 100
			array($acl_get_map['all'], 			$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['all'], 			$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['all_first'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']), // 105
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']), // 110

			array($acl_get_map['time_first'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_false'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']), // 115
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_false'], 	1, $event_datasets['f1_f_false']), // 120
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_false'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']), // 125
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			array($acl_get_map['none'],			$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['none'],			$event_datasets['f1_f_false'],	1, $event_datasets['f1_f_false']),
			array($acl_get_map['none'],			$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['none'],			$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']), // 130

			// Additional testcases for last post:
			// f20_s
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']), // 135
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_true'], 	1, $event_datasets['f20_s_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_false'], 1, $event_datasets['f20_s_false']), // 140
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_time'], 	1, $event_datasets['f20_s_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_null'], 	1, $event_datasets['f20_s_null']),

			// f20_f
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']), // 145
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']), // 150
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_true'], 	1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_false'], 1, $event_datasets['f20_f_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_time'], 	1, $event_datasets['f20_f_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_null'], 	1, $event_datasets['f20_f_null']),

			// f20_l
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_true']), // 155
			array($acl_get_map['all'], 			$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_true']),
			array($acl_get_map['all'], 			$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_true']),
			// Expected behavior: Allow none
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_true']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_true']), // 160
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_true']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_true']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_true']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_true']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_time']), // 165
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_time']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_false']), // 170
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']), // 175
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_false'], 1, $event_datasets['f20_l_false']), // 180
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_first'],	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['all_first'],	$event_datasets['f20_l_false'],	1, $event_datasets['f20_l_false']),
			array($acl_get_map['all_first'],	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']), // 185
			array($acl_get_map['all_first'],	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_false'],	1, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']), // 190
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_first'],	$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_false'],	1, $event_datasets['f20_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_l_true'], 	1, $event_datasets['f20_l_false']), // 195
			array($acl_get_map['none'],			$event_datasets['f20_l_false'],	1, $event_datasets['f20_l_false']),
			array($acl_get_map['none'],			$event_datasets['f20_l_time'], 	1, $event_datasets['f20_l_null']),
			array($acl_get_map['none'],			$event_datasets['f20_l_null'], 	1, $event_datasets['f20_l_null']), // 198

			// wrong forum id:
			// f1_s
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']), // 200
			array($acl_get_map['all_last'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']), // 205
			array($acl_get_map['edit_last'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f1_s_true'], 	1, $event_datasets['f1_s_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_s_false'],  1, $event_datasets['f1_s_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_s_time'], 	1, $event_datasets['f1_s_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_s_null'], 	1, $event_datasets['f1_s_null']), // 210

			// f1_f
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']), // 215
			array($acl_get_map['edit_last'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f1_f_true'], 	1, $event_datasets['f1_f_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_f_false'],  1, $event_datasets['f1_f_false']), // 220
			array($acl_get_map['time_last'], 	$event_datasets['f1_f_time'], 	1, $event_datasets['f1_f_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_f_null'], 	1, $event_datasets['f1_f_null']),

			// f1_l
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']), // 225
			array($acl_get_map['all'], 			$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Allow none
			array($acl_get_map['all_last'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['all_last'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_last'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']), // 230
			array($acl_get_map['edit_last'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']), // 235
			array($acl_get_map['time_last'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_reply'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']), // 240
			array($acl_get_map['all_reply'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']), // 245
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_l_false'],  1, $event_datasets['f1_l_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_first'],	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']), // 250
			array($acl_get_map['all_first'],	$event_datasets['f1_l_false'],	1, $event_datasets['f1_l_false']),
			array($acl_get_map['all_first'],	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['all_first'],	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_first'],	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['edit_first'],	$event_datasets['f1_l_false'],	1, $event_datasets['f1_l_false']), // 255
			array($acl_get_map['edit_first'],	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['edit_first'],	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_first'],	$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f1_l_false'],	1, $event_datasets['f1_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']), // 260
			array($acl_get_map['time_first'],	$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f1_l_true'], 	1, $event_datasets['f1_l_false']),
			array($acl_get_map['none'],			$event_datasets['f1_l_false'],	1, $event_datasets['f1_l_false']),
			array($acl_get_map['none'],			$event_datasets['f1_l_time'], 	1, $event_datasets['f1_l_null']),
			array($acl_get_map['none'],			$event_datasets['f1_l_null'], 	1, $event_datasets['f1_l_null']), // 265

			// Wrong user_id ==========================================================================================
			// Expected behavior for all entries: Follow rights for cannot_edit_time regardless of authorship but always deny edit
			// So compared to above, true -> false and time -> null
			// Add 266 to all line numbers from here on
			// f20_s
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']), // 0
			array($acl_get_map['all'], 			$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_false']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']), // 5
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']), // 10
			array($acl_get_map['edit_first'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']), // 15
			// Expected behavior: Allow all
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_false']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']), // 20
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']), // 25
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_false']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['none'],			$event_datasets['f20_s_false'],	2, $event_datasets['f20_s_false']),
			array($acl_get_map['none'],			$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']), // 30
			array($acl_get_map['none'],			$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),

			// f20_f
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']), // 35
			array($acl_get_map['all'], 			$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_false']),
			// Expected behavior: Allow all
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']), // 40
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_false']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']), // 45
			array($acl_get_map['edit_first'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_false']), // 50
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']), // 55
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']), // 60
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['none'],			$event_datasets['f20_f_false'],	2, $event_datasets['f20_f_false']),
			array($acl_get_map['none'],			$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']), // 65
			array($acl_get_map['none'],			$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),

			// f1_s
			// Expected behavior for all entries: Deny edit, leave edit_time as is
			array($acl_get_map['all'], 			$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_false'],  2, $event_datasets['f1_s_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['all'], 			$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']), // 70

			array($acl_get_map['all_first'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_false'],  2, $event_datasets['f1_s_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']), // 75
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_false'],  2, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			array($acl_get_map['time_first'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_false'], 	2, $event_datasets['f1_s_false']), // 80
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_false'],  2, $event_datasets['f1_s_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']), // 85
			array($acl_get_map['all_reply'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_false'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']), // 90

			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_false'], 	2, $event_datasets['f1_s_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			array($acl_get_map['none'],			$event_datasets['f1_s_true'], 	2, $event_datasets['f1_s_false']), // 95
			array($acl_get_map['none'],			$event_datasets['f1_s_false'],	2, $event_datasets['f1_s_false']),
			array($acl_get_map['none'],			$event_datasets['f1_s_time'], 	2, $event_datasets['f1_s_null']),
			array($acl_get_map['none'],			$event_datasets['f1_s_null'], 	2, $event_datasets['f1_s_null']),

			// f1_f
			// Expected behavior for all entries: Deny edit, leave edit_time as is
			array($acl_get_map['all'], 			$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['all'], 			$event_datasets['f1_f_false'],  2, $event_datasets['f1_f_false']), // 100
			array($acl_get_map['all'], 			$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['all'], 			$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['all_first'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_false'],  2, $event_datasets['f1_f_false']),
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']), // 105
			array($acl_get_map['all_first'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_false'],  2, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['edit_first'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']), // 110

			array($acl_get_map['time_first'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_false'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['time_first'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']), // 115
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_false'],  2, $event_datasets['f1_f_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_false'], 	2, $event_datasets['f1_f_false']), // 120
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_false'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']), // 125
			array($acl_get_map['time_reply'], 	$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']),

			array($acl_get_map['none'],			$event_datasets['f1_f_true'], 	2, $event_datasets['f1_f_false']),
			array($acl_get_map['none'],			$event_datasets['f1_f_false'],	2, $event_datasets['f1_f_false']),
			array($acl_get_map['none'],			$event_datasets['f1_f_time'], 	2, $event_datasets['f1_f_null']),
			array($acl_get_map['none'],			$event_datasets['f1_f_null'], 	2, $event_datasets['f1_f_null']), // 130

			// Additional testcases for last post:
			// f20_s
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']), // 135
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_true'], 	2, $event_datasets['f20_s_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_false'], 2, $event_datasets['f20_s_false']), // 140
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_time'], 	2, $event_datasets['f20_s_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_s_null'], 	2, $event_datasets['f20_s_null']),

			// f20_f
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']), // 145
			array($acl_get_map['all_last'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']), // 150
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_true'], 	2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_false'], 2, $event_datasets['f20_f_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_time'], 	2, $event_datasets['f20_f_null']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_f_null'], 	2, $event_datasets['f20_f_null']),

			// f20_l
			// Expected behavior: Allow all
			array($acl_get_map['all'], 			$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']), // 155
			array($acl_get_map['all'], 			$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all'], 			$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_false']),
			// Expected behavior: Allow none
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']), // 160
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_last'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_false']),
			// Expected behavior: Leave cannot_edit_time as is, but allow edit
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),  // 165
			array($acl_get_map['edit_last'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// These rights are rubbish - no edit rights, but allow edit time. Expected behavior: allow edit time but deny edit
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_last'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_false']),  // 170
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['all_reply'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']), // 175
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['edit_reply'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_false'], 2, $event_datasets['f20_l_false']), // 180
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['time_reply'], 	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['all_first'],	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_first'],	$event_datasets['f20_l_false'],	2, $event_datasets['f20_l_false']),
			array($acl_get_map['all_first'],	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']), // 185
			array($acl_get_map['all_first'],	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_false'],	2, $event_datasets['f20_l_false']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['edit_first'],	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']), // 190
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['time_first'],	$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_false'],	2, $event_datasets['f20_l_false']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['time_first'],	$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']),
			// Expected behavior: Leave cannot_edit_time as is, but deny edit
			array($acl_get_map['none'],			$event_datasets['f20_l_true'], 	2, $event_datasets['f20_l_false']), // 195
			array($acl_get_map['none'],			$event_datasets['f20_l_false'],	2, $event_datasets['f20_l_false']),
			array($acl_get_map['none'],			$event_datasets['f20_l_time'], 	2, $event_datasets['f20_l_null']),
			array($acl_get_map['none'],			$event_datasets['f20_l_null'], 	2, $event_datasets['f20_l_null']), // 198
		);

	}
}
