<?php

declare(strict_types=1);

namespace News\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use News\Model\Table\TintucTable;
use News\Model\Table\TheloaiTable;

class Category_detailController extends AbstractActionController
{

    private $tintucTable;
    private $theloaiTable;

    public function __construct(TintucTable $tintucTable, TheloaiTable $theloaiTable)
    {
        $this->tintucTable = $tintucTable;
        $this->theloaiTable = $theloaiTable;
    }

    public function indexAction()
    {
        $newsId = (int)$this->params('id');

        #grab paginator from UsersTable
        $paginator = $this->tintucTable->fetchNewsByCate(true, $newsId);
        $page = (int) $this->params()->fromQuery('page', 1); # sorry i forgot this line..
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);

        $title = $this->theloaiTable->fetchTheloaiById($newsId);


        return new ViewModel(
            [
                'datas' => $paginator,
                'title' => $title

        ]
        );
    }
}
