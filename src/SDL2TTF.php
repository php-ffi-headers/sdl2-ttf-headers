<?php

/**
 * This file is part of FFI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FFI\Headers;

use FFI\Contracts\Headers\HeaderInterface;
use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Contracts\Preprocessor\Exception\PreprocessorExceptionInterface;
use FFI\Contracts\Preprocessor\PreprocessorInterface;
use FFI\Headers\SDL2TTF\HeadersDownloader;
use FFI\Headers\SDL2TTF\Version;
use FFI\Contracts\Headers\VersionInterface;
use FFI\Preprocessor\Preprocessor;

class SDL2TTF implements HeaderInterface
{
    /**
     * @var non-empty-string
     */
    private const HEADERS_DIRECTORY = __DIR__ . '/../resources/headers';

    /**
     * @param PreprocessorInterface $pre
     * @param VersionInterface $version
     */
    public function __construct(
        public readonly PreprocessorInterface $pre,
        public readonly VersionInterface $version = Version::LATEST,
    ) {
        if (!$this->exists()) {
            HeadersDownloader::download($this->version, self::HEADERS_DIRECTORY);

            if (!$this->exists()) {
                throw new \RuntimeException('Could not initialize (download) header files');
            }
        }
    }

    /**
     * @return bool
     */
    private function exists(): bool
    {
        return \is_file($this->getHeaderPathname());
    }

    /**
     * @return non-empty-string
     */
    public function getHeaderPathname(): string
    {
        return self::HEADERS_DIRECTORY . '/' . $this->version->toString() . '/SDL_ttf.h';
    }

    /**
     * @param VersionInterface|non-empty-string $version
     * @param PreprocessorInterface $pre
     * @return self
     * @throws DirectiveDefinitionExceptionInterface
     */
    public static function create(
        VersionInterface|string $version = Version::LATEST,
        PreprocessorInterface $pre = new Preprocessor(),
    ): self {
        $pre = clone $pre;

        $pre->add('SDL.h', <<<'CPP'
        typedef enum { SDL_FALSE = 0, SDL_TRUE = 1 } SDL_bool;

        typedef uint16_t Uint16;
        typedef uint32_t Uint32;

        typedef struct SDL_version SDL_version;
        typedef struct SDL_RWops SDL_RWops;
        typedef struct SDL_Surface SDL_Surface;
        typedef struct SDL_Color SDL_Color;
        CPP);

        $pre->add('begin_code.h', '');
        $pre->add('close_code.h', '');

        $pre->define('DECLSPEC', '');
        $pre->define('SDLCALL', '');

        if (!$version instanceof VersionInterface) {
            $version = Version::create($version);
        }

        return new self($pre, $version);
    }

    /**
     * @return non-empty-string
     * @throws PreprocessorExceptionInterface
     */
    public function __toString(): string
    {
        return $this->pre->process(new \SplFileInfo($this->getHeaderPathname())) . \PHP_EOL;
    }
}
