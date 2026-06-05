# Data Love AI — Interactive Demo

A self-contained, clickable HTML prototype of the **Data Love AI platform**, re-skinnable across **five SDoH verticals** — Hunger, Housing, Economic Stability, Access to Care, and Veteran Services. Each vertical presents a fictional Colorado nonprofit with its own personas, KPIs, charts, Dorothy question, HITL moment, report, and StoryCraft narrative. Pick a vertical on the login screen, or switch live from the sidebar. The demo shows stakeholders the end-to-end experience: logging in, asking Dorothy (the AI Data Officer) a question in natural language, watching the AI plan and assemble a report, reviewing data intents before SQL runs, and exporting a final stakeholder-ready document.

> **Illustrative demo — sample data.** All organizations, people, and numbers are fictional.

---

## Files in this folder

| File | Purpose |
| --- | --- |
| `index.html` | The full interactive demo — a single self-contained HTML file with embedded CSS and JS. **Open this in a browser to view.** Served live via GitHub Pages. |
| `Data Love AI Demo 2026-04-11.gif` | Reference recording (animated GIF) of an earlier version of the demo. |
| `Data Love AI Demo 2026-04-11.mov` | Reference recording (MOV) of an earlier version. |
| `README.md` | This file. |
| `DESIGN_SPEC.md` | Design + implementation requirements to hand off to the engineering team. |

---

## How to run the demo

The demo is a single HTML file with no build step or dependencies.

**Option A — Open directly:**
Double-click `index.html`. It will open in your default browser. Some browsers restrict local file capabilities, but for this static demo it works fine.

**Option B — Local server (recommended for the smoothest experience):**

```bash
python3 -m http.server 8000
```

Then open <http://localhost:8000/index.html>.

**Best viewed at:** 1440×900 or larger. Designed for laptop/desktop screens. Not yet mobile-optimized.

---

## The demo scenarios — five SDoH verticals

All scenario content lives in a single `VERTICALS` config object in `index.html`; every screen renders from the active vertical. Each vertical has a buyer (ED/CEO), a primary user (Program/Services Director), and a technical champion (Data/Outcomes Manager):

| Vertical | Organization | Accent | Personas |
| --- | --- | --- | --- |
| **Hunger** | Harvest Bridge Network — Denver, CO (~$8.5M) | Coral | Maya Chen (ED) · David Okafor (Program Dir.) · Priya Nair (Data Mgr.) |
| **Housing** | Cornerstone Home Partners — Longmont, CO (~$9M) | Teal | Ruth Delgado (ED) · Marcus Bell (Family Services Dir.) · Lena Park (Impact & Data Mgr.) |
| **Economic Stability** | Ascend Financial Futures — Aurora, CO (~$6M) | Gold | Daniel Osei (CEO) · Sofia Reyes (Workforce Program Dir.) · Amir Haddad (Data & Outcomes Lead) |
| **Access to Care** | Wellspring Community Health Collaborative — Pueblo, CO (~$7M) | Purple | Dr. Naomi Wright (ED) · Carlos Mendez (Care Coordination Dir.) · Aisha Bello (Health Data Analyst) |
| **Veteran Services** | Sentinel Veteran Collective — Colorado Springs, CO (~$5.5M) | Ink | James Whitfield (ED) · Tanya Brooks (Veteran Services Dir.) · Ryan Cho (Data & Eligibility Analyst) |

The original food-security scenario is preserved unchanged as the **Hunger** vertical: a nonprofit running a Pantry Network alongside School Meals, Senior Nutrition, SNAP Outreach, and Mobile & Education programs, with district labels (North / East / Central / West / South) that apply universally to any service area.

## What's in the demo

The demo is a single-page app with eight "screens" the user can navigate between via the sidebar:

| Screen | What it shows |
| --- | --- |
| **Login** | Branded login card with the **SDoH vertical picker** (5 cards — selecting one re-skins the entire demo), Data Love Co color palette, and DM Sans/Space Grotesk typography. |
| **Dashboard** | Welcome panel + KPIs (4–6 stat cards per vertical), the product suite (Dorothy, DataHub, DataFlow, Insights Dash, StoryCraft, PolicyPulse), and two charts: monthly volume across all programs + a "Service Mix by Program" donut. |
| **Dorothy** | The AI chat experience. **Auto-plays on entry:** the active vertical's prompt types itself into a wrapping textarea, the bot streams its response, and a 7-stage pipeline panel animates. *Centerpiece of the demo.* |
| **HITL Review** | "Human-in-the-Loop" review screen. Surfaces the data intents Dorothy generated (5 per vertical, with grain/filter details) so the user can approve them (or attach context) before SQL runs. |
| **Report** | The final auto-generated report for the active vertical's question (e.g., Hunger: the Neighbor Experience Survey participation analysis) — 4 sections, embedded accumulation chart, summary tables, data-limitation callouts, and export actions. |
| **DataHub** | Schema/lineage view of six connected data sources per vertical and a unified `warehouse.curated.*` schema explorer showing 10 tables across programs. |
| **Insights Dash** | A self-serve analytics dashboard with **program filters** (per vertical) and four insight cards: weekly volume, monthly trend, a distribution chart, and a survey response tracker. |
| **StoryCraft** | Auto-narrative report builder — drafts a quarterly program write-up addressed to a fictional stakeholder (e.g., Regional Food Policy Office for Hunger). |

