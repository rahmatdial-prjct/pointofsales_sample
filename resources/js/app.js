import './bootstrap';

// Custom Date Picker Enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Enhance all date inputs
    const dateInputs = document.querySelectorAll('input[type="date"]');

    dateInputs.forEach(function(input) {
        enhanceDateInput(input);
    });

    function enhanceDateInput(input) {
        // Create wrapper if not exists
        if (!input.parentElement.classList.contains('date-input-wrapper')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'date-input-wrapper';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);
        }

        // Add placeholder functionality
        const placeholder = input.getAttribute('placeholder') || 'Pilih tanggal';
        input.parentElement.setAttribute('data-placeholder', placeholder);

        // Add custom styling class
        input.classList.add('custom-date-input');

        // Handle focus and blur events
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });

        // Handle value changes
        input.addEventListener('change', function() {
            if (this.value) {
                this.parentElement.classList.add('has-value');
            } else {
                this.parentElement.classList.remove('has-value');
            }
        });

        // Initial state check
        if (input.value) {
            input.parentElement.classList.add('has-value');
        }
    }

    // Format date display for better UX
    dateInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    weekday: 'long'
                };
                const formattedDate = date.toLocaleDateString('id-ID', options);

                // Create or update tooltip
                let tooltip = this.nextElementSibling;
                if (!tooltip || !tooltip.classList.contains('date-tooltip')) {
                    tooltip = document.createElement('div');
                    tooltip.className = 'date-tooltip';
                    this.parentNode.insertBefore(tooltip, this.nextSibling);
                }

                tooltip.textContent = formattedDate;
                tooltip.style.cssText = `
                    position: absolute !important;
                    top: 100% !important;
                    left: 0 !important;
                    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%) !important;
                    color: white !important;
                    padding: 6px 12px !important;
                    border-radius: var(--radius-sm) !important;
                    font-size: 12px !important;
                    font-weight: 500 !important;
                    margin-top: 6px !important;
                    box-shadow: var(--shadow) !important;
                    white-space: nowrap !important;
                    z-index: 1000 !important;
                    opacity: 0;
                    transform: translateY(-10px);
                    transition: all 0.3s ease;
                    pointer-events: none;
                `;

                // Show tooltip briefly
                setTimeout(() => {
                    tooltip.style.opacity = '1';
                    tooltip.style.transform = 'translateY(0)';
                }, 100);

                setTimeout(() => {
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'translateY(-10px)';
                }, 3000);
            }
        });
    });
});
