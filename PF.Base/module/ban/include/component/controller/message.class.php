<?php
/**
 * [Nulled by DarkGoth - NCP TEAM] - 2015
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: message.class.php 783 2009-07-20 19:01:47Z Raymond_Benc $
 */
class Ban_Component_Controller_Message extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		$this->template()->setTemplate('blank');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('ban.component_controller_message_clean')) ? eval($sPlugin) : false);
	}
}

?>