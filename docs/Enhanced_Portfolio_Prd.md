# Enhanced Portfolio PRD

## 🚧 Live Progress Tracker

### ✅ Phase 1 – Core Functionality

| Feature                       | Status        | Notes                               |
| ----------------------------- | ------------- | ----------------------------------- |
| CPT `projects`                | ✅ Done        | Registered in `functions.php`       |
| Taxonomies `role`, `industry` | ✅ Done        | Nested & hierarchical               |
| ACF Basic Field Group         | ✅ Done        | Year, Tech, Collab, Gallery         |
| ACF Advanced Fields           | ✅ Done        | Vimeo, Process, BTS, Downloads with accordions |
| Homepage Masonry Grid         | ✅ Done        | Interactive filtering & masonry layout complete |
| Single Project Template       | ✅ Done        | All ACF and media logic implemented |
| Theme Versioning with Git     | ✅ Setup       | Theme and ACF JSON tracked in Git   |

### 🔄 Phase 2 – Advanced Features

| Feature                       | Status        | Notes                               |
| ----------------------------- | ------------- | ----------------------------------- |
| Before/After Slider           | ❌ Not started | Image comparison component          |
| Video Background              | ❌ Not started | Hero video support                  |
| Lightbox Gallery              | ❌ Not started | Modal image viewer                  |
| Download Tracking             | ❌ Not started | Analytics for file downloads       |
| Related Projects              | ✅ Done        | Based on taxonomies                 |
| SEO Optimization              | ❌ Not started | Meta tags, schema markup           |

### 🎨 Phase 3 – Polish & Performance

| Feature                       | Status        | Notes                               |
| ----------------------------- | ------------- | ----------------------------------- |
| Animation System              | ❌ Not started | GSAP/CSS animations                 |
| Performance Optimization      | ❌ Not started | Image lazy loading, minification   |
| Mobile Optimization           | ❌ Not started | Touch interactions, responsive      |
| Accessibility Audit          | ❌ Not started | ARIA labels, keyboard navigation    |
| Browser Testing               | ❌ Not started | Cross-browser compatibility         |

### 📦 Phase 4 – Production Ready

| Feature                       | Status        | Notes                               |
| ----------------------------- | ------------- | ----------------------------------- |
| Content Migration             | ❌ Not started | Import existing work                |
| Backup System                 | ❌ Not started | Automated backups                   |
| Security Hardening            | ❌ Not started | WordPress security best practices   |
| Analytics Setup               | ❌ Not started | Google Analytics, heatmaps          |
| Performance Monitoring        | ❌ Not started | Core Web Vitals, GTmetrix          |

---

## 🧱 Core Functionality (Phase 1)

### ✅ 1. Custom Post Type: projects

* Registered in functions.php
* URL format: /projects/project-name
* Visible in Admin for easy content entry

### ✅ 2. Enhanced ACF Fields for Projects

* **Year** (ACF Select): Dropdown of years
* **Technologies Used** (ACF Checkbox): Software, tools, techniques
* **Collaboration Type** (ACF Checkbox - Multiple): Solo, Colorist, VFX Compositor, 3D Generalist, FXTD, Motion Designer, Editor
* **Description** (WYSIWYG): Supports inline images
* **Image Gallery** (ACF Gallery): Optional; fallback to featured image
* **External Links** (ACF URL fields): Includes icons, open in new tab
* **Grid Media** (ACF File): Video/image for homepage grid display

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

### ✅ 5. Homepage Masonry Grid

* Custom template: front-page.php
* Interactive filtering by role, industry, and year
* Responsive masonry layout with CSS Grid
* Video autoplay for grid items with intersection observer
* Smooth animations and transitions
* Loading states and fallbacks
* SEO optimization with structured data

### ✅ 6. Hierarchical Taxonomies

* **Role taxonomy**: Colorist, VFX Compositor, 3D Generalist, FXTD, etc.
* **Industry taxonomy**: Film, TV, Advertising, Music Videos, etc.
* Both registered as hierarchical (category-like)
* Visible in admin columns for quick reference
* Used for filtering and related project suggestions

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

### ✅ 8. Dark Theme Design System

* **Color Palette:**
  * Primary: #000000 (Black)
  * Background: #1a1a1a (Dark Grey)
  * Surface: #333333 (Medium Grey)
  * Text: #cccccc (Light Grey)
  * Accent: #ff7f32 (Orange)
* **Typography:**
  * Headings: 'Doto' (pixel-style font)
  * Body: System font stack
* **Components:** Cards, buttons, forms with consistent styling
* **Responsive:** Mobile-first approach with breakpoints

### ✅ 9. Development Workflow

* **Sass/SCSS:** Organized component structure
* **Gulp:** Live reloading with BrowserSync
* **Git:** Version control with meaningful commits
* **Local Development:** WordPress local environment
* **Build Process:** Automated CSS compilation

