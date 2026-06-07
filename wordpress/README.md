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
<div style="position:relative;width:100%;aspect-ratio:16/9;min-height:700px;border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.12);">
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

The wrapper keeps a ~16:9 aspect ratio with a 700px minimum height (enough for the picker card without internal scrolling), so the demo stays usable on narrow columns and phones. The "Open the full demo" link is the fallback for readers whose browsers or email clients strip iframes. To boot straight into one scenario instead, append `?vertical=housing&tour=auto&embed=1` to both URLs.

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
[datalove_demo]                                ← focus-area picker (default)
[datalove_demo vertical="housing" tour="auto"] ← boot into Housing + auto-tour
[datalove_demo vertical="veterans"]
[datalove_demo vertical="care" min_height="720"]
```

| Shortcode attribute | Values | Default |
| --- | --- | --- |
| `vertical` | `hunger` / `housing` / `economic` / `care` / `veterans` — omit to show the picker | picker |
| `tour` | `auto` (only applies when `vertical` is set) | off |
| `min_height` | px, minimum 320 | `700` (picker) / `640` (direct) |

The shortcode outputs the same responsive wrapper + iframe + fallback link as Option 1 (`embed=1` is added automatically in direct mode).

---

## Notes

- The demo is fully static and makes no external calls (fonts aside), so there's nothing to configure server-side.
- The guided tour ends with a "Book a walkthrough" CTA linking to <https://dataloveco.com/contact>; it opens in a new tab, outside the iframe.
- All demo organizations, people, and numbers are fictional ("Illustrative demo — sample data").
