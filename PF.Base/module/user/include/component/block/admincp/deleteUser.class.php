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
 * @version 		$Id: deleteUser.class.php 2137 2010-11-15 13:37:06Z Raymond_Benc $
 */
class User_Component_Block_Admincp_DeleteUser extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		$iUser = $this->request()->get('iUser');
		$aUser = Phpfox::getService('user')->get($iUser, true);

		$this->template()->assign(array(
				'aUser' => $aUser,
				'iUserIdDelete' => $aUser['user_id']
			)
		);

		return 'block';
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('user.component_block_filter_clean')) ? eval($sPlugin) : false);
	}
}

?>