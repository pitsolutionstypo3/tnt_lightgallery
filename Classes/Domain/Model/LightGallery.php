<?php

namespace TNT\TntLightgallery\Domain\Model;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Abin Sabu <abin.s@tnt-graphics.com>, TnT Graphics
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * LightGallery
 */
class LightGallery extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * doGetFiles get all files for the gallery
     *
     * @return void
     * @api
     */
    public function doGetFiles($reffernceIds) {
        $select_fields = 'lgc.isPreview,sf.*,sfr.uid as refUid,sfr.*';
        $from_table = 'sys_file AS sf
                       LEFT JOIN sys_file_reference AS sfr ON sfr.uid_local = sf.uid 
                       LEFT JOIN tx_tntlightgallery_domain_model_lightgallery_collections AS lgc ON sfr.uid_foreign = lgc.uid';
        $whereClause = 'FIND_IN_SET(sfr.uid_foreign,"' . $reffernceIds . '") AND sfr.deleted = 0 AND sfr.hidden = 0 AND sfr.fieldname = "galleryimages"';
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
        $fileDetails = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields, $from_table, $whereClause, $groupBy = '', $orderBy = 'sfr.sorting_foreign ASC', $limit = '', $uidIndexField = '');
        return $fileDetails;
    }

    public function doGetFilesInCollection($collectionId) {

        $select_fields = 'gallerytitle,collections';
        $from_table = 'tx_tntlightgallery_domain_model_lightgallery';
        $whereClause = 'uid = ' . $collectionId . ' AND deleted = 0 AND hidden = 0';
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
        $collectionDetails = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields, $from_table, $whereClause, $groupBy = '', $orderBy = 'uid', $limit = '', $uidIndexField = '');
        return $collectionDetails;
    }

    /**
     * doGetFiles get all files for the gallery
     *
     * @return void
     * @api
     */
    public function doGetFilesReffer($reffernceId) {
        $select_fields = 'sf.*,sfr.uid as refUid,sfr.*';
        $from_table = 'sys_file AS sf LEFT JOIN sys_file_reference AS sfr ON sfr.uid_local = sf.uid';
        $whereClause = 'sfr.uid_foreign = ' . $reffernceId . ' AND sfr.deleted = 0 AND sfr.hidden = 0 AND sfr.fieldname = "tx_tnt_lightgallery"';
        $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
        $fileDetails = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select_fields, $from_table, $whereClause, $groupBy = '', $orderBy = 'sfr.sorting_foreign ASC', $limit = '', $uidIndexField = '');
         //echo  $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery;
        return $fileDetails;
    }

}
