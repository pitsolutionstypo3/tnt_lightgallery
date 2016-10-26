<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TNT.' . $_EXTKEY,
	'Tntlightgallery',
	array(
		'LightGallery' => '',
		
	),
	// non-cacheable actions
	array(
		'LightGallery' => 'teaser,mainview',
		
	)
);
