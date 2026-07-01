<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Illuminate\Support\Facades\Cache;
use Filament\Facades\Filament;

/**
 * Helper class for filament-gaze functionality.
 * Provides methods to check if a record is currently being viewed by someone.
 */
class Gaze
{
    /**
     * Check if a record is currently being viewed by anyone.
     *
     * @param mixed $record The record to check
     * @param bool $excludeCurrentUser Whether to exclude the current user from the check
     * @return bool True if the record is being viewed by someone
     */
    public static function isOpened($record, bool $excludeCurrentUser = true): bool
    {
        if (!$record) {
            return false;
        }

        $identifier = static::getIdentifier($record);
        $viewers = Cache::get('filament-gaze-' . $identifier, []);
        
        if (empty($viewers)) {
            return false;
        }

        $authGuard = Filament::getCurrentPanel()?->getAuthGuard() ?? config('filament.default_auth_guard', 'web');
        $currentUserId = auth()->guard($authGuard)?->id();

        foreach ($viewers as $viewer) {
            // Skip if we should exclude current user and this is the current user
            if ($excludeCurrentUser && isset($viewer['id']) && $viewer['id'] == $currentUserId) {
                continue;
            }

            // Check if viewer is still valid (not expired)
            if (isset($viewer['expires']) && now() < now()->parse($viewer['expires'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the number of users currently viewing a record.
     *
     * @param mixed $record The record to check
     * @param bool $excludeCurrentUser Whether to exclude the current user from the count
     * @return int The number of users viewing the record
     */
    public static function getViewerCount($record, bool $excludeCurrentUser = true): int
    {
        if (!$record) {
            return 0;
        }

        $identifier = static::getIdentifier($record);
        $viewers = Cache::get('filament-gaze-' . $identifier, []);
        
        if (empty($viewers)) {
            return 0;
        }

        $authGuard = Filament::getCurrentPanel()?->getAuthGuard() ?? config('filament.default_auth_guard', 'web');
        $currentUserId = auth()->guard($authGuard)?->id();

        $count = 0;
        foreach ($viewers as $viewer) {
            // Skip if we should exclude current user and this is the current user
            if ($excludeCurrentUser && isset($viewer['id']) && $viewer['id'] == $currentUserId) {
                continue;
            }

            // Check if viewer is still valid (not expired)
            if (isset($viewer['expires']) && now() < now()->parse($viewer['expires'])) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get the identifier used by filament-gaze for a record.
     *
     * @param mixed $record The record
     * @return string The identifier
     */
    public static function getIdentifier($record): string
    {
        return get_class($record) . '-' . $record->getKey();
    }

    /**
     * Check if a record is currently locked by someone else.
     *
     * @param mixed $record The record to check
     * @return bool True if the record is locked by someone else
     */
    public static function isLockedByOther($record): bool
    {
        if (!$record) {
            return false;
        }

        $identifier = static::getIdentifier($record);
        $viewers = Cache::get('filament-gaze-' . $identifier, []);
        
        if (empty($viewers)) {
            return false;
        }

        $authGuard = Filament::getCurrentPanel()?->getAuthGuard() ?? config('filament.default_auth_guard', 'web');
        $currentUserId = auth()->guard($authGuard)?->id();

        foreach ($viewers as $viewer) {
            // Check if this viewer has control and is not the current user
            if (isset($viewer['has_control']) && $viewer['has_control'] === true && 
                isset($viewer['id']) && $viewer['id'] != $currentUserId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the users currently viewing a record.
     *
     * @param mixed $record The record to check
     * @param bool $excludeCurrentUser Whether to exclude the current user from the list
     * @return array Array of viewer information
     */
    public static function getViewers($record, bool $excludeCurrentUser = true): array
    {
        if (!$record) {
            return [];
        }

        $identifier = static::getIdentifier($record);
        $viewers = Cache::get('filament-gaze-' . $identifier, []);
        
        if (empty($viewers)) {
            return [];
        }

        $authGuard = Filament::getCurrentPanel()?->getAuthGuard() ?? config('filament.default_auth_guard', 'web');
        $currentUserId = auth()->guard($authGuard)?->id();

        $result = [];
        foreach ($viewers as $viewer) {
            // Skip if we should exclude current user and this is the current user
            if ($excludeCurrentUser && isset($viewer['id']) && $viewer['id'] == $currentUserId) {
                continue;
            }

            // Check if viewer is still valid (not expired)
            if (isset($viewer['expires']) && now() < now()->parse($viewer['expires'])) {
                $result[] = $viewer;
            }
        }

        return $result;
    }
}
