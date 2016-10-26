<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$GLOBALS['TCA']['tx_tntlightgallery_domain_model_lightgallery_collections'] = array(
    'ctrl' => $GLOBALS['TCA']['tx_tntlightgallery_domain_model_lightgallery']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, ',
    ),
    'types' => array(
        '1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource,isPreview,galleryimages;;1, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
                ),
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_tntlightgallery_domain_model_lightgallery',
                'foreign_table_where' => 'AND tx_tntlightgallery_domain_model_lightgallery.pid=###CURRENT_PID### AND tx_tntlightgallery_domain_model_lightgallery.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        't3ver_label' => array(
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            )
        ),
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'collectiontitle' => array(
            'label' => 'Collection Title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            )
        ),
        'isPreview' => array(
            'exclude' => 1,
            'label' => 'Is Preview',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'galleryimages' => array(
            'exclude' => 0,
            'label' => 'Gallery',
            'config' => array(
                'maxitems' => 1,
                'type' => 'inline',
                'foreign_table' => 'sys_file_reference',
                'foreign_field' => 'uid_foreign',
                'foreign_sortby' => 'sorting_foreign',
                'foreign_table_field' => 'tablenames',
                'foreign_match_fields' => array(
                    'fieldname' => 'galleryimages'
                ),
                'foreign_label' => 'uid_local',
                'foreign_selector' => 'uid_local',
                'foreign_selector_fieldTcaOverride' => array(
                    'config' => array(
                        'appearance' => array(
                            'elementBrowserType' => 'file',
                            'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                        )
                    )
                ),
                'filter' => array(
                    array(
                        'userFunc' => 'TYPO3\\CMS\\Core\\Resource\\Filter\\FileExtensionFilter->filterInlineChildren',
                        'parameters' => array(
                            'allowedFileExtensions' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                            'disallowedFileExtensions' => ''
                        )
                    )
                ),
                'foreign_selector_fieldTcaOverride' => array(
                    'config' => array(
                        'appearance' => array(
                            'elementBrowserType' => 'file',
                            'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                        )
                    )
                ),
            )
        ),
        'galleryimages' => array(
            'config' => array(
                'type' => 'inline',
                'foreign_table' => 'sys_file_reference',
                'foreign_field' => 'uid_foreign',
                'foreign_sortby' => 'sorting_foreign',
                'foreign_table_field' => 'tablenames',
                'foreign_match_fields' => array(
                    'fieldname' => 'galleryimages'
                ),
                'foreign_label' => 'uid_local',
                'foreign_selector' => 'uid_local',
                'foreign_selector_fieldTcaOverride' => array(
                    'config' => array(
                        'appearance' => array(
                            'elementBrowserType' => 'file',
                            'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                        )
                    )
                ),
                'filter' => array(
                    array(
                        'userFunc' => 'TYPO3\\CMS\\Core\\Resource\\Filter\\FileExtensionFilter->filterInlineChildren',
                        'parameters' => array(
                            'allowedFileExtensions' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                        )
                    )
                ),
                'appearance' => array(
                    'useSortable' => TRUE,
                    'headerThumbnail' => array(
                        'field' => 'uid_local',
                        'width' => '45',
                        'height' => '45c',
                    ),
                    'showPossibleLocalizationRecords' => FALSE,
                    'showRemovedLocalizationRecords' => FALSE,
                    'showSynchronizationLink' => FALSE,
                    'showAllLocalizationLink' => FALSE,
                    'enabledControls' => array(
                        'info' => FALSE,
                        'new' => FALSE,
                        'dragdrop' => TRUE,
                        'sort' => FALSE,
                        'hide' => TRUE,
                        'delete' => TRUE,
                        'localize' => TRUE,
                    ),
                ),
                'behaviour' => array(
                    'localizationMode' => 'select',
                    'localizeChildrenAtParentLocalization' => TRUE,
                ),
            )
        ),
    )
);
