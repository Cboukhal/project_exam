$(document).ready(() => {
    // ============= SLIDER =============
    let images = $('#slider img');
    let dots = $('.dot');
    let nbrSlides = images.length;
    let imageActive = 0;
    
    // Affiche la première image
    images.eq(imageActive).fadeIn(500);

    // Fonction pour changer d’image
  function showSlide(index) {
    images.fadeOut(500);
    images.eq(index).fadeIn(500);
    dots.removeClass('active');
    dots.eq(index).addClass('active');
  }

  // Quand on clique sur un point
  dots.click(function() {
    imageActive = $(this).data('index');
    showSlide(imageActive);
  });

  // Slide automatique toutes les 10 secondes
  setInterval(() => {
    imageActive = (imageActive + 1) % nbrSlides;
    showSlide(imageActive);
  }, 10000);

    // ============= BURGER - À L'INTÉRIEUR DE READY =============
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('nav');
    const navLinks = document.querySelectorAll('nav ul li a');

    // Vérification que les éléments existent
    if (burger && nav) {
        // Ouvrir/Fermer le menu
        burger.addEventListener('click', () => {
            burger.classList.toggle('croix');
            nav.classList.toggle('active');
        });

        // Fermer le menu lors du clic sur un lien
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('croix');
                nav.classList.remove('active');
            });
        });

        // Fermer le menu si on clique en dehors
        document.addEventListener('click', (e) => {
            if (!burger.contains(e.target) && !nav.contains(e.target)) {
                burger.classList.remove('croix');
                nav.classList.remove('active');
            }
        });
    } else {
        console.error('Burger ou Nav introuvable !');
    }
});

// ----------------------------------------------------------------------------------------------------
// ============================================
// SYSTÈME DE NOTATION PAR ÉTOILES - CORRIGÉ
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // ✅ Vérifier que les éléments existent avant d'initialiser
    const starRatingContainer = document.getElementById('starRating');
    const commentForm = document.getElementById('commentForm');
    
    if (starRatingContainer && commentForm) {
        initStarRating();
    }
});

function initStarRating() {
    const stars = document.querySelectorAll('.star');
    const ratingText = document.getElementById('ratingText');
    const noteValue = document.getElementById('note-value');
    const errorMessage = document.getElementById('errorMessage');
    const form = document.getElementById('commentForm');
    
    // ✅ Vérification que tous les éléments existent
    if (!stars.length || !ratingText || !noteValue || !form) {
        console.error('Éléments manquants pour le système de notation');
        return;
    }
    
    let selectedRating = 0;

    const ratingLabels = {
        1: "Très insatisfait",
        2: "Insatisfait",
        3: "Moyen",
        4: "Satisfait",
        5: "Excellent !"
    };

    // Met à jour l'affichage des étoiles
    function updateStars(rating, isHover = false) {
        stars.forEach((star, index) => {
            if (index < rating) {
                if (isHover) {
                    star.classList.add('hovered');
                    star.classList.remove('active');
                } else {
                    star.classList.add('active');
                    star.classList.remove('hovered');
                }
            } else {
                star.classList.remove('active', 'hovered');
            }
        });
    }

    // Survol des étoiles
    stars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            updateStars(rating, true);
            ratingText.textContent = ratingLabels[rating];
        });
    });

    // Quand on quitte le survol - CORRECTION ICI
    const starRatingContainer = document.getElementById('starRating');
    if (starRatingContainer) {
        starRatingContainer.addEventListener('mouseleave', () => {
            updateStars(selectedRating, false);
            if (selectedRating === 0) {
                ratingText.textContent = "Choisissez une note";
            } else {
                ratingText.textContent = ratingLabels[selectedRating];
            }
        });
    }

    // Au clic - Sélection de la note
    stars.forEach(star => {
        star.addEventListener('click', () => {
            selectedRating = parseInt(star.getAttribute('data-rating'));
            noteValue.value = selectedRating;
            updateStars(selectedRating, false);
            ratingText.textContent = ratingLabels[selectedRating];
            
            // ✅ Masquer le message d'erreur si présent
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        });
    });

    // Validation du formulaire
    form.addEventListener('submit', (e) => {
        if (selectedRating === 0) {
            e.preventDefault();
            
            // ✅ Afficher le message d'erreur
            if (errorMessage) {
                errorMessage.style.display = 'block';
                errorMessage.textContent = '⚠️ Veuillez sélectionner une note avant de soumettre';
            }
            
            // ✅ Scroll vers les étoiles
            starRatingContainer.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
        // Si note sélectionnée, le formulaire se soumet normalement
    });

    // ✅ Initialisation : afficher le message par défaut
    ratingText.textContent = "Choisissez une note";
}
// ----------------------------------------------------------------------------------------------------

