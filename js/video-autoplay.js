/**
 * Video Autoplay Handler
 * 
 * Handles automatic playback of videos in the projects grid,
 * with intersection observer for performance and fallbacks.
 */
document.addEventListener('DOMContentLoaded', () => {
  // Get all grid videos
  const gridVideos = document.querySelectorAll('.project-card__video');
  
  // If we don't have any videos, exit early
  if (gridVideos.length === 0) return;
  
  // Check if IntersectionObserver is supported
  if ('IntersectionObserver' in window) {
    // Function to handle playing videos when they come into view
    const playVisibleVideos = (entries, observer) => {
      entries.forEach(entry => {
        // Get the video element
        const video = entry.target;
        
        if (entry.isIntersecting) {
          // Video is visible, try to play it
          const playPromise = video.play();
          
          // Handle play promise rejection (browsers might block autoplay)
          if (playPromise !== undefined) {
            playPromise.catch(error => {
              console.warn('Video playback prevented:', error);
              
              // If autoplay failed, add a play button or other UI indication
              addPlayButton(video);
            });
          }
        } else {
          // Video is not visible, pause it to save resources
          if (!video.paused) {
            video.pause();
          }
        }
      });
    };
    
    // Create an intersection observer
    const videoObserver = new IntersectionObserver(playVisibleVideos, {
      root: null, // use the viewport
      threshold: 0.1 // trigger when at least 10% of the video is visible
    });
    
  // Observe all grid videos
  gridVideos.forEach(video => {
    videoObserver.observe(video);
    
    // Add error handling for videos
    video.addEventListener('error', () => {
      handleVideoError(video);
    });
    
    // Trigger layout update when videos load to maintain proper grid
    video.addEventListener('loadedmetadata', () => {
      // Give the video a moment to apply its natural dimensions
      setTimeout(() => {
        // Dispatch a custom event that main.js can listen for
        const videoLoadedEvent = new CustomEvent('videoLoaded', {
          detail: { 
            videoElement: video,
            mediaId: video.dataset.mediaId 
          }
        });
        document.dispatchEvent(videoLoadedEvent);
        
        // Also trigger a resize event as fallback
        window.dispatchEvent(new Event('resize'));
      }, 100);
    });
  });
  } else {
    // Fallback for browsers without IntersectionObserver
    gridVideos.forEach(video => {
      // Try to play video immediately
      const playPromise = video.play();
      
      if (playPromise !== undefined) {
        playPromise.catch(error => {
          console.warn('Video playback prevented:', error);
          addPlayButton(video);
        });
      }
      
      // Add error handling
      video.addEventListener('error', () => {
        handleVideoError(video);
      });
    });
  }
  
  /**
   * Handle video loading errors by showing the fallback image
   */
  function handleVideoError(video) {
    console.warn('Video failed to load, showing fallback image');
    
    // Hide the video
    video.style.display = 'none';
    
    // Find the parent media container
    const mediaContainer = video.closest('.project-card__media');
    
    // Look for a fallback image
    const fallbackImg = video.querySelector('img');
    
    if (fallbackImg) {
      // Show the fallback image
      fallbackImg.style.display = 'block';
    } else {
      // Create a placeholder if no fallback image
      const placeholder = document.createElement('div');
      placeholder.className = 'project-card__placeholder';
      
      const placeholderText = document.createElement('span');
      placeholderText.className = 'project-card__placeholder-text';
      placeholderText.textContent = 'Media Unavailable';
      
      placeholder.appendChild(placeholderText);
      mediaContainer.appendChild(placeholder);
    }
  }
  
  /**
   * Add a play button for videos that can't autoplay
   */
  function addPlayButton(video) {
    // Check if a play button already exists
    if (video.parentNode.querySelector('.project-card__play-button')) return;
    
    // Create play button
    const playButton = document.createElement('button');
    playButton.className = 'project-card__play-button';
    playButton.setAttribute('aria-label', 'Play video');
    
    // Add play icon (simple CSS triangle)
    playButton.innerHTML = '<span class="play-icon">▶</span>';
    
    // Insert after the video
    video.parentNode.insertBefore(playButton, video.nextSibling);
    
    // Add click event to play the video
    playButton.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      
      video.play()
        .then(() => {
          // Hide the play button when video starts playing
          playButton.style.display = 'none';
        })
        .catch(error => {
          console.error('Video playback failed after click:', error);
        });
    });
  }
});
