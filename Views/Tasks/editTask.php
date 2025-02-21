<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Tâche</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-yellow-100 to-red-300 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-xl rounded-lg max-w-3xl w-full p-8 border border-red-200">
        <!-- Titre principal -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-center text-red-800 mb-8">
            Modifier Tâche
        </h1>

        <!-- Formulaire de modification -->
        <form action="index.php?action=updateTask" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo $task->getId(); ?>">

            <!-- Champ du nom de la tâche -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom de la Tâche</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($task->getName()); ?>"
                       class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez le nom de la tâche" required>
            </div>

            <!-- Champ de description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" 
                          placeholder="Entrez la description de la tâche" required><?php echo htmlspecialchars($task->getDescription()); ?></textarea>
            </div>

            <!-- Champ de projet -->
            <div>
                <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-1">Projet Associé</label>
                <select id="project_id" name="project_id"
                        class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" required>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo $project->getId(); ?>" <?php echo $task->getProjectId() == $project->getId() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($project->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Champ de date de début -->
            <div>
                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Date de Début</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($task->getStartDate()); ?>"
                       class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" required>
            </div>

            <!-- Champ de date prévue de fin -->
            <div>
                <label for="planned_end_date" class="block text-sm font-semibold text-gray-700 mb-1">Date Prévue de Fin</label>
                <input type="date" id="planned_end_date" name="planned_end_date" value="<?php echo htmlspecialchars($task->getPlannedEndDate()); ?>"
                       class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" required>
            </div>

            <!-- Champ du statut -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Statut</label>
                <select id="status" name="status"
                        class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out">
                    <option value="En cours" <?php echo $task->getStatus() == 'En cours' ? 'selected' : ''; ?>>En cours</option>
                    <option value="Terminé" <?php echo $task->getStatus() == 'Terminé' ? 'selected' : ''; ?>>Terminé</option>
                    <option value="Suspendu" <?php echo $task->getStatus() == 'Suspendu' ? 'selected' : ''; ?>>Suspendu</option>
                </select>
            </div>

            <!-- Champ de priorité -->
            <div>
                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1">Priorité</label>
                <select id="priority" name="priority"
                        class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out">
                    <option value="Basse" <?php echo $task->getPriority() == 'Basse' ? 'selected' : ''; ?>>Basse</option>
                    <option value="Moyenne" <?php echo $task->getPriority() == 'Moyenne' ? 'selected' : ''; ?>>Moyenne</option>
                    <option value="Haute" <?php echo $task->getPriority() == 'Haute' ? 'selected' : ''; ?>>Haute</option>
                </select>
            </div>

            <!-- Champ d'assignation -->
            <div>
                <label for="member_id" class="block text-sm font-semibold text-gray-700 mb-1">Assigné à</label>
                <select id="member_id" name="member_id"
                        class="w-full p-4 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 ease-in-out" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user->getId(); ?>" <?php echo $task->getAssigneeId() == $user->getId() ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            

            <!-- Boutons -->
            <div class="flex justify-between items-center">
                <!-- Bouton de soumission -->
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none">
                    Enregistrer
                </button>

                <!-- Bouton retour -->
                <a href="index.php?action=listTasks" 
                   class="text-red-600 hover:text-red-700 font-medium transition duration-300 ease-in-out">
                    Retour
                </a>
            </div>
        </form>
    </div>

</body>

<?php 
include 'footer.php';
?>
</html>
