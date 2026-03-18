# RadicalMart Search

**RadicalMart Search** is a search package for RadicalMart that provides a dedicated product search flow, including a search component, a search module, and AJAX-based search field rendering.

The package is focused on finding RadicalMart products by keyword and presenting search results through a standalone search page or embedded search UI. It extends the storefront experience without changing RadicalMart core product logic.

---

## Purpose

This package provides the **search layer** for RadicalMart storefronts.

Its role is to:

* accept keyword-based search queries
* find matching products in RadicalMart data
* render search forms and search results
* provide reusable search UI for templates and modules

The package does not change product data or business rules.

---

## Architecture role

Within the RadicalMart ecosystem:

```
Storefront UI
      ↓
 Search Module / Search Field
      ↓
 Search Component
      ↓
 RadicalMart Product Model
      ↓
 Search Results
```

The package represents the **storefront product search layer**.

---

## Package contents

The package includes:

* a **search component** for handling search requests and rendering result pages
* a **search module** for embedding a search form in the storefront
* an **AJAX search field layout** for interactive search input and quick results
* client-side assets used by the AJAX search interface

These parts work together but can also be used independently in different storefront layouts.

---

## What this package does

* Provides a dedicated product search page
* Provides a reusable storefront search module
* Supports AJAX-based search requests for interactive search UI
* Searches RadicalMart products by keyword
* Renders product results using RadicalMart product layouts

---

## What this package does NOT do

* ❌ Does not change RadicalMart product logic
* ❌ Does not manage categories, prices, or stock
* ❌ Does not implement external search engines
* ❌ Does not replace RadicalMart core catalog functionality

This package provides a **search interface and request handling layer**, not a separate catalog engine.

---

## Search flow

1. A keyword is entered through the module, search page, or AJAX field.
2. The search component receives the request.
3. RadicalMart product data is queried for matching products.
4. Matching products are rendered using storefront layouts.
5. Results are shown either as a full page or as AJAX response content.

---

## Search scope

The search package is designed for keyword-based product lookup.

Search queries are matched against product-related text fields used by RadicalMart search handling, such as product title, code, and searchable text content.

---

## Usage

This package is intended for:

* storefront product search pages
* header or sidebar search modules
* quick search interfaces with AJAX suggestions or result previews
* projects that need a separate search entry point for RadicalMart products

It can be integrated into custom templates and layout overrides using standard Joomla mechanisms.

---

## Extensibility

Search behavior can be extended by:

* overriding search layouts
* replacing or extending module output
* customizing result rendering in the component templates
* integrating additional query logic around the RadicalMart product model

The package is designed to stay separate from RadicalMart core business logic.
