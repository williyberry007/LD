# Alago Events — WordPress Theme

An elegant, fully customizable WordPress theme for **luxury wedding & event
planners**. It reproduces the *layout and style* of a high-end destination
wedding-planner website (full-screen hero, story, services, portfolio gallery,
pricing, testimonials and a contact section), with every piece of content
editable from the WordPress Customizer.

> The theme ships with original placeholder copy and images. It is *inspired by*
> the structure of destination wedding-planner sites — it does not include any
> third party's logos, photography or written content. Replace the placeholders
> with your own brand assets.

## Requirements

- WordPress 5.8+
- PHP 7.4+

## Installation

1. Zip the `alago-events` folder (or copy it into `wp-content/themes/`).
2. In the admin: **Appearance → Themes → Add New → Upload Theme**, choose the
   zip, then **Activate**.
3. Activating registers the Services, Portfolio and Testimonial content types
   and flushes permalinks automatically.

## Set up the homepage

1. Create a Page (e.g. "Home") and, optionally, a "Journal" page for the blog.
2. **Settings → Reading → Your homepage displays → A static page** and choose
   your Home page. (The theme's `front-page.php` then renders the configurable
   sections.)
3. **Appearance → Menus** — create a menu and assign it to *Primary*. Anchor
   links such as `#services`, `#portfolio`, `#contact` scroll to the matching
   homepage section.

## Customize everything

Go to **Appearance → Customize**. Panels and sections:

| Area | What you can edit |
| --- | --- |
| **Front Page Sections → Hero** | Background image, overline, title, subtitle, two buttons |
| **Front Page Sections → Intro / About** | Image, heading, body text, signature |
| **Front Page Sections → Services** | 3 quick cards *or* unlimited Service posts |
| **Front Page Sections → Portfolio** | Heading, intro, number of items (pulls Portfolio posts) |
| **Front Page Sections → Pricing** | 3 packages with features, price and badge |
| **Front Page Sections → Testimonials** | Background, heading (pulls Testimonial posts) |
| **Front Page Sections → Contact** | Email, phone, address, intro, or a form shortcode |
| **Theme Colours** | Accent / dark / cream colours (live preview) |
| **Typography** | Heading & body font stacks |
| **Social & Footer** | Instagram, Facebook, Pinterest, YouTube, copyright text |

Each section can be toggled on/off with its "Show this section" checkbox.

## Content types

- **Services** — add detailed services with a featured icon/image. When at least
  one exists, the Services section uses them instead of the Customizer cards.
- **Portfolio** — real weddings/events with categories. Shown in the homepage
  gallery and at `/portfolio/`.
- **Testimonials** — quotes with a "Names" and "Location/Venue" field, shown in
  the rotating slider.

## Page templates

- **Default** — page with optional blog sidebar.
- **Full Width (No Sidebar)** — clean, centered content.
- **Landing (Homepage Sections)** — build extra landing pages reusing the
  homepage sections.

## Contact form

The built-in form uses a `mailto:` action for zero-config use. For a production
form, install a plugin like Contact Form 7 and paste its shortcode in
**Customize → Front Page Sections → Contact → Contact Form Shortcode**.

## File structure

```
alago-events/
├── style.css                 Theme header + all styles
├── functions.php             Setup, enqueues, widget areas, dynamic CSS
├── front-page.php            Homepage section assembly
├── header.php / footer.php   Header (sticky/transparent) and footer
├── index.php / archive.php   Blog & archive listings
├── single.php / page.php     Single post and page
├── single-ae_portfolio.php   Single portfolio item
├── search.php / 404.php      Search results and not-found
├── comments.php / sidebar.php / searchform.php
├── inc/
│   ├── customizer.php        All Customizer controls
│   ├── custom-post-types.php Services, Portfolio, Testimonials
│   └── template-tags.php     Helper template functions
├── template-parts/
│   ├── content-card.php      Blog card
│   ├── content-none.php      Empty state
│   └── sections/             hero, intro, services, portfolio,
│                             pricing, testimonials, contact
├── page-templates/           full-width, landing
└── assets/js/                navigation, main, customizer
```

## License

GNU General Public License v2 or later.
