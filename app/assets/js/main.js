/**
 * Main JavaScript File
 * Frontend interactivity and utilities
 */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeComponents();
    setupEventListeners();
});

/**
 * Initialize all components
 */
function initializeComponents() {
    initializeMenus();
    initializeModals();
    initializeTables();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const isDropdown = event.target.closest('[class*="dropdown"]');
        if (!isDropdown) {
            closeAllDropdowns();
        }
    });
    
    // Handle form submissions
    document.addEventListener('submit', handleFormSubmit);
}

/**
 * Initialize menu components
 */
function initializeMenus() {
    const menuButtons = document.querySelectorAll('[data-menu-toggle]');
    menuButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMenu(this);
        });
    });
}

/**
 * Toggle menu visibility
 */
function toggleMenu(button) {
    const menuId = button.getAttribute('data-menu-toggle');
    const menu = document.getElementById(menuId);
    if (menu) {
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
}

/**
 * Close all dropdowns
 */
function closeAllDropdowns() {
    const dropdowns = document.querySelectorAll('[class*="dropdown"]');
    dropdowns.forEach(dropdown => {
        dropdown.style.display = 'none';
    });
}

/**
 * Initialize modals
 */
function initializeModals() {
    // Modal close buttons
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('[class*="modal"]');
            if (modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    // Close modal on background click
    const modals = document.querySelectorAll('[class*="modal-overlay"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    });
}

/**
 * Open modal
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

/**
 * Close modal
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

/**
 * Initialize tables
 */
function initializeTables() {
    const tables = document.querySelectorAll('table[data-sortable]');
    tables.forEach(table => {
        setupTableSorting(table);
    });
}

/**
 * Setup table sorting
 */
function setupTableSorting(table) {
    const headers = table.querySelectorAll('th[data-sortable]');
    headers.forEach((header, columnIndex) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            sortTable(table, columnIndex, this);
        });
    });
}

/**
 * Sort table
 */
function sortTable(table, columnIndex, header) {
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const isAscending = !header.classList.contains('sort-asc');
    
    rows.sort((a, b) => {
        const aValue = a.querySelectorAll('td')[columnIndex].textContent.trim();
        const bValue = b.querySelectorAll('td')[columnIndex].textContent.trim();
        
        const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
        const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return isAscending ? aNum - bNum : bNum - aNum;
        }
        
        return isAscending ? 
            aValue.localeCompare(bValue) : 
            bValue.localeCompare(aValue);
    });
    
    const tbody = table.querySelector('tbody');
    rows.forEach(row => tbody.appendChild(row));
    
    // Update header styles
    table.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
    
    header.classList.add(isAscending ? 'sort-asc' : 'sort-desc');
}

/**
 * Handle form submissions
 */
function handleFormSubmit(e) {
    const form = e.target;
    
    // Add loading state to submit button
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Loading...';
        
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.textContent = submitButton.getAttribute('data-original-text') || 'Submit';
        }, 1000);
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'success', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 16px 24px;
        background: var(--${type === 'error' ? 'danger' : 'secondary'});
        color: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, duration);
}

/**
 * Format currency
 */
function formatCurrency(amount, currency = 'PKR') {
    const symbols = {
        'PKR': 'Rs.',
        'USD': '$',
        'EUR': '€',
        'GBP': '£',
        'INR': '₹'
    };
    
    const symbol = symbols[currency] || currency;
    return `${symbol} ${parseFloat(amount).toLocaleString()}`;
}

/**
 * Format date
 */
function formatDate(dateString, format = 'short') {
    const date = new Date(dateString);
    
    if (format === 'short') {
        return date.toLocaleDateString();
    } else if (format === 'long') {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } else if (format === 'time') {
        return date.toLocaleTimeString();
    }
    
    return date.toLocaleString();
}

/**
 * API helper functions
 */
const API = {
    /**
     * Make API request
     */
    async request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            showToast(error.message, 'error');
            throw error;
        }
    },
    
    /**
     * GET request
     */
    get(url) {
        return this.request(url, { method: 'GET' });
    },
    
    /**
     * POST request
     */
    post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },
    
    /**
     * PUT request
     */
    put(url, data) {
        return this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },
    
    /**
     * DELETE request
     */
    delete(url) {
        return this.request(url, { method: 'DELETE' });
    }
};

/**
 * Utility functions
 */
const Utils = {
    /**
     * Debounce function
     */
    debounce(func, delay) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    },
    
    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    /**
     * Validate email
     */
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    /**
     * Validate phone
     */
    isValidPhone(phone) {
        return /^[\d\s\-\+\(\)]{10,}$/.test(phone);
    },
    
    /**
     * Get query parameter
     */
    getQueryParam(param) {
        const params = new URLSearchParams(window.location.search);
        return params.get(param);
    }
};

// CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .sort-asc::after,
    .sort-desc::after {
        content: ' ↕';
        color: var(--primary);
    }
    
    .sort-asc::after {
        content: ' ↑';
    }
    
    .sort-desc::after {
        content: ' ↓';
    }
`;
document.head.appendChild(style);
