# Salon Website Manager — Phase 2

This folder contains the **WordPress plugin foundation**, developed separately from the approved browser-only Last Tangle prototype.

## Architecture
- A single WordPress installation per salon (initial model).
- A shared plugin stores standardized content under the `swm_content` option.
- Each salon uses a separate bespoke WordPress theme.
- GET `/wp-json/salon-manager/v1/content` returns public website content.
- POST/PUT/PATCH `/wp-json/salon-manager/v1/content` requires a logged-in WordPress user with `edit_pages` permission and a valid REST nonce for cookie-based browser requests.
- WordPress Media Library will hold photographs. Media uploads and the visual editor are **not implemented yet**.

## Content sections
Homepage, Our Story, Services, Our Team, Gallery, Careers, Contact.

## Install on a staging WordPress site
1. Copy the `last-tangle-manager` directory into `wp-content/plugins/`.
2. Activate **Salon Website Manager** in WordPress Plugins.
3. Open **Salon Website** in WordPress admin to see the API location.
4. Use a separate staging site; do not deploy to a client's live site yet.

## Not ready for production
The plugin has not been executed in WordPress or browser-tested. Draft/publish separation, image ownership checks, revision history, visual editing, migration, and comprehensive validation remain to be built. The existing GitHub Pages demo is unchanged and still stores edits locally.

## Baseline
`baseline/v1.0-safari-accepted-2026-10-27` records the user-accepted prototype.
