$(document).ready(function() {
  // Store scroll position when opening modal
  var scrollPosition = 0;
  
  // Toggle sidebar on mobile
  $('#sidebarToggle').click(function() {
    $('#sidebar').toggleClass('active');
  });

  // Filter Modal Toggle
  $('#filterButton').click(function() {
    scrollPosition = $(window).scrollTop();
    $('#filterModal, #filterBackdrop').fadeIn();
    $('body').addClass('modal-open');
  });
  
  $('#closeFilter, #filterBackdrop').click(function() {
    $('#filterModal, #filterBackdrop').fadeOut();
    $('body').removeClass('modal-open');
    $(window).scrollTop(scrollPosition);
  });
  
  // Prevent modal close when clicking inside
  $('#filterModal').click(function(e) {
    e.stopPropagation();
  });
  
  // Apply Filter Button - Now with proper client-side filtering
  $('#applyFilter').click(function() {
    const authorId = $('#authorFilter').val();
    const categoryId = $('#categoryFilter').val();
    const yearMin = $('#yearFilter').val();
    
    // Hide modal first
    $('#filterModal, #filterBackdrop').fadeOut();
    $('body').removeClass('modal-open');
    $(window).scrollTop(scrollPosition);
    
    // Client-side filtering
    $('.book-card').each(function() {
      const $card = $(this);
      const cardAuthorId = $card.data('author-id');
      const cardCategoryId = $card.data('category-id');
      const cardYear = $card.data('year');
      
      let shouldShow = true;
      
      // Apply author filter if specified
      if (authorId && authorId !== '' && cardAuthorId != authorId) {
        shouldShow = false;
      }
      
      // Apply category filter if specified
      if (categoryId && categoryId !== '' && cardCategoryId != categoryId) {
        shouldShow = false;
      }
      
      // Apply year filter if specified
      if (yearMin && yearMin !== '' && cardYear && parseInt(cardYear) < parseInt(yearMin)) {
        shouldShow = false;
      }
      
      // Toggle visibility based on filters
      $card.toggleClass('filter-hidden', !shouldShow);
    });
    
    // Show message if no books match filters
    if ($('.book-card:not(.filter-hidden)').length === 0) {
      $('#bookResults').append('<p class="text-center py-4">No books match your filters.</p>');
    }
  });

  // Function to update book display (kept for consistency but not used in client-side filtering)
  function updateBookDisplay(books) {
    const $bookResults = $('#bookResults');
    $bookResults.empty();
    
    if (books.length === 0) {
      $bookResults.append('<p class="text-center py-4">No books match your filters.</p>');
      return;
    }

    books.forEach(book => {
      const bookHtml = `
        <div class="book-card">
          <img src="uploads/cover/${book.cover}" class="book-cover" alt="${book.title}">
          <div class="book-details">
            <h3 class="book-title">${book.title}</h3>
            <div class="book-author">
              <i class="fas fa-user-tie"></i>
              ${book.author_name || 'Unknown Author'}
            </div>
            <div class="book-meta">
              <span class="book-category">${book.category_name || 'Uncategorized'}</span>
              <span class="book-pages">
                <i class="fas fa-file-alt"></i>
                ${book.pages || 'N/A'} pages
              </span>
              <span class="book-year">
                <i class="fas fa-calendar-alt"></i>
                ${book.published_year || 'N/A'}
              </span>
            </div>
            <p class="book-description">
              ${book.description ? book.description.substring(0, 200) + '...' : 'No description available'}
            </p>
            <?php if(isset($_SESSION['user_id'])): ?>
            <div class="book-actions">
              <a href="edit-book.php?id=${book.id}" class="action-btn btn btn-primary">
                <i class="fas fa-pencil-alt"></i> Edit
              </a>
              <a href="delete-book.php?id=${book.id}" class="action-btn btn btn-outline-secondary" onclick="return confirm('Are you sure you want to delete this book?')">
                <i class="fas fa-trash"></i> Delete
              </a>
              <a href="view-book.php?id=${book.id}" class="action-btn btn btn-outline-primary">
                <i class="fas fa-eye"></i> View Details
              </a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      `;
      $bookResults.append(bookHtml);
    });
  }

  // Allow scrolling in modal if content is long
  $('#filterModal').on('wheel', function(e) {
    e.stopPropagation();
  });
  
  // Fix for View Details button - ensure correct path
  $(document).on('click', 'a[href^="view-book.php"]', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    // Check if file exists
    $.ajax({
      url: url,
      type: 'HEAD',
      error: function() {
        alert('The view-book.php file was not found. Please ensure it exists in your project directory.');
      },
      success: function() {
        window.location.href = url;
      }
    });
  });

  // Export button functionality
  $('#exportButton').click(function() {
    window.location.href = 'export-books.php';
  });


  
});