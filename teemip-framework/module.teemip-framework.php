<?php
/*
 * @copyright   Copyright (C) 2010-2025 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'teemip-framework/3.2.2',
	array(
		// Identification
		//
		'label' => 'TeemIp Framework',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/3.2.0',
		),
		'mandatory' => true,
		'visible' => false,

		// Components
		//
		'datamodel' => array(
			'vendor/autoload.php',
			'src/Model/AttributeAliasList.php',
			'src/Model/AttributeClassWithIP.php',
			'src/Model/AttributeDomainName.php',
			'src/Model/AttributeHostName.php',
			'src/Model/AttributeIPFieldInClass.php',
			'src/Model/AttributeIPPercentage.php',
			'src/Model/AttributeMacAddress.php',
			'src/Model/DashletBadgeFiltered.php',
            'src/Model/TeemIpObjectResult.php',
			'model.teemip-framework.php',
		),
		'webservice' => array(),
		'data.struct' => array(// add your 'structure' definition XML files here,
		),
		'data.sample' => array(// add your sample data XML files here,
			'data/data.sample.IPGlue.xml',
			'data/data.sample.IPConfig.xml',
			'data/data.sample.IPRangeUsage.xml',
			'data/data.sample.IPUsage.xml',
		),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(// Module specific settings go here, if any
		),
	)
);
