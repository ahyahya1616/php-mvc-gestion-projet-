<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-blue-300 min-h-screen p-10">

    <div class="container mx-auto">
        <!-- Titre principal -->
        <h1 class="text-4xl font-bold text-center text-blue-800 mb-10">
            Liste des Projets
        </h1>

        <!-- Tableau des projets -->
        <div class="overflow-x-auto shadow-xl rounded-lg bg-white p-6 border border-blue-200">
            
            <table class="table-auto w-full border-collapse border border-blue-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-3 text-left border border-blue-400">ID</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Nom</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Description</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Date de début</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Date de fin</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Statut</th>
                        <th class="px-4 py-3 text-left border border-blue-400">Responsable</th>
                        <th class="px-4 py-3 text-center border border-blue-400">Actions</th>
                    </tr>
                
                </thead>
                <tbody>
                 
                    <?php if (!empty($projects) ): ?>
                    
                        <?php foreach ($projects as $project): ?>
                            
                            <tr class="hover:bg-blue-100 transition">
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getId()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getName()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getDescription()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getStartDate()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getEndDate()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getStatus()); ?></td>
                                <td class="px-4 py-3 border border-blue-300"><?php echo htmlspecialchars($project->getManagerName()); ?></td>
                                
                                <td class="px-4 py-3 text-center border border-blue-300">
                                    <div class="flex justify-center items-center space-x-6 group">
                                        <!-- Modifier -->
                                        <a href="index.php?action=editProject&id=<?php echo $project->getId(); ?>" 
                                           class="relative group flex items-center text-green-600 hover:text-green-800 transition duration-200">
                                            <span class="absolute left-0 transform -translate-x-full text-sm font-semibold text-white bg-gray-700 rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                Modifier
                                            </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 010 2.828l-9.586 9.586a1 1 0 01-.293.207l-4 1.5a1 1 0 01-1.272-1.272l1.5-4a1 1 0 01.207-.293l9.586-9.586a2 2 0 012.828 0zm-4.828 3.414l-9.586 9.586L4 15l9.586-9.586L12.586 6z"/>
                                            </svg>
                                        </a>
                                        
                                        <!-- Supprimer -->
                                        <a href="index.php?action=confirmDeleteProject&id=<?php echo $project->getId(); ?>" 
                                           class="relative group flex items-center text-red-600 hover:text-red-800 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 011-1h2a1 1 0 011 1v1h3a1 1 0 110 2h-1v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5H4a1 1 0 110-2h3V2zm3 1h-2v1h2V3zm-5 4a1 1 0 011 1v6a1 1 0 11-2 0V8a1 1 0 011-1zm6 0a1 1 0 011 1v6a1 1 0 11-2 0V8a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="absolute right-0 transform translate-x-full text-sm font-semibold text-white bg-gray-700 rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                Supprimer
                                            </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">Aucun projet trouvé.</td>
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
