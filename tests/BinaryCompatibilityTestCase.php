<?php

/**
 * This file is part of FFI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FFI\Headers\SDL2TTF\Tests;

use FFI\Headers\SDL2TTF;
use FFI\Headers\SDL2TTF\Version;
use FFI\Headers\Testing\Downloader;
use FFI\Location\Locator;

class BinaryCompatibilityTestCase extends TestCase
{
    private const DIR_STORAGE = __DIR__ . '/storage';

    /**
     * @requires OSFAMILY Windows
     *
     * @dataProvider configDataProvider
     */
    public function testWindowsBinaryCompatibility(Version $version): void
    {
        if (!\is_file(self::DIR_STORAGE . '/SDL2.dll')) {
            Downloader::zip(\vsprintf('https://www.libsdl.org/release/SDL2-2.0.10-win32-x64.zip', [
                $version->toString()
            ]))
                ->extract('SDL2.dll', self::DIR_STORAGE . '/SDL2.dll');
        }

        if (!\is_file(self::DIR_STORAGE . '/SDL2_ttf.dll')) {
            Downloader::zip('https://github.com/libsdl-org/SDL_ttf/releases/download/release-2.0.18/SDL2_ttf-2.0.18-win32-x64.zip')
                ->extract('SDL2_ttf.dll', self::DIR_STORAGE . '/SDL2_ttf.dll');
        }

        $this->assertHeadersCompatibleWith(
            SDL2TTF::create($version),
            self::DIR_STORAGE . '/SDL2_ttf.dll'
        );
    }

    /**
     * @requires OSFAMILY Darwin
     *
     * @dataProvider configDataProvider
     */
    public function testDarwinBinaryCompatibility(Version $version): void
    {
        if (!Locator::exists('libSDL2_ttf-2.0.0.dylib')) {
            $this->markTestSkipped('sdl2_ttf not installed');
        }

        $this->assertHeadersCompatibleWith(
            SDL2TTF::create($version),
            Locator::resolve('libSDL2_ttf-2.0.0.dylib')
        );
    }

    /**
     * @requires OSFAMILY Linux
     *
     * @dataProvider configDataProvider
     */
    public function testLinuxBinaryCompatibility(Version $version): void
    {
        if (!Locator::exists('libSDL2_ttf-2.0.so.0')) {
            $this->markTestSkipped('sdl2_ttf not installed');
        }

        $this->assertHeadersCompatibleWith(
            SDL2TTF::create($version),
            Locator::resolve('libSDL2_ttf-2.0.so.0')
        );
    }
}