Every screen has a compact **"SDoH Vertical" switcher** in the sidebar — changing it re-renders all screens and charts live, no reload.

---

## What's new in this version

### 1. Generic, multi-program scenario
All references to specific organizations have been removed and the data model has been expanded to reflect a nonprofit running multiple programs in parallel.

| Old reference | New reference |
| --- | --- |
| Plentiful (single pantry reservation product) | **Pantry Network Operations** — one program of several |
| NYC Mayor's Office of Food Policy / MOFP | **Regional Food Policy Office** |
| Bronx / Brooklyn / Manhattan / Queens / Staten Island | **North / East / Central / West / South Districts** |
| Alchemer | **Survey Platform** |
| Twilio | **SMS Provider** |
| `plentiful.curated.*` namespace | **`warehouse.curated.*`** (unified, cross-program) |
| Four data sources, all pantry-focused | **Six data sources** spanning Pantry Network, School Meals, Senior Nutrition, SNAP Outreach, Survey Platform, and Public Data |
| Seven tables, all pantry-focused | **Ten tables across programs**, with a Program column making it obvious which program each table belongs to and a `unified_clients` table for cross-program identity |
| Dashboard donut: "Visits by District" | **"Service Mix by Program"** — visualizes the program portfolio |
| Insights filters: districts | **Programs** (All / Pantry Network / School Meals / Senior Nutrition / SNAP Outreach / Mobile & Education) |
| StoryCraft: a single quarterly pantry utilization report | **Quarterly Program Report: Pantry Network**, explicitly framed as one of multiple program reports |

**Kept intentionally:** "Dorothy" (the AI agent name) and "Data Love Co" / "Data Love AI" (the platform brand). The logged-in demo user is each vertical's Executive Director persona (e.g., Maya Chen for Hunger). Public datasets (Census ACS, AHRQ SDOH) are kept since any nonprofit can connect to them.

### 2. Interactive typing & response animation
The Dorothy chat screen feels alive without any extra controls:

- **Auto-plays on every visit.** Each time the user lands on the Dorothy screen, the chat resets and the demo runs itself:
  1. The user prompt is **typed character-by-character** into a multi-line wrapping textarea (with subtle timing jitter, like a real human typist) — long prompts wrap and stay fully visible.
  2. The prompt is sent as a chat bubble.
  3. A **three-dot typing indicator** appears while the bot "thinks."
  4. The bot's response **streams token-by-token** with a blinking caret, mimicking real LLM streaming.
  5. The right-hand **Pipeline Status** panel animates: Stage 1 turns "active" → "complete," then Stage 2 enters "HITL Review →" state, then the **Generate Report** button enables and gently pulses.
- **Live typing too.** The user can type their own question into the textarea (it auto-grows as they type) or click the microphone, and the bot will respond with a generic streamed reply + pipeline animation.
- **No demo control buttons** — auto-play handles the walkthrough. To re-run the script, navigate away from Dorothy and back.
- **HITL screen** intents fade in with a stagger.
- **Charts** animate in on first reveal.

### 3. Documentation
- `README.md` — this file.
- `DESIGN_SPEC.md` — wireframes, components, color tokens, typography, behaviors, and accessibility guidance to hand to your engineering team.

---

## Architecture (at a glance)

The demo is intentionally one file so anyone can open it without setup. Internally it's a tiny single-page app:

```
index.html
├── <style>           Embedded CSS with CSS custom properties (design tokens, incl. per-vertical --accent)
├── Screens           One <div class="screen"> per page (login, dashboard, dorothy, hitl, report, datahub, insights, storycraft)
├── Floating widgets  Data-freshness pill (bottom right), toast
└── <script>          VERTICALS config (all scenario content) + render functions, navigation,
                      typewriter, streaming, pipeline animation, vertical picker/switcher
```

Navigation toggles `.active` on screens. Each vertical's content renders from `VERTICALS[STATE.vertical]`; switching verticals re-renders every screen and re-initializes the charts. The choice is held in memory only (no localStorage). There is no router, no framework, no build step.

---

## Replaying the recording

If you want to compare the live demo to the original recording, both `.gif` and `.mov` files are included in this folder.

---

## Known limitations of this prototype

This is a **visual prototype**, not a working product. Specifically:

- All data values, charts, and tables are hard-coded.
- No real LLM call — the "streamed" response is a fixed string played back character-by-character.
- The microphone button does **not** actually capture audio; it simulates listening for visual effect.
- The pipeline stages are timed animations, not actual processing.
- There is no persistence, auth, or backend.

See `DESIGN_SPEC.md` for the implementation expectations when this is built for real.
