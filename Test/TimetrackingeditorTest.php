
<?php

require_once 'tests/units/BaseProcedureTest.php';

use Kanboard\Core\Plugin\Loader;
use Kanboard\
use Kanboard\Plugin\Timetrackingeditor\SubtasktimetrackingCreationModel;
use Kanboard\Plugin\Timetrackingeditor\SubtasktimetrackingEditModel;

class TimetrackingeditorTest extends BaseProcedureTest
{
  protected $projectName = 'My project to test time tracking';
  protected $project_id;
  protected $task_id;
  protected $subtask_id;


  public function setUp()
  {
    parent::setUp();

    $plugin = new Load($this->container);
    $plugin->scan();
  }

  public function testAll()
  {
    $this->assertCreateTeamProject();

    $this->task_id = $this->app->createTask(array('project_id' => $this->projectId, 'title' => 'Task 1'));
    $this->subtask_id = $this->app->createSubTask(array('project_id' => $this->projectId, 'task_id' => $this->task_id, 'title' => 'Subtask 1'));

    $this->assertNotFalse($this->task_id);
    $this->assertNotFalse($this->subtask_id);

    $this->testCreateEntry();
  }

  public function testCreateEntry()
  {
    


  }


}
