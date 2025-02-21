function openTaskModal(projectId) {
    document.getElementById('taskForm').reset();
    document.getElementById('projectId').value = projectId;

    // Charger les membres disponibles
    fetch(`index.php?action=getNonProjectMembers&projectId=${projectId}`, { // Inclure l'ID du projet
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            const assigneeSelect = document.getElementById('assignee');
            assigneeSelect.innerHTML = ''; // Réinitialiser la liste
    
            if (data.members && data.members.length > 0) {
                data.members.forEach(member => {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = `${member.name} (${member.email})`;
                    assigneeSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "Tous les utilisateurs ne sont pas libres (ils ont des tâches à faire)";
                option.disabled = true;
                option.selected = true;
                assigneeSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Erreur lors du chargement des membres disponibles:', error));
    
    // Afficher le modal
    document.getElementById('taskModal').classList.remove('hidden');
}

// Fermer le modal
function closeTaskModal() {
    document.getElementById('taskModal').classList.add('hidden');
}

// Soumettre le formulaire
document.getElementById('taskForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('index.php?action=createTaskWithMember', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.message) {
                alert('Tâche créée avec succès.');
                closeTaskModal();
                // Actualiser la page ou mettre à jour la vue
                location.reload();
            } else {
                alert(result.error || 'Erreur inconnue.');
            }
        })
        .catch(error => console.error('Erreur lors de la création de la tâche:', error));
});
