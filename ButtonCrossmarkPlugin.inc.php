<?php
/**
 * Copyright (c) 2021 Roberto Paleari do Amaral Camargo 
 * @btocamargo
 * @file plugins/generic/buttonCrossmark/ButtonCrossmarkPlugin.inc.php
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ButtonCrossmarkPlugin extends GenericPlugin {
	/**
	 * @see Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {
		if (!parent::register($category, $path, $mainContextId)) return false;
		if ($this->getEnabled($mainContextId)) {
			HookRegistry::register('Templates::Article::Main', array($this, 'crossmarkButton'), HOOK_SEQUENCE_NORMAL);

		}
		return true;
	}


	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return 'Crossmark Button';
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return 'Creates the Crossmark button in the article ';
	}

	/**
	 * insert script Crossmark Button
	 * @param string $hookName
	 * @param array $args
	 */
	function crossmarkButton($hookName, $args) {
		//$request =& $args[0];

		$templateMgr = TemplateManager::getManager();
		$request = $this->getRequest();
		$context = $request->getContext();
		$doi = $this->getSubmissionDOI($templateMgr, $context->getId(), $hookName);
		if($doi){
		$templateMgr->display($this->getTemplateResource('display.tpl'));
		}

		return false;
	}
	
    /**
	 * Get DOI
	 * @param string $hookName
	 * @param array $args
	 */

	function getSubmissionDOI($templateMgr, $context, $hookName) {
	
		// submission is required to retreive DOI
		$submission = null;
		if (method_exists($templateMgr, 'get_template_vars')) {
			// Smarty 2
			$submission = $templateMgr->get_template_vars('article');
			} else if (method_exists($templateMgr, 'getTemplateVars')) {
				// Smarty 3
			
		}

			
		if (!$submission) {
				return false;
		}

		// requested page must be a submission with a DOI for widget display
		$doi = $submission->getStoredPubId('doi');
		if (!$doi) {
				return false;
		}


			return $doi;
		}

}
