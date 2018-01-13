<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TaskLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TaskLogsTable Test Case
 */
class TaskLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TaskLogsTable
     */
    public $TaskLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.task_logs',
        'app.tasks',
        'app.projects',
        'app.clients',
        'app.task_states',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TaskLogs') ? [] : ['className' => TaskLogsTable::class];
        $this->TaskLogs = TableRegistry::get('TaskLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TaskLogs);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
