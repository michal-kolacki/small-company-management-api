<?php
namespace App\Controller;

use App\Controller\AppController;


class TasksController extends AppController
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
        $taskId = $this->request->getParam('id');

        if ($this->request->is('get')) {
            $this->__view($taskId);
        } else if ($this->request->is(['put', 'post', 'patch'])) {
            $this->__edit($taskId);
        } else if ($this->request->is('delete')) {
            $this->__delete($taskId);
        }
    }


    public function logs() {
        $taskId = $this->request->getParam('id');
        $this->loadModel('TaskLogs');
        $taskLogs = $this->TaskLogs->find()
            ->where(['task_id' => $taskId]);

        $this->_json($taskLogs);
    }


    private function __index() {
        $tasks = $this->Tasks->find();
        $this->_json($tasks);
    }


    private function __view($id)
    {
        $task = $this->Tasks->get($id);
        $this->_json($task);
    }


    private function __add()
    {
        $task = $this->Tasks->newEntity();
        $state = 200;
        $message = null;

        if ($this->request->is('post')) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            if ($this->Tasks->save($task)) {
                $message = $task;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($task->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('Cannot create task') . ', ' . implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __edit($id)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        $state = 200;
        $message = null;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $task = $this->Tasks->patchEntity($task, $this->request->getData());
            if ($this->Tasks->save($task)) {
                $message = $task;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($task->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('The task could not be saved') . ', ' .implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        $state = 200;
        $message = '';

        if (!$this->Tasks->delete($task)) {
            $state = 404;
            $message = [
                'message' => __('The task could not be deleted. Please, try again.')
            ];
        }

        $this->_json($message, $state);
    }
}
