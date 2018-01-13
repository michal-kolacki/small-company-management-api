<?php
namespace App\Controller;

use App\Controller\AppController;


class TaskLogsController extends AppController
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
        } else if ($this->request->is('post')) {
            $this->__add();
        }
    }


    public function view()
    {
        $taskLogId = $this->request->getParam('id');

        if ($this->request->is('get')) {
            $this->__view($taskLogId);
        } else if ($this->request->is(['put', 'post', 'patch'])) {
            $this->__edit($taskLogId);
        } else if ($this->request->is('delete')) {
            $this->__delete($taskLogId);
        }
    }


    private function __index() {
        $taskLogs = $this->TaskLogs->find();
        $this->_json($taskLogs);
    }


    private function __view($id)
    {
        $taskLog = $this->TaskLogs->get($id);
        $this->_json($taskLog);
    }


    private function __add()
    {
        $taskLog = $this->TaskLogs->newEntity();
        $state = 200;
        $message = null;

        if ($this->request->is('post')) {
            $taskLog = $this->TaskLogs->patchEntity($taskLog, $this->request->getData());
            if ($this->TaskLogs->save($taskLog)) {
                $message = $taskLog;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($taskLog->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('Cannot create task log') . ', ' . implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __edit($id)
    {
        $taskLog = $this->TaskLogs->get($id, [
            'contain' => []
        ]);
        $state = 200;
        $message = null;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $taskLog = $this->TaskLogs->patchEntity($taskLog, $this->request->getData());
            if ($this->TaskLogs->save($taskLog)) {
                $message = $taskLog;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($taskLog->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('The task log could not be saved') . ', ' .implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $taskLog = $this->TaskLogs->get($id);
        $state = 200;
        $message = '';

        if (!$this->TaskLogs->delete($taskLog)) {
            $state = 404;
            $message = [
                'message' => __('The task log could not be deleted. Please, try again.')
            ];
        }

        $this->_json($message, $state);
    }
}
