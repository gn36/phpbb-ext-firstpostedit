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
	
	static public function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_cannot_edit_conditions'    => 'post_edit',
			'core.viewtopic_modify_post_action_conditions'    => 'viewtopic_edit',
			'core.permissions'            => 'add_permissions',
		);
	}

	public function __construct(\phpbb\auth\auth $auth)
	{
		$this->auth = $auth;
	}

	public function post_edit($event)
	{
		if ($event['s_cannot_edit_time'])
		{
			$is_first_post = $event['post_data']['topic_first_post_id'] == $event['post_data']['post_id'];
			$allowed_forum = $this->auth->acl_get('f_edit_first_post', $event['post_data']['forum_id']);
			
			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);
		}
	}

	public function viewtopic_edit($event)
	{
		if ($event['s_cannot_edit_time'])
		{
			$is_first_post = $event['topic_data']['topic_first_post_id'] == $event['row']['post_id'];
			$allowed_forum = $this->auth->acl_get('f_edit_first_post', $event['row']['forum_id']);

			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);
		}
	}
	public function add_permissions($event)
	{
		$event['permissions'] = array_merge($event['permissions'], array(
			// Forum perms
			'f_edit_first_post'            => array('lang' => 'ACL_F_FIRST_POST_EDIT', 'cat' => 'post'),
		));
	}
}
