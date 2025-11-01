// Mobile Navigation and Touch Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'mobile-menu-toggle';
    mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    mobileMenuToggle.style.cssText = `
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1001;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-size: 1.25rem;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    `;

    // Add mobile menu toggle to body
    document.body.appendChild(mobileMenuToggle);

    // Show mobile menu toggle on mobile devices
    function checkMobile() {
        if (window.innerWidth <= 768) {
            mobileMenuToggle.style.display = 'block';
        } else {
            mobileMenuToggle.style.display = 'none';
            // Close sidebar if open
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.remove('open');
            }
            // Remove overlay if exists
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    }

    // Initial check
    checkMobile();

    // Check on resize
    window.addEventListener('resize', checkMobile);

    // Mobile menu toggle functionality
    mobileMenuToggle.addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) return;

        const isOpen = sidebar.classList.contains('open');
        
        if (isOpen) {
            // Close sidebar
            sidebar.classList.remove('open');
            mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            
            // Remove overlay
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) {
                overlay.remove();
            }
        } else {
            // Open sidebar
            sidebar.classList.add('open');
            mobileMenuToggle.innerHTML = '<i class="fas fa-times"></i>';
            
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: block;
            `;
            
            // Close sidebar when overlay is clicked
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
                overlay.remove();
            });
            
            document.body.appendChild(overlay);
        }
    });

    // Touch-friendly interactions
    function addTouchSupport() {
        // Add touch feedback to buttons
        const buttons = document.querySelectorAll('.btn, .action-card, .nav-item, .card, .stat-card');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(0.98)';
                this.style.opacity = '0.8';
                this.style.transition = 'all 0.1s ease';

                // Add ripple effect
                const ripple = document.createElement('span');
                ripple.className = 'touch-ripple';
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.touches[0].clientX - rect.left - size / 2;
                const y = e.touches[0].clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                    z-index: 1;
                `;

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);
            });

            button.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = '';
                    this.style.opacity = '';
                    this.style.transition = '';
                }, 150);
            });

            button.addEventListener('touchcancel', function() {
                this.style.transform = '';
                this.style.opacity = '';
                this.style.transition = '';
            });
        });

        // Improve form input focus on mobile
        const inputs = document.querySelectorAll('.form-input, .form-select, .form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                // Scroll input into view on mobile
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        this.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    }, 300);
                }
            });
        });
    }

    // Initialize touch support
    addTouchSupport();

    // Swipe gestures for mobile navigation
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 100;
        const sidebar = document.querySelector('.sidebar');
        
        if (!sidebar || window.innerWidth > 768) return;

        // Swipe right to open sidebar
        if (touchEndX - touchStartX > swipeThreshold && touchStartX < 50) {
            if (!sidebar.classList.contains('open')) {
                mobileMenuToggle.click();
            }
        }
        
        // Swipe left to close sidebar
        if (touchStartX - touchEndX > swipeThreshold && sidebar.classList.contains('open')) {
            mobileMenuToggle.click();
        }
    }

    // Optimize table scrolling on mobile
    const tableContainers = document.querySelectorAll('.table-container');
    tableContainers.forEach(container => {
        // Add scroll indicators
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator';
        scrollIndicator.innerHTML = '<i class="fas fa-chevron-right"></i> Geser untuk melihat lebih banyak';
        scrollIndicator.style.cssText = `
            display: none;
            text-align: center;
            padding: 0.5rem;
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            font-size: 0.75rem;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        `;
        
        container.parentNode.insertBefore(scrollIndicator, container);
        
        // Show indicator on mobile
        function checkTableScroll() {
            if (window.innerWidth <= 768) {
                const table = container.querySelector('.table');
                if (table && table.scrollWidth > container.clientWidth) {
                    scrollIndicator.style.display = 'block';
                } else {
                    scrollIndicator.style.display = 'none';
                }
            } else {
                scrollIndicator.style.display = 'none';
            }
        }
        
        checkTableScroll();
        window.addEventListener('resize', checkTableScroll);
        
        // Hide indicator when scrolled
        container.addEventListener('scroll', function() {
            if (this.scrollLeft > 10) {
                scrollIndicator.style.display = 'none';
            }
        });
    });

    // Mobile-friendly modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('touchmove', function(e) {
            // Prevent background scrolling when modal is open
            e.preventDefault();
        }, { passive: false });
    });

    // Optimize toast notifications for mobile
    function optimizeToasts() {
        const toastContainer = document.querySelector('.toast-container');
        if (toastContainer && window.innerWidth <= 768) {
            toastContainer.style.cssText = `
                position: fixed;
                top: 10px;
                left: 10px;
                right: 10px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
            `;
        }
    }

    // Check toast optimization on resize
    window.addEventListener('resize', optimizeToasts);
    optimizeToasts();

    // Add pull-to-refresh functionality (optional)
    let startY = 0;
    let currentY = 0;
    let pullDistance = 0;
    const pullThreshold = 100;

    document.addEventListener('touchstart', function(e) {
        if (window.pageYOffset === 0) {
            startY = e.touches[0].pageY;
        }
    });

    document.addEventListener('touchmove', function(e) {
        if (window.pageYOffset === 0 && startY) {
            currentY = e.touches[0].pageY;
            pullDistance = currentY - startY;
            
            if (pullDistance > 0 && pullDistance < pullThreshold) {
                // Visual feedback for pull-to-refresh
                document.body.style.transform = `translateY(${pullDistance * 0.5}px)`;
                document.body.style.transition = 'none';
            }
        }
    });

    document.addEventListener('touchend', function() {
        if (pullDistance > pullThreshold) {
            // Trigger refresh
            window.location.reload();
        }
        
        // Reset
        document.body.style.transform = '';
        document.body.style.transition = '';
        startY = 0;
        currentY = 0;
        pullDistance = 0;
    });

    // Keyboard navigation improvements for mobile
    document.addEventListener('keydown', function(e) {
        // Close mobile menu with Escape key
        if (e.key === 'Escape') {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('open')) {
                mobileMenuToggle.click();
            }
        }
    });
});
