<?php
/**
*
* @package firstpostedit
* @copyright (c) 2015 gn#36
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gn36\firstpostedit\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	protected $forum_list;
	
	static public function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_cannot_edit_conditions'	=> 'post_edit',
			'core.viewtopic_modify_post_action_conditions'	=> 'viewtopic_edit',
		);
	}

	public function __construct($forum_list)
	{
		$this->forum_list = $forum_list;
	}

	public function post_edit($event)
	{
		if ($event['s_cannot_edit_time'])
		{
			$is_first_post = $event['post_data']['topic_first_post_id'] == $event['post_data']['post_id'];
			$allowed_forum = in_array($event['post_data']['forum_id'], $this->forum_list);

			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);
		}
	}

	public function viewtopic_edit($event)
	{
		if ($event['s_cannot_edit_time'])
		{
			$is_first_post = $event['topic_data']['topic_first_post_id'] == $event['row']['post_id'];
			$allowed_forum = in_array($event['row']['forum_id'], $this->forum_list);

			$event['s_cannot_edit_time'] = !($is_first_post && $allowed_forum);
		}
	}
}
