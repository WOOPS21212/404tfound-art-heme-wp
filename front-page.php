<?php
get_header();
?>

<main class="site-main">
  <aside class="project-filters" role="complementary" aria-label="Project Filters">
    <form class="project-filters__form" role="search" aria-label="Filter Projects">
      <!-- Year Filter -->
      <fieldset class="project-filters__section project-filters__section--year">
        <legend class="project-filters__legend">Year</legend>
        <div class="project-filters__options">
          <?php
          // Get all distinct years from 'projects' CPT (ACF field 'year')
          global $wpdb;
          $years = $wpdb->get_col("
            SELECT DISTINCT pm.meta_value
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = 'year' AND p.post_type = 'projects' AND p.post_status = 'publish'
            ORDER BY pm.meta_value DESC
          ");
          if ($years):
            foreach ($years as $year):
              if (!$year) continue;
              ?>
              <label class="project-filters__option">
                <input type="checkbox" name="year[]" value="<?php echo esc_attr($year); ?>" class="project-filters__checkbox project-filters__checkbox--year">
                <span class="project-filters__label-text"><?php echo esc_html($year); ?></span>
              </label>
              <?php
            endforeach;
          endif;
          ?>
        </div>
      </fieldset>

      <!-- Industry Filter -->
      <fieldset class="project-filters__section project-filters__section--industry">
        <legend class="project-filters__legend">Industry</legend>
        <div class="project-filters__options">
          <?php
          $industries = get_terms([
            'taxonomy' => 'industry',
            'hide_empty' => false,
          ]);
          if (!empty($industries) && !is_wp_error($industries)):
            foreach ($industries as $industry):
              ?>
              <label class="project-filters__option">
                <input type="checkbox" name="industry[]" value="<?php echo esc_attr($industry->slug); ?>" class="project-filters__checkbox project-filters__checkbox--industry">
                <span class="project-filters__label-text"><?php echo esc_html($industry->name); ?></span>
              </label>
              <?php
            endforeach;
          endif;
          ?>
        </div>
      </fieldset>

      <!-- Role Filter -->
      <fieldset class="project-filters__section project-filters__section--role">
        <legend class="project-filters__legend">Role</legend>
        <div class="project-filters__options">
          <?php
          $roles = get_terms([
            'taxonomy' => 'role',
            'hide_empty' => false,
          ]);
          if (!empty($roles) && !is_wp_error($roles)):
            foreach ($roles as $role):
              ?>
              <label class="project-filters__option">
                <input type="checkbox" name="role[]" value="<?php echo esc_attr($role->slug); ?>" class="project-filters__checkbox project-filters__checkbox--role">
                <span class="project-filters__label-text"><?php echo esc_html($role->name); ?></span>
              </label>
              <?php
            endforeach;
          endif;
          ?>
        </div>
      </fieldset>

      <button type="button" class="project-filters__clear" aria-label="Clear All Filters">Clear All Filters</button>
    </form>
  </aside>

  <section class="projects-grid" aria-label="Portfolio Projects">
    <?php
    $projects_query = new WP_Query([
      'post_type'      => 'projects',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
    ]);
    if ($projects_query->have_posts()):
      while ($projects_query->have_posts()): $projects_query->the_post();
        $post_id = get_the_ID();
        ?>
        <article class="project-card" itemscope itemtype="http://schema.org/CreativeWork">
          <a class="project-card__link" href="<?php the_permalink(); ?>" itemprop="url">
            <div class="project-card__media-wrapper">
              <?php
              $grid_media = get_field('grid_media');
              if ($grid_media) {
                $file_info = wp_check_filetype($grid_media);
                $mime_type = $file_info['type'];
                
                if (strpos($mime_type, 'video/') === 0) {
                  ?>
                  <video 
                    class="project-card__media project-card__media--video" 
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    data-masonry-item
                    readystate="0"
                    onloadedmetadata="this.setAttribute('readystate', '2')"
                    oncanplay="this.setAttribute('readystate', '3')"
                    oncanplaythrough="this.setAttribute('readystate', '4')"
                  >
                    <source src="<?php echo esc_url($grid_media); ?>" type="<?php echo esc_attr($mime_type); ?>">
                  </video>
                  <?php
                } elseif ($mime_type === 'image/gif') {
                  ?>
                  <img 
                    class="project-card__media project-card__media--gif" 
                    src="<?php echo esc_url($grid_media); ?>" 
                    alt="<?php echo esc_attr(get_the_title()); ?>" 
                    loading="lazy"
                    data-masonry-item
                  >
                  <?php
                }
              } elseif (has_post_thumbnail()) {
                the_post_thumbnail('large', array(
                  'class' => 'project-card__media project-card__media--image',
                  'loading' => 'lazy',
                  'itemprop' => 'image',
                  'data-masonry-item' => ''
                ));
              }
              ?>
            </div>
            <h2 class="project-card__title" itemprop="name"><?php the_title(); ?></h2>
            <!-- Future: video preview, filters, lazy loading, badges, etc. -->
          </a>
        </article>
        <?php
      endwhile;
      wp_reset_postdata();
    else:
      ?>
      <p class="projects-grid__empty">No projects found.</p>
    <?php endif; ?>
  </section>
</main>

<button 
  onclick="document.querySelector('.projects-grid').classList.toggle('debug-layout')"
  style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; padding: 10px;"
>
  Toggle Debug Layout
</button>

<?php
get_footer();
