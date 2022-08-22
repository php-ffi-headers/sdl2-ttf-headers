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

    protected function skipIfVersionNotCompatible(Version $version, string $binary): void
    {
        $this->skipIfNoFFISupport();

        $ffi = \FFI::cdef(<<<'CPP'
        typedef struct SDL_version {
            uint8_t major;
            uint8_t minor;
            uint8_t patch;
        } SDL_version;

        extern const SDL_version * TTF_Linked_Version(void);
        CPP, $binary);

        $ver = $ffi->TTF_Linked_Version();
        $actual = \sprintf('%d.%d.%d', $ver->major, $ver->minor, $ver->patch);

        if (\version_compare($version->toString(), $actual, '>')) {
            $message = 'Unable to check compatibility because the installed version of the '
                . 'library (v%s) is lower than the tested headers (v%s)';

            $this->markTestSkipped(\sprintf($message, $actual, $version->toString()));
        }
    }

    /**
     * @requires OSFAMILY Windows
     *
     * @dataProvider configDataProvider
     */
    public function testWindowsBinaryCompatibility(Version $version): void
    {
        if (!\is_file(self::DIR_STORAGE . '/SDL2.dll')) {
            Downloader::zip(\vsprintf('https://www.libsdl.org/release/SDL2-2.24.0-win32-x64.zip', [
                $version->toString()
            ]))
                ->extract('SDL2.dll', self::DIR_STORAGE . '/SDL2.dll');
        }

        if (!\is_file($binary = self::DIR_STORAGE . '/SDL2_ttf.dll')) {
            Downloader::zip('https://github.com/libsdl-org/SDL_ttf/releases/download/release-2.20.1/SDL2_ttf-2.20.1-win32-x64.zip')
                ->extract('SDL2_ttf.dll', self::DIR_STORAGE . '/SDL2_ttf.dll');
        }

        // Set LoadLibrary linker directory
        \chdir(\dirname($binary));

        $this->skipIfVersionNotCompatible($version, $binary);
        $this->assertHeadersCompatibleWith(SDL2TTF::create($version), $binary);
    }

    /**
     * @requires OSFAMILY Darwin
     *
     * @dataProvider configDataProvider
     */
    public function testDarwinBinaryCompatibility(Version $version): void
    {
        $binary = Locator::resolve('libSDL2_ttf-2.0.0.dylib');

        if ($binary === null) {
            $this->markTestSkipped('sdl2_ttf not installed');
        }

        $this->skipIfVersionNotCompatible($version, $binary);
        $this->assertHeadersCompatibleWith(SDL2TTF::create($version), $binary);
    }

    /**
     * @requires OSFAMILY Linux
     *
     * @dataProvider configDataProvider
     */
    public function testLinuxBinaryCompatibility(Version $version): void
    {
        $binary = Locator::resolve('libSDL2_ttf-2.0.so.0');

        if ($binary === null) {
            $this->markTestSkipped('sdl2_ttf not installed');
        }

        $this->skipIfVersionNotCompatible($version, $binary);
        $this->assertHeadersCompatibleWith(SDL2TTF::create($version), $binary);
    }
}
