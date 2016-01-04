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
 * @package  		Module_Mail
 * @version 		$Id: view.class.php 3527 2011-11-16 13:49:01Z Raymond_Benc $
 */
class Mail_Component_Controller_Thread extends Phpfox_Component
{
	/**
	 * Controller
	 */
	public function process()
	{
		Phpfox::isUser(true);
		if (!Phpfox::getParam('mail.threaded_mail_conversation'))
		{
			$this->url()->send('mail');
		}

		$aVals = $this->request()->get('val');
		if ($aVals && ($iNewId = Mail_Service_Process::instance()->add($aVals)))
		{
			list($aCon, $aMessages) = Mail_Service_Mail::instance()->getThreadedMail($iNewId);
			$aMessages = array_reverse($aMessages);

			Phpfox_Template::instance()->assign(array(
					'aMail' => $aMessages[0],
					'aCon' => $aCon,
					'bIsLastMessage' => true
				)
			)->getTemplate('mail.block.entry');

			$content = ob_get_contents();
			ob_clean();
			return [
				'append' => [
					'to' => '#mail_threaded_new_message',
					'with' => $content
				]
			];
		}
		
		$iThreadId = $this->request()->getInt('id');
		
		list($aThread, $aMessages) = Mail_Service_Mail::instance()->getThreadedMail($iThreadId);
		
		if ($aThread === false)
		{
			return Phpfox_Error::display(Phpfox::getPhrase('mail.unable_to_find_a_conversation_history_with_this_user'));
		}		
		
		$aValidation = array(
			'message' => Phpfox::getPhrase('mail.add_reply')
		);		
		
		$oValid = Phpfox_Validator::instance()->set(array(
				'sFormName' => 'js_form', 
				'aParams' => $aValidation
			)
		);			
		
		if ($aThread['user_is_archive'])
		{
			$this->request()->set('view', 'trash');
		}
		
		Mail_Service_Mail::instance()->buildMenu();
		
		Mail_Service_Process::instance()->threadIsRead($aThread['thread_id']);

		$iUserCnt = 0;
		$sUsers = '';	
		$bCanViewThread = false;	
		foreach ($aThread['users'] as $aUser)
		{	
			if ($aUser['user_id'] == Phpfox::getUserId())
			{
				$bCanViewThread = true;
			}
			
			if ($aUser['user_id'] == Phpfox::getUserId())
			{
				continue;
			}			
			
			$iUserCnt++;
			
			if ($iUserCnt == (count($aThread['users']) - 1) && (count($aThread['users']) - 1) > 1)
			{
				$sUsers .= ' &amp; ';
			}	
			else
			{
				if ($iUserCnt != '1')
				{
					$sUsers .= ', ';
				}
			}
			$sUsers .= $aUser['full_name'];
		}
		
		if (!$bCanViewThread)
		{			
			return Phpfox_Error::display('Unable to view this thread.');
		}
		else
		{
			$this->template()->setBreadcrumb(Phpfox::getPhrase('mail.mail'), $this->url()->makeUrl('mail'))->setBreadcrumb($sUsers, $this->url()->makeUrl('mail.thread', array('id' => $iThreadId)), true);
		}
		
		$this->template()->setTitle($sUsers)
			->setTitle(Phpfox::getPhrase('mail.mail'))			
			->setHeader('cache', array(
					'mail.js' => 'module_mail',
					'mail.css' => 'style_css',
					'jquery/plugin/jquery.scrollTo.js' => 'static_script'
				)
			)					
			->assign(array(
					'sCreateJs' => $oValid->createJS(),
					'sGetJsForm' => $oValid->getJsForm(false),				
					'aMessages' => $aMessages,
					'aThread' => $aThread,
					'sCurrentPageCnt' => ($this->request()->getInt('page', 0) + 1)
				)
			);
		
		$this->setParam('attachment_share', array(		
				'type' => 'mail',
				'id' => 'js_form_mail'
			)
		);	
		
		$this->setParam('global_moderation', array(
				'name' => 'mail',
				'ajax' => 'mail.mailThreadAction',
				'custom_fields' => '<div><input type="hidden" name="forward_thread_id" value="' . $aThread['thread_id'] . '" id="js_forward_thread_id" /></div>',
				'menu' => array(
					array(
						'phrase' => Phpfox::getPhrase('mail.forward'),
						'action' => 'forward'
					)			
				)
			)
		);		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mail.component_controller_thread_clean')) ? eval($sPlugin) : false);
	}	
}
?>