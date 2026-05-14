# Data Love AI — Design & Implementation Spec

**Audience:** Engineering team building the production version of the Data Love AI platform.
**Source of truth for visual design:** `data_love_demo.html` in this folder. When this document and the prototype disagree, prefer the prototype's behavior unless this doc explicitly overrides it.
**Target customer profile:** A multi-program food security nonprofit (e.g., a Feeding America affiliate or a Nourish Colorado-style policy/programs org) running a Pantry Network alongside School Meals, Senior Nutrition, SNAP Outreach, and Mobile & Education programs. The data model and screens are designed for this portfolio shape rather than a single-program operator.
**Last updated:** May 2026.

---

## 1. Purpose & Scope

This document describes the design system, page layouts, component anatomy, and interaction behaviors that the production Data Love AI app should implement, derived from the interactive HTML prototype in this folder. It covers:

- The seven screens (Login, Dashboard, Dorothy, HITL Review, Report, DataHub, Insights Dash, StoryCraft).
- The data-love-co visual language (color, type, spacing, motion).
- The "streamed AI response" and "pipeline status" behaviors that are central to the brand experience.
- Accessibility, responsive, and engineering-hygiene requirements.

What this document **does not** cover: backend architecture, data warehouse schema design, LLM prompting strategy, or auth/permissions model. Those will be in separate technical specs.

---

## 2. Design Tokens (DLC Style Guide)

All tokens are already defined as CSS custom properties at the top of the prototype. Re-implementations in any framework (Tailwind, Chakra, CSS-in-JS, etc.) should map to these exact values.

### 2.1 Color

| Token | Hex | Usage |
| --- | --- | --- |
| `--purple-deep` | `#3D1D72` | Primary brand color. Headlines, primary stat values, "user" chat bubble, active sidebar item dot. |
| `--purple-mid` | `#5B3A9E` | Secondary purple. Hover states, secondary text accents, focus borders. |
| `--purple-light` | `#7B5FC0` | Tertiary purple. Hover borders, secondary chart fills. |
| `--purple-bg` | `#F6F3FB` | Faint purple background (e.g., dorothy avatar bg, hover row bg). |
| `--purple-sidebar` | `#4A2583` | The fixed sidebar background. |
| `--teal` | `#3BBFAD` | Primary CTA color (Login button, Approve button, Active sidebar indicator). |
| `--teal-dark` | `#2DA396` | Teal hover state. |
| `--teal-light` | `#E6F8F5` | Teal callout / insight-box background. |
| `--coral` | `#E85D75` | Accent / "listening" microphone state. |
| `--orange` | `#F5A623` | Chart segment color (3rd–4th categories). |
| `--blue-accent` | `#4A90D9` | Data-intent numbered chips, 5th chart segment. |
| `--text-primary` | `#1A1A2E` | Body text, headings. |
| `--text-secondary` | `#6B7280` | Subtitles, descriptions. |
| `--text-muted` | `#9CA3AF` | Labels, axis ticks, low-emphasis. |
| `--bg-white` / `--bg-off` | `#FFFFFF` / `#F9FAFB` | Page backgrounds. Cards on `--bg-off`. |
| `--border` / `--border-light` | `#E5E7EB` / `#F3F4F6` | Card outlines, dividers. |

**Chart palette (in priority order):** `--purple-deep` → `--teal` → `--coral` → `--orange` → `--blue-accent` → `--purple-light`. Always use this order so that the "primary" category in any chart is purple-deep.

### 2.2 Typography

- **Primary font:** `'DM Sans', sans-serif` for all UI text.
- **Display/accent font:** `'Space Grotesk', sans-serif` is loaded but currently unused — reserve for marketing surfaces, oversized hero numbers, or report titles if the design team wants a contrast voice.
- Load both via Google Fonts.

Type scale (use these px values, not arbitrary sizes):

| Use | Size / Weight |
| --- | --- |
| Page H1 (Welcome, screen title) | 22–28px / 700 |
| Section H2 | 16–18px / 600 |
| Card H3 | 14–15px / 600 |
| Body | 13–15px / 400 |
| Stat value (hero number) | 32px / 700, color `--purple-deep` |
| Stat label | 13px / 500, color `--text-secondary` |
| Small labels / chips | 10–12px / 600, often uppercase with letter-spacing 0.5–1.2px |
| Sidebar nav | 14px / 500 |

### 2.3 Spacing, Radius, Shadow

