<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

return array(
	'insert' => array(
		'ansi' => '
			INSERT INTO "fos_user_list_type"( "siteid", "code", "domain", "label", "status",
				"mtime", "editor", "ctime" )
			VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )
		',
	),
	'update' => array(
		'ansi' => '
			UPDATE "fos_user_list_type"
			SET "siteid"=?, "code" = ?, "domain" = ?, "label" = ?, "status" = ?, "mtime" = ?, "editor" = ?
			WHERE "id" = ?
		',
	),
	'delete' => array(
		'ansi' => '
			DELETE FROM "fos_user_list_type"
			WHERE :cond AND siteid = ?
		',
	),
	'search' => array(
		'ansi' => '
			SELECT foslity."id" AS "customer.lists.type.id", foslity."siteid" AS "customer.lists.type.siteid",
				foslity."code" AS "customer.lists.type.code", foslity."domain" AS "customer.lists.type.domain",
				foslity."label" AS "customer.lists.type.label", foslity."status" AS "customer.lists.type.status",
				foslity."mtime" AS "customer.lists.type.mtime", foslity."editor" AS "customer.lists.type.editor",
				foslity."ctime" AS "customer.lists.type.ctime"
			FROM "fos_user_list_type" AS foslity
			:joins
			WHERE
				:cond
			/*-orderby*/ ORDER BY :order /*orderby-*/
			LIMIT :size OFFSET :start
		',
	),
	'count' => array(
		'ansi' => '
			SELECT COUNT(*) AS "count"
			FROM (
				SELECT DISTINCT foslity."id"
				FROM "fos_user_list_type" AS foslity
				:joins
				WHERE :cond
				LIMIT 10000 OFFSET 0
			) AS LIST
		',
	),
	'newid' => array(
		'db2' => 'SELECT IDENTITY_VAL_LOCAL()',
		'mysql' => 'SELECT LAST_INSERT_ID()',
		'oracle' => 'SELECT fos_user_list_type.CURRVAL FROM DUAL',
		'pgsql' => 'SELECT lastval()',
		'sqlite' => 'SELECT last_insert_rowid()',
		'sqlsrv' => 'SELECT SCOPE_IDENTITY()',
		'sqlanywhere' => 'SELECT @@IDENTITY',
	),
);
