# Silverstripe Stylist

> Note: This module is still in development and is not ready for production yet. Use at your own risk.

## Introduction

This module adds a `Theme` tab in the admin that allows users/admins to set site wide theme defaults. These generate a css stylesheet that can be used within the main theme css to dynamically style the site.

> Warning: CSS variables are still not available in all browsers. If you need to support IE11 - well just don't. Check [http://caniuse.com/#feat=css-variables](http://caniuse.com/#feat=css-variables) to see what browsers have support.


## Requirements

 * SilverStripe > 3.1

## Installation

### Via composer

`composer require "voyage:silverstripe-stylist:dev-master"`

### Configuration

In your main theme css set up your code to use css variables like the below example.
These variables will use the values as set in the `Theme` admin.

For example in your main css you might want to style your sites buttons using the primary and secondary colours. In `themes/base/scss/_buttons.scss` we can have the following code.

```css
.btn {
  display: block;
  padding: 6px 12px;
}

.btn-primary {
  background-color: var(--primary-colour);
}

.btn-secondary {
  background-color: var(--secondary-colour);
}
```

#### Default variables

The following variables are defined in this module. If you require any others feel free to extend or contribute to the module.

```css

:root {    
    --primary-colour:;
    --secondary-colour:;
    --font-family:;
    --heading-font-family:;
}

```

## TODO

 * [ ] Ability to change font family in admin
 * [ ] Convert DB field names to snake-case when compiling CSS
 * [ ] Convert DB fields to css automatically (currently need to be defined)
 * [ ] Add colour picker fields to CMS for colour fields
 * [ ] Tidy things up / Convert to PSR-2


## License

## Contributors

 * [Voyage](http://voyage.studio)
 * [Ryan O'Hara](http://github.com/ohararyan)
