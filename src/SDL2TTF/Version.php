<?php

/**
 * This file is part of FFI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FFI\Headers\SDL2TTF;

use FFI\Contracts\Headers\Version as CustomVersion;
use FFI\Contracts\Headers\Version\Comparable;
use FFI\Contracts\Headers\Version\ComparableInterface;
use FFI\Contracts\Headers\VersionInterface;

enum Version: string implements ComparableInterface
{
    use Comparable;

    case V2_0_8 = '2.0.8';
    case V2_0_9 = '2.0.9';
    case V2_0_10 = '2.0.10';
    case V2_0_11 = '2.0.11';
    case V2_0_12 = '2.0.12';
    case V2_0_13 = '2.0.13';
    case V2_0_14 = '2.0.14';
    case V2_0_15 = '2.0.15';
    case V2_0_18 = '2.0.18';
    case V2_20_0 = '2.20.0';
    case V2_20_1 = '2.20.1';

    public const LATEST = self::V2_20_1;

    /**
     * @param non-empty-string $version
     * @return VersionInterface
     */
    public static function create(string $version): VersionInterface
    {
        /** @var array<non-empty-string, VersionInterface> $versions */
        static $versions = [];

        return self::tryFrom($version)
            ?? $versions[$version]
            ??= CustomVersion::fromString($version);
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->value;
    }
}
