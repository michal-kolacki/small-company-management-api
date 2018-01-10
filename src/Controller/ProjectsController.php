<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;


class ProjectsController extends AppController
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
        $projectId = $this->request->getParam('id');

        if ($this->request->is('get')) {
            $this->__view($projectId);
        } else if ($this->request->is(['put', 'post', 'patch'])) {
            $this->__edit($projectId);
        } else if ($this->request->is('delete')) {
            $this->__delete($projectId);
        }
    }


    public function tasks() {
        $projectId = $this->request->getParam('id');
        $this->loadModel('Tasks');
        $tasks = $this->Tasks->find()
            ->where(['project_id' => $projectId]);

        $this->_json($tasks);
    }


    private function __index() {
        $projects = $this->Projects->find();
        $this->_json($projects);
    }


    private function __view($id)
    {
        $project = $this->Projects->get($id);
        $this->_json($project);
    }


    private function __add()
    {
        $project = $this->Projects->newEntity();
        $state = 200;
        $message = '';

        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $message = __('Project has been created');
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($project->getErrors() as $key => $err) {
                    $msgTmp[] = $key . ': ' . reset($err);
                }
                $message = __('Cannot create project') . ': ' . implode(', ', $msgTmp);
            }
        }

        $this->_json(['message' => $message], $state);
    }



    private function __edit($id)
    {
        $project = $this->Projects->get($id, [
            'contain' => []
        ]);
        $state = 200;
        $message = '';

        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $message = __('The project has been saved.');
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($project->getErrors() as $key => $err) {
                    $msgTmp[] = $key . ': ' . reset($err);
                }
                $message = __('The project could not be saved. Please, try again.') . ': ' .implode(', ', $msgTmp);
            }
        }

        $this->_json(['message' => $message], $state);
    }



    private function __delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $project = $this->Projects->get($id);
        $state = 200;
        $message = '';

        if ($this->Projects->delete($project)) {
            $message = __('The project has been deleted.');
        } else {
            $state = 404;
            $message = __('The project could not be deleted. Please, try again.');
        }

        $this->_json(['message' => $message], $state);
    }
}
