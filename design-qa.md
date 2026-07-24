# Design QA

- Source visual truth path: `C:\Users\Programer\.codex\generated_images\019f8230-701d-7af3-ab6c-f574ecee10aa\exec-80985e97-6476-406a-b6f5-825c17a5f3de.png`
- Implementation route: `http://localhost:8184/lms-pandoralite/coursemain/all_courses`
- Intended viewport: 1440 × 1024 desktop, with 991px tablet and 390px mobile checks pending
- State: authenticated course catalog
- PHP syntax: passed in the project PHP 8.4 Docker runtime

**Findings**

- [P0] Authenticated implementation cannot be captured yet
  - Location: course catalog route.
  - Evidence: the in-app browser is redirected to `/home?redirect=coursemain/all_courses` because its isolated session is not signed in.
  - Impact: the rendered implementation, interactions, console state, and responsive breakpoints cannot be visually verified against the selected mockup.
  - Fix: sign in to the LMS in the in-app browser, then repeat desktop/tablet/mobile capture and comparison.

**Comparison history**

- Shell decision: restored the shared production `topbar` and `precision-sidebar-v2 precision-sidebar` used by Dashboard. Removed the page-specific replacement so language, notifications, profile menus, permissions, and navigation retain their existing tested behavior and contrast.

- Iteration 1 screenshot: `C:\Users\PROGRA~1\AppData\Local\Temp\codex-clipboard-a051dbe5-7ab1-4b43-b1af-3ec4e085d298.png`
  - P1: white application header and floating white sidebar did not match the dark shell. Fixed with page-scoped dark header and 80px icon rail.
  - P1: hero lacked dedicated imagery. Fixed with `assets/images/course-catalog/hero-technician-engine.png`.
  - P1: featured card reused a generic course illustration. Fixed with `assets/images/course-catalog/featured-technician.png`.
- Iteration 2 screenshot: `C:\Users\PROGRA~1\AppData\Local\Temp\codex-clipboard-286cd60f-bf52-4d0e-a006-0e16ab92999f.png`
  - P1: E-Learning logo drifted to the center of the header. Fixed by anchoring it beside the ISUZU lockup.
  - P1: featured enrollment control rendered as an oversized red/gray block. Fixed with strict absolute sizing and pseudo-element suppression.
  - P2: category strip left excessive empty space. Fixed by distributing the live categories across the intended 70% hero width.
  - P2: course cards were substantially taller than the source. Fixed by reducing desktop thumbnail height and featured-card height.
- Iteration 3 screenshot: `C:\Users\PROGRA~1\AppData\Local\Temp\codex-clipboard-1f3fc272-fd21-4e73-a27e-7b2b1413a4af.png`
  - P1: the inherited `.btn_register` hook still corrupted the featured CTA after load. Fixed by removing that legacy class entirely and binding enrollment to the isolated `.catalog-enroll-action` hook.
  - P1: the E-Learning logo overlapped the ISUZU lockup because its absolute position used the full navbar as its containing block. Fixed with an explicit post-brand offset.
  - P2: some category icons were unsupported by the installed legacy Material Design Icons version. Replaced with compatible icon names.

**Required fidelity surfaces**

- Fonts and typography: code review complete; browser comparison blocked by authentication.
- Spacing and layout rhythm: responsive CSS review complete; browser comparison blocked by authentication.
- Colors and visual tokens: implemented from the selected graphite, warm-white, and ISUZU-red direction; browser comparison blocked by authentication.
- Image quality and asset fidelity: production banner and course-upload images are used; crop and sharpness comparison blocked by authentication.
- Copy and content: localized Thai, English, and Japanese interface copy is present; live-data wrapping comparison blocked by authentication.

**Full-view comparison evidence**

- Source mockup opened successfully.
- Implementation capture is unavailable because the route redirects to login.

**Focused region comparison evidence**

- Not available until the authenticated banner/category strip and course grid can be rendered.

**Primary interactions tested**

- Not browser-tested yet: search, category filtering, bookmark state, enrollment, course navigation.

**Console errors checked**

- Blocked: authenticated screen did not render.

**Implementation checklist**

1. Authenticate the in-app browser.
2. Capture 1440 × 1024 and compare the hero, category strip, featured course, and card grid.
3. Test search, category selection, bookmark state, and a non-destructive course link.
4. Capture 991px and 390px responsive views.
5. Fix any P0/P1/P2 mismatch and repeat comparison.

final result: blocked

## Document viewer refinement — 2026-07-21

- Source visual truth path: `C:\Users\PROGRA~1\AppData\Local\Temp\codex-clipboard-20a76f3f-be32-43b4-b5f3-cae073d13582.png`
- Implementation route: `http://localhost:8184/lms-pandoralite/viewdoc/fileview/80/course_file/111`
- Viewport: source screenshot 1920 × 1080; responsive CSS includes 991px and 575px breakpoints
- State: PDF preview error

**Full-view comparison evidence**

