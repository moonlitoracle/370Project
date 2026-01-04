# UI Unification Implemenation Plan

## 1. Style Unification
We will extract inline styles from `skill-gap-analysis.html` and `progress-tracking.html` and move them to `public/css/style.css`.

### New CSS Sections
*   **Skill Gap Analysis**: `.overall`, `.career-card` (if not exists), `.match`, `.gaps`.
*   **Progress Tracking**: `.stat-card` (unify with dashboard?), `.proficiency-section`, `.prof-bar`, `.level-display`.

## 2. Container Standardization
*   `dashboard.html`, `goals.html` use `.dashboard-container`.
*   `skill-gap-analysis.html`, `progress-tracking.html` use `.container`.
*   **Decision**: We will rename the custom `.container` styles in the extracted CSS to be more specific (e.g., `.analysis-container`) OR simply alias them to the standard wrapper if `style.css` has one. `style.css` usually has a `.container` class.

## 3. Redundant File Cleanup
*   Double check `js/` and `css/` folders. (Done: clean).

## 4. Execution Steps
1.  Read `style.css`.
2.  Append missing styles to `style.css`.
3.  Remove `<style>` blocks from HTML files.
4.  Remove Reduntant Body styles (e.g. background color overrides).
