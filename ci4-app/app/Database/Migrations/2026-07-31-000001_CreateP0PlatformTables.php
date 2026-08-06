<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateP0PlatformTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'secret' => ['type' => 'VARCHAR', 'constraint' => 255],
            'enabled_at' => ['type' => 'DATETIME', 'null' => true],
            'recovery_codes' => ['type' => 'TEXT', 'null' => true],
            'last_used_step' => ['type' => 'BIGINT', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->createTable('lms_user_mfa', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'provider' => ['type' => 'VARCHAR', 'constraint' => 50],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 191],
            'user_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['provider', 'subject']);
        $this->forge->addKey('user_id');
        $this->forge->createTable('lms_oidc_identity', true);

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'queue' => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => 'default'],
            'type' => ['type' => 'VARCHAR', 'constraint' => 120],
            'payload' => ['type' => 'LONGTEXT'],
            'idempotency_key' => ['type' => 'VARCHAR', 'constraint' => 191, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'attempts' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'max_attempts' => ['type' => 'INT', 'unsigned' => true, 'default' => 5],
            'available_at' => ['type' => 'DATETIME'],
            'reserved_at' => ['type' => 'DATETIME', 'null' => true],
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
            'last_error' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('idempotency_key');
        $this->forge->addKey(['queue', 'status', 'available_at']);
        $this->forge->createTable('lms_jobs', true);

        $this->forge->addField([
            'backup_id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'backup_type' => ['type' => 'VARCHAR', 'constraint' => 40],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20],
            'file_path' => ['type' => 'TEXT', 'null' => true],
            'file_size' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'checksum_sha256' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'started_at' => ['type' => 'DATETIME'],
            'finished_at' => ['type' => 'DATETIME', 'null' => true],
            'message' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('backup_id', true);
        $this->forge->addKey(['backup_type', 'status', 'started_at']);
        $this->forge->createTable('lms_backup_runs', true);
    }

    public function down()
    {
        foreach (['lms_backup_runs', 'lms_jobs', 'lms_oidc_identity', 'lms_user_mfa'] as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
