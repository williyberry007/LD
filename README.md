# Hubtown Nigeria — Interactive Map Landing Page

A dark, immersive landing page for a real estate developer, built around an
interactive map of Nigeria. Inspired by the map-explorer experience at
hubtown.co.in/map, re-imagined for Nigerian regions and projects.

## Features

- **Interactive Nigeria map** — stylised SVG silhouette with city labels,
  Niger/Benue river hints, and pulsing project markers
- **Region index** — Lagos, Abuja FCT, Port Harcourt, Kano with project
  counts; selecting a region zooms/pans the map to it
- **Project explorer** — Prev/Next navigation (also arrow keys) through a
  portfolio of 12 sample projects, each with status, type, year, units and
  a short description
- **Filters** — filter the markers and carousel by status (Now Selling /
  Under Construction / Sold Out) and type (Residential / Commercial / Mixed
  Use), with a live `[NN]` active-filter count
- **Map controls** — drag to pan, scroll or +/− to zoom (0.6x–3.0x), compass
  rose, live lat/lon readout for the selected project
- **Sound toggle** — subtle WebAudio tick on interactions when enabled
- **Preloader, mega menu, responsive layout** — stacks to a single column
  under 880px

## Running

It's a static site — no build step:

```bash
python3 -m http.server 8000
# open http://localhost:8000
```

## Files

| File | Purpose |
| --- | --- |
| `index.html` | Page structure: top bar, region rail, map stage, project card, bottom bar |
| `styles.css` | Dark/gold theme, layout, animations |
| `script.js` | Project data, markers, filters, pan/zoom, sound, menu |
