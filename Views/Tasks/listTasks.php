<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Tâches</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-300 min-h-screen p-10">

    <div class="container mx-auto">
        <!-- Titre principal -->
        <h1 class="text-4xl font-bold text-center text-blue-800 mb-10">
            Liste des Tâches
        </h1>

        <!-- Tableau des tâches -->
        <div class="overflow-x-auto shadow-xl rounded-lg bg-white p-6 border border-blue-200">
            
            <table class="table-auto w-full border-collapse border border-blue-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-3 text-left border border-blue-400">ID</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Nom</th>
                     
                        <th class="px-4 py-3 text-left border border-blue-400">Projet</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Date de début</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Date prévue de fin</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Statut</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Priorité</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Assigné à</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr class="hover:bg-blue-100 transition">
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['task_id']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['project_id']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['start_date']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['planned_end_date']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['status']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['priority']); ?></td>
                            <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($task['assignee_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-gray-500 py-4">Aucune tâche trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Bouton de retour -->
        <div class="mt-6 flex justify-center">
            <a href="index.php?action=dashboardAdmin" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                Retour au Tableau de Bord
            </a>
        </div>
    </div>

</body>


</html>
