<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums;

enum Typo3SeoTabFieldsEnum: string
{
    /**
     * FIELDS
     */
    case CANONICAL_LINK = 'canonical_link';
    case HTML_TITLE = 'html_title';
    case META_ABSTRACT = 'meta_abstract';
    case META_DESCRIPTION = 'meta_description';
    case META_KEYWORDS = 'meta_keywords';
}
