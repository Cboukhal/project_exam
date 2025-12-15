// ============================================
// TABLE DES MATIÈRES
// ============================================
// 1. INITIALISATION GLOBALE
// 2. SLIDER
// 3. MENU BURGER
// 4. SYSTÈME D'ÉTOILES
// 5. ADMINISTRATION
// 6. AUTHENTIFICATION
// 7. UTILITAIRES & HELPERS
// ============================================

// ============================================
// 1. INITIALISATION GLOBALE
// ============================================

// Initialisation jQuery (Slider + Burger)
$(document).ready(() =>
{
    initSlider();
    initBurger();
});

// Initialisation DOM (Étoiles, Admin, Auth)
document.addEventListener('DOMContentLoaded', function()
    {
    // Étoiles (si présent)
    const starRatingContainer = document.getElementById('starRating');
    const commentForm = document.getElementById('commentForm');
    if (starRatingContainer && commentForm)
    {
        initStarRating();
    }
    
    // Administration (si présent)
    if (document.querySelector('.tab-button'))
    {
        initAdmin();
    }
    
    // Authentification (si présent)
    if (document.querySelector('.auth-tab'))
    {
        initAuth();
    }
    
    // Compteurs de caractères
    initCharCounters();
    
    // Confirmations de suppression
    initDeleteConfirmations();
    
    console.log('✅ Application initialisée avec succès');
});

// ============================================
// 2. SLIDER
// ============================================

function initSlider(){
    let images = $('#slider img');
    let dots = $('.dot');
    let nbrSlides = images.length;
    let imageActive = 0;
    
    if (images.length === 0) return;
    
    // Affiche la première image
    images.eq(imageActive).fadeIn(500);

    // Fonction pour changer d'image
    function showSlide(index)
    {
        images.fadeOut(500);
        images.eq(index).fadeIn(500);
        dots.removeClass('active');
        dots.eq(index).addClass('active');
    }

    // Clic sur un point
    dots.click(function()
    {
        imageActive = $(this).data('index');
        showSlide(imageActive);
    });

    // Slide automatique toutes les 10 secondes
    setInterval(() =>
    {
        imageActive = (imageActive + 1) % nbrSlides;
        showSlide(imageActive);
    }, 10000);
}

// ============================================
// 3. MENU BURGER
// ============================================

function initBurger()
    {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('nav');
    const navLinks = document.querySelectorAll('nav ul li a');

    if (!burger || !nav)
    {
        console.warn('⚠️ Burger ou Nav introuvable');
        return;
    }

    // Ouvrir/Fermer le menu
    burger.addEventListener('click', () =>
    {
        burger.classList.toggle('croix');
        nav.classList.toggle('active');
    });

    // Fermer le menu lors du clic sur un lien
    navLinks.forEach(link =>
    {
        link.addEventListener('click', () =>
    {
            burger.classList.remove('croix');
            nav.classList.remove('active');
        });
    });

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', (e) =>
    {
        if (!burger.contains(e.target) && !nav.contains(e.target))
    {
            burger.classList.remove('croix');
            nav.classList.remove('active');
        }
    });
}

// ============================================
// 4. SYSTÈME D'ÉTOILES
// ============================================