- Base spacing unit: **4px**. Use 4 / 8 / 12 / 16 / 20 / 24 / 28 / 32 / 40px. Avoid arbitrary in-between values.
- Page horizontal padding: **40px**. Vertical 32px top, 40px bottom.
- Card padding: **24–28px**.
- Border radius:
  - `--radius-sm: 8px` — inputs, intent items, table cells.
  - `--radius: 12px` — cards, chat bubbles, panels.
  - `--radius-lg: 16px` — large modals, login card.
  - Pill / rounded button: `24–28px` or `border-radius: 50%` for circular icon buttons.
- Shadow:
  - `--shadow-sm: 0 1px 2px rgba(0,0,0,0.05)` — default card lift.
  - `--shadow-md: 0 4px 12px rgba(0,0,0,0.08)` — hover/active card lift.
  - `--shadow-lg: 0 8px 30px rgba(0,0,0,0.12)` — modal, login card, toast.

### 2.4 Motion

Default transition: `transition: all 0.2s ease` or per-property at 0.2–0.4s. Specific named animations:

| Name | Duration | Use |
| --- | --- | --- |
| `fadeIn` | 0.4s | Screen entry. |
| `msgIn` | 0.3s | Chat bubble appearance. |
| `pulse` | 1.5s infinite | Active pipeline stage dot + "ready" CTA. |
| `pulse-coral` | 1.2s infinite | Microphone "listening" state. |
| `typing-dot` | 1.2s infinite | 3-dot typing indicator. |
| `blink-caret` | 0.9s steps(2) infinite | Streaming response caret. |
| `fadeUp` | 0.4s, staggered | HITL intent reveal. |

Respect `prefers-reduced-motion: reduce` — disable pulse, caret blink, and stagger animations when set.

### 2.5 Logo

The brand mark is **five vertical bars of varying heights** in alternating brand colors (purple-mid, teal, purple-deep, coral, purple-light), followed by the wordmark "Data Love" or "Data Love AI" in DM Sans 700. The exact bar heights and color order are visible in the prototype's `.logo-dots` rule. Maintain that proportion at every size.

---

## 3. Information Architecture & Navigation

```
┌─ Login
└─ App shell (after login)
   ├─ Sidebar (persistent)
   │   ├─ Section: PLATFORM
   │   │  ├─ Dashboard
   │   │  ├─ Dorothy
   │   │  ├─ DataHub
   │   │  ├─ Insights Dash
   │   │  └─ StoryCraft
   │   ├─ Section: REVIEW
   │   │  ├─ HITL Review
   │   │  └─ Reports
   │   └─ Footer: avatar + user name + role
   └─ Main content (per-screen)
```

The active item is indicated by **white text + 8% white background + 3px teal left border**. Section labels are 10px / 600 / 1.2px letter-spacing / uppercase / 35% white.

Data freshness indicator (bottom-right floating pill, blinking teal dot) is global and persists across screens.

---

## 4. Page-by-Page Wireframes

ASCII wireframes below — they're the visual contract. See the prototype for pixel-accurate spacing.

### 4.1 Login

```
┌───────────────────────────────────────────────────────────┐
│              [ purple gradient background ]               │
│                                                           │
│                ┌────────────────────────┐                 │
│                │   ▮▮▮▮▮  Data Love     │  ← logo mark    │
│                │                        │                 │
│                │   Login                │                 │
│                │   Your Data, Your      │                 │
│                │   Impact, Simplified   │                 │
│                │                        │                 │
│                │   Email                │                 │
│                │   [____________________]│                │
│                │                        │                 │
│                │   [    OK (teal pill) ] │  ← primary CTA │
│                │                        │                 │
│                │   DON'T HAVE AN ACCOUNT?│                │
│                └────────────────────────┘                 │
└───────────────────────────────────────────────────────────┘
```

- Card: 440px wide, 56×48px padding, `--radius-lg`, `--shadow-lg`.
- Background: linear gradient at 135deg, `--purple-deep → --purple-mid → --purple-light`.

### 4.2 Dashboard

