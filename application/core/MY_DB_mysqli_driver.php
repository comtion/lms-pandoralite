<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_DB_mysqli_driver extends CI_DB_mysqli_driver
{
	protected $_audit_enabled = TRUE;
	protected $_audit_excluded_tables = array(
		'lms_audit_logs',
		'lms_lg',
		'lms_lg_import',
		'lms_lg_import_detail'
	);
	protected $_audit_table_ready = NULL;

	public function query($sql, $binds = FALSE, $return_object = NULL)
	{
		$should_log = $this->_audit_enabled && $this->_audit_is_raw_write($sql);
		$audit_sql = $this->_audit_interpolate_sql($sql, $binds);
		$table = $should_log ? $this->_audit_table_from_sql($audit_sql) : NULL;
		$old_values = NULL;
		$new_values = NULL;
		$changed_values = NULL;

		if ($should_log && ! $this->_audit_should_skip_table($table))
		{
			$old_values = $this->_audit_raw_old_values($audit_sql);
			$new_values = $this->_audit_raw_new_values($audit_sql);
			$changed_values = $this->_audit_changes($old_values, $new_values);
		}

		$result = parent::query($sql, $binds, $return_object);

		if ($should_log && $result)
		{
			if ( ! $this->_audit_should_skip_table($table))
			{
				$this->_audit_write_log(array(
					'action' => $this->_audit_action_from_sql($audit_sql),
					'table_name' => $table,
					'row_key' => $this->_audit_rows_key($old_values),
					'old_values' => $old_values,
					'new_values' => $new_values,
					'changed_values' => $changed_values,
					'sql_text' => $audit_sql,
				));
			}
		}

		return $result;
	}

	public function insert($table = '', $set = NULL, $escape = NULL)
	{
		$raw_set = $set;
		if ($set !== NULL)
		{
			$this->set($set, '', $escape);
		}

		if ($this->_validate_insert($table) === FALSE)
		{
			return FALSE;
		}

		$table_name = $this->_audit_clean_table($this->qb_from[0]);
		$new_values = is_array($raw_set) ? $this->_audit_clean_values($raw_set) : $this->_audit_unescape_qb_set($this->qb_set);
		$sql = $this->_insert(
			$this->protect_identifiers($this->qb_from[0], TRUE, $escape, FALSE),
			array_keys($this->qb_set),
			array_values($this->qb_set)
		);

		$this->_reset_write();
		$result = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'insert',
				'table_name' => $table_name,
				'row_key' => $this->_audit_insert_row_key(),
				'old_values' => NULL,
				'new_values' => $new_values,
				'changed_values' => $this->_audit_changes(NULL, $new_values),
				'sql_text' => $sql,
			));
		}

		return $result;
	}

	public function insert_batch($table, $set = NULL, $escape = NULL, $batch_size = 100)
	{
		$raw_rows = is_array($set) ? $set : NULL;
		$result = $this->_audit_without_raw_query_log(function () use ($table, $set, $escape, $batch_size) {
			return parent::insert_batch($table, $set, $escape, $batch_size);
		});

		$table_name = $this->_audit_clean_table($table);
		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'insert_batch',
				'table_name' => $table_name,
				'row_key' => NULL,
				'old_values' => NULL,
				'new_values' => $raw_rows,
				'changed_values' => $this->_audit_changes(NULL, $raw_rows),
				'sql_text' => 'INSERT_BATCH '.$table_name,
			));
		}

		return $result;
	}

	public function replace($table = '', $set = NULL)
	{
		$raw_set = $set;
		if ($set !== NULL)
		{
			$this->set($set);
		}

		if (count($this->qb_set) === 0)
		{
			return ($this->db_debug) ? $this->display_error('db_must_use_set') : FALSE;
		}

		if ($table === '')
		{
			if ( ! isset($this->qb_from[0]))
			{
				return ($this->db_debug) ? $this->display_error('db_must_set_table') : FALSE;
			}

			$table = $this->qb_from[0];
		}

		$table_name = $this->_audit_clean_table($table);
		$new_values = is_array($raw_set) ? $this->_audit_clean_values($raw_set) : $this->_audit_unescape_qb_set($this->qb_set);
		$sql = $this->_replace($this->protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->qb_set), array_values($this->qb_set));

		$this->_reset_write();
		$result = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'replace',
				'table_name' => $table_name,
				'row_key' => $this->_audit_insert_row_key(),
				'old_values' => NULL,
				'new_values' => $new_values,
				'changed_values' => $this->_audit_changes(NULL, $new_values),
				'sql_text' => $sql,
			));
		}

		return $result;
	}

	public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
	{
		$this->_merge_cache();

		if ($set !== NULL)
		{
			$this->set($set);
		}

		if ($this->_validate_update($table) === FALSE)
		{
			return FALSE;
		}

		if ($where !== NULL)
		{
			$this->where($where);
		}

		if ( ! empty($limit))
		{
			$this->limit($limit);
		}

		$table_name = $this->_audit_clean_table($table !== '' ? $table : $this->qb_from[0]);
		$new_values = is_array($set) ? $this->_audit_clean_values($set) : $this->_audit_unescape_qb_set($this->qb_set);
		$old_rows = $this->_audit_snapshot_rows($table_name);
		$sql = $this->_update($this->qb_from[0], $this->qb_set);
		$this->_reset_write();

		$result = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'update',
				'table_name' => $table_name,
				'row_key' => $this->_audit_rows_key($old_rows),
				'old_values' => $old_rows,
				'new_values' => $new_values,
				'changed_values' => $this->_audit_changes($old_rows, $new_values),
				'sql_text' => $sql,
			));
		}

		return $result;
	}

	public function update_batch($table, $set = NULL, $index = NULL, $batch_size = 100)
	{
		$raw_rows = is_array($set) ? $set : NULL;
		$old_rows = array();

		if ($index !== NULL && is_array($set) && ! $this->_audit_should_skip_table($table))
		{
			$ids = array();
			foreach ($set as $row)
			{
				if (isset($row[$index]))
				{
					$ids[] = $row[$index];
				}
			}
			$old_rows = $this->_audit_snapshot_by_ids($table, $index, $ids);
		}

		$result = $this->_audit_without_raw_query_log(function () use ($table, $set, $index, $batch_size) {
			return parent::update_batch($table, $set, $index, $batch_size);
		});

		$table_name = $this->_audit_clean_table($table);
		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'update_batch',
				'table_name' => $table_name,
				'row_key' => $index,
				'old_values' => $old_rows,
				'new_values' => $raw_rows,
				'changed_values' => $this->_audit_changes($old_rows, $raw_rows),
				'sql_text' => 'UPDATE_BATCH '.$table_name,
			));
		}

		return $result;
	}

	public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE)
	{
		$this->_merge_cache();

		if (is_array($table))
		{
			return parent::delete($table, $where, $limit, $reset_data);
		}

		if ($table === '')
		{
			if ( ! isset($this->qb_from[0]))
			{
				return ($this->db_debug) ? $this->display_error('db_must_set_table') : FALSE;
			}

			$table = $this->qb_from[0];
		}
		else
		{
			$table = $this->protect_identifiers($table, TRUE, NULL, FALSE);
		}

		if ($where !== '')
		{
			$this->where($where);
		}

		if ( ! empty($limit))
		{
			$this->limit($limit);
		}

		if (count($this->qb_where) === 0)
		{
			return ($this->db_debug) ? $this->display_error('db_del_must_use_where') : FALSE;
		}

		$table_name = $this->_audit_clean_table($table);
		$old_rows = $this->_audit_snapshot_rows($table_name);
		$sql = $this->_delete($table);

		if ($reset_data)
		{
			$this->_reset_write();
		}

		if ($this->return_delete_sql === TRUE)
		{
			return $sql;
		}

		$result = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'delete',
				'table_name' => $table_name,
				'row_key' => $this->_audit_rows_key($old_rows),
				'old_values' => $old_rows,
				'new_values' => NULL,
				'changed_values' => $this->_audit_changes($old_rows, NULL),
				'sql_text' => $sql,
			));
		}

		return $result;
	}

	public function empty_table($table = '')
	{
		$table_name = $this->_audit_clean_table($table !== '' ? $table : (isset($this->qb_from[0]) ? $this->qb_from[0] : ''));
		$old_rows = $table_name !== '' ? $this->_audit_snapshot_all_rows($table_name) : array();
		$result = $this->_audit_without_raw_query_log(function () use ($table) {
			return parent::empty_table($table);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'empty_table',
				'table_name' => $table_name,
				'row_key' => NULL,
				'old_values' => $old_rows,
				'new_values' => NULL,
				'changed_values' => $this->_audit_changes($old_rows, NULL),
				'sql_text' => 'DELETE FROM '.$table_name,
			));
		}

		return $result;
	}

	public function truncate($table = '')
	{
		$table_name = $this->_audit_clean_table($table !== '' ? $table : (isset($this->qb_from[0]) ? $this->qb_from[0] : ''));
		$old_rows = $table_name !== '' ? $this->_audit_snapshot_all_rows($table_name) : array();
		$result = $this->_audit_without_raw_query_log(function () use ($table) {
			return parent::truncate($table);
		});

		if ($result && ! $this->_audit_should_skip_table($table_name))
		{
			$this->_audit_write_log(array(
				'action' => 'truncate',
				'table_name' => $table_name,
				'row_key' => NULL,
				'old_values' => $old_rows,
				'new_values' => NULL,
				'changed_values' => $this->_audit_changes($old_rows, NULL),
				'sql_text' => 'TRUNCATE '.$table_name,
			));
		}

		return $result;
	}

	protected function _audit_snapshot_rows($table)
	{
		if ($this->_audit_should_skip_table($table))
		{
			return array();
		}

		$select = $this->qb_select;
		$from = $this->qb_from;
		$order = $this->qb_orderby;
		$limit = $this->qb_limit;
		$offset = $this->qb_offset;

		$this->qb_select = array('*');
		$this->qb_from = array($this->protect_identifiers($table, TRUE, NULL, FALSE));
		$this->qb_orderby = array();
		$sql = $this->_compile_select();

		$this->qb_select = $select;
		$this->qb_from = $from;
		$this->qb_orderby = $order;
		$this->qb_limit = $limit;
		$this->qb_offset = $offset;

		$query = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		return $query ? $query->result_array() : array();
	}

	protected function _audit_snapshot_all_rows($table)
	{
		if ($this->_audit_should_skip_table($table))
		{
			return array();
		}

		$sql = 'SELECT * FROM '.$this->protect_identifiers($table, TRUE, NULL, FALSE);
		$query = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		return $query ? $query->result_array() : array();
	}

	protected function _audit_snapshot_by_ids($table, $index, array $ids)
	{
		if (empty($ids))
		{
			return array();
		}

		$escaped = array();
		foreach ($ids as $id)
		{
			$escaped[] = $this->escape($id);
		}

		$sql = 'SELECT * FROM '.$this->protect_identifiers($table, TRUE, NULL, FALSE)
			.' WHERE '.$this->protect_identifiers($index, TRUE, NULL, FALSE).' IN ('.implode(',', $escaped).')';
		$query = $this->_audit_without_raw_query_log(function () use ($sql) {
			return parent::query($sql);
		});

		return $query ? $query->result_array() : array();
	}

	protected function _audit_write_log(array $payload)
	{
		if ( ! $this->_audit_enabled || ! $this->_audit_table_exists())
		{
			return;
		}

		$context = $this->_audit_context();
		$data = array(
			'audit_action' => $payload['action'],
			'audit_table' => $payload['table_name'],
			'audit_row_key' => $payload['row_key'],
			'audit_old_values' => $this->_audit_json($payload['old_values']),
			'audit_new_values' => $this->_audit_json($payload['new_values']),
			'audit_changed_values' => $this->_audit_json($payload['changed_values']),
			'audit_sql' => $payload['sql_text'],
			'audit_controller' => $context['controller'],
			'audit_method' => $context['method'],
			'audit_uri' => $context['uri'],
			'audit_http_method' => $context['http_method'],
			'audit_ip' => $context['ip'],
			'audit_user_agent' => $context['user_agent'],
			'audit_user_id' => $context['user_id'],
			'audit_emp_id' => $context['emp_id'],
			'audit_com_id' => $context['com_id'],
			'audit_username' => $context['username'],
			'audit_user_display' => $context['user_display'],
			'audit_created_at' => date('Y-m-d H:i:s'),
		);

		$this->_audit_insert_with_separate_connection($data);
	}

	protected function _audit_insert_with_separate_connection(array $data)
	{
		$mysqli = $this->_audit_new_mysqli();
		if ($mysqli === NULL)
		{
			return;
		}
		$chain = $this->_audit_hash_chain($mysqli, $data);
		$data['audit_prev_hash'] = $chain['prev_hash'];
		$data['audit_hash'] = $chain['hash'];

		$fields = array();
		$values = array();
		foreach ($data as $field => $value)
		{
			$fields[] = '`'.str_replace('`', '``', $field).'`';
			$values[] = $value === NULL ? 'NULL' : "'".$mysqli->real_escape_string((string) $value)."'";
		}

		$sql = 'INSERT INTO `lms_audit_logs` ('.implode(',', $fields).') VALUES ('.implode(',', $values).')';
		if ( ! $mysqli->query($sql))
		{
			log_message('error', 'Audit log insert failed: '.$mysqli->error);
		}
		$mysqli->close();
	}

	protected function _audit_hash_chain($mysqli, array $data)
	{
		$prev_hash = NULL;
		$result = $mysqli->query("SELECT audit_hash FROM `lms_audit_logs` ORDER BY audit_id DESC LIMIT 1");
		if ($result && ($row = $result->fetch_assoc()))
		{
			$prev_hash = $row['audit_hash'];
		}

		$payload = $data;
		ksort($payload);
		$hash = hash('sha256', (string) $prev_hash.'|'.json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

		return array('prev_hash' => $prev_hash, 'hash' => $hash);
	}

	protected function _audit_new_mysqli()
	{
		$hostname = $this->hostname;
		$port = ini_get('mysqli.default_port');
		if (strpos($hostname, ':') !== FALSE)
		{
			list($hostname, $port) = explode(':', $hostname, 2);
		}
		elseif (isset($this->port) && $this->port !== '')
		{
			$port = $this->port;
		}

		$mysqli = @new mysqli($hostname, $this->username, $this->password, $this->database, (int) $port);
		if ($mysqli->connect_errno)
		{
			log_message('error', 'Audit log connection failed: '.$mysqli->connect_error);
			return NULL;
		}

		$mysqli->set_charset($this->char_set);
		return $mysqli;
	}

	protected function _audit_context()
	{
		$context = array(
			'controller' => NULL,
			'method' => NULL,
			'uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL,
			'http_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : NULL,
			'ip' => $this->_audit_client_ip(),
			'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL,
			'user_id' => NULL,
			'emp_id' => NULL,
			'com_id' => NULL,
			'username' => NULL,
			'user_display' => NULL,
		);

		if (class_exists('CI_Controller', FALSE))
		{
			$CI =& get_instance();
			if (isset($CI->router))
			{
				$context['controller'] = $CI->router->fetch_class();
				$context['method'] = $CI->router->fetch_method();
			}
			if (isset($CI->session))
			{
				$user = $CI->session->userdata('user');
				if (is_array($user))
				{
					$context['user_id'] = isset($user['u_id']) ? $user['u_id'] : NULL;
					$context['emp_id'] = isset($user['emp_id']) ? $user['emp_id'] : NULL;
					$context['com_id'] = isset($user['com_id']) ? $user['com_id'] : NULL;
					$context['username'] = isset($user['useri']) ? $user['useri'] : (isset($user['emp_c']) ? $user['emp_c'] : NULL);
					$context['user_display'] = isset($user['fullname_th']) ? $user['fullname_th'] : (isset($user['fullname_en']) ? $user['fullname_en'] : NULL);
				}
			}
		}

		return $context;
	}

	protected function _audit_without_raw_query_log($callback)
	{
		$enabled = $this->_audit_enabled;
		$this->_audit_enabled = FALSE;
		$result = $callback();
		$this->_audit_enabled = $enabled;

		return $result;
	}

	protected function _audit_changes($old, $new)
	{
		if ($old === NULL)
		{
			return array('before' => NULL, 'after' => $new);
		}
		if ($new === NULL)
		{
			return array('before' => $old, 'after' => NULL);
		}

		$rows = $this->_audit_is_list($old) ? $old : array($old);
		$changes = array();
		foreach ($rows as $row_index => $row)
		{
			if ( ! is_array($row))
			{
				continue;
			}
			foreach ($new as $field => $value)
			{
				if (array_key_exists($field, $row) && (string) $row[$field] !== (string) $value)
				{
					$changes[$row_index][$field] = array(
						'from' => $row[$field],
						'to' => $value,
					);
				}
				elseif ( ! array_key_exists($field, $row))
				{
					$changes[$row_index][$field] = array(
						'from' => NULL,
						'to' => $value,
					);
				}
			}
		}

		return $changes;
	}

	protected function _audit_unescape_qb_set(array $qb_set)
	{
		$data = array();
		foreach ($qb_set as $key => $value)
		{
			$key = trim(str_replace('`', '', $key));
			$data[$key] = $this->_audit_sql_literal_to_value($value);
		}

		return $data;
	}

	protected function _audit_sql_literal_to_value($value)
	{
		if ($value === 'NULL')
		{
			return NULL;
		}
		if (is_string($value) && strlen($value) >= 2 && $value[0] === "'" && substr($value, -1) === "'")
		{
			return str_replace("\\'", "'", substr($value, 1, -1));
		}

		return $value;
	}

	protected function _audit_clean_values($values)
	{
		if (is_object($values))
		{
			$values = get_object_vars($values);
		}

		if ( ! is_array($values))
		{
			return $values;
		}

		$clean = array();
		foreach ($values as $key => $value)
		{
			$clean[trim(str_replace('`', '', $key))] = is_object($value) ? get_object_vars($value) : $value;
		}

		return $clean;
	}

	protected function _audit_json($value)
	{
		if ($value === NULL)
		{
			return NULL;
		}

		$value = $this->_audit_mask_sensitive($value);
		return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	protected function _audit_table_exists()
	{
		if ($this->_audit_table_ready !== NULL)
		{
			return $this->_audit_table_ready;
		}

		$mysqli = $this->_audit_new_mysqli();
		if ($mysqli === NULL)
		{
			$this->_audit_table_ready = FALSE;
			return FALSE;
		}
		$result = $mysqli->query("SHOW TABLES LIKE 'lms_audit_logs'");
		$this->_audit_table_ready = ($result && $result->num_rows > 0);
		$mysqli->close();

		return $this->_audit_table_ready;
	}

	protected function _audit_mask_sensitive($value, $key_name = '')
	{
		if (is_array($value))
		{
			$masked = array();
			foreach ($value as $key => $item)
			{
				$masked[$key] = $this->_audit_mask_sensitive($item, (string) $key);
			}

			return $masked;
		}

		if (is_object($value))
		{
			return $this->_audit_mask_sensitive(get_object_vars($value), $key_name);
		}

		if ($key_name !== '' && preg_match('/(pass|password|userp|token|secret|csrf|session)/i', $key_name))
		{
			return '[masked]';
		}

		return $value;
	}

	protected function _audit_clean_table($table)
	{
		$table = trim((string) $table);
		$table = preg_replace('/\s+AS\s+.+$/i', '', $table);
		$table = preg_replace('/\s+.+$/', '', $table);
		return trim(str_replace('`', '', $table));
	}

	protected function _audit_should_skip_table($table)
	{
		$table = $this->_audit_clean_table($table);
		return $table === '' || in_array($table, $this->_audit_excluded_tables, TRUE);
	}

	protected function _audit_is_raw_write($sql)
	{
		if ( ! is_string($sql))
		{
			return FALSE;
		}

		return (bool) preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql);
	}

	protected function _audit_action_from_sql($sql)
	{
		preg_match('/^\s*(INSERT|UPDATE|DELETE|REPLACE|TRUNCATE)\s+/i', $sql, $matches);
		return isset($matches[1]) ? strtolower($matches[1]).'_raw' : 'query_raw';
	}

	protected function _audit_table_from_sql($sql)
	{
		if (preg_match('/^\s*INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/^\s*REPLACE\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/^\s*UPDATE\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/^\s*DELETE\s+FROM\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches))
		{
			return $matches[1];
		}
		if (preg_match('/^\s*TRUNCATE\s+`?([a-zA-Z0-9_]+)`?/i', $sql, $matches))
		{
			return $matches[1];
		}

		return NULL;
	}

	protected function _audit_interpolate_sql($sql, $binds)
	{
		if (empty($binds))
		{
			return $sql;
		}

		if ( ! is_array($binds))
		{
			$binds = array($binds);
		}

		foreach ($binds as $bind)
		{
			$sql = preg_replace('/\?/', $this->escape($bind), $sql, 1);
		}

		return $sql;
	}

	protected function _audit_raw_old_values($sql)
	{
		$table = $this->_audit_table_from_sql($sql);
		if ($this->_audit_should_skip_table($table))
		{
			return NULL;
		}

		$where = NULL;
		if (preg_match('/^\s*UPDATE\s+`?[a-zA-Z0-9_]+`?\s+SET\s+.+?\s+WHERE\s+(.+)$/is', $sql, $matches))
		{
			$where = $this->_audit_strip_order_limit($matches[1]);
		}
		elseif (preg_match('/^\s*DELETE\s+FROM\s+`?[a-zA-Z0-9_]+`?\s+WHERE\s+(.+)$/is', $sql, $matches))
		{
			$where = $this->_audit_strip_order_limit($matches[1]);
		}
		elseif (preg_match('/^\s*TRUNCATE\s+/i', $sql))
		{
			return $this->_audit_snapshot_all_rows($table);
		}

		if ($where === NULL || trim($where) === '')
		{
			return NULL;
		}

		$snapshot_sql = 'SELECT * FROM '.$this->protect_identifiers($table, TRUE, NULL, FALSE).' WHERE '.$where;
		$query = $this->_audit_without_raw_query_log(function () use ($snapshot_sql) {
			return parent::query($snapshot_sql);
		});

		return $query ? $query->result_array() : NULL;
	}

	protected function _audit_raw_new_values($sql)
	{
		if (preg_match('/^\s*UPDATE\s+`?[a-zA-Z0-9_]+`?\s+SET\s+(.+?)\s+WHERE\s+/is', $sql, $matches))
		{
			return $this->_audit_parse_assignments($matches[1]);
		}

		if (preg_match('/^\s*INSERT\s+INTO\s+`?[a-zA-Z0-9_]+`?\s*\((.+?)\)\s*VALUES\s*\((.+?)\)/is', $sql, $matches))
		{
			$fields = $this->_audit_split_sql_list($matches[1]);
			$values = $this->_audit_split_sql_list($matches[2]);
			$row = array();
			foreach ($fields as $index => $field)
			{
				$field = trim(str_replace('`', '', $field));
				$row[$field] = isset($values[$index]) ? $this->_audit_sql_literal_to_value(trim($values[$index])) : NULL;
			}

			return $row;
		}

		return NULL;
	}

	protected function _audit_parse_assignments($set_sql)
	{
		$parts = $this->_audit_split_sql_list($set_sql);
		$data = array();
		foreach ($parts as $part)
		{
			if (strpos($part, '=') === FALSE)
			{
				continue;
			}
			list($field, $value) = explode('=', $part, 2);
			$field = trim(str_replace('`', '', $field));
			$data[$field] = $this->_audit_sql_literal_to_value(trim($value));
		}

		return $data;
	}

	protected function _audit_split_sql_list($sql)
	{
		$items = array();
		$current = '';
		$quote = NULL;
		$length = strlen($sql);
		for ($i = 0; $i < $length; $i++)
		{
			$char = $sql[$i];
			if ($quote !== NULL)
			{
				$current .= $char;
				if ($char === $quote && ($i === 0 || $sql[$i - 1] !== '\\'))
				{
					$quote = NULL;
				}
				continue;
			}
			if ($char === "'" || $char === '"')
			{
				$quote = $char;
				$current .= $char;
				continue;
			}
			if ($char === ',')
			{
				$items[] = trim($current);
				$current = '';
				continue;
			}
			$current .= $char;
		}
		if (trim($current) !== '')
		{
			$items[] = trim($current);
		}

		return $items;
	}

	protected function _audit_strip_order_limit($where)
	{
		$where = preg_replace('/\s+ORDER\s+BY\s+.+$/is', '', $where);
		$where = preg_replace('/\s+LIMIT\s+\d+(\s*,\s*\d+)?\s*$/is', '', $where);
		return trim($where);
	}

	protected function _audit_insert_row_key()
	{
		$id = $this->insert_id();
		return $id ? (string) $id : NULL;
	}

	protected function _audit_rows_key($rows)
	{
		if ( ! is_array($rows) || empty($rows))
		{
			return NULL;
		}

		$keys = array('id', 'u_id', 'emp_id', 'cos_id', 'qcode', 'scode');
		$output = array();
		foreach ($rows as $row)
		{
			if ( ! is_array($row))
			{
				continue;
			}
			foreach ($keys as $key)
			{
				if (isset($row[$key]))
				{
					$output[] = $key.'='.$row[$key];
					break;
				}
			}
		}

		return empty($output) ? NULL : implode(',', $output);
	}

	protected function _audit_client_ip()
	{
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
		{
			if ( ! empty($_SERVER[$key]))
			{
				return $_SERVER[$key];
			}
		}

		return NULL;
	}

	protected function _audit_is_list(array $array)
	{
		return $array === array_values($array);
	}
}
