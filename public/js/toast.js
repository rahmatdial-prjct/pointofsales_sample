/**
 * Toast Notification System
 */
class ToastManager {
    constructor() {
        this.container = this.createContainer();
        this.toasts = [];
    }

    createContainer() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = 'info', title = null, duration = 5000) {
        const toast = this.createToast(message, type, title);
        this.container.appendChild(toast);
        this.toasts.push(toast);

        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);

        // Auto remove
        if (duration > 0) {
            setTimeout(() => {
                this.remove(toast);
            }, duration);
        }

        return toast;
    }

    createToast(message, type, title) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const titleMap = {
            success: 'Berhasil!',
            error: 'Terjadi Kesalahan!',
            warning: 'Peringatan!',
            info: 'Informasi'
        };

        toast.innerHTML = `
            <div class="toast-icon">
                <i class="${iconMap[type] || iconMap.info}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title || titleMap[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="toastManager.remove(this.parentElement)">
                <i class="fas fa-times"></i>
            </button>
        `;

        return toast;
    }

    remove(toast) {
        if (!toast || !toast.parentElement) return;

        toast.classList.remove('show');
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
            
            const index = this.toasts.indexOf(toast);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        }, 300);
    }

    success(message, title = null) {
        return this.show(message, 'success', title);
    }

    error(message, title = null) {
        return this.show(message, 'error', title);
    }

    warning(message, title = null) {
        return this.show(message, 'warning', title);
    }

    info(message, title = null) {
        return this.show(message, 'info', title);
    }

    clear() {
        this.toasts.forEach(toast => this.remove(toast));
    }
}

// Initialize global toast manager
const toastManager = new ToastManager();

// Auto-show Laravel session messages
document.addEventListener('DOMContentLoaded', function() {
    // Check for Laravel session flash messages
    const successMessage = document.querySelector('meta[name="flash-success"]');
    const errorMessage = document.querySelector('meta[name="flash-error"]');
    const warningMessage = document.querySelector('meta[name="flash-warning"]');
    const infoMessage = document.querySelector('meta[name="flash-info"]');

    if (successMessage) {
        toastManager.success(successMessage.getAttribute('content'));
    }

    if (errorMessage) {
        toastManager.error(errorMessage.getAttribute('content'));
    }

    if (warningMessage) {
        toastManager.warning(warningMessage.getAttribute('content'));
    }

    if (infoMessage) {
        toastManager.info(infoMessage.getAttribute('content'));
    }

    // Handle old alert elements and convert them to toasts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        let type = 'info';
        let message = alert.textContent.trim();

        if (alert.classList.contains('alert-success')) {
            type = 'success';
        } else if (alert.classList.contains('alert-error') || alert.classList.contains('alert-danger')) {
            type = 'error';
        } else if (alert.classList.contains('alert-warning')) {
            type = 'warning';
        }

        if (message) {
            toastManager.show(message, type);
            alert.style.display = 'none';
        }
    });
});

// Export for global use
window.toastManager = toastManager;
window.showToast = (message, type, title) => toastManager.show(message, type, title);
