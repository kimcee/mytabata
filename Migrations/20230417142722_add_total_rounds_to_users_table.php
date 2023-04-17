<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTotalRoundsToUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('users');
        $table->addColumn('rounds', 'integer', ['after' => 'break_time', 'default' => 1])->update();
    }
}
