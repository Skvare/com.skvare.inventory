<?php
return [
	[
		'name' => 'SavedSearch_Committee_Appointments',
		'entity' => 'SavedSearch',
		'cleanup' => 'always',
		'update' => 'unmodified',
		'params' => [
		'version' => 4,
		'values' => [
			'name' => 'Committee_Appointments',
			'label' => 'Committee Appointments',
			'form_values' => NULL,
			'mapping_id' => NULL,
			'search_custom_id' => NULL,
			'api_entity' => 'CommitteeAppointment',
			'api_params' => [
			'version' => 4,
			'select' => [
				'id',
				'CommitteeAppointment_Contact_contact_id_01.display_name',
				'CommitteeAppointment_Committee_committee_id_01.name',
				'CommitteeAppointment_CommitteeRole_committee_role_id_01.name',
				'start_date',
				'end_date',
				'eligibility_end_date',
				'is_active',
				'CommitteeAppointment_Contact_contact_id_01_Contact_Membership_contact_id_01.status_id:label',
			],
			'orderBy' => [],
			'where' => [],
			'groupBy' => [],
			'join' => [
				[
					'Committee AS CommitteeAppointment_Committee_committee_id_01',
					'INNER',
					[
						'committee_id',
						'=',
						'CommitteeAppointment_Committee_committee_id_01.id',
					],
					[
						'CommitteeAppointment_Committee_committee_id_01.is_active',
						'=',
						TRUE,
					],
				],
				[
					'Contact AS CommitteeAppointment_Contact_contact_id_01',
					'INNER',
					[
						'contact_id',
						'=',
						'CommitteeAppointment_Contact_contact_id_01.id',
					],
				],
				[
					'CommitteeRole AS CommitteeAppointment_CommitteeRole_committee_role_id_01',
					'INNER',
					[
						'committee_role_id',
						'=',
						'CommitteeAppointment_CommitteeRole_committee_role_id_01.id',
					],
					[
						'CommitteeAppointment_CommitteeRole_committee_role_id_01.is_active',
						'=',
						TRUE,
					],
				],
				[
					'Membership AS CommitteeAppointment_Contact_contact_id_01_Contact_Membership_contact_id_01',
					'LEFT',
					[
						'CommitteeAppointment_Contact_contact_id_01.id',
						'=',
						'CommitteeAppointment_Contact_contact_id_01_Contact_Membership_contact_id_01.contact_id',
					],
				],
			],
			'having' => [],
			],
			'expires_date' => NULL,
			'description' => NULL,
		],
		],
	],
	[
		'name' => 'SavedSearch_Committee_Appointments_SearchDisplay_Committee_Appointments_Table',
		'entity' => 'SearchDisplay',
		'cleanup' => 'always',
		'update' => 'unmodified',
		'params' => [
			'version' => 4,
			'values' => [
				'name' => 'Committee_Appointments_Table',
				'label' => 'Committee Appointments Table',
				'saved_search_id.name' => 'Committee_Appointments',
				'type' => 'table',
				'settings' => [
				'description' => NULL,
				'sort' => [],
				'limit' => 50,
				'pager' => [],
				'placeholder' => 5,
				'columns' => [
					[
						'type' => 'field',
						'key' => 'id',
						'dataType' => 'Integer',
						'label' => 'ID',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_Contact_contact_id_01.display_name',
						'dataType' => 'String',
						'label' => 'Contact Name',
						'sortable' => TRUE,
						'link' => [
							'path' => '',
							'entity' =>'Contact',
							'action' => 'view',
							'join' => 'CommitteeAppointment_Contact_contact_id_01',
							'target' => '_blank'
						],
						'title' => 'View Contact'
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_Committee_committee_id_01.name',
						'dataType' => 'String',
						'label' => 'Committee',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_CommitteeRole_committee_role_id_01.name',
						'dataType' => 'String',
						'label' => 'Role',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'start_date',
						'dataType' => 'Date',
						'label' => 'Appointment Start Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'end_date',
						'dataType' => 'Date',
						'label' => 'Annual Appointment End Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'eligibility_end_date',
						'dataType' => 'Date',
						'label' => 'Eligibility End Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'is_active',
						'dataType' => 'Boolean',
						'label' => 'Is Active',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_Contact_contact_id_01_Contact_Membership_contact_id_01.status_id:label',
						'dataType' => 'Integer',
						'label' => ts('Membership Status'),
						'sortable' => TRUE,
					],
					[
						'size' => 'btn-xs',
						'links' => [
							[
								'task' => 'disable',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-toggle-off',
								'text' => ts('Disable'),
								'style' => 'default',
								'path' => '',
								'action' => '',
								'condition' => [
									'is_active',
									'=',
									TRUE,
								],
							],
								[
								'task' => 'enable',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-toggle-on',
								'text' => ts('Enable'),
								'style' => 'default',
								'path' => '',
								'action' => '',
								'condition' => [
									'is_active',
									'=',
									FALSE,
								],
							],
							[
								'entity' => 'CommitteeAppointment',
								'action' => 'edit',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-external-link',
								'text' => 'Edit',
								'style' => 'default',
								'path' => '',
								'condition' => [],
							],
							[
								'task' => 'delete',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-trash',
								'text' => ts('Delete'),
								'style' => 'danger',
								'path' => '',
								'action' => '',
								'condition' => [],
							],
						],
						'type' => 'buttons',
						'alignment' => 'text-right',
					],
				],
				'actions' => TRUE,
				'classes' => [
					'table',
					'table-striped',
				],
				],
				'acl_bypass' => FALSE,
			],
		],
	],
	[
		'name' => 'SavedSearch_Committee_Appointments_SearchDisplay_Contact_Committee_Appointments_Table',
		'entity' => 'SearchDisplay',
		'cleanup' => 'always',
		'update' => 'unmodified',
		'params' => [
			'version' => 4,
			'values' => [
				'name' => 'Contact_Committee_Appointments',
				'label' => 'Contact Committee Appointments',
				'saved_search_id.name' => 'Committee_Appointments',
				'type' => 'table',
				'settings' => [
				'description' => NULL,
				'sort' => [],
				'limit' => 50,
				'pager' => [],
				'placeholder' => 5,
				'columns' => [
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_Committee_committee_id_01.name',
						'dataType' => 'String',
						'label' => 'Committee',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_CommitteeRole_committee_role_id_01.name',
						'dataType' => 'String',
						'label' => 'Role',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'start_date',
						'dataType' => 'Date',
						'label' => 'Appointment Start Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'end_date',
						'dataType' => 'Date',
						'label' => 'Annual Appointment End Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'eligibility_end_date',
						'dataType' => 'Date',
						'label' => 'Eligibility End Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'is_active',
						'dataType' => 'Boolean',
						'label' => 'Is Active',
						'sortable' => TRUE,
					],
					[
						'size' => 'btn-xs',
						'links' => [
							[
								'task' => 'disable',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-toggle-off',
								'text' => ts('Disable'),
								'style' => 'default',
								'path' => '',
								'action' => '',
								'condition' => [
									'is_active',
									'=',
									TRUE,
								],
							],
								[
								'task' => 'enable',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-toggle-on',
								'text' => ts('Enable'),
								'style' => 'default',
								'path' => '',
								'action' => '',
								'condition' => [
									'is_active',
									'=',
									FALSE,
								],
							],
							[
								'entity' => 'CommitteeAppointment',
								'action' => 'edit',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-external-link',
								'text' => 'Edit',
								'style' => 'default',
								'path' => '',
								'condition' => [],
							],
							[
								'task' => 'delete',
								'entity' => 'CommitteeAppointment',
								'join' => '',
								'target' => 'crm-popup',
								'icon' => 'fa-trash',
								'text' => ts('Delete'),
								'style' => 'danger',
								'path' => '',
								'action' => '',
								'condition' => [],
							],
						],
						'type' => 'buttons',
						'alignment' => 'text-right',
					],
				],
				'actions' => TRUE,
				'classes' => [
					'table',
					'table-striped',
				],
				],
				'acl_bypass' => FALSE,
			],
		],
	],
	[
		'name' => 'SavedSearch_Profile_Committee_Appointments',
		'entity' => 'SavedSearch',
		'cleanup' => 'always',
		'update' => 'unmodified',
		'params' => [
		'version' => 4,
		'values' => [
			'name' => 'Profile_Committee_Appointments',
			'label' => 'Profile Committee Appointments',
			'form_values' => NULL,
			'mapping_id' => NULL,
			'search_custom_id' => NULL,
			'api_entity' => 'CommitteeAppointment',
			'api_params' => [
			'version' => 4,
			'select' => [
				'id',
				'CommitteeAppointment_Contact_contact_id_01.display_name',
				'CommitteeAppointment_Committee_committee_id_01.name',
				'CommitteeAppointment_CommitteeRole_committee_role_id_01.name',
				'start_date',
				'end_date',
				'eligibility_end_date',
				'is_active',
			],
			'orderBy' => [],
			'where' => [],
			'groupBy' => [],
			'join' => [
				[
				'Committee AS CommitteeAppointment_Committee_committee_id_01',
				'INNER',
				[
					'committee_id',
					'=',
					'CommitteeAppointment_Committee_committee_id_01.id',
				],
				[
					'CommitteeAppointment_Committee_committee_id_01.is_active',
					'=',
					TRUE,
				],
				],
				[
				'Contact AS CommitteeAppointment_Contact_contact_id_01',
				'INNER',
				[
					'contact_id',
					'=',
					'CommitteeAppointment_Contact_contact_id_01.id',
				],
				[
					'CommitteeAppointment_Contact_contact_id_01.id',
					'=',
					'"user_contact_id"',
				],
				],
				[
				'CommitteeRole AS CommitteeAppointment_CommitteeRole_committee_role_id_01',
				'INNER',
				[
					'committee_role_id',
					'=',
					'CommitteeAppointment_CommitteeRole_committee_role_id_01.id',
				],
				[
					'CommitteeAppointment_CommitteeRole_committee_role_id_01.is_active',
					'=',
					TRUE,
				],
				],
			],
			'having' => [],
			],
			'expires_date' => NULL,
			'description' => NULL,
		],
		],
	],
	[
		'name' => 'SavedSearch_Profile_Committee_Appointments_SearchDisplay_Profile_Committee_Appointments_Table',
		'entity' => 'SearchDisplay',
		'cleanup' => 'always',
		'update' => 'unmodified',
		'params' => [
			'version' => 4,
			'values' => [
				'name' => 'Profile_Committee_Appointment',
				'label' => 'Profile Committee Appointments',
				'saved_search_id.name' => 'Profile_Committee_Appointments',
				'type' => 'table',
				'settings' => [
				'description' => NULL,
				'sort' => [],
				'limit' => 50,
				'pager' => [],
				'placeholder' => 5,
				'columns' => [
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_Committee_committee_id_01.name',
						'dataType' => 'String',
						'label' => 'Committee',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'CommitteeAppointment_CommitteeRole_committee_role_id_01.name',
						'dataType' => 'String',
						'label' => 'Role',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'start_date',
						'dataType' => 'Date',
						'label' => 'Appointment Start Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'end_date',
						'dataType' => 'Date',
						'label' => 'Annual Appointment End Date',
						'sortable' => TRUE,
					],
					[
						'type' => 'field',
						'key' => 'eligibility_end_date',
						'dataType' => 'Date',
						'label' => 'Eligibility End Date',
						'sortable' => TRUE,
					],
				],
				'actions' => FALSE,
				'classes' => [
					'table',
					'table-striped',
				],
				],
				'acl_bypass' => TRUE,
			],
		],
	],
];
