# Silverstripe Stylist

> Note: This module is still in development and is not ready for production yet. Use at your own risk.

## Introduction

This module adds a `Theme` tab in the admin that allows users/admins to set site wide theme defaults. These generate a css stylesheet that can be used within the main theme css to dynamically style the site.

## Requirements

 * SilverStripe > 3.1

## Installation

### Via composer

`composer require "voyage:silverstripe-stylist:dev-master"`

### Configuration

In your main theme css set up your code to use css variables like the below example.
These variables will render to use the variables as set in the `Theme` admin.

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