### ✅ 10. Admin Interface Improvements

* **ACF Field Organization:**
  * Accordion sections added to declutter project creation page
  * Basic fields remain open: Grid Media, Description, Year, Technologies, Collaboration Type
  * Collapsed sections: "Gallery & Links" containing Image Gallery and External Links
  * Future sections planned: "Advanced Content" and "Downloads & Resources"
* **Field Type Optimizations:**
  * Collaboration Type changed from select to checkbox for multiple selections
  * Custom CSS styling for larger, more prominent accordion section titles
  * Improved visual hierarchy in admin interface

---

## 🚀 Advanced Features (Phase 2)

### ❌ 11. Before/After Image Slider

* Uses twentytwenty.js or custom solution
* Touch/swipe support for mobile
* ACF implementation with two image fields
* Only appears when both images are uploaded
* Responsive and accessible

### ❌ 12. Video Background Support

* Upload hero videos for project pages
* Fallback to featured image if video fails
* Autoplay, muted, loop attributes
* Performance optimization for mobile
* Format support: MP4, WebM

### ❌ 13. Advanced Gallery with Lightbox

* Replace basic gallery with enhanced version
* Lightbox modal with navigation
* Thumbnail grid with hover effects
* Caption support
* Keyboard navigation
* Mobile swipe gestures

### ❌ 14. Download Tracking Analytics

* Track file download events
* Google Analytics integration
* Admin dashboard with download stats
* Popular resource identification
* User behavior insights

### ❌ 15. Enhanced SEO

* Custom meta descriptions per project
* Open Graph tags for social sharing
* Schema.org markup for creative works
* Sitemap generation
* Image alt text optimization

### ❌ 16. Performance Optimization

* Image lazy loading
* Video lazy loading
* Critical CSS inlining
* JavaScript minification
* CDN integration possibilities

---

## 🎨 Polish & Performance (Phase 3)

### ❌ 17. Animation System

* GSAP integration for smooth animations
* Scroll-triggered animations
* Page transition effects
* Hover state micro-interactions
* Loading animations

### ❌ 18. Mobile Experience Enhancement

* Touch-optimized interactions
* Mobile-specific layouts
* Gesture support for galleries
* Performance optimization for mobile
* Progressive Web App considerations

### ❌ 19. Accessibility Compliance

* WCAG 2.1 AA compliance
* Keyboard navigation support
* Screen reader optimization
* Focus management
* Color contrast verification

### ❌ 20. Browser Compatibility

* Cross-browser testing
* Progressive enhancement
* Fallbacks for older browsers
* Performance testing
* Mobile browser optimization

---

## 📦 Production Deployment (Phase 4)

### ❌ 21. Content Migration Strategy

* Export/import tools for existing content
* Image optimization pipeline
* Content audit and cleanup
* URL redirection mapping
* SEO preservation during migration

### ❌ 22. Backup & Security

* Automated daily backups
* Security plugin configuration
* User role management
* Login security enhancements
* Regular security audits

### ❌ 23. Analytics & Monitoring

* Google Analytics 4 setup
* Conversion goal tracking
* Performance monitoring
* User behavior analysis
* Regular reporting dashboard

### ❌ 24. Maintenance Plan

* WordPress core updates
* Plugin update strategy
* Content review schedule
* Performance optimization reviews
* Security monitoring

---

## 📋 Implementation Notes

### Development Environment Setup

1. **Local WordPress:** XAMPP/MAMP/Local by Flywheel
2. **Theme Structure:** Based on Underscores (_s)
3. **Dependencies:** ACF Pro, Gulp, Sass, BrowserSync
4. **Version Control:** Git with meaningful commit messages
5. **Code Editor:** Cursor IDE with AI assistance for development tasks

### Content Strategy

1. **Project Categories:** Organize by industry and role
2. **Media Strategy:** High-quality images and videos
3. **SEO Planning:** Keyword research for each project
4. **Content Calendar:** Regular portfolio updates
5. **Client Case Studies:** Detailed project breakdowns

### Performance Targets

1. **Page Load Time:** Under 3 seconds
2. **Core Web Vitals:** Green scores
3. **Mobile Performance:** 90+ PageSpeed score
4. **Accessibility:** WCAG 2.1 AA compliance
5. **SEO:** Technical SEO optimization

---

## 🔄 Change Log

**Latest Updates:**
- Homepage masonry grid with filtering completed
- Admin interface improvements with accordion organization
- Collaboration Type field changed to checkboxes
- Video autoplay with intersection observer implemented
- Custom CSS styling for accordion titles planned

**Previous Updates:**
- Enhanced ACF fields with advanced content sections
- Single project template with conditional content blocks
- Dark theme design system implementation
- Git-based versioning with ACF JSON tracking
- Hierarchical taxonomies for role and industry classification

---

*This PRD is a living document that evolves with the project. All changes should be tracked and committed to Git along with the corresponding code updates.*