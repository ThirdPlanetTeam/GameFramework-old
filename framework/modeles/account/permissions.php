<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013-2014 Third Planet Team *
 **********************************/

class PermissionsModel extends Modeles {

	const GROUP_TABLE_NAME = 'gf_groups';
	const PERMS_TABLE_NAME = 'gf_perms';
	const LINK_GU_TABLE_NAME = 'gf_groups_users';
	const LINK_GP_TABLE_NAME = 'gf_groups_perms';

	const GROUP_ID = 'group_id';
	const PERM_ID = 'perm_id';
	const LINK_GP_GROUP = 'gp_groups';
	const LINK_GP_PERM = 'gp_perms';
	const LINK_GU_GROUP = 'gu_groups';
	const LINK_GU_USER = 'gu_users';

	const ADMIN_FIELD = 'group_admin'	;
	const PERM_FIELD = 'perm_code'	;



	public function getPermissions($userid) {

		Modeles::loadModel('account', 'account');

		$usr = $this->pdo->quote($userid, PDO::PARAM_INT);

		$query = 'SELECT '.PermissionsModel::ADMIN_FIELD.', '.PermissionsModel::PERM_FIELD.' 
			FROM '.AccountModel::TABLE_NAME.'
			LEFT JOIN '.	PermissionsModel::LINK_GU_TABLE_NAME.	' on ('.AccountModel::FIELD_ID.				' = '.PermissionsModel::LINK_GU_USER.')
			LEFT JOIN '.	PermissionsModel::GROUP_TABLE_NAME.		' on ('.PermissionsModel::LINK_GU_GROUP.	' = '.PermissionsModel::GROUP_ID.')
			LEFT JOIN '.	PermissionsModel::LINK_GP_TABLE_NAME.	' on ('.PermissionsModel::GROUP_ID.			' = '.PermissionsModel::LINK_GP_GROUP.')
			LEFT JOIN '.	PermissionsModel::PERMS_TABLE_NAME.		' on ('.PermissionsModel::LINK_GP_PERM.		' = '.PermissionsModel::PERM_ID.') 
			WHERE ' . AccountModel::FIELD_ID . " = $usr";

		$result = $this->select($query);

		Modeles::$nb_query[] = $query;

		return $result;
	}	
}