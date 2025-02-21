let lastNotificationCount = 0;

function toggleNotificationModal() {
    const modal = document.getElementById('notificationModal');
    if (!modal) {
        console.error('Erreur : Modal de notification introuvable.');
        return;
    }

    if (!modal.classList.contains('hidden')) {
        console.log('Marquer les notifications comme lues.');
        markNotificationsAsRead();
    }
    modal.classList.toggle('hidden');
    modal.classList.toggle('fade-in');
    modal.classList.toggle('fade-out');
}

function markNotificationsAsRead() {
    fetch('index.php?action=markNotificationsAsRead', {
        method: 'POST',
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur réseau : ${response.status} ${response.statusText}`);
            }
            console.log('Les notifications ont été marquées comme lues.');
            const notificationCountElement = document.getElementById('notificationCount');
            if (notificationCountElement) {
                notificationCountElement.classList.add('hidden');
            }
            lastNotificationCount = 0;
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour des notifications:', error.message);
        });
}

function fetchNotifications() {
    console.log('Récupération des notifications...');
    fetch('index.php?action=getNotifications')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur réseau : ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues des notifications :', data);
            if (!data.notifications) {
                throw new Error('Données malformées : Champ "notifications" manquant.');
            }
            if (!Array.isArray(data.notifications)) {
                throw new Error('Données malformées : "notifications" n\'est pas un tableau.');
            }
            if (data.notifications.length !== lastNotificationCount) {
                console.log(`Mise à jour des notifications : ${data.notifications.length} nouvelles.`);
                updateNotificationDisplay(data.notifications);
                lastNotificationCount = data.notifications.length;
            } else {
                console.log('Aucune nouvelle notification à afficher.');
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des notifications:', error.message);
            displayError('Impossible de charger les notifications. Réessayez plus tard.');
        });
}

function updateNotificationDisplay(notifications) {
    console.log('Mise à jour de l\'affichage des notifications.');
    const unreadCount = notifications.filter(n => !n.is_read).length;
    const countElement = document.getElementById('notificationCount');
    const listElement = document.getElementById('notificationList');

    if (!countElement || !listElement) {
        console.error('Erreur : Éléments pour les notifications introuvables.');
        return;
    }

    // Mise à jour du badge
    if (unreadCount > 0) {
        console.log(`Nombre de notifications non lues : ${unreadCount}`);
        countElement.textContent = unreadCount;
        countElement.classList.remove('hidden');
    } else {
        countElement.classList.add('hidden');
    }

    // Mise à jour de la liste
    listElement.innerHTML = notifications.length
        ? ''
        : '<li class="text-gray-500">Aucune notification</li>';

    notifications.forEach(notification => {
        const listItem = document.createElement('li');
        listItem.classList.add(
            'p-4',
            'border-b',
            'last:border-0',
            'hover:bg-gray-50',
            'transition-colors',
            'duration-200'
        );
        if (!notification.is_read) {
            listItem.classList.add('bg-blue-50', 'rounded-lg');
        }

        listItem.innerHTML = `
            <div class="flex justify-between items-start">
                <h4 class="font-semibold text-gray-800">${notification.title}</h4>
                <span class="text-xs text-gray-500">${formatDate(notification.created_at)}</span>
            </div>
           <p class="mt-1 text-gray-600">
                ${notification.message}
                <br>
                <strong>Tâche : ${notification.task_name}</strong>  <!-- Affichage du nom de la tâche -->
            </p>
            <div class="mt-2 text-sm text-blue-600">Par ${notification.sender_name || 'Inconnu'}</div>
        `;
        listElement.appendChild(listItem);
    });
}

function displayError(message) {
    console.log('Affichage d\'une erreur à l\'utilisateur.');
    const listElement = document.getElementById('notificationList');
    if (listElement) {
        listElement.innerHTML = `<li class="text-red-500">${message}</li>`;
    } else {
        console.error('Impossible d\'afficher l\'erreur : Liste des notifications introuvable.');
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    if (isNaN(date)) {
        console.error(`Date invalide : ${dateString}`);
        return 'Date invalide';
    }
    return date.toLocaleString();
}

// Écouter l'événement DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('Initialisation du fetch des notifications.');
    fetchNotifications();
    setInterval(fetchNotifications, 30000);
});