/**
 * PANNEAU D'ADMINISTRATION - SCRIPTS
 * Gestion des interactions et fonctionnalités dynamiques
 */

document.addEventListener('DOMContentLoaded', function() {
    // ✅ Vérifier si on est sur une page admin/user avant d'initialiser
    if (document.querySelector('.tab-button')) {
        initTabs();
        initAlerts();
        initModals();
    }
});

// ============================================
// GESTION DES ONGLETS
// ============================================
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.dataset.tab;
            switchTab(tabName);
        });
    });

    // Activer l'onglet depuis l'URL au chargement
    const hash = window.location.hash.substring(1);
    if (hash) {
        switchTab(hash);
    }
}

function switchTab(tabName) {
    // Désactiver tous les onglets
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Activer l'onglet sélectionné
    const button = document.querySelector(`[data-tab="${tabName}"]`);
    const content = document.getElementById(tabName);

    if (button && content) {
        button.classList.add('active');
        content.classList.add('active');

        // Mettre à jour l'URL
        history.pushState(null, null, '#' + tabName);

        // Scroll vers le haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// ============================================
// GESTION DES ALERTES
// ============================================
function initAlerts() {
    // Auto-hide des messages flash après 5 secondes
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
}

// ============================================
// GESTION DES MODALES
// ============================================
function initModals() {
    // Fermer la modale en cliquant en dehors
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });
    };
}

// Afficher le message complet dans une modale
function showFullMessage(id, message) {
    const modal = document.getElementById('messageModal');
    const content = document.getElementById('modalMessageContent');
    
    content.textContent = message;
    modal.classList.add('show');
}

// Fermer la modale de message
function closeModal() {
    const modal = document.getElementById('messageModal');
    modal.classList.remove('show');
}

// ============================================
// ÉDITION DE SERVICE
// ============================================
function editService(service) {
    const modal = document.getElementById('editServiceModal');
    
    // Remplir le formulaire
    // document.getElementById('edit_service_id').value = service.id;
    document.getElementById('edit_title').value = service.title;
    document.getElementById('edit_description').value = service.description || '';
    document.getElementById('edit_categorie').value = service.categorie;
    
    // Afficher la modale
    modal.classList.add('show');
}

// Fermer la modale d'édition
function closeEditModal() {
    const modal = document.getElementById('editServiceModal');
    modal.classList.remove('show');
}

// ============================================
// MISE À JOUR DES STATUTS
// ============================================
function updateContactStatus(id, status) {
    if (confirm('Changer le statut de ce contact ?')) {
        window.location.href = `?update_contact_status=${id}&status=${status}#contacts`;
    }
}

function updateDevisStatus(id, status) {
    if (confirm('Changer le statut de cette demande ?')) {
        window.location.href = `?update_devis_status=${id}&status=${status}#devis`;
    }
}

// ============================================
// CONFIRMATION DE SUPPRESSION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Éviter de ré-exécuter si déjà fait
    if (!window.deleteLinksInitialized) {
        window.deleteLinksInitialized = true;
        
        const deleteLinks = document.querySelectorAll('a[href*="delete"]');
        deleteLinks.forEach(link => {
            if (!link.hasAttribute('onclick')) {
                link.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    }
});

// ============================================
// PRÉVISUALISATION D'IMAGE
// ============================================
const imageInput = document.getElementById('image');
if (imageInput) {
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérifier la taille
            const maxSize = 5 * 1024 * 1024; // 5 MB
            if (file.size > maxSize) {
                alert('Le fichier est trop volumineux (max 5 MB)');
                this.value = '';
                return;
            }

            // Vérifier le type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Type de fichier non autorisé. Utilisez JPG, PNG ou WebP.');
                this.value = '';
                return;
            }

            // Afficher la prévisualisation (optionnel)
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('Image prête à être uploadée:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });
}

// ============================================
// RECHERCHE DANS LES TABLEAUX
// ============================================
function addSearchToTable(tableId) {
    const table = document.querySelector(`#${tableId} .admin-table`);
    if (!table) return;

    // Créer le champ de recherche
    const searchContainer = document.createElement('div');
    searchContainer.style.marginBottom = '15px';
    searchContainer.innerHTML = `
        <input type="text" 
               id="search-${tableId}" 
               placeholder="🔍 Rechercher..." 
               style="padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; width: 300px; max-width: 100%;">
    `;

    table.parentElement.insertBefore(searchContainer, table);

    // Fonction de recherche
    const searchInput = document.getElementById(`search-${tableId}`);
    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

// ============================================
// TRI DES COLONNES
// ============================================
function makeSortable(table) {
    const headers = table.querySelectorAll('th');
    headers.forEach((header, index) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            sortTable(table, index);
        });
    });
}

