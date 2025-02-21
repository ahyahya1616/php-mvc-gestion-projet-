let currentTaskId = null;

function toggleComments(taskId) {
    if (!taskId) {
        console.error('ID de tâche non défini');
        return;
    }
    currentTaskId = taskId;
    document.getElementById('commentsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Empêcher le défilement
    loadComments(taskId);
}

function closeCommentsModal() {
    document.getElementById('commentsModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Réactiver le défilement
    currentTaskId = null;
}

function loadComments(taskId) {
    const commentsContainer = document.getElementById('commentsContainer');
    commentsContainer.innerHTML = '<div class="animate-pulse text-center py-4">Chargement des commentaires...</div>';

    fetch(`index.php?action=getComments&taskId=${taskId}`)
        .then(response => response.json())
        .then(data => {
            commentsContainer.innerHTML = '';

            if (data.comments.length === 0) {
                commentsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Aucun commentaire pour cette tâche.</p>';
            } else {
                data.comments.forEach(comment => {
                    const commentEl = document.createElement('div');
                    commentEl.classList.add('p-4', 'bg-gray-50', 'rounded-lg', 'mb-3', 'break-words', 'shadow-sm', 'hover:shadow-md', 'transition-shadow');
                    commentEl.innerHTML = `
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    ${comment.user_name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <span class="font-medium text-gray-900">${comment.user_name}</span>
                                    <span class="text-xs text-gray-500">${comment.time_ago}</span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap mt-1">${comment.content}</p>
                            </div>
                        </div>
                    `;
                    commentsContainer.appendChild(commentEl);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des commentaires:', error);
            commentsContainer.innerHTML = `
                <div class="text-center text-red-500 py-4">
                    Une erreur est survenue lors du chargement des commentaires.
                    <button onclick="loadComments(${taskId})" class="text-blue-500 hover:underline ml-2">Réessayer</button>
                </div>
            `;
        });
}

// Gestionnaire de soumission des commentaires
document.getElementById('commentForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const commentInput = document.getElementById('newComment');
    const content = commentInput.value.trim();
    if (content === '') return;

    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="animate-spin">↻</span> Envoi...';

    fetch(`index.php?action=addComment`, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `taskId=${currentTaskId}&content=${encodeURIComponent(content)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            commentInput.value = '';
            loadComments(currentTaskId);
        } else {
            throw new Error(data.message || 'Erreur inconnue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout du commentaire: ' + error.message);
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = 'Envoyer';
    });
});

// Gestionnaire des notifications
document.addEventListener('DOMContentLoaded', () => {
    const notificationButton = document.getElementById('notificationButton');
    const notificationList = document.getElementById('notificationList');
    const notificationCount = document.getElementById('notificationCount');

    if (!notificationButton || !notificationList || !notificationCount) return;

    async function loadNotifications() {
        try {
            const response = await fetch('index.php?action=getNotifications');
            const data = await response.json();

            // Mise à jour du badge
            notificationCount.textContent = data.unread_count;
            notificationCount.classList.toggle('hidden', data.unread_count === 0);

            // Mise à jour de la liste
            notificationList.innerHTML = data.notifications.length > 0
                ? data.notifications.map(notification => `
                    <li class="border-b py-3 px-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start space-x-3">
                            <span class="w-2 h-2 mt-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                            <div>
                                <p class="text-sm text-gray-800">${notification.message}</p>
                                <span class="text-xs text-gray-500">${notification.time_ago || 'À l\'instant'}</span>
                            </div>
                        </div>
                    </li>
                `).join('')
                : '<li class="py-4 text-center text-gray-500">Aucune notification</li>';
        } catch (error) {
            console.error('Erreur lors du chargement des notifications:', error);
            notificationList.innerHTML = '<li class="py-4 text-center text-red-500">Erreur de chargement</li>';
        }
    }

    notificationButton.addEventListener('click', loadNotifications);
});