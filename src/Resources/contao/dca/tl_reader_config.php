<?php

if (class_exists('HeimrichHannot\ReaderBundle\HeimrichHannotContaoReaderBundle')) {
    System::getContainer()->get('huh.utils.dca')->loadDc('tl_module');
    System::getContainer()->get('huh.utils.dca')->loadLanguageFile('tl_module');

    $dca       = &$GLOBALS['TL_DCA']['tl_reader_config'];
    $dcaModule = $GLOBALS['TL_DCA']['tl_module'];

    /**
     * Palettes
     */
    $dca['palettes']['__selector__'][] = 'paginationMode';
    $dca['palettes']['__selector__'][] = 'addTextualPagination';

    $dca['palettes']['default'] = str_replace('{template_legend', '{pagination_legend},paginationMode;{template_legend', $dca['palettes']['default']);

    /**
     * Subpalettes
     */
    $dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_AUTO]
        = $dcaModule['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_AUTO];
    $dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL]
        = $dcaModule['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL];
    $dca['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK]
        = $dcaModule['subpalettes']['paginationMode_' . \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK];
    $dca['subpalettes']['addTextualPagination']
        = $dcaModule['subpalettes']['addTextualPagination'];

    /**
     * Fields
     */
    $fields = [
        'paginationMode'              => $dcaModule['fields']['paginationMode'],
        'addTextualPagination'        => $dcaModule['fields']['addTextualPagination'],
        'textPaginationDelimiter'     => $dcaModule['fields']['textPaginationDelimiter'],
        'templatePagination'          => $dcaModule['fields']['templatePagination'],
        'textPaginationMaxCharCount'  => $dcaModule['fields']['textPaginationMaxCharCount'],
        'paginationMaxCharCount'      => $dcaModule['fields']['paginationMaxCharCount'],
        'paginationCeTextCssSelector' => $dcaModule['fields']['paginationCeTextCssSelector'],
        'avoidTrailingHeadlines'      => $dcaModule['fields']['avoidTrailingHeadlines'],
        'fullVersionGetParameter'     => $dcaModule['fields']['fullVersionGetParameter'],
        'acceptPrintGetParameter'     => $dcaModule['fields']['acceptPrintGetParameter'],
        'addFullVersionCanonicalLink' => $dcaModule['fields']['addFullVersionCanonicalLink'],
        'addPrevNextLinks'            => $dcaModule['fields']['addPrevNextLinks'],
    ];

    $dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $fields);
}