function sortTable(table, columnIndex) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const isAscending = table.dataset.sortOrder !== 'asc';

    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();

        if (isAscending) {
            return aValue.localeCompare(bValue, 'fr', { numeric: true });
        } else {
            return bValue.localeCompare(aValue, 'fr', { numeric: true });
        }
    });

    // Réorganiser les lignes
    rows.forEach(row => tbody.appendChild(row));

    // Mettre à jour l'ordre
    table.dataset.sortOrder = isAscending ? 'asc' : 'desc';
}

// ============================================
// COPIER DANS LE PRESSE-PAPIER
// ============================================
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Copié dans le presse-papier !', 'success');
        }).catch(err => {
            console.error('Erreur de copie:', err);
        });
    }
}

// ============================================
// NOTIFICATIONS TOAST
// ============================================
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `toast-notification toast-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// STATISTIQUES EN TEMPS RÉEL (optionnel)
// ============================================
function refreshStats() {
    // Récupérer les nouvelles statistiques via AJAX
    console.log('Rafraîchissement des statistiques...');
}

// Rafraîchir toutes les 30 secondes (optionnel)
// setInterval(refreshStats, 30000);

// ============================================
// EXPORT DE DONNÉES (optionnel)
// ============================================
function exportTableToCSV(tableId, filename) {
    const table = document.querySelector(`#${tableId} .admin-table`);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = Array.from(cols).map(col => {
            let data = col.textContent.trim();
            data = data.replace(/"/g, '""'); // Échapper les guillemets
            return `"${data}"`;
        });
        csv.push(rowData.join(','));
    });

    // Créer le fichier
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename || 'export.csv';
    link.click();
}

// ============================================
// RACCOURCIS CLAVIER
// ============================================
document.addEventListener('keydown', function(e) {
    // CTRL/CMD + S pour sauvegarder (prévenir le comportement par défaut)
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const submitBtn = document.querySelector('form button[type="submit"]:not([name="envoyer_commentaire"])');
        if (submitBtn) {
            showNotification('Formulaire envoyé', 'info');
            submitBtn.click();
        }
    }

    // ESC pour fermer les modales
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => modal.classList.remove('show'));
    }
});

// Compteur de caractères pour les avis
const commentaireTextarea = document.getElementById('commentaire');
const charCount = document.getElementById('charCount');

if (commentaireTextarea && charCount) {
    commentaireTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        // Changer la couleur selon la longueur
        if (count < 10) {
            charCount.style.color = '#f44336'; // Rouge
        } else if (count > 450) {
            charCount.style.color = '#ff9800'; // Orange
        } else {
            charCount.style.color = '#4caf50'; // Vert
        }
    });
}

// Compteur de caractères pour le devis
document.addEventListener('DOMContentLoaded', function() {
    const messageDevisTextarea = document.getElementById('message_devis');
    const charCountDevis = document.getElementById('charCountDevis');
    
    if (messageDevisTextarea && charCountDevis) {
        messageDevisTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCountDevis.textContent = count;
            
            // Changer la couleur selon la longueur
            if (count < 20) {
                charCountDevis.style.color = '#f44336'; // Rouge
            } else if (count > 900) {
                charCountDevis.style.color = '#ff9800'; // Orange
            } else {
                charCountDevis.style.color = '#4caf50'; // Vert
            }
        });
    }
});

// ============================================
// CONSOLE LOG POUR DEBUG
// ============================================
console.log('🎨 Panneau d\'administration chargé');
console.log('📊 Fonctionnalités disponibles:');
console.log('- Gestion des onglets');
console.log('- Modales interactives');
console.log('- Mise à jour de statuts');
console.log('- Auto-hide des alertes');
console.log('- Confirmations de suppression');