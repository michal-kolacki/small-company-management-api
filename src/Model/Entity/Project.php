<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Project extends Entity
{
    protected $_accessible = [
        'name' => true,
        'code' => true,
        'client_id' => true,
        'created' => true,
        'client' => true,
        'tasks' => true
    ];


    protected $_hidden = [
        'created'
    ];
}