```
┌─────────┬──────────────────────────────────────────────────┐
│ SIDEBAR │ Welcome back, Jasmine 👋                         │
│         │ Your comprehensive platform for data analytics… │
│ ▮ Dash  │ ┌──────────┬──────────┬──────────┐               │
│   Doroty│ │2.4M      │847       │612       │  ← KPI cards │
│   DataH │ │Visits ↑12│Reports ↑34│Pantries ↑8│             │
│   InsiD │ └──────────┴──────────┴──────────┘              │
│   Story │                                                 │
│         │ Product Suite                                   │
│ ─REVIEW │ ┌─────┬─────┬─────┐                              │
│   HITL  │ │Dorot│DataH│DataF│  ← 3 cards per row          │
│   Repts │ ├─────┼─────┼─────┤                              │
│         │ │Insig│Story│PolPu│                              │
│         │ └─────┴─────┴─────┘                              │
│  ─────  │                                                 │
│  [JM]   │ ┌──────────────────────┬─────────────┐           │
│  Admin  │ │ Monthly Visit Volume │ By District │           │
│         │ │  [bar chart]         │ [donut]     │           │
└─────────┴──────────────────────────────────────────────────┘
```

### 4.3 Dorothy (the centerpiece)

```
┌─────────┬─────────────────────────────────────┬────────────┐
│ SIDEBAR │ [✦] Data Love AI                    │ Pipeline   │
│         │     Your personal, AI Data Officer. │ Status     │
│         │ ─────────────────────────────────── │            │
│ ▮ Dorot │                                     │ 1 ✓ Plan   │
│         │ ┌─────────────────────────────────┐ │   Complete │
│         │ │ 🤖  Hi Jasmine! 👋               │ │            │
│         │ │     I'm glad to help…           │ │ 2 ⏺ Intent │
│         │ └─────────────────────────────────┘ │   Running… │
│         │                                     │            │
│         │           ┌────────────────────────┐│ 3 · SQL    │
│         │           │ Give me a breakdown…  ││   Waiting  │
│         │           └────────────────────────┘│            │
│         │                                     │ 4 · Sum    │
│         │ ┌─────────────────────────────────┐ │ 5 · Insight│
│         │ │ • • •      (typing indicator)   │ │ 6 · Charts │
│         │ └─────────────────────────────────┘ │ 7 · Assem  │
│         │                                     │            │
│         │ ┌─────────────────────────────────┐ │            │
│         │ │ Great — I'll structure…▋         │ │ ← stream  │
│         │ └─────────────────────────────────┘ │            │
│         │                                     │            │
│         │ ┌────────────────┬──┬──┬───────────┐ │            │
│         │ │ Auto-growing   │🎙│➤ │📊 Gener.  │ │            │
│         │ │ multi-line     │  │  │ Report    │ │            │
│         │ │ textarea       │  │  │ (pulse)   │ │            │
│         │ └────────────────┴──┴──┴───────────┘ │            │
└─────────┴─────────────────────────────────────┴────────────┘
```

**Key behavior:**
- Chat-input bar has four elements left-to-right: **auto-growing multi-line textarea** (flex:1, min-height 48px, max-height 200px, wraps long content), microphone button, send button (➤), Generate Report (primary CTA).
- The textarea grows as the user (or the typewriter animation) types, so a long prompt remains fully visible while being typed.
- `Enter` sends; `Shift+Enter` inserts a newline.
- Generate Report starts **disabled** and pulses to "ready" once the pipeline reaches stage 2 active.
- Pipeline panel is 320px fixed width, white, with timeline-style vertical connecting line between dots.
- Stage dot states: `pending` (grey), `active` (purple-deep, pulsing), `done` (teal with ✓).
- **Auto-play:** every time the Dorothy screen is activated, the chat resets to the welcome bubble and the scripted walkthrough begins after ~800ms. There are no explicit Play/Skip/Reset controls — navigating away and back replays the scenario. In production, decide whether auto-play happens on every visit, only on first visit per session, or behind a user setting.

### 4.4 HITL Review

```
┌─────────┬──────────────────────────────────────────────────┐
│ SIDEBAR │ Human-in-the-Loop Review                        │
│         │ Review and approve data intents before Dorothy… │
│         │                                                 │
│ ▮ HITL  │ ┌─────────────────────────────────────────────┐ │
│         │ │ Review: Survey Design       [Data Intent]   │ │
│         │ │ Quantify the overall structure…             │ │
│         │ │                                             │ │
│         │ │ 🎯 Data Intents [5]                          │ │
│         │ │ ┌───────────────────────────────────────┐   │ │
│         │ │ │ ① total_response_count             ⌄ │   │ │
│         │ │ │   Grain: overall | Filters: none      │   │ │
│         │ │ ├───────────────────────────────────────┤   │ │
│         │ │ │ ② cumulative_response_percentage   ⌄ │   │ │
│         │ │ ├───────────────────────────────────────┤   │ │
│         │ │ │ ③ question_response_rate           ⌄ │   │ │
│         │ │ └───────────────────────────────────────┘   │ │
│         │ │ ┌──── Knowledge Box (blue outline) ────┐   │ │
│         │ │ │ You can use both complete and…       │   │ │
│         │ │ └───────────────────────────────────────┘   │ │
│         │ │ [ ✓ Approve ]  [ ➤ Submit Knowledge ]       │ │
│         │ └─────────────────────────────────────────────┘ │
└─────────┴──────────────────────────────────────────────────┘
```

