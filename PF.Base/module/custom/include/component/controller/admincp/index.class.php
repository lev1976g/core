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
 * @version 		$Id: index.class.php 5946 2013-05-24 07:53:54Z Miguel_Espinoza $
 */
class Custom_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		Phpfox::getUserParam('custom.can_manage_custom_fields', true);
		$bOrderUpdated = false;
		
		if (($iDeleteId = $this->request()->getInt('delete')) && Phpfox::getService('custom.group.process')->delete($iDeleteId))
		{
			$this->url()->send('admincp.custom', null, Phpfox::getPhrase('custom.custom_group_successfully_deleted'));
		}
		
		if (($aFieldOrders = $this->request()->getArray('field')) && Phpfox::getService('custom.process')->updateOrder($aFieldOrders))
		{			
			$bOrderUpdated = true;
		}
		
		if (($aGroupOrders = $this->request()->getArray('group')) && Phpfox::getService('custom.group.process')->updateOrder($aGroupOrders))
		{			
			$bOrderUpdated = true;
		}		
		
		if ($bOrderUpdated === true)
		{
			$this->url()->send('admincp.custom', null, Phpfox::getPhrase('custom.custom_fields_successfully_updated'));
		}
		
		$this->template()
			->setSectionTitle('Custom Fields')
			->setActionMenu([
				'Create a Custom Field' => [
					'url' => $this->url()->makeUrl('admincp.custom.add'),
					'class' => '_popup'
				]
			])
			->setTitle(Phpfox::getPhrase('custom.manage_custom_fields'))
			->setBreadcrumb(Phpfox::getPhrase('custom.manage_custom_fields'))
			->setPhrase(array(
					'custom.are_you_sure_you_want_to_delete_this_custom_option'
				)
			)			
			->setHeader(array(
					'admin.js' => 'module_custom',
					'<script type="text/javascript">$Behavior.custom_set_url = function() { $Core.custom.url(\'' . $this->url()->makeUrl('admincp.custom') . '\'); };</script>',
					'jquery/ui.js' => 'static_script',
					'<script type="text/javascript">$Behavior.custom_admin_addSort = function(){$Core.custom.addSort();};</script>'
				)
			)
			->assign(array(
					'aGroups' => Phpfox::getService('custom')->getForListing()
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('custom.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
