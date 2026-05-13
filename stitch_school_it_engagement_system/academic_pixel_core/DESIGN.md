---
name: Academic Pixel-Core
colors:
  surface: '#faf8ff'
  surface-dim: '#d9d9e5'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3fe'
  surface-container: '#ededf9'
  surface-container-high: '#e7e7f3'
  surface-container-highest: '#e1e2ed'
  on-surface: '#191b23'
  on-surface-variant: '#434655'
  inverse-surface: '#2e3039'
  inverse-on-surface: '#f0f0fb'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#495c95'
  on-secondary: '#ffffff'
  secondary-container: '#acbfff'
  on-secondary-container: '#394c84'
  tertiary: '#943700'
  on-tertiary: '#ffffff'
  tertiary-container: '#bc4800'
  on-tertiary-container: '#ffede6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#dbe1ff'
  secondary-fixed-dim: '#b4c5ff'
  on-secondary-fixed: '#00174b'
  on-secondary-fixed-variant: '#31447b'
  tertiary-fixed: '#ffdbcd'
  tertiary-fixed-dim: '#ffb596'
  on-tertiary-fixed: '#360f00'
  on-tertiary-fixed-variant: '#7d2d00'
  background: '#faf8ff'
  on-background: '#191b23'
  surface-variant: '#e1e2ed'
typography:
  display-xl:
    fontFamily: Space Grotesk
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  section-title:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '800'
    lineHeight: 20px
    letterSpacing: 0.1em
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-pixel:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
spacing:
  unit: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 40px
  gutter: 24px
  margin: 32px
---

## Brand & Style

This design system blends the structural reliability of a traditional academic dashboard with the energetic, high-feedback nature of a gaming interface. It is designed to modernize IT engagement within educational environments by treating technical tasks and tickets as "quests" or "achievements."

The aesthetic is a hybrid of **Modern Corporate** and **Neo-Retro Pixel-Art**. It utilizes high-clarity typography for data density while incorporating "stepped" corner details and chunky borders to evoke a digital, tech-centric nostalgia. The goal is to make administrative IT work feel like a progressive journey rather than a chore, maintaining professional standards through clean white space and structured alignment.

## Colors

The palette is anchored in **Deep Blue** for authority and **Electric Cyan** for technical energy. Success and Danger states use high-vibrancy Green and Red to ensure notification badges and status indicators pop against the relatively neutral backgrounds.

- **Primary & Cyan:** Used for interactive elements, progress bars, and high-level navigation.
- **Surface Tints:** Soft Blue (`#EFF6FF`) is used for dashboard card backgrounds to distinguish them from the main page white-space.
- **Borders:** A near-black `neutral_dark` is used for the "chunky" border style to provide the required structural definition.

## Typography

This design system employs a multi-font strategy to balance legibility with the "IT/Gaming" theme.

- **Headlines:** `Space Grotesk` provides a geometric, slightly technical feel that mimics the structure of pixel fonts without sacrificing high-resolution readability.
- **Body:** `Inter` is the workhorse for all data-heavy sections, ticket descriptions, and settings.
- **Labels/Badges:** `JetBrains Mono` is used for status badges, tags, and small metadata to emphasize the technical "IT" nature of the platform.
- **Section Headers:** Always bold and uppercase to create clear visual anchors on complex dashboards.

## Layout & Spacing

The system uses a **12-column fixed grid** for desktop to maintain a "command center" feel, transitioning to a fluid single-column layout for mobile. 

Spacing is intentionally "bold" and generous. Elements should never feel cramped; the IT Engagement System relies on clarity to reduce student anxiety regarding technical issues. Use `40px` (xl) for vertical section separation and `16px` (md) for internal card padding. All components should align to a 4px baseline grid to maintain the "stepped" mathematical feel of the pixel-inspired aesthetic.

## Elevation & Depth

Depth is achieved through **Tonal Layering** and **Bold Outlines** rather than heavy blurring. 

1. **Flat Layers:** Most surfaces are flat, differentiated by color (e.g., a Light Blue card on a White background).
2. **Shadows:** Use "Sharp Shadows"—short offset (4px), 0-blur shadows using a semi-transparent version of the primary blue or black. This creates a "sticker" or "pop-out" effect common in gamified interfaces.
3. **Hover States:** Interactive elements should shift 2px up and to the left on hover, with the shadow increasing in offset to simulate physical lifting.

## Shapes

To achieve the "pixel-inspired" look while remaining modern, the design system utilizes **Sharp (0px) corners** for most containers, but with a specific "Stepped Corner" treatment for primary buttons and large cards.

- **Stepped Corners:** Instead of a radius, corners are "cut" at a 45-degree angle or stepped by 4px to simulate low-resolution curves.
- **Borders:** All primary containers have a 2px solid border (`neutral_dark`). Interactive elements use a 3px border to emphasize clickability.

## Components

### Buttons & Inputs
- **Primary Buttons:** High-contrast Cyan or Blue background with a 2px black bottom-border (shadow-style). Text is bold and uppercase.
- **Input Fields:** Thick 2px borders. On focus, the border changes to Cyan with a soft 4px glow (no blur).

### Feedback & Gamification
- **Notification Badges:** Circular or "stepped" square badges in Green (Success) or Red (Alert). They should include a subtle 1px white inner border to "pop."
- **Progress Bars:** Use a "segmented" look, where the progress is filled with distinct blocks rather than a smooth gradient, reinforcing the pixel aesthetic.
- **XP/Status Chips:** Small badges using `JetBrains Mono` text, used to denote user "Level" or ticket priority.

### Cards
- **Dashboard Cards:** White background, 2px dark border. The header of the card should have a solid "title bar" in soft blue to separate the title from the content.

### Animations
- **Transitions:** Use "Snappy" easing (e.g., `cubic-bezier(0.18, 0.89, 0.32, 1.28)`) for a playful, responsive feel.
- **Loading States:** A pixelated "pulse" animation or a stepping progress bar.