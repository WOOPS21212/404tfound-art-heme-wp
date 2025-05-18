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
        <article class="project-card" 
          itemscope 
          itemtype="http://schema.org/CreativeWork"
          data-year="<?php echo esc_attr(get_field('year')); ?>"
          data-role="<?php 
            $roles = get_the_terms(get_the_ID(), 'role');
            echo esc_attr($roles ? implode(',', wp_list_pluck($roles, 'slug')) : '');
          ?>"
          data-industry="<?php 
            $industries = get_the_terms(get_the_ID(), 'industry');
            echo esc_attr($industries ? implode(',', wp_list_pluck($industries, 'slug')) : '');
          ?>"
        >
          <a class="project-card__link" href="<?php the_permalink(); ?>" itemprop="url">
            <div class="project-card__media">
              <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('medium', ['class' => 'project-card__thumbnail', 'itemprop' => 'image']); ?>
              <?php else: ?>
                <!-- Fallback image or placeholder handled in future enhancement -->
              <?php endif; ?>
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

<?php
get_footer(); 