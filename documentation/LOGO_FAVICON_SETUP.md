# Logo and Favicon Helpers Documentation

This document explains how to use the centralized logo and favicon system in your application.

## Overview

The logo and favicon files are now managed through helper functions and Blade components. This allows you to update these assets from a single central location without having to change references throughout the application.

### Available Assets

- **Logos**: `/public/logo/poricito.png` and `/public/logo/poricito_transparent.png`
- **Favicon Files**: `/public/favicon/` (contains various favicon formats)

---

## Usage

### 1. Using Helper Functions

#### Get Logo Path (URL)
```php
// In a controller or any PHP file
$logoUrl = logo();  // Returns: /logo/poricito.png

// In Blade templates
{{ logo() }}
```

#### Get Transparent Logo Path (URL)
```php
// In a controller or any PHP file
$logoTransparentUrl = logoTransparent();  // Returns: /logo/poricito_transparent.png

// In Blade templates
{{ logoTransparent() }}
```

#### Get Favicon Path (URL)
```php
// In a controller or any PHP file
$faviconUrl = favicon();  // Returns: /favicon/favicon.ico

// In Blade templates
{{ favicon() }}
```

#### Get Favicon Links (HTML)
```php
// In a controller or any PHP file
$faviconHtml = faviconLinks();  // Returns complete HTML favicon links

// In Blade templates (automatically rendered in welcome.blade.php)
{!! faviconLinks() !!}
```

### 2. Using Helper Functions with Image Tags

You can generate complete image tags directly from the helper functions:

#### Logo Image Tag
```blade
<!-- Simple usage -->
{{ logo('My App Logo', ['class' => 'w-32']) }}

<!-- This generates -->
<img src="/logo/poricito.png" alt="My App Logo" class="w-32">
```

#### Transparent Logo Image Tag
```blade
{{ logoTransparent('Transparent Logo', ['class' => 'h-16', 'id' => 'header-logo']) }}

<!-- This generates -->
<img src="/logo/poricito_transparent.png" alt="Transparent Logo" class="h-16" id="header-logo">
```

### 3. Using Blade Components

Alternatively, use the provided Blade components for a more semantic approach:

#### Logo Component
```blade
<x-logo alt="My App Logo" class="w-32" />

<!-- This generates -->
<img src="/logo/poricito.png" alt="My App Logo" class="w-32">
```

#### Transparent Logo Component
```blade
<x-logo-transparent alt="Transparent Logo" class="h-16" />

<!-- This generates -->
<img src="/logo/poricito_transparent.png" alt="Transparent Logo" class="h-16">
```

### 4. Using the ImageHelpers Class Directly

If you prefer to use the class directly in your code:

```php
use App\Helpers\ImageHelpers;

// Get paths
$logo = ImageHelpers::getLogo();
$logoTransparent = ImageHelpers::getLogoTransparent();
$favicon = ImageHelpers::getFavicon();

// Get HTML
$faviconLinks = ImageHelpers::getFaviconLinks();
$logoImage = ImageHelpers::logoImage('Logo', ['class' => 'w-32']);
$transparentLogoImage = ImageHelpers::logoTransparentImage('Logo', ['class' => 'w-32']);
```

---

## Examples

### Example 1: Adding Logo to Navigation Bar

**In your navigation view:**
```blade
<nav>
    <a href="{{ route('home') }}">
        {{ logo('App Logo', ['class' => 'h-8 w-auto']) }}
    </a>
    <!-- Navigation items -->
</nav>
```

### Example 2: Adding Logo to Header

**In your layout:**
```blade
<header>
    <x-logo alt="Company Logo" class="logo-header" />
    <h1>{{ config('app.name') }}</h1>
</header>
```

### Example 3: Using Transparent Logo for Dark Background

**In a hero section:**
```blade
<section class="bg-gray-900 text-white">
    <x-logo-transparent alt="Logo on Dark" class="w-48 mb-4" />
    <h1>Welcome to {{ config('app.name') }}</h1>
</section>
```

### Example 4: Adding Favicons to Layout

**Already added in `resources/views/welcome.blade.php`:**
```blade
<head>
    <!-- ... other head content ... -->
    {!! faviconLinks() !!}
    <!-- ... -->
</head>
```

---

## Updating Assets

To update your logo or favicon:

1. **Replace the logo files:**
   - Replace `/public/logo/poricito.png` with your new primary logo
   - Replace `/public/logo/poricito_transparent.png` with your transparent logo

2. **Replace the favicon files:**
   - Replace the contents of `/public/favicon/` with your new favicon files

3. **No code changes needed!** All references throughout your application will automatically use the updated assets.

---

## File Locations

- **Helper Functions:** `app/Helpers/helpers.php`
- **Helper Class:** `app/Helpers/ImageHelpers.php`
- **Logo Components:** `resources/views/components/logo.blade.php` and `resources/views/components/logo-transparent.blade.php`
- **Logo Assets:** `public/logo/`
- **Favicon Assets:** `public/favicon/`

---

## Tips

- Use `logo()` without parameters to get just the URL string
- Use `logo('Alt Text', ['class' => 'css-class'])` to get HTML img tags
- Use `<x-logo />` component for cleaner, more semantic templates
- Always provide meaningful `alt` text for accessibility
- For dark backgrounds, use the transparent logo variant
- Favicon links are already configured in the main layout

---

## Regenerating Composer Autoload

After initial setup, make sure composer autoload is regenerated:

```bash
composer dump-autoload
```

This ensures the helpers file is properly included in the autoload chain.