### 4.5 Report

```
┌─────────┬──────────────────────────────────────────────────┐
│ SIDEBAR │ Neighbor Experience Survey — Participation       │
│         │ 📅 Generated: Apr 11, 2026                       │
│         │ 📊 Data source: foodconnect.curated.survey_neig… │
│         │ 🔒 Includes partial responses                    │
│         │                                                  │
│         │ ┌──────────────────────────────────────────────┐ │
│         │ │ 1. Survey Design & Participation Overview    │ │
│         │ │ The Neighbor Experience Survey received…     │ │
│         │ │ ┌─ teal insight callout ─────────────────┐   │ │
│         │ │ │ 💡 Including partial responses…        │   │ │
│         │ │ └─────────────────────────────────────────┘   │ │
│         │ │ [data table]                                  │ │
│         │ └──────────────────────────────────────────────┘ │
│         │                                                  │
│         │ ┌──────────────────────────────────────────────┐ │
│         │ │ 2. Response Accumulation Over Time           │ │
│         │ │ [bar chart, S-curve]                          │ │
│         │ └──────────────────────────────────────────────┘ │
│         │ … (sections 3, 4) …                              │
│         │                                                  │
│         │ [📥 PDF] [📊 Data] [📝 Send to StoryCraft]       │
└─────────┴──────────────────────────────────────────────────┘
```

Each section is a white card (`--radius`, `--shadow-sm`) with an H2 in `--purple-deep`. Insight callouts use `--teal-light` background with a 3px `--teal` left border.

### 4.6 DataHub

Two-column grid of source cards on top (6 connectors in a 2×3 grid — one per program plus survey + public data) and a wide schema table below. The schema table is a unified `warehouse.curated.*` namespace with a **Program** column indicating which of the org's programs each table belongs to (and a `Cross-program` value for unified identity tables like `unified_clients`).

```
┌────────────────────────────┬────────────────────────────┐
│ ⬡ Pantry Network Operations │ 🎒 School Meals & Backpack │
├────────────────────────────┼────────────────────────────┤
│ 👵 Senior Nutrition System │ 📋 SNAP Outreach Tracker   │
├────────────────────────────┼────────────────────────────┤
│ 📝 Survey Platform         │ 📊 Public Data (Census+SDOH)│
└────────────────────────────┴────────────────────────────┘
```

Connection status is a pill: connected = `--teal-light`, pending = light amber. In production, the source list should be data-driven — adding/removing programs should not require a code change.

### 4.7 Insights Dash

```
┌──────────────────────────────────────────────────────┐
│ Insights Dash                                        │
│ Self-serve analytics, filter by program…             │
│ [All Programs] [Pantry] [School Meals] [Senior]      │
│ [SNAP] [Mobile & Education]                          │
│ ┌─────────┬─────────┐                                │
│ │ Weekly  │ Unique  │  ← 2×2 grid                    │
│ │ Service │ Recip.  │                                │
│ ├─────────┼─────────┤                                │
│ │ HH size │ Survey  │                                │
│ │ (Pantry)│ Tracker │                                │
│ └─────────┴─────────┘                                │
└──────────────────────────────────────────────────────┘
```

Each card has: title, subtitle, **freshness indicator** ("Updated today at 08:14 AM ET" with a teal dot), and a small visualization. The primary filter is **by program**; a secondary filter (district / time range) can be layered below the program filter row in production.

### 4.8 StoryCraft

A single, large "preview" card that mimics a finished document, with a stakeholder header line, executive summary, key findings, and data limitations sections. Below: export actions.

---

## 5. Component Library

These are the building blocks the engineering team should implement as reusable components. Keep them stateless where possible.

### 5.1 `<AppShell>`
Holds `<Sidebar>` + `<MainContent>`. Sidebar is 240px fixed width, full height. Main is the rest, scrollable.

### 5.2 `<Sidebar>`
- Logo at top (28px tall, white-filtered).
- Nav sections with 10px / uppercase section labels.
- Nav items: 12×24 padding, 14px text, 20px leading icon, `border-left: 3px solid transparent` (becomes teal when active).
- Footer: 32px circular avatar (teal bg, white initials) + name + role.

