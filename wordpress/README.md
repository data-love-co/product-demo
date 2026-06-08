# Embedding the Data Love AI demo in WordPress

The demo is a single static page served from GitHub Pages:

> **https://data-love-co.github.io/product-demo/**

It is iframe-friendly (responsive down to phone widths, no localStorage, the guided tour stays inside the frame). **With no URL parameters, it opens on the focus-area picker** — visitors choose their own scenario; this is the recommended default for embeds. URL parameters deep-link past the picker:

| Parameter | Values | Effect |
| --- | --- | --- |
| `vertical` | `hunger` \| `housing` \| `economic` \| `care` \| `veterans` | Boot directly into that focus area, skipping the landing picker (the param name stays `vertical` for back-compat) |
| `tour` | `auto` | Auto-start the 7-step guided tour after load (jumps to the dashboard, so pair it with `vertical`) |
| `embed` | `1` | Skip the landing picker and boot straight into the app |

Picker (default): `https://data-love-co.github.io/product-demo/`
Direct boot: `https://data-love-co.github.io/product-demo/?vertical=housing&tour=auto&embed=1`

---

## Option 1 — Self-hosted WordPress (WordPress.org): Custom HTML block

No plugin required.

1. Edit the page or post in the block editor.
2. Add a block → search for **"Custom HTML"**.
3. Paste the snippet below (also in [`embed-snippet.html`](embed-snippet.html)).
4. Preview/publish. Change the `vertical` value in the URL to embed a different scenario.

```html
<!-- Data Love AI — demo embed (focus-area picker) -->
<div style="position:relative;width:100%;height:100dvh;min-height:560px;border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.12);">
  <iframe
    src="https://data-love-co.github.io/product-demo/"
    title="Data Love AI — guided product demo"
    loading="lazy"
    allowfullscreen
    style="position:absolute;inset:0;width:100%;height:100%;border:0;"></iframe>
</div>
<p style="margin-top:8px;font-size:14px;">
  Trouble viewing? <a href="https://data-love-co.github.io/product-demo/" target="_blank" rel="noopener">Open the full demo ↗</a>
</p>
```

The wrapper is sized to the **viewport height** (`100dvh`) so the demo fills the screen like a real app — the WordPress page itself doesn't scroll, and scrolling happens only inside the demo's panels where it's meant to. (`dvh` is the *dynamic* viewport height, which behaves correctly on phones as the browser's address bar shows/hides.) The "Open the full demo" link is the fallback for readers whose browsers or email clients strip iframes.

**Removing the last sliver of page scroll.** Because your theme's header sits above the embed, a full `100dvh` embed can leave a small amount of page scroll equal to the header's height. To fit the remaining screen exactly, subtract that height: change `height:100dvh` to `height:calc(100dvh - 90px)`, using your header's actual height in place of `90px`. (Quick way to find it: right-click your site header → **Inspect**, and read its height in the browser dev tools.)

To boot straight into one scenario instead, append `?vertical=housing&tour=auto&embed=1` to both URLs.

## Option 2 — WordPress.com

⚠️ **WordPress.com restricts custom HTML and arbitrary iframes.**

- On the **Business plan (or higher)**: the Custom HTML block above works as-is (Business also allows plugin uploads, so Option 3 works too).
- On **Free / Personal / Premium plans**: arbitrary iframes are stripped at save time — the embed **will not render**. Your options are to upgrade to Business, self-host WordPress (Option 1), or simply link out to the demo URL instead of embedding it.

## Option 3 — Tiny plugin with a shortcode (self-hosted or WP.com Business)

[`datalove-demo.php`](datalove-demo.php) registers a `[datalove_demo]` shortcode so the demo can be dropped onto any page or post — with different verticals per page — without copy-pasting HTML.

**Install (either way):**

- *Upload via admin:* zip `datalove-demo.php` (the single file is fine), then **Plugins → Add New → Upload Plugin** → choose the zip → Install → **Activate**.
- *Drop in directly:* copy `datalove-demo.php` into `wp-content/plugins/` on the server, then activate **Data Love AI Demo Embed** under **Plugins**.

**Use in any page/post:**

```text
[datalove_demo offset="90"]                    ← fit below a 90px site header (recommended)
[datalove_demo vertical="housing" tour="auto"] ← boot into Housing + auto-tour
[datalove_demo expand="off"]                   ← inline only, hide the Expand button
[datalove_demo contact_url="https://dataloveco.com/book"]
```

| Shortcode attribute | Values | Default |
| --- | --- | --- |
| `vertical` | `hunger` / `housing` / `economic` / `care` / `veterans` — omit to show the picker | picker |
| `tour` | `auto` (only applies when `vertical` is set) | off |
| `height` | any CSS length (`100dvh`, `90vh`, `800px`) — fills the viewport so the page doesn't scroll | `100dvh` |
| `offset` | px subtracted from `height` for your theme's header, for an exact fit below it | `0` |
| `min_height` | px, minimum 320 — a low floor so short windows shrink instead of scrolling | `420` |
| `expand` | `on` / `off` — show the **⛶ Expand** full-window button | `on` |
| `contact_url` | URL for the **Contact us** button shown in the expanded overlay | `https://dataloveco.com/contact` |

### How the hybrid layout works

- **Inline (default):** the demo fits the screen *below your site header*, so your nav/menu stays visible the whole time — visitors can reach Contact or browse the rest of the site at any point. Set `offset` to your header's height (plus any theme padding) to remove the last bit of page scroll: e.g. `[datalove_demo offset="90"]`. Find your header height via right-click → **Inspect**.
- **Expanded (⛶ Expand button):** clicking it lifts the demo to a **full-window overlay** with no page scroll at all. The overlay carries a slim bar with **✕ Exit demo** (collapses back to the page; the **Esc** key also works) and a **Contact us** button. This uses a CSS overlay rather than the native Fullscreen API, so it works reliably on phones too.
- Scrolling *inside* the demo's own panels (e.g. the chat history) is expected — that's the app behaving like an app.

`embed=1` is added automatically in direct mode. The CSS/JS the buttons need is printed once per page, even with multiple shortcodes.

---

## Notes

- The demo is fully static and makes no external calls (fonts aside), so there's nothing to configure server-side.
- The guided tour ends with a "Book a walkthrough" CTA linking to <https://dataloveco.com/contact>; it opens in a new tab, outside the iframe.
- All demo organizations, people, and numbers are fictional ("Illustrative demo — sample data").
