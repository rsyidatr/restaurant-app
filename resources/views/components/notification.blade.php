<!-- Notification Component -->
<div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<style>
/* Notification Styles */
.notification {
    min-width: 300px;
    max-width: 400px;
    transform: translateX(400px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.notification.show {
    transform: translateX(0);
    opacity: 1;
}

.notification.hide {
    transform: translateX(400px);
    opacity: 0;
}

.notification-content {
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.notification-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-success .notification-icon {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
}

.notification-error .notification-icon {
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: white;
}

.notification-warning .notification-icon {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
}

.notification-info .notification-icon {
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: white;
}

.notification-close {
    transition: all 0.2s ease;
    opacity: 0.5;
}

.notification-close:hover {
    opacity: 1;
    transform: scale(1.1);
}

.notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.6));
    border-radius: 0 0 12px 12px;
    animation: progress 5s linear forwards;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>

<script>
class NotificationManager {
    constructor() {
        this.container = document.getElementById('notification-container');
        this.notifications = [];
        this.autoHideTimeout = 5000; // 5 seconds
    }

    show(message, type = 'success', autoHide = true) {
        const id = Date.now().toString();
        const notification = this.createNotification(id, message, type, autoHide);
        
        this.container.appendChild(notification);
        this.notifications.push(id);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Auto hide
        if (autoHide) {
            setTimeout(() => {
                this.hide(id);
            }, this.autoHideTimeout);
        }
        
        return id;
    }

    createNotification(id, message, type, autoHide) {
        const notification = document.createElement('div');
        notification.id = `notification-${id}`;
        notification.className = `notification notification-${type}`;
        
        const icons = {
            success: 'fas fa-check',
            error: 'fas fa-times',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info'
        };
        
        const borderColors = {
            success: 'border-green-200',
            error: 'border-red-200', 
            warning: 'border-yellow-200',
            info: 'border-blue-200'
        };
        
        notification.innerHTML = `
            <div class="notification-content bg-white ${borderColors[type]} border rounded-xl p-4 relative overflow-hidden">
                <div class="flex items-start space-x-3">
                    <div class="notification-icon">
                        <i class="${icons[type]}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 leading-relaxed">${message}</p>
                    </div>
                    <button onclick="notificationManager.hide('${id}')" 
                            class="notification-close text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
                ${autoHide ? '<div class="notification-progress"></div>' : ''}
            </div>
        `;
        
        // Click to dismiss
        notification.addEventListener('click', (e) => {
            if (!e.target.closest('button')) {
                this.hide(id);
            }
        });
        
        return notification;
    }

    hide(id) {
        const notification = document.getElementById(`notification-${id}`);
        if (notification) {
            notification.classList.add('hide');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
                this.notifications = this.notifications.filter(n => n !== id);
            }, 400);
        }
    }

    hideAll() {
        this.notifications.forEach(id => this.hide(id));
    }

    success(message, autoHide = true) {
        return this.show(message, 'success', autoHide);
    }

    error(message, autoHide = true) {
        return this.show(message, 'error', autoHide);
    }

    warning(message, autoHide = true) {
        return this.show(message, 'warning', autoHide);
    }

    info(message, autoHide = true) {
        return this.show(message, 'info', autoHide);
    }
}

// Initialize notification manager
const notificationManager = new NotificationManager();

// Auto-show Laravel session flash messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        notificationManager.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        notificationManager.error('{{ session('error') }}');
    @endif

    @if(session('warning'))
        notificationManager.warning('{{ session('warning') }}');
    @endif

    @if(session('info'))
        notificationManager.info('{{ session('info') }}');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            notificationManager.error('{{ $error }}');
        @endforeach
    @endif
});

// Global functions for manual use
window.showNotification = (message, type = 'success', autoHide = true) => {
    return notificationManager.show(message, type, autoHide);
};

window.showSuccess = (message, autoHide = true) => {
    return notificationManager.success(message, autoHide);
};

window.showError = (message, autoHide = true) => {
    return notificationManager.error(message, autoHide);
};

window.showWarning = (message, autoHide = true) => {
    return notificationManager.warning(message, autoHide);
};

window.showInfo = (message, autoHide = true) => {
    return notificationManager.info(message, autoHide);
};
</script>
