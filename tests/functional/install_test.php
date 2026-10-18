<?php

/**
*
* @package phpBB.de Pastebin
* @copyright (c) 2014 phpBB.de
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace gn36\firstpostedit\tests\functional;

/**
* @group functional
*/
class install_test extends \phpbb_functional_test_case
{
	use \gn36\firstpostedit\tests\compat;

	static protected function setup_extensions()
	{
		return array('gn36/firstpostedit');
	}

	public function test_validate_posting()
	{
		// Check whether we can still call posting.php in edit mode (even though login prevents any useful use)
		$crawler = self::request('GET', 'posting.php?mode=edit&f=2&p=1');
		$this->assertTextContains('Username', $crawler->filter('dt')->text());
	}

	public function test_validate_viewtopic()
	{
		// Check whether we can still call viewtopic.php without errors
		$crawler = self::request('GET', 'viewtopic.php?f=2&p=1');
		$this->assertTextContains('Welcome', $crawler->filter('h2')->text());
	}
}
