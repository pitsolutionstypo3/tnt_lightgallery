<?php
namespace TYPO3\TntLightgallery\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Abin Sabu <abin.s@tnt-graphics.com>, TnT Graphics
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 ***************************************************************/

/**
 * Test case for class TYPO3\TntLightgallery\Controller\LightGalleryController.
 *
 * @author Abin Sabu <abin.s@tnt-graphics.com>
 */
class LightGalleryControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\TntLightgallery\Controller\LightGalleryController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('TYPO3\\TntLightgallery\\Controller\\LightGalleryController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllLightGalleriesFromRepositoryAndAssignsThemToView() {

		$allLightGalleries = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$lightGalleryRepository = $this->getMock('', array('findAll'), array(), '', FALSE);
		$lightGalleryRepository->expects($this->once())->method('findAll')->will($this->returnValue($allLightGalleries));
		$this->inject($this->subject, 'lightGalleryRepository', $lightGalleryRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('lightGalleries', $allLightGalleries);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenLightGalleryToView() {
		$lightGallery = new \TYPO3\TntLightgallery\Domain\Model\LightGallery();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('lightGallery', $lightGallery);

		$this->subject->showAction($lightGallery);
	}
}
