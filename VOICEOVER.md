# Data Love AI — Demo Voiceover Script

Bullet-format script intended to be read over a screen recording of `data_love_demo.html`. Estimated total run time: **~3:30 to 4:00 minutes** at conversational pace.

Stage directions are in *italics*. Bullets are the words to speak — feel free to paraphrase to sound natural.

---

## Intro · [0:00 – 0:15]
*Hold on the Data Love AI login screen, or a brand title card.*

- "Meet **Data Love AI** — an AI-powered data platform purpose-built for food security nonprofits."
- "Today we'll follow **Jasmine**, a program admin at a multi-program nonprofit, as she goes from a natural-language question to a stakeholder-ready report — in minutes, not weeks."
- "Her organization runs five programs in parallel: a Pantry Network, School Meals & Backpack, Senior Nutrition, SNAP Outreach, and Mobile & Education."

---

## Scene 1 · Login · [0:15 – 0:25]
*Show login screen. Click OK to enter.*

- "Logging in is a single email — no friction, branded experience."
- "Once Jasmine is in, the platform is built around two ideas: **one view of every program**, and **an AI agent that does the heavy lifting**."

---

## Scene 2 · Dashboard · [0:25 – 0:55]
*Land on Dashboard. Pan across KPIs, then the donut, then the bar chart.*

- "This is the **portfolio dashboard** — the whole organization at a glance."
- "**1.2 million people served year-to-date.** That's across every program."
- "**847 reports generated this year.** Up 34% over last year."
- "**712 active program sites** in the field — and counting."
- "Below the KPIs, the **Service Mix by Program** donut shows the program portfolio: the Pantry Network leads at 33%, but School Meals, Senior Nutrition, SNAP Outreach, and Mobile & Education each carry meaningful share."
- "On the right, the monthly service volume across all programs — trending steadily upward through 2025."
- "And these six **product modules** — Dorothy, DataHub, DataFlow, Insights Dash, StoryCraft, and PolicyPulse — are the suite. Let's start with Dorothy."

---

## Scene 3 · Dorothy · [0:55 – 1:50]   *(the centerpiece)*
*Click Dorothy. The chat auto-plays — bot greeting is already visible, then the prompt types itself.*

- "This is **Dorothy** — your **AI Data Officer**."
- "Notice what Jasmine doesn't have to do: she doesn't have to know SQL, doesn't have to know which table holds which column, and doesn't have to wait on a data analyst."
- "She just types her question in her own words…"

*Prompt finishes typing and sends; bot starts streaming.*

- "Dorothy reads the question, structures a report plan, and **streams her response back** — token by token, just like a modern AI assistant."
- "She lays out four sections: survey design, response accumulation, geographic participation, and completion analysis."

*Pipeline panel on the right starts animating.*

- "But here's what's different from a chatbot: Dorothy **shows her work**. On the right is the **seven-stage pipeline** she runs every time."
- "Stage 1: Report Planning — done. Stage 2: Data Intent Generation — and at that point she pauses for human review. **She doesn't just hit the database.**"
- "Every stage is visible, every stage is auditable. Jasmine always knows where the AI is in its process."

*Generate Report button starts pulsing.*

- "The Generate Report button is now live. Let's see what Dorothy wants to ask the data."

---

## Scene 4 · HITL Review · [1:50 – 2:25]
*Click Generate Report. HITL screen loads with intents fading in.*

- "This is **Human-in-the-Loop review** — and it's the trust layer that makes AI-generated analytics safe for a nonprofit."
- "Before any SQL runs against the warehouse, Dorothy surfaces the **data intents** — the specific metrics she's planning to query."
- "Total response count… cumulative response rate… per-question response rate… distinct locations…"
- "Jasmine can expand each intent to see exactly what grain and filters Dorothy plans to use."
- "And here — in the **knowledge box** — Jasmine can add domain context that Dorothy will respect. In this case, she's reminding Dorothy that partial responses are valid for this survey."
- "**Approve.** Now Dorothy has the green light to execute."

---

## Scene 5 · Report · [2:25 – 3:00]
*Click Approve. Report screen loads.*

- "And here it is — **the auto-generated report.**"
- "Structured into the four sections Dorothy planned. Clean tables, embedded charts, insight callouts in teal."
- "Notice the metadata at the top: **program**, generation date, **the exact data source**, and a note that partial responses are included. Every report is fully traceable."
- "The North District accounts for the largest share, the West has the strongest completion rate — and Dorothy flags a **data limitation** right in the report: question-level rates reflect who reached each question, not skip patterns. Honest about its own limits."
- "From here, Jasmine can export to PDF, export the underlying data, or send to StoryCraft for a stakeholder narrative."

---

## Scene 6 · DataHub · [3:00 – 3:25]
*Click DataHub in the sidebar.*

- "**DataHub** is the central data workspace — the foundation Dorothy is built on."
- "Six connected sources, **one per program** plus the survey platform and public data: Pantry Network Operations, School Meals & Backpack, Senior Nutrition, SNAP Outreach, the Survey Platform, and Census plus AHRQ social determinants of health."
- "Below, the **unified schema explorer** — ten tables across all programs in a single `warehouse.curated` namespace."
- "Every table is tagged with its program. There's even a `unified_clients` table for **cross-program identity** — so we can ask 'how many Pantry Network families also use School Meals?' and actually answer it."

---

## Scene 7 · Insights Dash · [3:25 – 3:45]
*Click Insights Dash.*

- "When Jasmine doesn't need a full report — when she just wants to **eyeball the numbers** — she comes to **Insights Dash**."
- "Filter by program: all programs, Pantry, School Meals, Senior, SNAP, Mobile & Education."
- "Weekly service volume, unique recipients trend, household size distribution, survey participation — each card shows freshness so she knows exactly when the data updated."

---

## Scene 8 · StoryCraft · [3:45 – 4:05]
*Click StoryCraft.*

- "And finally, **StoryCraft** — for when Jasmine needs to communicate with stakeholders."
- "Funders, the board, the Regional Food Policy Office — they don't want SQL. They want a **narrative**."
- "StoryCraft drafts that narrative automatically: executive summary, key findings, **and explicit data limitations** — same standard as the report."
- "And it's framed for the portfolio — this quarterly report is the Pantry Network's, with explicit pointers to where the other programs are covered."
- "Stakeholder-ready in minutes."

---

## Close · [4:05 – 4:20]
*Return to Dashboard or a closing brand card.*

- "**Data Love AI** — one platform, every program, every question, answered."
- "Built for food security nonprofits who need to move at the speed of their mission."
- "**Your data. Your impact. Simplified.**"

---

## Pacing tips for the narrator

- **Don't rush Dorothy.** Scene 3 is the heart of the demo. Let the typewriter and streaming actually play out — those animations are the emotional payload. ~55 seconds for that scene is right.
- **Pause on the pipeline reveal.** When the pipeline starts animating, slow down and let the viewer's eye follow stage 1 → stage 2.
- **Lean into trust language** on HITL and the data-limitation moments. The differentiator vs. a generic AI chatbot is *transparency and human control* — say those words out loud.
- **Numbers are anchors.** "1.2 million people served," "five programs," "ten tables," "seven-stage pipeline" — these stick. Hit them clearly.
- **Skip filler.** If the cut goes long, the easiest scenes to trim are Insights Dash and StoryCraft (15–20 seconds each); the must-keep scenes are Dorothy, HITL, and Report.
