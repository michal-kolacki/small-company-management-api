<?php
namespace App\Controller;

use App\Controller\AppController;


class TaskStatesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('ajax');
    }


    public function index()
    {
        if ($this->request->is('get')) {
            $this->__index();
        }
    }


    private function __index() {
        $taskStates = $this->TaskStates->find();
        $this->_json($taskStates);
    }
}
