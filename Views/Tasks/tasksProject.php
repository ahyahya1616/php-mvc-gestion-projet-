<?php include_once 'utils.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-500 text-white py-6 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold">Détails des Tâches du Projet</h1>
            <a href="index.php?action=dashboardResponsable" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium shadow-md hover:bg-gray-100">
                Retour au tableau de bord
            </a>
        </div>
    </header>
    <main class="container mx-auto my-8 space-y-8">
    <h1 class="text-2xl font-semibold mb-4 text-gray-700">Projet : <?php echo $project->getName(); ?></h1>
    
    <!-- Tâches -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Liste des Tâches </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tâche</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre assigné</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priorité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaires</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                    <?php echo htmlspecialchars($task['task_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                    <?php echo htmlspecialchars($task['assignee_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    <p><span class="font-medium">Début :</span> <?php echo $task['start_date']; ?></p>
                                    <p><span class="font-medium">Fin prévue :</span> <?php echo $task['planned_end_date']; ?></p>
                                    <?php if ($task['actual_end_date']): ?>
                                        <p><span class="font-medium">Fin réelle :</span> <?php echo $task['actual_end_date']; ?></p>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500">Non terminée</p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-sm 
                                    <?php 
                                        if ($task['status'] === 'À faire') echo 'bg-gray-200 text-gray-800'; 
                                        elseif ($task['status'] === 'En cours') echo 'bg-yellow-200 text-yellow-800'; 
                                        elseif ($task['status'] === 'Terminé') echo 'bg-green-200 text-green-800'; 
                                        elseif ($task['status'] === 'Bloquée') echo 'bg-red-200 text-red-800'; 
                                    ?>">
                                        <?php echo htmlspecialchars($task['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-sm 
                                    <?php 
                                        if ($task['priority'] === 'haute') echo 'bg-red-100 text-red-800'; 
                                        elseif ($task['priority'] === 'moyenne') echo 'bg-yellow-100 text-yellow-800'; 
                                        elseif ($task['priority'] === 'basse') echo 'bg-green-100 text-green-800'; 
                                    ?>">
                                        <?php echo htmlspecialchars($task['priority']); ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    <button 
                                        class="text-indigo-600 hover:text-indigo-700 font-medium"
                                        onclick="toggleComments(<?php echo $task['task_id']; ?>)">
                                        Afficher/Ecrire Commentaires
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4">Aucune tâche trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>



            
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
    <h3 class="text-2xl font-semibold mb-4 text-gray-700">Membres du Groupe</h3>
    
    <ul class="divide-y divide-gray-300">
        <!-- Liste des membres (inchangée) -->
        <?php if (!empty($members)): ?>
            <?php foreach ($members as $member): ?>
                <li class="py-4 flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-500 text-white rounded-full flex items-center justify-center text-lg">
                        <?php echo strtoupper(substr($member['member_name'], 0, 1)); ?>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-medium text-gray-800">
                            <?php echo htmlspecialchars($member['member_name']); ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            <?php echo htmlspecialchars($member['member_email']); ?> - 
                            <span class="italic"><?php echo htmlspecialchars($member['member_role']); ?></span>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">Aucun membre trouvé pour ce projet.</p>
        <?php endif; ?>
    </ul>
</div>


<!-- Modale pour afficher les commentaires -->
<div id="commentsModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white w-96 max-w-md rounded-lg shadow-xl relative">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Commentaires</h2>
            <button onclick="closeCommentsModal()" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
                &times;
            </button>
        </div>
        
        <!-- Comments Container -->
        <div id="commentsContainer" class="px-6 py-4 max-h-60 overflow-y-auto">
            <!-- Comments will be loaded here -->
        </div>
        
        <!-- Comment Form -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <form id="commentForm">
                <textarea 
                    id="newComment" 
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
                    rows="3" 
                    placeholder="Écrire un commentaire..."
                ></textarea>
                <button type="submit" 
                    class="mt-3 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Ajouter un commentaire
                </button>
            </form>
        </div>
    </div>

</div>



</main>

<!-- Footer -->
<footer class="bg-gray-100 py-4">
    <div class="container mx-auto text-center text-gray-600 text-sm">
        &copy; 2024 Gestion de Projets. Tous droits réservés.
    </div>
</footer>

<script src="assets/js/commentaire.js"></script>

</body>


</html>
