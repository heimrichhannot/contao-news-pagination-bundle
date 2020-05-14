<?php

$dca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$dca['palettes']['newsreader'] = str_replace('{template_legend', '{pagination_legend},paginationMode;{template_legend', $dca['palettes']['newsreader']);

/**
 * Subpalettes
 */
$dca['palettes']['__selector__'][] = 'paginationMode';
$dca['palettes']['__selector__'][] = 'addTextualPagination';

$dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_AUTO]                      = 'addTextualPagination,paginationMaxCharCount,avoidTrailingHeadlines,paginationCeTextCssSelector,fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks,templatePagination';
$dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL]                    = 'addTextualPagination,fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks,templatePagination';
$dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK] = 'addTextualPagination,paginationMaxCharCount,avoidTrailingHeadlines,paginationCeTextCssSelector,fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks,templatePagination';
$dca['subpalettes']['addTextualPagination']                                                                                        = 'textPaginationDelimiter,textPaginationMaxCharCount';

/**
 * Fields
 */
$fields = [
    'paginationMode'              => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationMode'],
        'exclude'   => true,
        'filter'    => true,
        'inputType' => 'select',
        'options'   => \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODES,
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['reference']['newsPaginationBundle'],
        'eval'      => ['tl_class' => 'w50', 'includeBlankOption' => true, 'submitOnChange' => true],
        'sql'       => "varchar(64) NOT NULL default ''",
    ],
    'addTextualPagination'        => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['addTextualPagination'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50', 'submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'textPaginationDelimiter'     => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['textPaginationDelimiter'],
        'exclude'   => true,
        'search'    => true,
        'inputType' => 'text',
        'eval'      => ['maxlength' => 8, 'tl_class' => 'w50', 'mandatory' => true],
        'sql'       => "varchar(8) NOT NULL default '…'",
    ],
    'templatePagination'          => [
        'label'            => &$GLOBALS['TL_LANG']['tl_module']['templatePagination'],
        'exclude'          => true,
        'filter'           => true,
        'inputType'        => 'select',
        'options_callback' => ['HeimrichHannot\NewsPagination\Backend\Backend', 'getPaginationTemplate'],
        'reference'        => &$GLOBALS['TL_LANG']['tl_module']['reference']['newsPaginationBundle'],
        'eval'             => ['tl_class' => 'w50', 'includeBlankOption' => true, 'submitOnChange' => true],
        'sql'              => "varchar(64) NOT NULL default ''",
    ],
    'textPaginationMaxCharCount'  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['textPaginationMaxCharCount'],
        'exclude'   => true,
        'search'    => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'digit', 'maxlength' => 10, 'tl_class' => 'w50', 'mandatory' => true],
        'sql'       => "int(10) unsigned NOT NULL default '80'",
    ],
    'paginationMaxCharCount'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationMaxCharCount'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'digit', 'tl_class' => 'w50 clr', 'mandatory' => true],
        'sql'       => "int(10) unsigned NOT NULL default '0'",
    ],
    'paginationCeTextCssSelector' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationCeTextCssSelector'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50', 'decodeEntities' => true],
        'sql'       => "varchar(128) NOT NULL default ''",
    ],
    'avoidTrailingHeadlines'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['avoidTrailingHeadlines'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default '1'",
    ],
    'fullVersionGetParameter'     => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['fullVersionGetParameter'],
        'exclude'   => true,
        'search'    => true,
        'inputType' => 'text',
        'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default 'full'",
    ],
    'acceptPrintGetParameter'     => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['acceptPrintGetParameter'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'addFullVersionCanonicalLink' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['addFullVersionCanonicalLink'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default '1'",
    ],
    'addPrevNextLinks'            => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['setPrevNextLinks'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 clr'],
        'sql'       => "char(1) NOT NULL default '1'",
    ],
];

$dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $fields);
