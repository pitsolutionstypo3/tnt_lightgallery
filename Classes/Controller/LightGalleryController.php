<?php

namespace TYPO3\TntLightgallery\Controller;

use TYPO3\TntLightgallery\Domain\Model;

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
 * LightGalleryController
 */
class LightGalleryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * lightGalleryModel
     *
     * @var TYPO3\TntLightgallery\Domain\Model
     */
    protected $lightGalleryModel;

    /**
     * Initialize arguments
     *
     * @return void
     */
    public function __construct() {
        $this->lightGalleryModel = new \TYPO3\TntLightgallery\Domain\Model\LightGallery();
        $this->resourceFolders = $GLOBALS['TSFE']->baseUrl . "typo3conf/ext/tnt_lightgallery/Resources/Public/";
    }

    /**
     * Renders the list of all existing collections and their content
     *
     * @return void
     */
    public function doGetgallery($collectionIds, $key) {
        $this->cObj = $this->configurationManager->getContentObject();
        //load essential js files
        //check to load the jquery lib from extention
        if ($this->settings['isExtJquery']) {
            $GLOBALS['TSFE']->getPageRenderer()->addJsFile($this->resourceFolders . 'Js/jquery-1.11.1.min.js', NULL, FALSE, FALSE, '', TRUE);
        }
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($this->resourceFolders . 'Js/lightGallery.js', NULL, FALSE, FALSE, '', TRUE);
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($this->resourceFolders . 'Js/Custom.js', NULL, FALSE, FALSE, '', TRUE);
        //get all the files
        $this->settings['header'] = $this->cObj->data['header'];
        $this->settings['randomService'] = $this->randomService;
        $imageCollection = $this->lightGalleryModel->doGetFiles($collectionIds);
        $imageCollection = $this->prepareImage($imageCollection);
        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey].=
                '<script type="text/javascript">
            contentArray[' . $key . '] = ' . json_encode($imageCollection) . ';
           </script>';
        //create image parameter array
        return $imageCollection;
    }

    /**
     * Renders the list of all existing collections and their content
     *
     * @return void
     */
    public function teaserAction() {
        $flexSettings = $this->settings;
        if (!$this->settings['useThisCollections']) {
            $imageCollection = $this->doGetCollectiosImages();
        } else {
            $imageCollection = $this->loadGalleryFlex();
        }
        //create image parameter array
        $this->view->assignMultiple(array(
            'collections' => $imageCollection,
            'configData' => $this->settings,
        ));
    }

    /**
     * Renders the list of all existing collections and their content
     *
     * @return void
     */
    public function mainviewAction() {
        $imageCollection = $this->doGetCollectiosImages();
        //create image parameter array
        $this->view->assignMultiple(array(
            'collections' => $imageCollection,
            'configData' => $this->settings,
        ));
    }

    /**
     * Prepare JSON Data for the gallery
     *
     * @return void
     */
    public function prepareImage($imageCollection) {
        $generatedBaseUrl = sprintf("%s://%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']) . '/';
        $baseUrl = (!empty($GLOBALS['TSFE']->baseUrl) ? $GLOBALS['TSFE']->baseUrl : $generatedBaseUrl);
        $this->cObj = $this->configurationManager->getContentObject();
        foreach ($imageCollection as $key => $value) {
            $lconf['image.']['params'] = '';
            $lconf["image."]["file."]["treatIdAsReference"] = 1;
            $lconf["image."]["file"] = $value['refUid']; // sys_file_reference.uid that links a sys_file to e.g. a tt_content element
            $lconf['image.']['altText'] = 'altText';
            $lconf["image."]["file."]["height"] = "150c";
            $lconf["image."]["file."]["width"] = "150c";
            $theImgCode[$key]['thumb'] = $baseUrl . $this->cObj->IMG_RESOURCE($lconf["image."]);
            $theImgCode[$key]['mobile'] = $baseUrl . 'fileadmin' . $value['identifier'];
            $theImgCode[$key]['firstImage'] = 'fileadmin' . $value['identifier'];
            if ($value['isPreview']) {
                $theImgCode[$key]['firstImage'] = 'fileadmin' . $value['identifier'];
            }
            $theImgCode[$key]['src'] = $baseUrl . 'fileadmin' . $value['identifier'];
            $theImgCode[$key]['caption'] = $value['title'];
            $theImgCode[$key]['desc'] = $value['description'];
            $dynamincContent = "<div class='customHtml'><h4>" . $value['title'] . "</h4><p></p>" . $value['description'] . "</div>";
            $theImgCode[$key]['sub-html'] = $dynamincContent;
            $theImgCode[$key]['isPreview'] = $value['isPreview'];
        }
        return $theImgCode;
    }

    /**
     * Prepare image Data for the gallery
     *
     * @return void
     */
    public function doGetCollectiosImages() {
        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey].=
                '<script type="text/javascript">
                    var contentArray = {};
           </script>';
        $flexSettings = $this->settings;
        if (!empty($flexSettings['setImageCollection'])) {
            $collections = explode(',', $flexSettings['setImageCollection']);
            foreach ($collections as $key => $collectionId) {
                $imagesInCollection = $this->lightGalleryModel->doGetFilesInCollection($collectionId);
                $imageInCollectionPro = $this->doGetgallery($imagesInCollection[0]['collections'], $key);
                $imageCollection[$key] = $imageInCollectionPro;
                $imageCollection[$key]['galleryTitle'] = $imagesInCollection[0]['gallerytitle'];
                $imageCollection[$key]['imageCount'] = count($imageInCollectionPro);
            }
        }

        return $imageCollection;
    }

    /**
     * Renders the list of all existing collections and their content
     *
     * @return void
     */
    public function loadGalleryFlex() {
        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey].=
                '<script type="text/javascript">
                    var contentArray = {};
           </script>';
        $flexSettings = $this->settings;
        $this->cObj = $this->configurationManager->getContentObject();
        //load essential js files
        //check to load the jquery lib from extention
        if ($this->settings['isExtJquery']) {
            $GLOBALS['TSFE']->getPageRenderer()->addJsFile($this->resourceFolders . 'Js/jquery-1.11.1.min.js', NULL, FALSE, FALSE, '', TRUE);
        }
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($this->resourceFolders . 'Js/lightGallery.js', NULL, FALSE, FALSE, '', TRUE);
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($this->resourceFolders . 'Js/Custom.js', NULL, FALSE, FALSE, '', TRUE);
        //get all the files
        $this->settings['contentId'] = $this->cObj->data['uid'];
        $this->settings['header'] = $this->cObj->data['header'];
        $imageCollection = $this->lightGalleryModel->doGetFilesReffer($this->cObj->data['uid']);
        $imageCollection = $this->prepareImage($imageCollection);
        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey].=
                '<script type="text/javascript">
            contentArray[' . $this->cObj->data["uid"] . '] = ' . json_encode($imageCollection) . ';
           </script>';
        return $imageCollection;
    }

}
