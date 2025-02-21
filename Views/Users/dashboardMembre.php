<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Membre de l'équipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <?php include 'header.php' ?>

    <!-- Welcome Section -->
    <div class=" text-white text-center py-8 px-10 shadow-2xl rounded-lg max-w-7xl mx-auto mt-5">
    <h1 class="text-4xl font-extrabold text-teal-600">Bienvenue, <?php echo htmlspecialchars($user->getName()); ?> !</h1>
    <p class="text-gray-600 text-lg mt-2">Voici un aperçu des tâches qui vous attendent.</p>
</div>



    <!-- Main Content -->
    <main class="container mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 px-6 mt-8">

        <!-- Left Sidebar -->
        <section class="lg:col-span-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Charge de Travail</h2>
            <div class="grid grid-cols-1 gap-4">
                <!-- Card 1 -->
                <div class="bg-white shadow-lg rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 text-lg">Tâches à venir</h3>
                        <p class="text-indigo-600 text-3xl font-bold"><?php echo $upcomingTasks; ?></p>
                    </div>
                    <div class="text-indigo-600 text-4xl">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white shadow-lg rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 text-lg">Tâches terminées</h3>
                        <p class="text-green-600 text-3xl font-bold"><?php echo $completedTasks; ?></p>
                    </div>
                    <div class="text-green-600 text-4xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white shadow-lg rounded-lg p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-600 text-lg">Tâches en retard</h3>
                        <p class="text-red-600 text-3xl font-bold"><?php echo $overdueTasks; ?></p>
                    </div>
                    <div class="text-red-600 text-4xl">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="lg:col-span-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes Projets</h2>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($projects as $project): ?>
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($project['name']) ?></h3>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars($project['description']) ?></p>
                            </div>
                            <button 
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 shadow-md transition"
                                onclick="showTasksModal(<?= $project['id'] ?>)">
                                Voir
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

    </main>

   
<!-- Dans votre dashboardMembre.php -->
<div id="tasksModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 overflow-auto">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl">
        <!-- Header du modal -->
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Tâches du Projet</h2>
                <button onclick="closeTasksModal()" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Contenu du modal avec scrollbar -->
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto px-6 py-4">
            <div id="tasksContent" class="space-y-4">
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>
    </div>
</div>



    <div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative overflow-hidden">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h3 class="text-lg font-bold text-gray-700">Notifications</h3>
                <button onclick="toggleNotificationModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <div id="notificationList" class="space-y-4 max-h-96 overflow-y-auto"></div>
            <div class="mt-4 flex justify-end">
                <button onclick="toggleNotificationModal()" class="bg-red-500 text-white px-4 py-2 rounded shadow-md hover:bg-red-600">
                    Fermer
                </button>
            </div>
        </div>
    </div>

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
    <!-- Scripts -->
    <script src="assets/js/dashboardMember.js"></script>
    <script src="assets/js/commentaire.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>


</html>
