<?php

namespace Kanboard\Plugin\Timetrackingeditor\Schema;

const VERSION = 2;

function vesion_2($pdo)
{
  $pdo->exec("ALTER TABLE subtasks add time_billable INTEGER default 0");
}

function version_1($pdo)
{
  $pdo->exec("ALTER TABLE subtask_time_tracking add comment TEXT");
  $pdo->exec("ALTER TABLE subtask_time_tracking add is_billable BOOLEAN");
}
