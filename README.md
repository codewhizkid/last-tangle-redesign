# Last Tangle — Unofficial Redesign Concept

**Independent demonstration only. Not affiliated with, endorsed by, or approved by Last Tangle Salon.**

## Pages

- [Public website preview](https://codewhizkid.github.io/last-tangle-redesign/)
- [Website management dashboard](https://codewhizkid.github.io/last-tangle-redesign/dashboard.html)

Enable GitHub Pages in Settings → Pages → Deploy from a branch → main → /(root).

## Dashboard features

- Edit homepage, Our Story, services, careers, contact, and approved booking URL.
- Replace hero, salon story, and service photos.
- Add team profiles and upload portraits.
- Upload gallery photos and captions.
- Save drafts, preview drafts, and publish changes **locally in the same browser**.
- Export/import content JSON (image binaries are not included).

### Limitations

This is a static GitHub Pages demonstration, **not** a production content management system. It uses localStorage for content and IndexedDB for photos. Images and edits do not sync across devices or users, do not modify files in GitHub, and do not affect Last Tangle's live website. Clearing browser storage can permanently remove edits and photos. Exported JSON does not include photo files. There is no login or server-side authorization. Do not store sensitive data here.

## Production roadmap

For real shared publishing: implement authenticated WordPress content types, permissions, media library integration, revisions, staging and production publishing, backups, and authorized Meevo booking links. Port the complete approved photographic redesign into the theme.
