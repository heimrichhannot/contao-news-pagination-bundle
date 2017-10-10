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
$arrDca['palettes'][\HeimrichHannot\NewsPagination\NewsPagination::CONTENT_ELEMENT_NEWS_PAGINATION_START] =
    '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$arrDca['palettes'][\HeimrichHannot\NewsPagination\NewsPagination::CONTENT_ELEMENT_NEWS_PAGINATION_STOP]  =
    '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{invisible_legend:hide},invisible,start,stop';


/**
 * Fields
 */
$arrFields = [
    'newsPaginationStopCreated' => [
        'eval' => ['doNotCopy' => true],
        'sql' => "char(1) NOT NULL default ''"
    ]
];

$arrDca['fields'] += $arrFields;