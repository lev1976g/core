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
 * @version 		$Id: share.class.php 2510 2011-04-07 19:13:26Z Raymond_Benc $
 */
class Link_Component_Block_Share extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('link.component_block_share_clean')) ? eval($sPlugin) : false);
	}
}

?>