- Source screenshot inspected: the preview error copy had insufficient contrast, the dark stage dominated the viewport, and the recovery actions lacked a clear container.
- Updated implementation cannot be browser-captured because this protected route requires an authenticated LMS session unavailable in the isolated in-app browser.

**Focused region comparison evidence**

- Source error region inspected at the center of the document stage.
- Post-change rendered focus capture is blocked by authentication.

**Fixes made**

- Rebuilt the error state as a high-contrast white recovery panel within the dark document stage.
- Strengthened typography selectors to prevent legacy global heading styles from making error copy unreadable.
- Unified retry/download actions with the ISUZU red and neutral button hierarchy.
- Improved mobile padding, action stacking, and panel sizing.
- Removed decorative gradients from the viewer stylesheet and retained solid design tokens.

**Required fidelity surfaces**

- Fonts and typography: explicit error heading/body hierarchy added; rendered comparison blocked.
- Spacing and layout rhythm: recovery panel constrained to 660px with desktop/mobile spacing; rendered comparison blocked.
- Colors and visual tokens: solid ISUZU red, navy, white, and neutral palette applied with accessible contrast.
- Image quality and asset fidelity: no new raster assets required; existing file media remains unchanged.
- Copy and content: existing Thai/English/Japanese viewer copy retained.

**Findings**

- [P0] Authenticated document viewer cannot be captured in the isolated verification browser.
  - Impact: post-change visual comparison and interaction testing remain unavailable.
  - Fix: authenticate the in-app browser, then capture the PDF error state at desktop and mobile widths.

final result: blocked

## Course detail option 3 implementation — 2026-07-21

- Selected visual truth: `C:\Users\Programer\.codex\generated_images\019f8230-701d-7af3-ab6c-f574ecee10aa\exec-1e136ac4-54a9-42e8-ac58-964ea1ba5c02.png`.
- Implemented the compact dark hero with cover on the left and course identity/metadata on the right.
- Implemented the two-column Learning Workspace: course overview on the left and ordered learning path on the right.
- Learning-step numbering is rendered from the live pre-test, lesson, post-test, and survey sequence without changing lock/unlock logic.
- Progress is calculated from each live `data-statustc` value and displayed above the learning path.
- Responsive behavior collapses the workspace to one column below 992px and tightens controls below 640px.
- PHP lint and whitespace validation passed for the changed view files.
- Visual comparison remains blocked because the protected course route redirects the isolated in-app browser session to login.

## Demo course redesign — 2026-07-21

- Source visual truth path: `C:\Users\PROGRA~1\AppData\Local\Temp\codex-clipboard-cf44a506-1d68-416e-a251-9490cb78db53.png`
- Implementation route: `http://localhost:8184/lms-pandoralite/managecourse/courses_demo/633`
- Viewport: intended desktop 1920 × 1080; responsive rules added for 992px and 768px breakpoints
- State: authenticated course preview/demo

**Full-view comparison evidence**

- The source screenshot was supplied and inspected.
- Browser rendering was redirected to `/home?redirect=managecourse/courses_demo/633`; the isolated in-app browser has no authenticated LMS session.

**Focused region comparison evidence**

- Not available because the authenticated Demo hero and learning path could not render in the verification browser.

**Implementation changes**

- Replaced the disconnected legacy cover/details cards with a dark, unified course-preview hero matching the real course page.
- Grouped pre-test, lessons, post-test, and survey in one premium learning-path workspace.
- Added an explicit preview-only state while retaining all existing Demo endpoints and handlers.
- Added responsive desktop, tablet, and mobile rules scoped to `.demo-course-detail`.

**Required fidelity surfaces**

- Fonts and typography: hierarchy aligned in scoped CSS; rendered comparison blocked by authentication.
- Spacing and layout rhythm: desktop/tablet/mobile rules inspected; rendered comparison blocked by authentication.
- Colors and visual tokens: ISUZU red, dark navy, white, and neutral tokens reused from the real course page.
- Image quality and asset fidelity: existing uploaded course cover retained; rendered crop comparison blocked by authentication.
- Copy and content: Demo labels support Thai, English, and Japanese; course data continues using the current-language database values.

**Primary interactions tested**

- PHP syntax passed for `course_detail_demo.php` and `tab/course_demo.php`.
- Browser interaction testing is blocked before the protected route renders.

**Console errors checked**

- The authenticated implementation did not render, so page-specific console verification is blocked.

**Findings**

- [P0] Authenticated Demo screen cannot be captured in the isolated verification browser.
  - Impact: visual fidelity and expanded lesson interactions cannot receive a browser-rendered pass.
  - Fix: authenticate the in-app browser and repeat desktop/mobile capture and interaction checks.

final result: blocked

final result: blocked

## Course detail redesign — 2026-07-21

- Source: authenticated screenshot of `/coursemain/detail/111` supplied by the user.
- Implemented: compact dark course hero, constrained cover image, course metadata, full-width overview, premium learning-path cards, redesigned language modal, and responsive breakpoints at 992px and 640px.
- Static verification: PHP lint and whitespace validation passed.
- Browser comparison: blocked because the in-app browser redirects the protected route to login and has no authenticated session.

final result: blocked
