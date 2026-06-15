# Alarede — WordPress Theme

An elegant, fully customizable WordPress theme for **luxury wedding, event and
academy businesses**. It features a video/image hero slider, story, services,
portfolio gallery, pricing, testimonials and contact sections, plus a set of
ready-made page templates — all editable from the WordPress Customizer and the
block editor.

> The theme ships with original placeholder copy and images. It is *inspired by*
> the structure of destination wedding/event websites — it does not include any
> third party's logos, photography or written content. Replace the placeholders
> with your own brand assets.

## Requirements

- WordPress 5.8+
- PHP 7.4+

## Installation

1. Upload `alarede.zip` via **Appearance → Themes → Add New → Upload Theme**,
   then **Activate** (or copy the `alarede` folder into `wp-content/themes/`).
2. On activation the theme automatically:
   - registers the Hero Slides, Services, Portfolio and Testimonial content types;
   - creates starter pages (Home, About, Luxury Events, Alago Academy, Media
     Gallery, Careers, Contact, FAQ) with the correct template assigned to each;
   - sets the **Home** page as your static front page (only if you haven't set one);
   - builds and assigns a **Primary** navigation menu;
   - flushes permalinks.

   Existing pages with the same slug are never overwritten.

## Hero slider (video or image)

The homepage hero is a slider. To build it, add posts under **Hero Slides** in
the dashboard. Each slide offers:

- **Background Media** — choose *Image* (uses the slide's Featured Image) or
  *Video*.
- **Video URL** — a self-hosted `.mp4`/`.webm` plays as a muted, looping
  background; a YouTube or Vimeo link is embedded automatically.
- **Overline/Kicker**, plus the slide **Title** (heading) and **Content**
  (subtitle), and two optional buttons.

With no Hero Slides, the hero falls back to a single slide configured in
**Customize → Front Page Sections → Hero** (which also supports image *or*
video, plus a slider autoplay speed).

## Customize everything

**Appearance → Customize**:

| Area | What you can edit |
| --- | --- |
| **Front Page Sections → Hero** | Media type (image/video), image, video URL, autoplay speed, text, buttons |
| **Front Page Sections → Intro / About** | Image, heading, body, signature |
| **Front Page Sections → Services** | 3 quick cards *or* unlimited Service posts |
| **Front Page Sections → Portfolio** | Heading, intro, item count (Portfolio posts) |
| **Front Page Sections → Pricing** | 3 packages with features, price, badge |
| **Front Page Sections → Testimonials** | Background, heading (Testimonial posts) |
| **Front Page Sections → Contact** | Email, phone, address, form shortcode, map embed |
| **Theme Colours** | Accent / dark / cream (live preview) |
| **Typography** | Heading & body font stacks |
| **Social & Footer** | Instagram, Facebook, Pinterest, YouTube, copyright |

Each homepage section has a show/hide toggle.

## Page templates

Assign these from **Page → Page Attributes → Template**:

| Template | Purpose |
| --- | --- |
| **About / Founder** | Portrait, biography, philosophy and stats |
| **Events** | Intro + the event-services grid (Introductions, Engagements, Wedding Reception, MC, Weddings, Marriage Counselling) |
| **Academy** | Intro + course grid (Event Mastery, Gele, Makeup, Gift Wrapping, Eru-Iyawo Wrapping, Letters) |
| **Media Gallery** | Filterable gallery built from Portfolio items + categories |
| **Careers** | Values grid + open-positions list |
| **Contact** | Contact details, form (or shortcode) and optional map |
| **FAQ** | Accordion of questions |
| **General Page** | Flexible default layout with optional sidebar |
| **Full Width (No Sidebar)** | Clean, centered content |
| **Landing (Homepage Sections)** | Reuse the homepage sections on any page |

The Events/Academy/Careers/FAQ lists live in a small `$array` near the top of
each template, so you can edit the items directly if you prefer code, or use
the page editor for the surrounding copy.

## Content types

- **Hero Slides** — image/video slides for the hero.
- **Services** — used by the homepage Services section when present.
- **Portfolio** (with categories) — homepage gallery, the Media Gallery page,
  and `/portfolio/`.
- **Testimonials** — quotes (with names + venue) for the slider.

## Contact form & map

The built-in form uses a `mailto:` action for zero-config use. For production,
install a form plugin (e.g. Contact Form 7) and paste its shortcode in
**Customize → Front Page Sections → Contact**. Paste a Google Maps embed
`<iframe>` in the same place to show a map on the Contact page.

## File structure

```
alarede/
├── style.css                 Theme header + all styles
├── functions.php             Setup, enqueues, widgets, dynamic CSS
├── front-page.php            Homepage section assembly
├── header.php / footer.php   Header (sticky/transparent) and footer
├── index.php / archive.php   Blog & archive listings
├── single.php / page.php     Single post and page
├── single-ae_portfolio.php   Single portfolio item
├── search.php / 404.php / comments.php / sidebar.php / searchform.php
├── inc/
│   ├── customizer.php        All Customizer controls
│   ├── custom-post-types.php Slides, Services, Portfolio, Testimonials + activation
│   └── template-tags.php     Helper template functions
├── template-parts/
│   ├── content-card.php / content-none.php
│   └── sections/             hero (slider), intro, services, portfolio,
│                             pricing, testimonials, contact
├── page-templates/           about, events, academy, gallery, careers,
│                             contact, faq, general, full-width, landing
└── assets/js/                navigation, main (slider/faq/gallery), customizer
```

## License

GNU General Public License v2 or later.
