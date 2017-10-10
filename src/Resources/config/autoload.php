<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'HeimrichHannot\NewsPagination\NewsPagination'      => 'system/modules/news_pagination/classes/NewsPagination.php',
	'HeimrichHannot\NewsPagination\Hooks'               => 'system/modules/news_pagination/classes/Hooks.php',
	'HeimrichHannot\NewsPagination\Backend\Content'     => 'system/modules/news_pagination/classes/backend/Content.php',

	// Elements
	'HeimrichHannot\NewsPagination\NewsPaginationStart' => 'system/modules/news_pagination/elements/NewsPaginationStart.php',
	'HeimrichHannot\NewsPagination\NewsPaginationStop'  => 'system/modules/news_pagination/elements/NewsPaginationStop.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_news_pagination_start' => 'system/modules/news_pagination/templates',
	'ce_news_pagination_stop'  => 'system/modules/news_pagination/templates',
));
