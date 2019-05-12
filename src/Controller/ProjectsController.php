<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Collection\Collection;
use Cake\Core\Configure;


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
            ->where(['project_id' => $projectId])
            ->order(['task_state_id' => 'ASC']);

        $this->_json($tasks);
    }


    public function report($projectId = null, $dateFrom = null, $dateTo = null) {
        $this->loadModel('Projects');
        $this->loadModel('Tasks');
        $this->loadModel('TaskLogs');

        $project = $this->Projects->get($projectId);

        $tasksIds = $this->Tasks->find()
            ->where(['project_id' => $projectId])
            ->extract('id')
            ->toArray();
        // pr($tasksIds); die;

        $conditions = ['TaskLogs.task_id IN' => $tasksIds];
        if ($dateFrom) {
            $conditions['TaskLogs.created >='] = $dateFrom . ' 00:00:00';
        }
        if ($dateTo) {
            $conditions['TaskLogs.created <='] = $dateTo . ' 23:59:59';
        }
        $tasksLogs = $this->TaskLogs->find()
            ->where($conditions)
            ->order(['TaskLogs.task_id' => 'ASC'])
            ->contain('Tasks')
            ->map(function ($log) {
                $log->ftime = $this->__formatTime($log->time);
                return $log;
            })
            ->toArray();
        // pr($tasksLogs); die;

        $timeSum = 0;
        foreach ($tasksLogs as $log) {
            $timeSum += $log->time;
        }
        // echo $timeSum; die;
        $this->set('timeSum', $this->__formatTime($timeSum));


        $this->set('project', $project);
        $this->set('logs', $tasksLogs);
        $this->set('dateFrom', $dateFrom);
        $this->set('dateTo', $dateTo);

        Configure::write('debug', 0);
        $html = $this->render('report', 'pdf');
        // echo $html; die;

        require_once ROOT . DS . 'vendor' . DS . 'mpdf60' . DS . 'mpdf.php';
        $mpdf = new \mPDF('utf-8', 'A4', '', '', '10', '10', '10', '16');
        $mpdf->ignore_invalid_utf8 = true;
        $mpdf->WriteHTML($html);

        $mpdf->Output('report.pdf', 'I');
        die;
        return;
    }


    private function __index() {
        $projects = $this->Projects->find();
        $this->_json($projects);
    }


    private function __view($id)
    {
        $project = $this->Projects->get($id);
        $this->loadModel('Tasks');
        $tasksTime = $this->Tasks->find()
            ->where(['project_id' => $id])
            ->contain(['TaskLogs'])
            ->extract('task_logs.{*}.time')
            ->reduce(function ($sum, $time) {
                return $sum + $time;
            }, 0);

//        $timeSum = 0;
//        $logIndex = 1;
//        foreach ($tasksTime as $task) {
//            echo $task->name . '<br />';
//            foreach ($task->task_logs as $log) {
//                echo $logIndex . '. ' . $log->comment . ' (' . $log->time . ')<br />';
//                $logIndex++;
//                $timeSum += $log->time;
//            }
//        }
//
//        echo $timeSum; die;

        $project->time = $tasksTime;
        $this->_json($project);
    }


    private function __add()
    {
        $project = $this->Projects->newEntity();
        $state = 200;
        $message = null;

        if ($this->request->is('post')) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $message = $project;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($project->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('Cannot create project') . ', ' . implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __edit($id)
    {
        $project = $this->Projects->get($id, [
            'contain' => []
        ]);
        $state = 200;
        $message = null;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $message = $project;
            } else {
                $state = 404;
                $msgTmp = [];
                foreach($project->getErrors() as $key => $err) {
                    $msgTmp[] = '"' . $key . '": ' . reset($err);
                }
                $message = [
                    'message' => __('The project could not be saved') . ', ' .implode(', ', $msgTmp)
                ];
            }
        }

        $this->_json($message, $state);
    }



    private function __delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $project = $this->Projects->get($id);
        $state = 200;
        $message = '';

        if (!$this->Projects->delete($project)) {
            $state = 404;
            $message = [
                'message' => __('The project could not be deleted. Please, try again.')
            ];
        }

        $this->_json($message, $state);
    }



    private function __formatTime($seconds) {
        $tmpTime = $seconds;
        $h = (int)($tmpTime / 60 / 60);
        $tmpTime -= $h * 60 * 60;
        $m = (int)($tmpTime / 60);
        $s = $tmpTime - ($m * 60);

        $h = $h < 10 ? '0' . $h : $h;
        $m = $m < 10 ? '0' . $m : $m;
        $s = $s < 10 ? '0' . $s : $s;

        return $h . ':' . $m . ':' . $s;
      }
}
