# Profile redesign QA

- Source visual truth: `C:\Users\Programer\.codex\generated_images\019fee5a-beaf-75b1-84b5-bac491d7d44c\exec-2125e9bb-d202-4385-8ba4-574864f45301.png`
- Intended implementation route: `http://localhost:8184/lms-pandoralite/dashboard/profile`
- Browser evidence: `H:\php84-apache-mysql-docker\src\lms-pandoralite\design-qa-auth-blocker.png`
- Viewport: 1751 x 807 CSS pixels
- Source pixels: 1848 x 851
- Implementation capture pixels: 1751 x 807 at device scale factor 1
- Density normalization: source and viewport share the same approximately 2.17:1 aspect ratio; no visual comparison was completed because the authenticated screen was unavailable.
- State: requested profile route redirected to the public login page at `/home`.

**Findings**

- [P0] Authenticated implementation screen cannot be captured
  - Location: `/dashboard/profile`
  - Evidence: browser navigation redirected to `/home`, so the implementation screenshot contains the login page rather than the profile screen.
  - Impact: layout fidelity, responsive behavior, tab interactions, and visual polish cannot be evaluated from browser-rendered evidence.
  - Fix: open the profile route in an authenticated in-app browser session, capture it at 1751 x 807, and rerun the comparison against the selected source visual.

**Required fidelity surfaces**

- Fonts and typography: blocked pending authenticated render.
- Spacing and layout rhythm: blocked pending authenticated render.
- Colors and visual tokens: code tokens align to the selected white/charcoal/red direction; browser comparison blocked.
- Image quality and asset fidelity: existing user portrait and Material Design icon font are preserved; browser comparison blocked.
- Copy and content: Thai/English labels and existing dynamic profile values are preserved; browser comparison blocked.

**Interaction checks**

- General-settings tab: blocked by authentication redirect.
- Certificates tab: blocked by authentication redirect.
- Profile image picker: blocked by authentication redirect.
- Save form: not submitted; blocked by authentication redirect.
- Console errors: none on the redirect/login page, but this does not validate the profile page.

**Implementation checklist**

- Capture the authenticated profile page at the target viewport.
- Compare the full view against the selected visual.
- Inspect focused regions for the identity rail, summary strip, bilingual field groups, and sticky save action.
- Test both tabs, the language selector, image picker, and form validation without committing profile changes.
- Fix all P0/P1/P2 differences and repeat the capture.

**Comparison history**

- Initial pass: blocked before visual comparison because the app redirected to login.
- Fixes made: none; bypassing authentication or submitting guessed credentials was not appropriate.
- Post-fix evidence: not available.

final result: blocked
