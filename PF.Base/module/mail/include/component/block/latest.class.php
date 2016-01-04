<?php
/**
 * [Nulled by DarkGoth - NCP TEAM] - 2015
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: latest.class.php 3600 2011-11-29 08:28:51Z Miguel_Espinoza $
 */
class Mail_Component_Block_Latest extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		$aMessages = Mail_Service_Mail::instance()->getLatest();
		foreach ($aMessages as $iKey => $aMessage)
		{
			$aMessages[$iKey]['preview'] = strip_tags(str_replace(array('&lt;','&gt;'), array('<','> '), $aMessage['preview']));
		}
		
		$this->template()->assign(array(
				'aMessages' => $aMessages
			)
		);	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mail.component_block_latest_clean')) ? eval($sPlugin) : false);
	}
}

?>