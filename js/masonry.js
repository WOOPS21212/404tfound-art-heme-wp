document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('.projects-grid');
  if (!grid) return;

  // Initialize Macy.js for masonry layout
  const macyInstance = Macy({
    container: '.projects-grid',
    trueOrder: false,
    waitForImages: true,
    margin: 32, // Matches the gap: 2rem from CSS
    columns: 3,
    breakAt: {
      1200: 3,
      940: 2, 
      520: 1
    }
  });

  // Handle videos
  const initializeVideos = () => {
    console.group('Initializing Videos');
    const videos = grid.querySelectorAll('.project-card__media--video');
    
    videos.forEach(video => {
      // Set initial attributes
      video.autoplay = true;
      video.loop = true;
      video.muted = true;
      video.playsInline = true;
      
      // Trigger macy recalculation when video metadata is loaded
      video.addEventListener('loadedmetadata', () => {
        setTimeout(() => macyInstance.recalculate(true), 100);
      }, { once: true });
      
      console.log('Initialized video:', {
        src: video.src,
        readyState: video.readyState,
        dimensions: `${video.videoWidth}x${video.videoHeight}`
      });
    });
    console.groupEnd();
  };

  // Handle images
  const initializeImages = () => {
    console.group('Initializing Images');
    const images = grid.querySelectorAll('.project-card__media:not(.project-card__media--video)');
    
    images.forEach(image => {
      // Trigger macy recalculation when image is loaded
      image.addEventListener('load', () => {
        setTimeout(() => macyInstance.recalculate(true), 100);
      }, { once: true });
    });
    console.groupEnd();
  };

  // Initialize
  initializeVideos();
  initializeImages();

  // Run macy recalculation after a delay to ensure all items are processed
  setTimeout(() => {
    macyInstance.recalculate(true);
    console.log('Initial Macy.js layout calculated');
  }, 500);

  // Resize handler with debounce
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      macyInstance.recalculate(true);
      console.log('Macy.js layout recalculated after resize');
    }, 200);
  });

  // Add debug toggle button functionality
  const debugButton = document.querySelector('.debug-layout-toggle');
  if (debugButton) {
    debugButton.addEventListener('click', () => {
      grid.classList.toggle('debug-layout');
      console.clear();
      macyInstance.recalculate(true);
    });
  }
});
