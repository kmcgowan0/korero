<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersInterestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersInterestsTable Test Case
 */
class UsersInterestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersInterestsTable
     */
    public $UsersInterests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_interests',
        'app.users',
        'app.interests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersInterests') ? [] : ['className' => UsersInterestsTable::class];
        $this->UsersInterests = TableRegistry::get('UsersInterests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersInterests);

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
