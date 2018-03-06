<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$arrDca['palettes']['newsreader'] =
    str_replace('{template_legend', '{pagination_legend},paginationMode;{template_legend', $arrDca['palettes']['newsreader']);

/**
 * Subpalettes
 */
$arrDca['palettes']['__selector__'][]                                                                                                 = 'paginationMode';
$arrDca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_AUTO]                      =
    'paginationMaxCharCount,avoidTrailingHeadlines,paginationCeTextCssSelector,fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks';
$arrDca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL]                    =
    'fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks';
$arrDca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK] =
    'paginationMaxCharCount,avoidTrailingHeadlines,paginationCeTextCssSelector,fullVersionGetParameter,acceptPrintGetParameter,addFullVersionCanonicalLink,addPrevNextLinks';

/**
 * Fields
 */
$arrFields = [
    'paginationMode'              => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationMode'],
        'exclude'   => true,
        'filter'    => true,
        'inputType' => 'select',
        'options'   => \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODES,
        'reference' => &$GLOBALS['TL_LANG']['tl_module']['reference']['newsPaginationBundle'],
        'eval'      => ['tl_class' => 'w50', 'includeBlankOption' => true, 'submitOnChange' => true],
        'sql'       => "varchar(64) NOT NULL default ''"
    ],
    'paginationMaxCharCount'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationMaxCharCount'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'digit', 'tl_class' => 'w50 clr', 'mandatory' => true],
        'sql'       => "int(10) unsigned NOT NULL default '0'"
    ],
    'paginationCeTextCssSelector' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['paginationCeTextCssSelector'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50', 'decodeEntities' => true],
        'sql'       => "varchar(128) NOT NULL default ''"
    ],
    'avoidTrailingHeadlines'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['avoidTrailingHeadlines'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default '1'"
    ],
    'fullVersionGetParameter'     => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['fullVersionGetParameter'],
        'exclude'   => true,
        'search'    => true,
        'inputType' => 'text',
        'eval'      => ['maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default 'full'"
    ],
    'acceptPrintGetParameter'     => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['acceptPrintGetParameter'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'addFullVersionCanonicalLink' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['addFullVersionCanonicalLink'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50'],
        'sql'       => "char(1) NOT NULL default '1'"
    ],
    'addPrevNextLinks'            => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['setPrevNextLinks'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 clr'],
        'sql'       => "char(1) NOT NULL default '1'"
    ],
];

$arrDca['fields'] += $arrFields;
