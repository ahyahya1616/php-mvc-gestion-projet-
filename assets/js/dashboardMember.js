// Constantes pour les couleurs
const STATUS_COLORS = {
    'À faire': 'bg-gray-100 text-gray-800',
    'En cours': 'bg-blue-100 text-blue-800',
    'Terminé': 'bg-green-100 text-green-800',
    'Bloquée': 'bg-red-100 text-red-800'
};

const PRIORITY_COLORS = {
    'haute': 'text-red-600 font-medium',
    'moyenne': 'text-yellow-600 font-medium',
    'basse': 'text-green-600 font-medium'
};

// Fonctions utilitaires
function formatDate(dateString) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

function getStatusColor(status) {
    return STATUS_COLORS[status] || STATUS_COLORS['À faire'];
}

function getPriorityColor(priority) {
    return PRIORITY_COLORS[priority] || PRIORITY_COLORS['basse'];
}

// Gestion des tâches
function showTasksModal(projectId) {
    const modal = document.getElementById('tasksModal');
    const tasksContent = document.getElementById('tasksContent');

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch('index.php?action=getTasksForProject', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `project_id=${projectId}`
    })
        .then(response => response.json())
        .then(tasks => {
            tasksContent.innerHTML = tasks.length > 0
                ? tasks.map(task => `
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">${task.name}</h3>
                            <span class="px-2 py-1 text-sm rounded-full ${getStatusColor(task.status)}">
                                ${task.status}
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <p class="text-gray-600">${task.description || 'Aucune description'}</p>
                            
                            <div class="flex flex-wrap gap-4 text-sm">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="${getPriorityColor(task.priority)}">${task.priority}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-600">Échéance: ${formatDate(task.planned_end_date)}</span>
                                </div>
                            </div>
                        </div>
<div class="flex flex-col sm:flex-row gap-2">
    <!-- Formulaire de mise à jour du statut -->
    <form onsubmit="updateTaskStatus(event, ${task.id})" class="flex-grow">
        <div class="flex flex-col sm:flex-row gap-2">
            <div class="flex-grow">
                <label for="status-${task.id}" class="block text-sm font-medium text-gray-700 mb-1">
                    Modifier le statut
                </label>
                <select id="status-${task.id}" name="status" 
                        class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="À faire" ${task.status === 'À faire' ? 'selected' : ''}>À faire</option>
                    <option value="En cours" ${task.status === 'En cours' ? 'selected' : ''}>En cours</option>
                    <option value="Terminé" ${task.status === 'Terminé' ? 'selected' : ''}>Terminé</option>
                    <option value="Bloquée" ${task.status === 'Bloquée' ? 'selected' : ''}>Bloquée</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
    
    <!-- Bouton Commentaires -->
    <div class="flex items-end">
        <button onclick="toggleComments(${task.id})" 
                class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Commentaires
            </div>
        </button>
    </div>
</div>
</div>
                `).join('')
                : '<div class="text-center text-gray-500 py-8">Aucune tâche trouvée.</div>';
        })
        .catch(() => {
            tasksContent.innerHTML = '<div class="text-center text-red-500 py-8">Erreur lors du chargement des tâches.</div>';
        });
}

function closeTasksModal() {
    document.getElementById('tasksModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function updateTaskStatus(event, taskId) {
    event.preventDefault();
    const select = document.getElementById(`status-${taskId}`);
    const newStatus = select.value;

    fetch('index.php?action=updateStatusTask', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_id=${taskId}&status=${newStatus}`
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Statut mis à jour avec succès.');
                showTasksModal(data.project_id);
            } else {
                alert(`Erreur côté serveur : ${data.error || 'Erreur inconnue'}`);
            }
        })
        .catch(error => {
            console.error("Erreur réseau :", error);
            alert(`Erreur réseau : ${error.message}`);
        });
}