<?php

declare(strict_types=1);

namespace News\Controller;

use Zend\Mvc\MvcEvent;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Feed\Reader as Feed;
use Laminas\Http\Request;
use News\Model\Table\DanhmucfeedTable;

class CategoryfeedController extends AbstractActionController
{
	private $DanhmucfeedTable;

	public function __construct(DanhmucfeedTable $danhmucfeedTable)
	{
		$this->danhmucfeedTable = $danhmucfeedTable;
	}

	public function indexAction()
	{
		//$categoryId = (int) $this->params()->fromRoute('id');
		//$data = $this->DanhmucfeedTable->fetchAllDanhMucFeed();

		$paginator = $this->danhmucfeedTable->fetchAllDanhMucFeed(true);
        $page = (int) $this->params()->fromQuery('page', 1); # sorry i forgot this line..
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);


        return new ViewModel(
            ['datas' => $paginator]
        );
	}

	public function detailAction(){
		$categoryId = (int) $this->params()->fromRoute('id');

		$datas = $this->danhmucfeedTable->fetchLinkDanhMucFeed($categoryId);
		$link = $datas->getLink();


		// return new ViewModel([
		// 	'link' => $link
		// ]);

		try{

            $slashdotRss = Feed\Reader::import($link);
        }catch (feed\Exception\RuntimeException $e){
            echo "error : " . $e->getMessage();
            exit;
        }

       $channelTT = [
		    'title'       => $slashdotRss->getTitle(),
		    'link'        => $slashdotRss->getLink(),
		    'description' => $slashdotRss->getDescription(),
		];

		// Loop over each channel item/entry and store relevant data for each
		foreach ($slashdotRss as $item) {
		    $channel[] = [
		        'title'       => $item->getTitle(),
		        'link'        => $item->getLink(),
		        'description' => $item->getDescription(),
		    ];
		}

        return new  ViewModel(array(
            'channel' => $channel
        ));
	}
}
