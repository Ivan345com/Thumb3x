# Thumb3x

Image thumbnails for MODX Revolution 3 powered by [League Glide](https://glide.thephpleague.com/).

The snippet resizes, crops and converts images on the fly, stores the result in a cache folder and returns its URL.
Every generated file is recorded in the database; the manager page lists cached files and lets you remove them or clear the whole cache.

**Requirements:** MODX 3.x, PHP 8.1+, GD or Imagick.

## Installation

Install the transport package through the MODX Package Manager (Extras → Installer).

## Usage

```html
<img src="[[!Thumb3x? &input=`path/to/image.jpg` &options=`w=300&h=200&fit=crop`]]" alt="">
```

Fenom:

```
<img src="{'!Thumb3x' | snippet : [
    'input' => 'path/to/image.jpg',
    'options' => ['w' => 300, 'h' => 200, 'fit' => 'crop', 'q' => 75, 'fm' => 'webp']
]}" alt="">
```

The snippet returns a URL relative to the site root, so the page needs `<base href="[[!++site_url]]">`.
On any error it returns an empty string and writes the reason to the MODX error log.

### Snippet parameters

| Parameter | Description |
|---|---|
| `input` | Path to the source image within the media source |
| `options` | [Glide parameters](https://glide.thephpleague.com/3.0/api/quick-reference/): string `w=300&h=200` or array |
| `sourceId` | Media source ID (default: `thumb3x.source_id`) |
| `quality` | Default quality if `q` is not set |
| `format` | Default output format if `fm` is not set |
| `watermark` | Path to a watermark image from the site root |

### System settings (namespace `thumb3x`)

| Key | Default | Description |
|---|---|---|
| `thumb3x.source_id` | `1` | Default media source |
| `thumb3x.driver` | `gd` | `gd` or `imagick` |
| `thumb3x.cache_path` | `assets/components/thumb3x/cache/` | Cache folder from the site root |
| `thumb3x.cache_path_mode` | `source` | `source`, `hashed` or `flat` |
| `thumb3x.cache_file_rights` | `0644` | Permissions for cached files |
| `thumb3x.cache_dir_rights` | `0755` | Permissions for cache folders |

## Building from source

All libraries are included (`core/components/thumb3x/vendor/`), nothing else needs to be installed.
Put the repository folder into the root of a MODX 3 site and run `php _build/build.php`: it builds the transport package into `core/packages/` and installs it on that site.

## Documentation

- English: https://docthumb3x.ivan345.com/en/
- Русский: https://docs.ivan345.com/books/thumb3x/page/thumb3x-dokumentaciia

## License

[MIT](core/components/thumb3x/docs/license.txt)

---

## Кратко по-русски

Thumb3x — сниппет нарезки картинок для MODX 3 на League Glide: уменьшает, обрезает и конвертирует изображения, кладёт результат в кэш и возвращает адрес.
В админке — список файлов кэша с удалением и полной очисткой. Устанавливается пакетом через «Установщик» MODX.
Примеры вызова и настройки — выше, подробная документация — по ссылке в разделе Documentation.
