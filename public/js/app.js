// Gestion du thème sombre/clair
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // Vérifier le thème sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        html.classList.toggle('dark', savedTheme === 'dark');
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        html.classList.add('dark');
    }

    // Gérer le basculement de thème
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }

    // Gestion des messages flash
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function (message) {
        setTimeout(function () {
            message.style.opacity = '0';
            setTimeout(function () {
                message.remove();
            }, 300);
        }, 5000);
    });

    // Gestion des confirmations de suppression
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const message = form.getAttribute('data-confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Gestion des dropdowns
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function (dropdown) {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (trigger && menu) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                menu.classList.toggle('hidden');
            });

            // Fermer le dropdown en cliquant ailleurs
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
});

// Fonction utilitaire pour afficher des notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Styles de base pour la notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem;
        border-radius: 0.5rem;
        color: white;
        font-weight: 500;
        z-index: 1000;
        max-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;

    // Couleurs selon le type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#059669';
            break;
        case 'error':
            notification.style.backgroundColor = '#dc2626';
            break;
        case 'warning':
            notification.style.backgroundColor = '#d97706';
            break;
        default:
            notification.style.backgroundColor = '#2563eb';
    }

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Fonction pour la recherche en temps réel
function setupSearch(inputSelector, tableSelector) {
    const searchInput = document.querySelector(inputSelector);
    const table = document.querySelector(tableSelector);

    if (!searchInput || !table) return;

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

// Fonction pour la pagination côté client
function setupPagination(items, itemsPerPage = 10) {
    let currentPage = 1;
    const totalPages = Math.ceil(items.length / itemsPerPage);

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageItems = items.slice(start, end);

        // Ici vous pouvez mettre à jour votre tableau avec pageItems
        console.log(`Affichage de la page ${page}:`, pageItems);
    }

    function goToPage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            showPage(currentPage);
        }
    }

    return {
        goToPage,
        currentPage: () => currentPage,
        totalPages: () => totalPages,
        showPage
    };
}

// Export des fonctions pour utilisation globale
window.AppUtils = {
    showNotification,
    setupSearch,
    setupPagination
};