function initStarRating()
    {
    const stars = document.querySelectorAll('.star');
    const ratingText = document.getElementById('ratingText');
    const noteValue = document.getElementById('note-value');
    const errorMessage = document.getElementById('errorMessage');
    const form = document.getElementById('commentForm');
    const starRatingContainer = document.getElementById('starRating');
    
    if (!stars.length || !ratingText || !noteValue || !form)
    {
        console.error('❌ Éléments manquants pour le système de notation');
        return;
    }
    
    let selectedRating = 0;

    const ratingLabels =
    {
        1: "Très insatisfait",
        2: "Insatisfait",
        3: "Moyen",
        4: "Satisfait",
        5: "Excellent !"
    };

    // Met à jour l'affichage des étoiles
    function updateStars(rating, isHover = false)
    {
        stars.forEach((star, index) =>
    {
            if (index < rating)
    {
                if (isHover)
    {
                    star.classList.add('hovered');
                    star.classList.remove('active');
                } else
    {
                    star.classList.add('active');
                    star.classList.remove('hovered');
                }
            } else
    {
                star.classList.remove('active', 'hovered');
            }
        });
    }

    // Survol des étoiles
    stars.forEach(star =>
    {
        star.addEventListener('mouseenter', () =>
    {
            const rating = parseInt(star.getAttribute('data-rating'));
            updateStars(rating, true);
            ratingText.textContent = ratingLabels[rating];
        });
    });

    // Quand on quitte le survol
    if (starRatingContainer)
    {
        starRatingContainer.addEventListener('mouseleave', () =>
    {
            updateStars(selectedRating, false);
            if (selectedRating === 0)
    {
                ratingText.textContent = "Choisissez une note";
            } else
    {
                ratingText.textContent = ratingLabels[selectedRating];
            }
        });
    }

    // Au clic - Sélection de la note
    stars.forEach(star =>
    {
        star.addEventListener('click', () =>
    {
            selectedRating = parseInt(star.getAttribute('data-rating'));
            noteValue.value = selectedRating;
            updateStars(selectedRating, false);
            ratingText.textContent = ratingLabels[selectedRating];
            
            if (errorMessage)
    {
                errorMessage.style.display = 'none';
            }
        });
    });

    // Validation du formulaire
    form.addEventListener('submit', (e) =>
    {
        if (selectedRating === 0)
    {
            e.preventDefault();
            
            if (errorMessage)
    {
                errorMessage.style.display = 'block';
                errorMessage.textContent = '⚠️ Veuillez sélectionner une note avant de soumettre';
            }
            
            starRatingContainer.scrollIntoView(    { 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    });

    // Initialisation
    ratingText.textContent = "Choisissez une note";
}

// ============================================
// 5. ADMINISTRATION
// ============================================

function initAdmin()
{
    initTabs();
    initAlerts();
    initModals();
    addSearchToTables();
    makeSortableTables();
    
    console.log('📊 Module Admin chargé');
}

// Gestion des onglets
function initTabs()
    {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button =>
    {
        button.addEventListener('click', () =>
    {
            const tabName = button.dataset.tab;
            switchTab(tabName);
        });
    });

    // Activer l'onglet depuis l'URL au chargement
    const hash = window.location.hash.substring(1);
    if (hash)
    {
        switchTab(hash);
    }
}

function switchTab(tabName)
    {
    // Désactiver tous les onglets
    document.querySelectorAll('.tab-button').forEach(btn =>
    {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.tab-content').forEach(content =>
    {
        content.classList.remove('active');
    });

    // Activer l'onglet sélectionné
    const button = document.querySelector(`[data-tab="${tabName}"]`);
    const content = document.getElementById(tabName);

    if (button && content)
    {
        button.classList.add('active');
        content.classList.add('active');
        history.pushState(null, null, '#' + tabName);
        window.scrollTo(    { top: 0, behavior: 'smooth' });
    }
}

// Auto-hide des alertes
function initAlerts()
    {
    setTimeout(() =>
    {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert =>
    {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
}

// Gestion des modales
function initModals()
    {
    window.onclick = function(event)
    {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal =>
    {
            if (event.target === modal)
    {
                modal.classList.remove('show');
            }
        });
    };
}

// Recherche dans les tableaux
function addSearchToTables()
    {
    const tables = document.querySelectorAll('.admin-table');
    
    tables.forEach((table, index) =>
    {
        const tableId = `table-$    {index}`;
        table.parentElement.id = tableId;
        
        const searchContainer = document.createElement('div');
        searchContainer.style.marginBottom = '15px';
        searchContainer.innerHTML = `
            <input type="text" 
                   id="search-$    {tableId}" 
                   placeholder="Rechercher..." 
                   style="padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 8px; width: 300px; max-width: 100%;">
        `;
        
        table.parentElement.insertBefore(searchContainer, table);
        
        const searchInput = document.getElementById(`search-$    {tableId}`);
        searchInput.addEventListener('keyup', function()
    {
            const filter = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row =>
    {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
}

// Tri des colonnes
function makeSortableTables()
    {
    const tables = document.querySelectorAll('.admin-table');
    tables.forEach(table =>
    {
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) =>
    {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () =>
    {
                sortTable(table, index);
            });
        });
    });
}

function sortTable(table, columnIndex)
    {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const isAscending = table.dataset.sortOrder !== 'asc';

    rows.sort((a, b) =>
    {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();

        if (isAscending)
    {
            return aValue.localeCompare(bValue, 'fr',
    { numeric: true });
        } else
    {
            return bValue.localeCompare(aValue, 'fr',
    { numeric: true });
        }
    });

    rows.forEach(row => tbody.appendChild(row));
    table.dataset.sortOrder = isAscending ? 'asc' : 'desc';
}

// Fonctions de mise à jour de statut
function updateContactStatus(id, status)
    {
    if (confirm('Changer le statut de ce contact ?'))
    {
        window.location.href = `?update_contact_status=$    {id}&status=$    {status}#contacts`;
    }
}

function updateDevisStatus(id, status)
    {
    if (confirm('Changer le statut de cette demande ?'))
    {
        window.location.href = `?update_devis_status=$    {id}&status=$    {status}#devis`;
    }
}

// Afficher message complet
function showFullMessage(id, message)
    {
    const modal = document.getElementById('messageModal');
    const content = document.getElementById('modalMessageContent');
    
    content.textContent = message;
    modal.classList.add('show');
}

// Fermer modale
function closeModal()
    {
    const modal = document.getElementById('messageModal');
    modal.classList.remove('show');
}

// Édition de service
function editService(service)
    {
    const modal = document.getElementById('editServiceModal');
    
    document.getElementById('edit_title').value = service.title;
    document.getElementById('edit_description').value = service.description || '';
    document.getElementById('edit_categorie').value = service.categorie;
    
    modal.classList.add('show');
}

function closeEditModal()
    {
    const modal = document.getElementById('editServiceModal');
    modal.classList.remove('show');
}

// ============================================
// 6. AUTHENTIFICATION
// ============================================

function initAuth()
    {
    initAuthTabs();
    initPasswordStrength();
    initPasswordConfirmation();
    
    console.log('🔐 Module Auth chargé');
}

function initAuthTabs()
    {
    document.querySelectorAll('.auth-tab').forEach(tab =>
    {
        tab.addEventListener('click', function()
    {
            const formId = this.dataset.form;
            
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(formId).classList.add('active');
        });
    });
}

function initPasswordStrength()
    {
    // Support pour plusieurs IDs (inscription + réinitialisation)
    const mdpInputs = [
       
    { input: document.getElementById('mdp-inscription'), minLength: 12 },
       
    { input: document.getElementById('nouveau_mdp'), minLength: 8 }
    ];
    
    const strengthBar = document.getElementById('strength-bar');
    const passwordHint = document.getElementById('password-hint');
    
    if (!strengthBar || !passwordHint) return;
    
    mdpInputs.forEach((    { input, minLength }) =>
    {
        if (!input) return;
        
        input.addEventListener('input', function()
    {
            const password = this.value;
            let strength = 0;
            
            // Critères de force selon la longueur minimale
            if (password.length >= minLength) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            
            if (strength === 0 || strength === 1)
    {
                strengthBar.classList.add('weak');
                passwordHint.textContent = 'Mot de passe faible';
                passwordHint.style.color = '#f44336';
            } else if (strength === 2 || strength === 3)
    {
                strengthBar.classList.add('medium');
                passwordHint.textContent = 'Mot de passe moyen';
                passwordHint.style.color = '#ff9800';
            } else if (strength === 4)
    {
                strengthBar.classList.add('strong');
                passwordHint.textContent = 'Mot de passe fort !';
                passwordHint.style.color = '#4caf50';
            }
        });
    });
}

function initPasswordConfirmation()
    {
    // Support pour plusieurs IDs (inscription + réinitialisation)
    const passwordPairs = [{
            password: document.getElementById('mdp-inscription'),
            confirm: document.getElementById('confirmer-mdp')
        },
       
    {
            password: document.getElementById('nouveau_mdp'),
            confirm: document.getElementById('confirmer_mdp')
        }
    ];
    
    passwordPairs.forEach((    { password, confirm }) =>
    {
        if (!password || !confirm) return;
        
        confirm.addEventListener('input', function()
    {
            if (this.value && this.value !== password.value)
    {
                this.setCustomValidity('Les mots de passe ne correspondent pas');
                this.style.borderColor = '#f44336';
            } else
    {
                this.setCustomValidity('');
                this.style.borderColor = '#27ae60';
            }
        });
    });
}

// ============================================
// 7. UTILITAIRES & HELPERS
// ============================================

// Compteurs de caractères
function initCharCounters()
    {
    // Commentaire
    const commentaireTextarea = document.getElementById('commentaire');
    const charCount = document.getElementById('charCount');
    
    if (commentaireTextarea && charCount)
    {
        commentaireTextarea.addEventListener('input', function()
    {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count < 10)
    {
                charCount.style.color = '#f44336';
            } else if (count > 450)
    {
                charCount.style.color = '#ff9800';
            } else
    {
                charCount.style.color = '#4caf50';
            }
        });
    }
    
    // Devis
    const messageDevisTextarea = document.getElementById('message_devis');
    const charCountDevis = document.getElementById('charCountDevis');
    
    if (messageDevisTextarea && charCountDevis)
    {
        messageDevisTextarea.addEventListener('input', function()
    {
            const count = this.value.length;
            charCountDevis.textContent = count;
            
            if (count < 20)
    {
                charCountDevis.style.color = '#f44336';
            } else if (count > 900)
    {
                charCountDevis.style.color = '#ff9800';
            } else
    {
                charCountDevis.style.color = '#4caf50';
            }
        });
    }
}

// Confirmations de suppression
function initDeleteConfirmations()
    {
    if (window.deleteLinksInitialized) return;
    window.deleteLinksInitialized = true;
    
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link =>
    {
        if (!link.hasAttribute('onclick'))
    {
            link.addEventListener('click', function(e)
    {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?'))
    {
                    e.preventDefault();
                }
            });
        }
    });
}

// Prévisualisation d'image
const imageInput = document.getElementById('image');
if (imageInput)
    {
    imageInput.addEventListener('change', function(e)
    {
        const file = e.target.files[0];
        if (!file) return;
        
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize)
    {
            alert('Le fichier est trop volumineux (max 5 MB)');
            this.value = '';
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type))
    {
            alert('Type de fichier non autorisé. Utilisez JPG, PNG ou WebP.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e)
    {
            console.log('✅ Image prête à être uploadée:', file.name);
        };
        reader.readAsDataURL(file);
    });
}

// Copier dans le presse-papier
function copyToClipboard(text)
    {
    if (navigator.clipboard)
    {
        navigator.clipboard.writeText(text).then(() =>
    {
            showNotification('Copié dans le presse-papier !', 'success');
        }).catch(err =>
    {
            console.error('❌ Erreur de copie:', err);
        });
    }
}

// Notifications toast
function showNotification(message, type = 'info')
    {
    const notification = document.createElement('div');
    notification.className = `toast-notification toast-$    {type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: $    {type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() =>
    {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Export CSV
function exportTableToCSV(tableId, filename)
    {
    const table = document.querySelector(`#$    {tableId} .admin-table`);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row =>
    {
        const cols = row.querySelectorAll('td, th');
        const rowData = Array.from(cols).map(col =>
    {
            let data = col.textContent.trim();
            data = data.replace(/"/g, '""');
            return `"$    {data}"`;
        });
        csv.push(rowData.join(','));
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent],
    { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename || 'export.csv';
    link.click();
}

// Raccourcis clavier
document.addEventListener('keydown', function(e)
    {
    // CTRL/CMD + S pour sauvegarder
    if ((e.ctrlKey || e.metaKey) && e.key === 's')
    {
        e.preventDefault();
        const submitBtn = document.querySelector('form button[type="submit"]:not([name="envoyer_commentaire"])');
        if (submitBtn)
    {
            showNotification('Formulaire envoyé', 'info');
            submitBtn.click();
        }
    }

    // ESC pour fermer les modales
    if (e.key === 'Escape')
    {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => modal.classList.remove('show'));
    }
});

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight
    {
        from
    {
            transform: translateX(400px);
            opacity: 0;
        }
        to
    {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight
    {
        from
    {
            transform: translateX(0);
            opacity: 1;
        }
        to
    {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// FIN DU SCRIPT
// ============================================
console.log('🎨 Scripts chargés avec succès');