<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Thin wrapper around the cache facade implementing the project's cache
 * standard (docs/CLAUDE.md Section 13): key pattern {module}:{type}:{id},
 * fixed TTLs for lists vs. detail pages, and explicit invalidation.
 *
 * Deliberately does not use Cache::tags() — the 'database' cache driver
 * (sometimes active locally, even though Redis is the standard for every
 * environment per Section 13) does not support tag-based stores, and this
 * class needs to behave correctly regardless of which store is active.
 */
class CacheService
{
    /**
     * TTL (seconds) for cached list/collection results, e.g. paginated
     * public listings.
     */
    public const int LIST_TTL = 3600;

    /**
     * TTL (seconds) for cached single published-content detail lookups.
     */
    public const int DETAIL_TTL = 86400;

    /**
     * Fetch $key from cache, or compute and store it via $callback for
     * $ttlSeconds if missing.
     */
    public function remember(string $key, int $ttlSeconds, Closure $callback): mixed
    {
        return Cache::remember($key, $ttlSeconds, $callback);
    }

    /**
     * Forget a single cache key.
     */
    public function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Forget several cache keys at once.
     *
     * @param  array<int, string>  $keys
     */
    public function forgetMany(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
