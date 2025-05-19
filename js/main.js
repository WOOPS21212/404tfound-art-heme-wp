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

    // Trigger Macy.js recalculation after filter change
    // This will properly handle the layout after filtering
    if (window.macyInstance) {
      setTimeout(() => window.macyInstance.recalculate(true), 300);
    }
  }

  // NOTE: Removed the conflicting updateMasonryLayout function
  // Macy.js is handling the masonry layout already
  
  // Remove ResizeObserver that was controlling grid layout
  // Macy.js has its own resize handling

  // Listen for video loaded event from video-autoplay.js
  document.addEventListener('videoLoaded', (event) => {
    // Recalculate the Macy.js layout when a video has loaded
    if (window.macyInstance) {
      window.macyInstance.recalculate(true);
    }
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
    
    // Update Macy.js layout
    if (window.macyInstance) {
      window.macyInstance.recalculate(true);
    }
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

  // Debug project card data attributes
  document.querySelectorAll('.project-card').forEach(card => {
    console.group('Project Card Data');
    console.log('Roles:', card.dataset.roles);
    console.log('Industries:', card.dataset.industries);
    console.log('Year:', card.dataset.year);
    console.groupEnd();
  });
});
