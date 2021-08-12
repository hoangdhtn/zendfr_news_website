<?php

declare(strict_types=1);

namespace News\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use News\Model\Table\TintucTable;


class HomeController extends AbstractActionController
{


    private $tintucTable;

    public function __construct(TintucTable $tintucTable)
    {
        $this->tintucTable = $tintucTable;
    }

    public function indexAction()
    {

        #grab paginator from UsersTable
        $paginator = $this->tintucTable->fetchAllTintuc(true);
        $page = (int) $this->params()->fromQuery('page', 1); 
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);


        return new ViewModel(
            ['datas' => $paginator]
        );
    }
}
