<?php
/**
 * @copyright   Copyright (C) 2023 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

SetupWebPage::AddModule(
	__FILE__,
	'teemip-datacenter-mgmt-adaptor/3.1.1',
	array(
		// Identification
		//
		'label' => 'TeemIp adaptor for iTop Datacenter Management',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-datacenter-mgmt/3.0.0',
			'teemip-ip-mgmt/3.1.2',
			'teemip-config-mgmt-adaptor/3.1.1',
		),
		'mandatory' => false,
		'visible' => true, // To prevent auto-install but shall not be listed in the install wizard
		'auto_select' => 'SetupInfo::ModuleIsSelected("itop-datacenter-mgmt") && SetupInfo::ModuleIsSelected("teemip-ip-mgmt") && SetupInfo::ModuleIsSelected("teemip-config-mgmt-adaptor")',

		// Components
		//
		'datamodel' => array(),
		'data.struct' => array(),
		'data.sample' => array(
			'data/data.sample.Rack.xml',
		),

		// Documentation
		//
		'doc.manual_setup' => '',
		'doc.more_information' => '',

		// Default settings
		//
		'settings' => array(),
	)
);
