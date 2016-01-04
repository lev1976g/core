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
 * @package  		Module_Attachment
 * @version 		$Id: current.class.php 877 2009-08-20 11:21:32Z Raymond_Benc $
 */
class Attachment_Component_Block_Current extends Phpfox_Component 
{
	/**
	 * @todo Need to replace the 500 with the total number of attachments allowed for the user
	 *
	 */
	/**
	 * Controller
	 */
	public function process()
	{
		$sIds = $this->getParam('sIds');
		$sIds = rtrim($sIds, ',');

		list($iCnt, $aItems) = Phpfox::getService('attachment')->get('attachment.attachment_id IN(' . $sIds . ')',	'attachment.time_stamp ASC', 0, 500, false);	
		
		$this->template()->assign(array(
				'aItems' => $aItems,
				'sUrlPath' => Phpfox::getParam('core.url_attachment'),
				'sThumbPath' => Phpfox::getParam('core.url_thumb'),
				'bCanUseInline' => $this->getParam('bCanUseInline'),
				'sAttachmentInput' => $this->getParam('sAttachmentInput')
			)
		);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('attachment.component_block_current_clean')) ? eval($sPlugin) : false);
	}
}

?>