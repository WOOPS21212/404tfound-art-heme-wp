document.addEventListener('DOMContentLoaded', () => {
  const grid = document.querySelector('.projects-grid');
  if (!grid) return;

  // Configuration
  const GRID_ROW_HEIGHT = 50; // Updated to match new CSS grid-auto-rows
  const GRID_GAP = 32; // Matches gap: 2rem from CSS
  const MIN_ROW_SPAN = 5; // Reduced since base row height is larger
  
  const logDimensions = (element, prefix = '') => {
    console.group(`${prefix} Dimensions Debug:`);
    const computed = window.getComputedStyle(element);
    console.log({
      element,
      naturalWidth: element instanceof HTMLVideoElement ? element.videoWidth : element.naturalWidth,
      naturalHeight: element instanceof HTMLVideoElement ? element.videoHeight : element.naturalHeight,
      offsetWidth: element.offsetWidth,
      offsetHeight: element.offsetHeight,
      computedHeight: computed.height,
      gridRowEnd: computed.gridRowEnd
    });
    console.groupEnd();
  };

  const calculateRowSpan = (height) => {
    console.group('Row Span Calculation');
    
    // Account for grid gap in the calculation
    const totalHeight = height + GRID_GAP;
    const rowHeightWithGap = GRID_ROW_HEIGHT + GRID_GAP;
    
    // Calculate and enforce minimum span
    const rowSpan = Math.max(
      Math.ceil(totalHeight / rowHeightWithGap),
      MIN_ROW_SPAN
    );

    console.log({
      height,
      totalHeight,
      rowHeightWithGap,
      calculatedSpan: Math.ceil(totalHeight / rowHeightWithGap),
      finalRowSpan: rowSpan
    });
    
    console.groupEnd();
    return rowSpan;
  };

  const updateMasonryLayout = () => {
    console.group('Masonry Layout Update');
    const items = grid.querySelectorAll('.project-card');
    
    items.forEach(item => {
      const media = item.querySelector('.project-card__media');
      if (!media) return;

      // Debug current state
      console.group(`Processing: ${item.querySelector('.project-card__title')?.textContent}`);
      logDimensions(media, 'Before Update');

      if (media instanceof HTMLVideoElement) {
        // Handle video elements
        console.group('Video Processing');
        
        if (media.readyState < 1) {
          console.log('Video metadata not loaded, applying temporary span');
          item.style.gridRowEnd = `span ${MIN_ROW_SPAN}`;
          
          // Wait for metadata to load
          media.addEventListener('loadedmetadata', () => {
            console.log('Video metadata loaded:', {
              videoWidth: media.videoWidth,
              videoHeight: media.videoHeight
            });
            updateItemLayout(item, media);
          }, { once: true });
          
          console.groupEnd();
          return;
        }

        // Calculate based on video's natural dimensions
        const aspectRatio = media.videoWidth / media.videoHeight;
        const width = item.offsetWidth;
        const height = width / aspectRatio;
        
        console.log('Video dimensions:', {
          naturalWidth: media.videoWidth,
          naturalHeight: media.videoHeight,
          aspectRatio,
          containerWidth: width,
          calculatedHeight: height
        });
        
        const rowSpan = calculateRowSpan(height);
        item.style.gridRowEnd = `span ${rowSpan}`;
        
        console.groupEnd();
      } else {
        // Handle images
        const aspectRatio = media.naturalWidth / media.naturalHeight;
        const width = item.offsetWidth;
        const height = width / aspectRatio;
        
        const rowSpan = calculateRowSpan(height);
        item.style.gridRowEnd = `span ${rowSpan}`;
      }

      // Debug final state
      logDimensions(media, 'After Update');
      console.groupEnd();
    });
    console.groupEnd();
  };

  // Handle video loads with retry
  const handleVideoLoad = (video) => {
    console.group('Video Load Handler');
    console.log('Video state:', {
      readyState: video.readyState,
      videoWidth: video.videoWidth,
      videoHeight: video.videoHeight
    });

    if (video.readyState >= 1) {
      const item = video.closest('.project-card');
      updateItemLayout(item, video);
    } else {
      console.log('Video not ready, waiting for metadata...');
      video.addEventListener('loadedmetadata', () => {
        const item = video.closest('.project-card');
        updateItemLayout(item, video);
      }, { once: true });
    }
    console.groupEnd();
  };

  // Add the missing initializeVideos function
  const initializeVideos = () => {
    console.group('Initializing Videos');
    const videos = grid.querySelectorAll('.project-card__media--video');
    
    videos.forEach(video => {
      // Set initial attributes
      video.autoplay = true;
      video.loop = true;
      video.muted = true;
      video.playsInline = true;
      
      // Handle video loading
      if (video.readyState >= 1) {
        handleVideoLoad(video);
      } else {
        video.addEventListener('loadedmetadata', () => handleVideoLoad(video), { once: true });
      }
      
      console.log('Initialized video:', {
        src: video.src,
        readyState: video.readyState,
        dimensions: `${video.videoWidth}x${video.videoHeight}`
      });
    });
    console.groupEnd();
  };

  // Add missing initializeImages function
  const initializeImages = () => {
    console.group('Initializing Images');
    const images = grid.querySelectorAll('.project-card__media:not(.project-card__media--video)');
    
    images.forEach(image => {
      if (image.complete) {
        updateItemLayout(image.closest('.project-card'), image);
      } else {
        image.addEventListener('load', () => {
          updateItemLayout(image.closest('.project-card'), image);
        }, { once: true });
      }
    });
    console.groupEnd();
  };

  // Add missing updateItemLayout function
  const updateItemLayout = (item, media) => {
    if (!item || !media) return;
    
    const width = item.offsetWidth;
    const aspectRatio = media instanceof HTMLVideoElement 
      ? media.videoWidth / media.videoHeight
      : media.naturalWidth / media.naturalHeight;
    
    const height = width / aspectRatio;
    const rowSpan = calculateRowSpan(height);
    
    item.style.gridRowEnd = `span ${rowSpan}`;
    console.log('Updated layout:', { width, height, aspectRatio, rowSpan });
  };

  // Initialize
  initializeVideos();
  initializeImages();
  updateMasonryLayout();

  // Resize handler with debounce
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(updateMasonryLayout, 100);
  });

  // Add debug toggle button functionality
  const debugButton = document.querySelector('.debug-layout-toggle');
  if (debugButton) {
    debugButton.addEventListener('click', () => {
      grid.classList.toggle('debug-layout');
      console.clear();
      updateMasonryLayout();
    });
  }
}); 