### 5.3 `<StatCard>`
White card, 24px padding, `--radius`, `--shadow-sm`. Three text rows: label (13/500/secondary), value (32/700/purple-deep), delta (12/500/teal-dark).

### 5.4 `<ModuleCard>`
Square-ish card with a centered 48×48 icon at top (one of 3 color treatments: purple, teal, coral), then H3 and 12px description. Hover: lift 2px + purple-light border.

### 5.5 `<ChatMessage variant="bot" | "user">`
- Max-width 72%, 16×20 padding, `--radius`, line-height 1.65.
- Bot: white bg, `--border-light` outline, `--shadow-sm`, align left.
- User: `--purple-deep` bg, white text, align right, slightly squared bottom-right corner.

### 5.6 `<TypingIndicator>`
Inline-flex of three 7px dots with 4px gap. Each dot animates `translateY(-4px)` with 0.15s stagger.

### 5.7 `<PipelinePanel>`
Vertical list of stages. Each stage = 28px circular dot + info column. Connecting line between dots is a 2px `--border` line behind the dots (`::after` pseudo). Three dot states: `pending` (grey), `active` (purple-deep + `pulse` animation), `done` (teal + ✓ glyph).

### 5.8 `<IntentItem>`
Collapsible row inside a HITL card. Numbered blue chip (24px circle), monospace-ish intent name, chevron. Click to reveal details panel (`grain:`, `filters:` etc.).

### 5.9 `<DataTable>`
Standard table — uppercase 12px column headers on `--bg-off` background, 10×12 cell padding, alternating hover (`--purple-bg`). Use the prototype's `.data-table` class as the contract.

### 5.10 `<FilterPills>`
Horizontal flex of pills. Active = `--purple-deep` bg + white text. Inactive = white bg + `--border` outline. Single-select.

### 5.11 `<DataFreshnessPill>`
Fixed bottom-right, white bg, teal blinking dot, "Data refreshed: …" copy. Global, persistent.

### 5.12 `<Toast>`
Bottom-center, `--text-primary` bg, white text, 24px radius, fades in/out. Auto-dismiss after ~2.2s.

---

## 6. Critical Interactive Behaviors

This is where most of the demo magic lives. **These behaviors are part of the brand experience** and must be implemented in production with the same feel.

### 6.1 Typewriter user prompt (scripted demo / replay mode)
- Character-by-character, base cadence ≈ 22ms with **0–18ms random jitter** per character to simulate a real typist.
- The input field's text value updates on each tick so screen readers stay in sync.
- Focus the input first so the caret blinks naturally.
- The input is an **auto-growing textarea** — call the auto-grow helper on every keystroke (including each scripted character) so a long prompt wraps and remains fully visible as it's typed.

### 6.2 Streamed bot response (production: real LLM)
- Token-by-token rendering, base cadence ≈ 14–18ms per chunk.
- Chunks are 1–4 characters; **HTML tags are emitted as a single chunk** so partial tags never render.
- A blinking caret (`▋` in `--purple-mid`) appears at the streaming tail and is removed when streaming finishes.
- In production, this should be wired to **server-sent events / streaming tokens from the LLM**, not a fixed string.

### 6.3 Typing indicator
- Appears in the chat area between user submission and the first streamed token (~700–1100ms).
- Replaced inline by the bot message — don't leave it ghosting.

### 6.4 Pipeline animation
- Triggered as soon as the user submits a prompt.
- Stage 1 → active for ~1100ms → done.
- Stage 2 → active, label changes to "HITL Review →".
- At that point, the **Generate Report** CTA un-disables and adds a `pulse` animation.
- After HITL approval, stages 3–7 progress at ~600–900ms each.
- In production, drive this off real pipeline events (websocket or polling), not timers.

### 6.5 Microphone (voice input)
- Click toggles a "listening" state: button turns coral, glyph changes to ⏺, coral pulse aura.
- For the demo, this is purely visual and resolves after 1.8s. In production, hook up Web Speech API or your chosen STT service. The visual contract is the same.

### 6.6 HITL intent reveal
- When the HITL screen activates, intent items fade up with 100ms stagger.
- Clicking an intent expands details inline (current behavior is correct).
- Approving sends the pipeline forward and navigates to the report.

### 6.7 Chart animations
- Bar charts: bars grow from 0 → final height with `height` transition ease, 0.8s.
- Line/area charts: animate `stroke-dashoffset` from full length → 0 to "draw" the line.

