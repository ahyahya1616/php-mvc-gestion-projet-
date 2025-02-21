<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsable de Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

   <?php include 'header.php'; ?>
    <!-- Main Content -->
    <main class="container mx-auto p-6 mt-6">

        <div class="bg-white shadow-lg rounded-xl p-6 text-center mb-12">
            <h2 class="text-4xl font-extrabold text-teal-600">Bienvenue, <?php echo htmlspecialchars($user->getName()); ?> !</h2>
            <p class="text-gray-600 text-lg mt-2">Voici un aperçu des statistiques clés et des outils de gestion.</p>
    
        </div>
  
        <!-- Dashboard Overview -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 shadow-lg rounded-xl transition-transform transform hover:scale-105 hover:shadow-2xl">
                <h2 class="text-xl font-semibold text-gray-700">Projets en cours</h2>
                <p class="text-4xl font-bold text-blue-600 mt-4"><?php echo htmlspecialchars($projectsInProgress); ?></p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-xl transition-transform transform hover:scale-105 hover:shadow-2xl">
                <h2 class="text-xl font-semibold text-gray-700">Projets terminés</h2>
                <p class="text-4xl font-bold text-green-600 mt-4"><?php echo htmlspecialchars($projectsCompleted); ?></p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-xl transition-transform transform hover:scale-105 hover:shadow-2xl">
                <h2 class="text-xl font-semibold text-gray-700">Projets en retard</h2>
                <p class="text-4xl font-bold text-red-600 mt-4"><?php echo htmlspecialchars($projectsOverdue); ?></p>
            </div>
        </section>

        <!-- Progression de Mes Projets -->
        <section class="mt-12">
    <h2 class="text-3xl font-bold text-gray-700 mb-12 text-center">Progression de Mes Projets</h2>
    
    <div class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Timeline -->
        <?php foreach ($projectsWithProgress as $index => $projectData): ?>
            <?php $project = $projectData['project']; ?>
            <?php $progress = $projectData['progress']; ?>
            
            <!-- Project Card -->
            <div class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg transition-all transform hover:scale-105 hover:shadow-xl">
                <!-- Project Circle Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 via-blue-500 to-teal-400 rounded-full flex items-center justify-center text-white text-2xl font-semibold mb-4">
                    <?php echo strtoupper(substr($project->getName(), 0, 1)); ?>
                </div>

                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($project->getName()); ?></h3>
                <p class="text-sm text-gray-600 mb-4">Date de fin prévue : <?php echo htmlspecialchars($project->getEndDate()); ?></p>

                <!-- Progress Bar -->
                <div class="relative w-full h-2 bg-gray-300 rounded-full mb-4">
                    <div class="absolute top-0 left-0 h-2 bg-gradient-to-r from-indigo-500 via-blue-500 to-teal-400 rounded-full" 
                         style="width: <?php echo $progress; ?>%;"></div>
                </div>

                <p class="text-xs text-gray-500 mb-4"><?php echo $progress; ?>% Complété</p>

                <!-- Actions -->
                <div class="flex justify-center space-x-6">
                    <a href="index.php?action=editProject&id=<?php echo $project->getId(); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm">Éditer</a>
                    <a href="index.php?action=seeProject&id=<?php echo $project->getId(); ?>" class="text-green-600 hover:text-green-800 text-sm">Voir</a>
                    <a href="#" onclick="openTaskModal(<?php echo $project->getId(); ?>)" class="text-black-600 hover:text-green-800 text-sm">Ajouter une tâche</a>
                    </div>
            </div>

        <?php endforeach; ?>
    </div>
</section>
<div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative overflow-hidden">
        <!-- Titre de la modal -->
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h3 class="text-lg font-bold text-gray-700">Notifications</h3>
            <button onclick="toggleNotificationModal()" class="text-gray-500 hover:text-gray-800">
                &times;
            </button>
        </div>
        
        <!-- Contenu défilant avec possibilité de glisser -->
        <div id="notificationList" class="space-y-4 max-h-96 overflow-y-auto">
            <!-- Notifications dynamiques -->
        </div>
        
        <!-- Pied de page -->
        <div class="mt-4 flex justify-end">
            <button onclick="toggleNotificationModal()" class="bg-red-500 text-white px-4 py-2 rounded shadow-md hover:bg-red-600">
                Fermer
            </button>
        </div>
    </div>
</div>



<div id="taskModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 hover:scale-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-800">Ajouter une tâche</h2>
                <button class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeTaskModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="taskForm" class="p-6 space-y-6">
            <input type="hidden" id="projectId" name="projectId">

            <!-- Nom et Description -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la tâche</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none" required></textarea>
                </div>
            </div>

            <!-- Assignation -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigner à</label>
                <select id="assignee" name="userId" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                    <option>Chargement...</option>
                </select>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="startDate" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="endDate" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>
            </div>

            <!-- Statut et Priorité -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="À faire">À faire</option>
                        <option value="En cours">En cours</option>
                        <option value="Terminé">Terminé</option>
                        <option value="Bloquée">Bloquée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                    <select name="priority" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeTaskModal()" 
                    class="px-5 py-2.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

           
    </main>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/membreGroup.js"></script>
    
</body>
<?php 
include 'footer.php';
?>

</html>
