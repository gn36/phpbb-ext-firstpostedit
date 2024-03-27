<?php
/**
*
* @package firstpostedit
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gn36\firstpostedit\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */

	public static function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_cannot_edit_conditions'	=> 'post_edit',
			'core.viewtopic_modify_post_action_conditions'	=> 'viewtopic_edit',
			'core.permissions'								=> 'add_permissions',
			'core.modify_posting_auth'						=> 'post_auth',
		);
	}

	public function __construct(\phpbb\auth\auth $auth, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->user = $user;
	}

	public function post_auth($event)
	{
		if (!$event['is_authed'] && $event['mode'] == 'edit')
		{
			// May still be authed if f_edit_first_post is set
			$event['is_authed'] = $this->auth->acl_get('f_edit_first_post', $event['forum_id']);

			// May be authed if f_edit_last_post is set
			$event['is_authed'] = $event['is_authed'] || $this->auth->acl_get('f_edit_last_post', $event['forum_id']);

			// May also be authed if f_edit is set
			// overrule any additional extension run before this one to keep things consistent
			$event['is_authed'] = $event['is_authed'] || $this->auth->acl_get('f_edit', $event['forum_id']);
		}
	}

	public function post_edit($event)
	{
		// Are we working on the first post of the topic?
		$is_first_post = $event['post_data']['topic_first_post_id'] == $event['post_data']['post_id'];
		$is_last_post  = $event['post_data']['topic_last_post_id'] == $event['post_data']['post_id'];
		$is_author = $event['post_data']['poster_id'] == $this->user->data['user_id'];

		// Time based editing
		if ($event['s_cannot_edit_time'])
		{
			// First post time bypass
			if ($is_first_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit_first_post', $event['post_data']['forum_id']);
			}
			// Last post time bypass
			else if ($is_last_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit_last_post', $event['post_data']['forum_id']);
			}
			// Other posts:
			else
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit', $event['post_data']['forum_id']);
			}
		}

		// Independent permissions for first post:
		if ($is_first_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_first_post', $event['post_data']['forum_id']));
		}
		else if ($is_last_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_last_post', $event['post_data']['forum_id']));
		}
		else
		{
			// We need to check again for edit permissions because we bypassed that earlier
			// Ignore data served by event because we changed the way this permission works
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit', $event['post_data']['forum_id']));
		}
	}

	public function viewtopic_edit($event)
	{
		$is_first_post = $event['topic_data']['topic_first_post_id'] == $event['row']['post_id'];
		$is_last_post  = $event['topic_data']['topic_last_post_id'] == $event['row']['post_id'];
		$is_author = $event['row']['user_id'] == $this->user->data['user_id'];

		// Time based editing
		if ($event['s_cannot_edit_time'])
		{
			// First Post
			if ($is_first_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit_first_post', $event['row']['forum_id']);
			}
			// Last post
			else if ($is_last_post)
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit_last_post', $event['row']['forum_id']);
			}
			// Other posts
			else
			{
				$event['s_cannot_edit_time'] = !$this->auth->acl_get('f_time_edit', $event['row']['forum_id']);
			}
		}

		// Independent permissions for first post:
		if ($is_first_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_first_post', $event['row']['forum_id']));
		}
		else if ($is_last_post)
		{
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit_last_post', $event['row']['forum_id']));
		}
		else
		{
			// We need to check again for edit permissions because we bypassed that earlier
			// Ignore data served by event because we changed the way this permission works
			$event['s_cannot_edit'] = !($is_author && $this->auth->acl_get('f_edit', $event['row']['forum_id']));
		}
	}

	public function add_permissions($event)
	{
		// We redefine f_edit so its new meaning is reflected in the text of the permissions
		$event['permissions'] = array_merge($event['permissions'], array(
			// Forum perms
			'f_edit_first_post'			=> array('lang' => 'ACL_F_FIRST_POST_EDIT', 'cat' => 'post'),
			'f_edit_last_post'			=> array('lang' => 'ACL_F_LAST_POST_EDIT', 'cat' => 'post'),
			'f_time_edit_first_post'	=> array('lang' => 'ACL_F_TIME_FIRST_POST_EDIT', 'cat' => 'post'),
			'f_time_edit_last_post'		=> array('lang' => 'ACL_F_TIME_LAST_POST_EDIT', 'cat' => 'post'),
			'f_time_edit'				=> array('lang' => 'ACL_F_TIME_EDIT', 'cat' => 'post'),
			'f_edit'					=> array('lang' => 'ACL_F_EDIT_REPLY', 'cat' => 'post'),
		));
	}
}
