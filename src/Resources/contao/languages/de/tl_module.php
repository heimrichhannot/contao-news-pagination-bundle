<?php

$lang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$lang['paginationMode'][0]                    = 'Paginierungsmodus';
$lang['paginationMode'][1]                    = 'Wählen Sie hier einen Paginierungsmodus aus.';
$lang['addTextualPagination'][0]              = 'Textuelle Paginierung hinzufügen';
$lang['addTextualPagination'][1]              = 'Wählen Sie diese Option, wenn die Paginierung nicht nur numerisch gerendert werden soll, sondern Teaser der jeweiligen Seite enthalten soll.';
$lang['textPaginationDelimiter'][0]           = 'Abtrennungszeichen für die textuelle Paginierung';
$lang['textPaginationDelimiter'][1]           = 'Geben Sie hier ein Abtrennungszeichen ein.';
$lang['textPaginationMaxCharCount'][0]        = 'Maximale Zeichenanzahl für die textuelle Paginierung';
$lang['textPaginationMaxCharCount'][1]        = 'Geben Sie hier die maximale Anzahl von Zeichen ein, nach der ein Seitenteaser abgeschnitten werden soll.';
$lang['textPaginationAddReadOnSinglePage'][0] = 'Link "Auf einer Seite lesen" hinzufügen';
$lang['textPaginationAddReadOnSinglePage'][1] = 'Wählen Sie diese Option, wenn der textuellen Paginierung ein Link zur Deaktivierung der Paginierung hinzugefügt werden soll.';
$lang['paginationMaxCharCount'][0]            = 'Maximale Zeichenanzahl';
$lang['paginationMaxCharCount'][1]            = 'Geben Sie hier an, wie viele Zeichen ein Abschnitt der Paginierung enthalten soll.';
$lang['paginationCeTextCssSelector'][0]       = 'CSS-Selektor in ce_text';
$lang['paginationCeTextCssSelector'][1]       = 'Wenn Sie das Template "ce_text" überschrieben haben, können Sie hier den CSS-Selektor anpassen, der zu den Elementen führt, die den Text enthalten, also bspw. den p-Elementen (Standard: ".ce_text").';
$lang['avoidTrailingHeadlines'][0]            = 'Überschriften am Ende einer Seite vermeiden';
$lang['avoidTrailingHeadlines'][1]            = 'Wählen Sie diese Option, um Überschriften und Absätze zusammenzuhalten.';
$lang['addFullVersionCanonicalLink'][0]       = 'Canonical-Link zum Head-Element hinzufügen';
$lang['addFullVersionCanonicalLink'][1]       = 'Wählen Sie diese Option, um dem &lt;head&gt;-Element Informationen zur Paginierung hinzuzufügen.';
$lang['fullVersionGetParameter'][0]           = 'GET-Parameter für nichtpaginierte Version der Nachricht';
$lang['fullVersionGetParameter'][1]           = 'Geben Sie hier den Namen des Parameters ein, der vorhanden sein muss, damit die nichtpaginierte Version der Nachricht angezeigt wird. Sinnvoll aus SEO-Gründen.';
$lang['acceptPrintGetParameter'][0]           = 'GET-Parameter "print" für nichtpaginierte Version der Nachricht akzeptieren';
$lang['acceptPrintGetParameter'][1]           = 'Wählen Sie diese Option, damit die nichtpaginierte Version der Nachricht angezeigt wird, wenn der Parameter "print" vorhanden ist. Sinnvoll für eine Druckenfunktion.';
$lang['setPrevNextLinks'][0]                  = 'Prev- und Next-Links zum Head-Element hinzufügen';
$lang['setPrevNextLinks'][1]                  = 'Wählen Sie diese Option, um dem &lt;head&gt;-Element die entsprechenden Relationslinks hinzuzufügen.';

/**
 * Legends
 */
$lang['pagination_legend'] = 'Paginierung';

$lang['reference']['newsPaginationBundle'] = [
    \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_AUTO                      => 'Automatisch',
    \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL                    => 'Manuell',
    \HeimrichHannot\NewsPaginationBundle\NewsPaginationBundle::MODE_MANUAL_WITH_AUTO_FALLBACK => 'Manuell mit Fallback auf Automatisch',
];