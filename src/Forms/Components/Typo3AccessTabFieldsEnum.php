<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

enum Typo3AccessTabFieldsEnum: string
{
    /**
     * FIELDS
     */
    case HIDDEN = 'hidden';
    case NAV_HIDE = 'nav_hide';
    case STARTTIME = 'starttime';
    case ENDTIME = 'endtime';

    /**
     * SECTIONS
     */
    case SECTION_VISIBILITY = 'Visibility';
    case SECTION_DATES = 'Publish Dates and Access Rights';

}
