## 🚧 Live Progress Tracker

### ✅ Phase 1 – Core Functionality

| Feature                       | Status        | Notes                               |
| ----------------------------- | ------------- | ----------------------------------- |
| CPT `projects`                | ✅ Done        | Registered in `functions.php`       |
| Taxonomies `role`, `industry` | ✅ Done        | Nested & hierarchical               |
| ACF Basic Field Group         | ✅ Done        | Year, Tech, Collab, Gallery         |
| ACF Advanced Fields           | ✅ Done        | Vimeo, Process, BTS, Downloads      |
| Homepage Masonry Grid         | ❌ Not started | Uses custom template                |
| Single Project Template       | ✅ Done        | All ACF and media logic implemented |
| Theme Versioning with Git     | ✅ Setup       | Theme and ACF JSON tracked in Git   |

...

## 🧱 Core Functionality (Phase 1)

### ✅ 1. Custom Post Type: projects

* Registered in functions.php
* URL format: /projects/project-name
* Visible in Admin for easy content entry

### ✅ 2. Enhanced ACF Fields for Projects

* **Year** (ACF Select): Dropdown of years
* **Technologies Used** (ACF Checkbox): Software, tools, techniques
* **Collaboration Type** (ACF Select – Multiple): Solo, Colorist, VFX Compositor, 3D Generalist, FXTD
* **Description** (WYSIWYG): Supports inline images
* **Image Gallery** (ACF Gallery): Optional; fallback to featured image
* **External Links** (ACF URL fields): Includes icons, open in new tab

### ✅ 3. Advanced Content Fields

* **Before/After Slider** (ACF Image fields): Optional comparison slider
* **Embedded Vimeo Videos** (ACF URL): Tutorial/case study videos with custom player
* **Process Sections** (ACF Flexible Content):

  * Pre-production notes
  * Production techniques
  * Post-production breakdown
  * Tutorial-style explanations
* **Behind-the-Scenes Content** (ACF Group):

  * Enable checkbox
  * Custom styling when active
  * BTS images/videos
* **Technical Breakdown** (ACF Group):

  * Enable checkbox
  * Making-of content
  * Custom styling when active
* **Downloadable Resources** (ACF File fields):

  * ZIP files
  * .hip files
  * Video files
  * Custom download tracking

### ✅ 4. Git-Based Theme Versioning

* Entire theme folder is Git-tracked
* Includes ACF JSON (`acf-json/` directory)
* PRD document is included in the Git repository
* Any changes to the PRD should be committed and pushed along with theme updates
* Enables migration to other machines or environments
* Use `.gitignore` to exclude uploads and local environment config

...

### ✅ 7. Enhanced Single Project Template

* **Template:** single-projects.php
* **Hero Section:**

  * Full-width media (70vh)
  * Title + Year (bottom-left)
  * Tags + Links (bottom-right)
  * Loading states for media
* **Content Section:**

  * Minimal breadcrumbs: Home / Projects / Project Name
  * Description with styled inline images
  * Technologies used badges
  * Before/after comparison slider (if enabled)
  * Embedded Vimeo videos with custom styling
  * **Conditional Content Sections:**

    * Process breakdown (tutorial-style, custom styling)
    * Behind-the-scenes (custom styling when enabled)
    * Technical breakdown (custom styling when enabled)
  * Downloadable resources section
  * Optional image gallery with lightbox
  * Related projects section (reusing homepage cards)
* **Navigation:** Large prev/next arrows
