---
name: 8-Bit Quest
colors:
  surface: '#f9f9f9'
  surface-dim: '#dadada'
  surface-bright: '#f9f9f9'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f4f3f3'
  surface-container: '#eeeeee'
  surface-container-high: '#e8e8e8'
  surface-container-highest: '#e2e2e2'
  on-surface: '#1a1c1c'
  on-surface-variant: '#4d4634'
  inverse-surface: '#2f3131'
  inverse-on-surface: '#f1f1f1'
  outline: '#7f7662'
  outline-variant: '#d0c6ae'
  surface-tint: '#735c00'
  primary: '#735c00'
  on-primary: '#ffffff'
  primary-container: '#ffd54f'
  on-primary-container: '#735c00'
  inverse-primary: '#ebc23e'
  secondary: '#5f5e5e'
  on-secondary: '#ffffff'
  secondary-container: '#e2dfde'
  on-secondary-container: '#636262'
  tertiary: '#1e6d12'
  on-tertiary: '#ffffff'
  tertiary-container: '#9aed83'
  on-tertiary-container: '#1e6d12'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffe087'
  primary-fixed-dim: '#ebc23e'
  on-primary-fixed: '#241a00'
  on-primary-fixed-variant: '#574500'
  secondary-fixed: '#e5e2e1'
  secondary-fixed-dim: '#c8c6c5'
  on-secondary-fixed: '#1b1c1c'
  on-secondary-fixed-variant: '#474746'
  tertiary-fixed: '#a3f78c'
  tertiary-fixed-dim: '#88da73'
  on-tertiary-fixed: '#012200'
  on-tertiary-fixed-variant: '#055300'
  background: '#f9f9f9'
  on-background: '#1a1c1c'
  surface-variant: '#e2e2e2'
typography:
  h1:
    fontFamily: Press Start 2P
    fontSize: 24px
    fontWeight: '400'
    lineHeight: '1.5'
    letterSpacing: 0px
  h2:
    fontFamily: Press Start 2P
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.4'
    letterSpacing: 0px
  h3:
    fontFamily: Press Start 2P
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1.4'
    letterSpacing: 0px
  body-lg:
    fontFamily: Lexend
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
    letterSpacing: 0px
  body-md:
    fontFamily: Lexend
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
    letterSpacing: 0px
  label-pixel:
    fontFamily: Press Start 2P
    fontSize: 10px
    fontWeight: '400'
    lineHeight: '1'
    letterSpacing: 1px
  button-text:
    fontFamily: Press Start 2P
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1'
    letterSpacing: 0px
spacing:
  unit: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 32px
  xl: 64px
  gutter: 24px
  margin: 24px
---

## Brand & Style

This design system blends **Retro-Brutalism** with **8-bit Pixel Art** to create a gamified environment for the "IT Quest System." The personality is adventurous, nostalgic, and high-energy, designed to transform academic or technical tasks into rewarding "quests." It strikes a balance between a playful arcade aesthetic and the structured clarity required for a functional student dashboard. 

The visual language relies on high-contrast outlines, rigid geometry, and pixel-perfect alignment. It avoids all modern gradients and blurs in favor of raw, dithered-inspired textures and chunky, physical-feeling interactions.

## Colors

The palette is anchored by a vibrant "Quest Gold" (#FFD54F), serving as the primary color for interactive elements and highlights. The secondary color is a deep "Obsidian Black" (#212121), which replaces standard grays for borders and primary text to ensure maximum contrast and an arcade punch. 

A deep "Forest Green" (#0a5f02) is introduced as the tertiary color, representing growth, success, and nature-themed quest rewards. The base environment uses a light gray neutral (#EEEEEE) to maintain professional readability for long-form content. A subtle pixel grid pattern is applied to the background using a repeating SVG or CSS pattern of 4px x 4px squares.

## Typography

This design system uses a dual-font strategy. **Press Start 2P** (or a similar pixelated block font) is reserved for headlines, buttons, and short labels to reinforce the 8-bit brand. For body text and dashboard data, **Lexend** is utilized. Its high readability and geometric clarity ensure that the dashboard remains professional and accessible for students, preventing the "visual fatigue" often associated with reading long passages in pixel fonts.

All pixel fonts should be rendered with `font-smoothing: none` or `pixelated` rendering properties where possible to maintain sharp edges.

## Layout & Spacing

The layout follows a **Fixed Grid** philosophy rooted in 4px increments (the "pixel unit"). Dashboards should be contained within a maximum width of 1280px to maintain the look of a classic game interface. 

Components are arranged using a 12-column system. White space is intentional but structured—never fluid or "airy." Every element should feel like a block being slotted into a grid. Gutters and margins are strictly defined to 24px to ensure the chunky UI elements have room to breathe without losing their structural integrity.

## Elevation & Depth

In this design system, depth is achieved through **Hard Offsets** rather than soft shadows. There are no blurs.

1.  **Level 0 (Base):** The background grid.
2.  **Level 1 (Cards/Containers):** Elements feature a 4px solid black border and a 4px or 8px offset "shadow" cast to the bottom-right. The shadow is a solid block of `#212121`.
3.  **Level 2 (Interactive):** Buttons and active chips. These use the same offset shadow.
4.  **Pressed State:** When an element is clicked, it translates `+4px` on the X-axis and `+4px` on the Y-axis, while the box-shadow is removed. This creates the tactile illusion of the button being physically pushed into the surface.

## Shapes

The shape language is strictly **Sharp (0px)**. There are no rounded corners in this design system. Every container, button, and input field must be a perfect rectangle or square. This reinforces the "pixel" nature of the 8-bit aesthetic and ensures that the chunky 2px and 4px borders align perfectly with the pixel grid.

## Components

### Buttons
Buttons are the core of the quest experience. They feature a `#FFD54F` or `#0a5f02` background, a 4px black border, and a black offset shadow. On hover, the background lightens slightly. On "press," the element shifts down-right.

### Cards
Dashboard cards use a white background with a 2px black border. Headers within cards should have a solid black bottom border (2px) and use the pixelated font for titles.

### Inputs & Checkboxes
Input fields use a white background and a 2px black inner-stroke effect. Checkboxes are simple squares; when checked, they display a pixelated "X" or a 2x2 pixel block in the center.

### Icons
Icons must be custom pixel-art assets. 
*   **Sword:** Used for "Assignments" or "Challenges."
*   **Scroll:** Used for "Resources" or "Documentation."
*   **Trophy:** Used for "Achievements" or "Grades."
*   **Checklist:** Used for "Tasks" or "Todo."
All icons should be drawn on a 16x16 or 32x32 grid and scaled up without interpolation (`image-rendering: pixelated`).

### Progress Bars
Quest progress is shown via "Health Bars." These are chunky rectangular containers with a 2px black border. The fill is a solid, non-gradient block of primary gold or tertiary green, appearing to grow in pixel-sized chunks.