document.addEventListener('DOMContentLoaded', () => {
  const projectsGrid = document.querySelector('.projects-grid');
  const filterForm = document.querySelector('.project-filters__form');
  const clearButton = document.querySelector('.project-filters__clear');
  const projectCards = document.querySelectorAll('.project-card');

  // Filter state object
  const filterState = {
    year: [],
    role: [],
    industry: []
  };

  // Update filters and cards
  function updateFilters() {
    // Collect all checked filters
    filterState.year = [...document.querySelectorAll('.project-filters__checkbox--year:checked')]
      .map(checkbox => checkbox.value);
    filterState.role = [...document.querySelectorAll('.project-filters__checkbox--role:checked')]
      .map(checkbox => checkbox.value);
    filterState.industry = [...document.querySelectorAll('.project-filters__checkbox--industry:checked')]
      .map(checkbox => checkbox.value);

    // Filter cards
    projectCards.forEach(card => {
      const yearMatch = filterState.year.length === 0 || 
        filterState.year.includes(card.dataset.year);
      
      const roleMatch = filterState.role.length === 0 || 
        filterState.role.split(',').some(role => filterState.role.includes(role));
      
      const industryMatch = filterState.industry.length === 0 || 
        filterState.industry.split(',').some(industry => 
          filterState.industry.includes(industry));

      const isVisible = yearMatch && roleMatch && industryMatch;

      // Toggle visibility with animation
      requestAnimationFrame(() => {
        card.classList.toggle('is-hidden', !isVisible);
        card.setAttribute('aria-hidden', !isVisible);
      });
    });

    // Trigger masonry reflow after animations
    setTimeout(updateMasonryLayout, 300);
  }

  // Masonry layout using ResizeObserver
  function updateMasonryLayout() {
    const grid = projectsGrid;
    const items = [...grid.children].filter(
      child => !child.classList.contains('is-hidden')
    );
    
    if (items.length === 0) return; // Exit if no items are visible
    
    // Get the computed grid gap
    const gridGap = parseInt(
      getComputedStyle(grid).getPropertyValue('grid-gap') || 
      getComputedStyle(grid).getPropertyValue('gap')
    );

    // Calculate and set the grid auto-rows
    const rowHeight = parseInt(getComputedStyle(items[0]).getPropertyValue('grid-auto-rows'));
    
    items.forEach(item => {
      // Get the entire card height, including both media and content
      const media = item.querySelector('.project-card__media');
      const content = item.querySelector('.project-card__content');
      
      let totalHeight = item.getBoundingClientRect().height;
      
      // Calculate appropriate rowspan based on the total height
      const rowSpan = Math.ceil(
        (totalHeight + gridGap) / (rowHeight + gridGap)
      );
      
      item.style.gridRowEnd = `span ${rowSpan}`;
    });
  }

  // Initialize ResizeObserver for responsive masonry
  const resizeObserver = new ResizeObserver(entries => {
    requestAnimationFrame(updateMasonryLayout);
  });
  resizeObserver.observe(projectsGrid);
  
  // Listen for video loaded event from video-autoplay.js
  document.addEventListener('videoLoaded', (event) => {
    // Recalculate the masonry layout when a video has loaded
    // This ensures the grid properly respects the natural aspect ratio of videos
    requestAnimationFrame(updateMasonryLayout);
  });

  // Event Listeners
  filterForm.addEventListener('change', event => {
    if (event.target.matches('.project-filters__checkbox')) {
      updateFilters();
    }
  });

  // Clear filters
  clearButton.addEventListener('click', () => {
    // Reset checkboxes
    filterForm.querySelectorAll('input[type="checkbox"]')
      .forEach(checkbox => checkbox.checked = false);
    
    // Show all cards
    projectCards.forEach(card => {
      card.classList.remove('is-hidden');
      card.setAttribute('aria-hidden', 'false');
    });

    // Reset filter state
    Object.keys(filterState).forEach(key => filterState[key] = []);
    
    // Update layout
    updateMasonryLayout();
  });

  // Keyboard accessibility for filter options
  filterForm.querySelectorAll('.project-filters__option').forEach(option => {
    option.addEventListener('keydown', event => {
      if (event.key === ' ' || event.key === 'Enter') {
        event.preventDefault();
        const checkbox = option.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        checkbox.dispatchEvent(new Event('change'));
      }
    });
  });

  // Initial layout
  updateMasonryLayout();

  // Debug project card data attributes
  document.querySelectorAll('.project-card').forEach(card => {
    console.group('Project Card Data');
    console.log('Roles:', card.dataset.roles);
    console.log('Industries:', card.dataset.industries);
    console.log('Year:', card.dataset.year);
    console.groupEnd();
  });
});