### 6.8 Reduced-motion
Disable: pulse animations, caret blink, fade-up stagger, typing-dot bounce. Replace streaming with instant-render. Replace typewriter input with instant-paste.

---

## 7. Accessibility Requirements

- **Color contrast:** all body text on white must be ≥ 4.5:1. The sidebar's white-on-purple-sidebar is fine (12+:1). Verify any new tints. The 35%-opacity section labels in the sidebar **fail** WCAG AA on first reading — bump to 60% opacity or larger weight in production.
- **Keyboard navigation:** every clickable element (sidebar nav, module cards, intents, filter pills, mic, send, generate) must be a real `<button>` or `<a>` and reachable by Tab in source order. The prototype uses `<a onclick>` for sidebar items — switch to `<button>` or proper `<a href>` in production.
- **Focus rings:** do not remove `:focus-visible` outlines. Style with `outline: 2px solid var(--purple-mid)` if the default is suppressed.
- **ARIA:**
  - Chat area: `role="log"` `aria-live="polite"` so screen readers announce new messages.
  - Typing indicator: `aria-label="Dorothy is typing"`.
  - Pipeline stages: each is a `<li>` inside an `<ol>` with `aria-current="step"` on the active stage.
  - Microphone toggle: `aria-pressed` true/false.
- **Reduced motion:** as described in §6.8.
- **Forms:** the login email field needs a real `<label for>` pairing. The prototype has it; preserve it.

---

## 8. Responsive Behavior

The prototype is **desktop-first** and not mobile-optimized. Production requirements:

| Breakpoint | Behavior |
| --- | --- |
| ≥ 1280px | Full layout as designed. |
| 1024–1279px | Sidebar collapses to 64px icon-only rail. Pipeline panel becomes a slide-over instead of fixed. |
| 768–1023px | Sidebar becomes a hamburger drawer. Dashboard grids collapse 3→2. |
| < 768px | Single column. Pipeline panel becomes a bottom sheet. Charts switch to scrollable horizontal cards. |

Charts must remain readable at narrow widths — use horizontal scroll or aggregate to fewer bars.

---

## 9. Engineering Conventions

- **Framework:** team's existing stack — likely React + TypeScript. Don't carry forward the demo's vanilla approach.
- **Styling:** map every token in §2 to your design-tokens file. Do not hard-code hex values in components.
- **State:** the streaming chat naturally fits a state machine (`idle → typing → streaming → awaiting_hitl → approved → generating → done`). Implement it as such.
- **Telemetry:** instrument: prompt submit, stage transitions, HITL approval, report export. The pipeline stages are the units of value — they must be measurable.
- **Performance budget:**
  - Initial paint (login) < 1.5s on a cold cache.
  - Dashboard interactive < 3s.
  - Streaming first-token latency: target < 800ms.
- **Error states:** every screen needs an empty state and an error state. The prototype shows happy-path only — design + build error/empty variants per screen.

---

## 10. Open Questions for the Product Team

These are decisions the production build needs answered that the prototype doesn't resolve:

1. **Auth model.** Login is decorative — what's the real auth (SSO, email magic link, password)?
2. **User roles & permissions.** The user has "Admin • Program Pilot" role — what are the role tiers, and what can each see?
3. **Pipeline failure UX.** If stage 3 (SQL generation) fails, what does the user see — retry, escalate, fall back?
4. **HITL trust model.** Can power users opt out of HITL on certain intent classes? How do we re-engage HITL when confidence is low?
5. **Multi-tenancy.** One nonprofit per deploy, or shared infra with isolated tenants?
6. **Data export controls.** Should PDF / data exports be audited? Watermarked? Time-limited?
7. **Mobile.** Is mobile a v1 requirement or a fast-follow?

Please answer these (or assign owners) before sprint planning starts.

---

## 11. Hand-off Checklist for the Dev Team

Before kicking off the build, confirm you have:

- [ ] Read this document end-to-end.
- [ ] Opened `data_love_demo.html` in a browser and clicked through every screen.
- [ ] Played the Dorothy auto-demo at least twice — once at full speed, once with Skip.
- [ ] Inspected the prototype's CSS variables in DevTools to extract pixel-accurate values.
- [ ] Identified which design-token file you'll write the values into.
- [ ] Confirmed the chart library you'll use (Recharts, Visx, ECharts) supports the bar/donut/line shapes shown.
- [ ] Confirmed your chosen LLM provider supports streaming responses (SSE).
- [ ] Aligned with product on the open questions in §10.
