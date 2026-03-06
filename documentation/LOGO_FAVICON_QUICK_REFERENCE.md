# Quick Reference: Logo & Favicon Helpers

## At a Glance

| Need | Usage | Returns |
|------|-------|---------|
| Logo URL | `logo()` | `/logo/poricito.png` |
| Logo IMG Tag | `logo('Alt', ['class' => 'w-32'])` | `<img src="/logo/poricito.png" alt="Alt" class="w-32">` |
| Transparent Logo URL | `logoTransparent()` | `/logo/poricito_transparent.png` |
| Transparent Logo IMG | `logoTransparent('Alt', ['class' => 'w-32'])` | `<img src="/logo/poricito_transparent.png" alt="Alt" class="w-32">` |
| Favicon URL | `favicon()` | `/favicon/favicon.ico` |
| All Favicon Links | `faviconLinks()` | Complete HTML for all favicons |

## Ready-Made Components

```blade
<!-- Logo Component -->
<x-logo alt="Logo" class="w-32" />

<!-- Transparent Logo Component -->
<x-logo-transparent alt="Transparent" class="w-32" />
```

## Most Common Uses

### In Views
```blade
<!-- Header Logo -->
<header>
    <x-logo alt="App Logo" class="h-12" />
</header>

<!-- Footer Logo -->
<footer>
    <x-logo-transparent alt="Company" class="w-48" />
</footer>
```

### In Controllers
```php
$logoUrl = logo();  // Get URL string
$faviconUrl = favicon();  // Get favicon URL
```

### In HTML Head
```blade
<head>
    {!! faviconLinks() !!}
</head>
```

## Update Assets = Update Everywhere

Replace files in:
- `public/logo/poricito.png` — Primary logo
- `public/logo/poricito_transparent.png` — Transparent version
- `public/favicon/*` — All favicon formats

Then your entire app automatically uses the new assets!

## Files Modified/Created

- ✅ `app/Helpers/ImageHelpers.php` — Core helper class
- ✅ `app/Helpers/helpers.php` — Global functions
- ✅ `resources/views/components/logo.blade.php` — Logo component
- ✅ `resources/views/components/logo-transparent.blade.php` — Transparent logo component
- ✅ `resources/views/layouts/app.blade.php` — Example layout
- ✅ `resources/views/welcome.blade.php` — Updated with favicon links
- ✅ `composer.json` — Updated autoload
- ✅ `LOGO_FAVICON_SETUP.md` — Full documentation
