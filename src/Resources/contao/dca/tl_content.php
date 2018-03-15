<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Callbacks
 */
$arrDca['config']['onsubmit_callback']['addNewsPaginationStopElement'] =
    ['HeimrichHannot\NewsPagination\Backend\Content', 'addNewsPaginationStopElement'];

/**
 * Palettes
 */
$arrDca['palettes'][\HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_START] =
    '{type_legend},type,newsPaginationTitle;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$arrDca['palettes'][\HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::CONTENT_ELEMENT_NEWS_PAGINATION_STOP]  =
    '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{invisible_legend:hide},invisible,start,stop';


/**
 * Fields
 */
$arrFields = [
    'newsPaginationTitle' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['newsPaginationTitle'],
        'exclude'                 => true,
        'search'                  => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength' => 255, 'tl_class' => 'w50'],
        'sql'                     => "varchar(255) NOT NULL default ''"
    ],
    'newsPaginationStopCreated' => [
        'eval' => ['doNotCopy' => true],
        'sql' => "char(1) NOT NULL default ''"
    ]
];

$arrDca['fields'] += $arrFields;