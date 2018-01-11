<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property int $project_id
 * @property int $state_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Project $project
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\TaskLog[] $task_logs
 */
class Task extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'content' => true,
        'project_id' => true,
        'task_state_id' => true,
        'created' => true,
        'modified' => true,
        'project' => true,
        'state' => true,
        'task_logs' => true
    ];
}
