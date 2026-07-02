<?php

namespace Egg2CodeLabs\FilamentTypo3\Actions;

enum AnchorTargetEnum: string
{
    case SELF = '_self';
    case BLANK = '_blank';
    case PARENT = '_parent';
    case TOP = '_top';
    case UNFENCED_TOP = '_unfencedTop';
}
