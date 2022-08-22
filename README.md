<p align="center">
    <a href="https://github.com/php-ffi-headers">
        <img src="https://avatars.githubusercontent.com/u/101121010?s=256" width="128" />
    </a>
</p>

<p align="center">
    <a href="https://github.com/php-ffi-headers/sdl2-ttf-headers/actions"><img src="https://github.com/php-ffi-headers/sdl2-ttf-headers/workflows/build/badge.svg"></a>
    <a href="https://packagist.org/packages/ffi-headers/sdl2-ttf-headers"><img src="https://img.shields.io/badge/PHP-8.1.0-ff0140.svg"></a>
    <a href="https://packagist.org/packages/ffi-headers/sdl2-ttf-headers"><img src="https://img.shields.io/badge/SDL2%20TTF-2.20.1-cc3c20.svg"></a>
    <a href="https://packagist.org/packages/ffi-headers/sdl2-ttf-headers"><img src="https://poser.pugx.org/ffi-headers/sdl2-ttf-headers/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/ffi-headers/sdl2-ttf-headers"><img src="https://poser.pugx.org/ffi-headers/sdl2-ttf-headers/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://packagist.org/packages/ffi-headers/sdl2-ttf-headers"><img src="https://poser.pugx.org/ffi-headers/sdl2-ttf-headers/downloads" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/php-ffi-headers/sdl2-ttf-headers/master/LICENSE.md"><img src="https://poser.pugx.org/ffi-headers/sdl2-ttf-headers/license" alt="License MIT"></a>
</p>

# SDL2 TTF Headers

This is a C headers of the [SDL2 TTF](https://github.com/libsdl-org/SDL_ttf) adopted for PHP.

## Requirements

- PHP >= 8.1

## Installation

Library is available as composer repository and can be installed using the
following command in a root of your project.

```sh
$ composer require ffi-headers/sdl2-ttf-headers
```

## Usage

```php
use FFI\Headers\SDL2TTF;

$headers = SDL2TTF::create(
    SDL2TTF\Version::V2_0_18,
);

echo $headers;
```

> Please note that the use of header files is not the latest version:
> - Takes time to download and install (This will be done in the background
    >   during initialization).
> - May not be compatible with the PHP headers library.

