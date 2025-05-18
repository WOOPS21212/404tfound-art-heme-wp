<?php
/**
 * Single Project Template
 *
 * @package Portfolio_Theme
 */

global $post;

get_header();
?>

<main class="project-single">
  <?php
  // Get ACF fields
  $vimeo_url = get_field('vimeo_url');
  $year = get_field('year');
  // Placeholder for future video URL field
  $hero_video_url = false; // get_field('hero_video_url');

  // Get featured image
  $featured_img_url = get_the_post_thumbnail_url($post, 'full');
  ?>

  <!-- Hero Section -->
  <section class="project-single__hero" style="height:70vh;position:relative;overflow:hidden;">
    <?php if ($hero_video_url): ?>
      <!-- Placeholder: Hero Video (muted, looping) -->
      <video class="project-single__hero-video" src="<?php echo esc_url($hero_video_url); ?>" autoplay loop muted playsinline style="width:100%;height:100%;object-fit:cover;"></video>
    <?php elseif ($featured_img_url): ?>
      <div class="project-single__hero-image" style="background-image:url('<?php echo esc_url($featured_img_url); ?>');background-size:cover;background-position:center;width:100%;height:100%;position:absolute;inset:0;"></div>
    <?php endif; ?>
    <div class="project-single__hero-overlay" style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(26,26,26,0.2) 0%,rgba(26,26,26,0.9) 100%);"></div>
    <div class="project-single__hero-content" style="position:absolute;bottom:0;left:0;right:0;display:flex;justify-content:space-between;align-items:flex-end;padding:32px;z-index:2;">
      <div class="project-single__hero-meta-left">
        <h1 class="project-single__title"><?php the_title(); ?></h1>
        <?php if ($year): ?>
          <span class="project-single__year"><?php echo esc_html($year); ?></span>
        <?php endif; ?>
      </div>
      <div class="project-single__hero-meta-right" style="text-align:right;">
        <?php
        // Taxonomy terms
        $roles = get_the_term_list($post->ID, 'role', '', ', ', '');
        $industries = get_the_term_list($post->ID, 'industry', '', ', ', '');
        if ($roles) {
          echo '<div class="project-single__roles">' . $roles . '</div>';
        }
        if ($industries) {
          echo '<div class="project-single__industries">' . $industries . '</div>';
        }
        // Placeholder: External ACF links (add fields as needed)
        ?>
      </div>
    </div>
  </section>

  <!-- Main Content Area -->
  <section class="project-single__content" style="max-width:900px;margin:0 auto;padding:32px 0;">
    <?php
    // Conditionally embed Vimeo video
    if ($vimeo_url):
      // Extract Vimeo ID for embed
      if (preg_match('/vimeo\.com\/(\d+)/', $vimeo_url, $matches)) {
        $vimeo_id = $matches[1];
      } else {
        $vimeo_id = false;
      }
      if ($vimeo_id): ?>
        <div class="project-single__vimeo" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;margin-bottom:32px;">
          <iframe src="https://player.vimeo.com/video/<?php echo esc_attr($vimeo_id); ?>" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Vimeo video for <?php the_title_attribute(); ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
        </div>
      <?php endif;
    endif;

    // 1. Process Sections (Flexible Content)
    if (have_rows('process_sections')): ?>
      <section class="project-single__process-sections">
        <?php while (have_rows('process_sections')): the_row();
          $layout = get_row_layout();
          if ($layout === 'pre_production_notes') {
            $content = get_sub_field('notes');
            if ($content) {
              echo '<div class="project-single__process-section project-single__process-section--pre-production-notes">';
              echo '<h2 class="project-single__process-heading">Pre-production Notes</h2>';
              echo '<div class="project-single__process-content">' . $content . '</div>';
              echo '</div>';
            }
          } elseif ($layout === 'production_techniques') {
            $content = get_sub_field('techniques');
            if ($content) {
              echo '<div class="project-single__process-section project-single__process-section--production-techniques">';
              echo '<h2 class="project-single__process-heading">Production Techniques</h2>';
              echo '<div class="project-single__process-content">' . $content . '</div>';
              echo '</div>';
            }
          } elseif ($layout === 'post_production_breakdown') {
            $content = get_sub_field('breakdown');
            if ($content) {
              echo '<div class="project-single__process-section project-single__process-section--post-production-breakdown">';
              echo '<h2 class="project-single__process-heading">Post-production Breakdown</h2>';
              echo '<div class="project-single__process-content">' . $content . '</div>';
              echo '</div>';
            }
          } elseif ($layout === 'tutorial_code_block') {
            $code = get_sub_field('code');
            $language = get_sub_field('language');
            if ($code) {
              echo '<div class="project-single__process-section project-single__process-section--tutorial-code-block">';
              if ($language) {
                echo '<h2 class="project-single__process-heading">' . esc_html($language) . ' Code</h2>';
              }
              echo '<pre class="project-single__code-block"><code>' . esc_html($code) . '</code></pre>';
              echo '</div>';
            }
          }
        endwhile; ?>
      </section>
    <?php endif;

    // 2. Behind the Scenes (Group)
    $bts = get_field('bts_content');
    if ($bts && !empty($bts['enable_bts'])) {
      echo '<section class="project-bts">';
      echo '<h2 class="project-bts__heading">Behind the Scenes</h2>';
      if (!empty($bts['bts_media'])) {
        echo '<div class="project-bts__gallery">';
        foreach ($bts['bts_media'] as $image) {
          echo '<figure class="project-bts__image">';
          echo '<img src="' . esc_url($image['sizes']['large']) . '" alt="' . esc_attr($image['alt']) . '" />';
          if (!empty($image['caption'])) {
            echo '<figcaption>' . esc_html($image['caption']) . '</figcaption>';
          }
          echo '</figure>';
        }
        echo '</div>';
      }
      echo '</section>';
    }

    // 3. Technical Breakdown (Group)
    $tech = get_field('tech_breakdown');
    if ($tech && !empty($tech['enable_tech'])) {
      echo '<section class="project-tech-breakdown">';
      echo '<h2 class="project-tech-breakdown__heading">Technical Breakdown</h2>';
      if (!empty($tech['making_of'])) {
        echo '<div class="project-tech-breakdown__content">' . $tech['making_of'] . '</div>';
      }
      echo '</section>';
    }

    // 4. Downloads Section (Repeater)
    if (have_rows('downloads')): ?>
      <section class="project-downloads" itemscope itemtype="https://schema.org/CreativeWork">
        <h2 class="project-downloads__heading">Downloadable Resources</h2>
        <ul class="project-downloads__list">
          <?php while (have_rows('downloads')): the_row();
            $file = get_sub_field('file');
            $label = get_sub_field('label');
            $file_type = get_sub_field('file_type');
            if ($file && $label && $file_type): ?>
              <li class="project-downloads__item" itemprop="distribution" itemscope itemtype="https://schema.org/DataDownload">
                <span class="project-downloads__label" itemprop="name"><?php echo esc_html($label); ?></span>
                <span class="project-downloads__type">(<?php echo esc_html($file_type); ?>)</span>
                <a class="project-downloads__button" href="<?php echo esc_url($file['url']); ?>" download target="_blank" itemprop="contentUrl" rel="noopener">
                  Download
                </a>
              </li>
            <?php endif;
          endwhile; ?>
        </ul>
      </section>
    <?php endif;

    // 5. Gallery Section
    $gallery = get_field('gallery');
    if ($gallery && is_array($gallery)): ?>
      <section class="project-gallery">
        <h2 class="project-gallery__heading">Gallery</h2>
        <div class="project-gallery__grid">
          <?php foreach ($gallery as $image):
            $alt = $image['title'] ? $image['title'] : ($image['caption'] ? $image['caption'] : 'Project image');
            ?>
            <figure class="project-gallery__item">
              <img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" class="project-gallery__img" />
              <?php if (!empty($image['caption'])): ?>
                <figcaption class="project-gallery__caption"><?php echo esc_html($image['caption']); ?></figcaption>
              <?php endif; ?>
            </figure>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif;

    // 6. Related Projects Section
    // Get current post taxonomies
    $related_args = array(
      'post_type' => 'projects',
      'posts_per_page' => 4,
      'post__not_in' => array($post->ID),
      'orderby' => 'rand',
      'tax_query' => array('relation' => 'OR'),
    );
    $role_terms = wp_get_post_terms($post->ID, 'role', array('fields' => 'ids'));
    $industry_terms = wp_get_post_terms($post->ID, 'industry', array('fields' => 'ids'));
    if (!empty($role_terms)) {
      $related_args['tax_query'][] = array(
        'taxonomy' => 'role',
        'field' => 'term_id',
        'terms' => $role_terms,
      );
    }
    if (!empty($industry_terms)) {
      $related_args['tax_query'][] = array(
        'taxonomy' => 'industry',
        'field' => 'term_id',
        'terms' => $industry_terms,
      );
    }
    // Only run query if there are terms
    if (count($related_args['tax_query']) > 1) {
      $related_query = new WP_Query($related_args);
      if ($related_query->have_posts()): ?>
        <section class="project-related">
          <h2 class="project-related__heading">Related Projects</h2>
          <div class="project-related__list">
            <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
              <article class="project-related__item">
                <a href="<?php the_permalink(); ?>" class="project-related__link">
                  <?php if (has_post_thumbnail()): ?>
                    <div class="project-related__thumb">
                      <?php the_post_thumbnail('medium', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
                    </div>
                  <?php endif; ?>
                  <h3 class="project-related__title"><?php the_title(); ?></h3>
                </a>
              </article>
            <?php endwhile; ?>
          </div>
        </section>
      <?php endif;
      wp_reset_postdata();
    }

    // Additional project content goes here
    the_content();
    ?>
  </section>
</main>

<?php get_footer(); ?> 