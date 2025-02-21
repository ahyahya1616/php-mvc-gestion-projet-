<?php if (!isset($user)): ?>
    <p>Utilisateur non connecté.</p>
    <a href="loginAdmin.php">Se connecter</a>
<?php else: ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles */
        .stat-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .divider {
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }

        .stat-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2b6cb0;
        }

        /* Simple grid for tools */
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<!-- En-tête -->
<header class="bg-gradient-to-r from-green-400 to-teal-500 py-6 shadow-lg">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-wider text-white">Gestion De Projet - Admin</h1>
            <div class="relative group">
                <a href="index.php?action=logout">
                    <button class="relative bg-transparent p-4 rounded-full border-2 border-white/50 
                                 transition-all duration-300 overflow-hidden
                                 hover:border-white hover:shadow-[0_0_15px_rgba(255,255,255,0.5)]
                                 group-hover:bg-blue-700 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                    class="group-hover:animate-[spin_3s_linear_infinite]"/>
                            <path stroke="currentColor" stroke-width="2" 
                                  d="M12 8 C13.5 8 15 9.5 15 11 C15 12.5 13.5 14 12 14 C10.5 14 9 12.5 9 11 C9 9.5 10.5 8 12 8"/>
                            <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                  d="M12 14 L12 20 M9 17 L12 20 L15 17"/>
                        </svg>
                    </button>
                </a>
                <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="bg-white text-blue-700 px-4 py-2 rounded-lg shadow-lg font-semibold whitespace-nowrap text-sm relative">
                        Déconnexion
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 border-8 border-transparent border-b-white"></div>
                    </div>
                </div>
        </div>
    </div>
    </div>
    
</header>

<!-- Section principale -->
<main class="container mx-auto px-6 py-12 space-y-12">

    <!-- Bienvenue -->
    <div class="bg-white shadow-lg rounded-xl p-6 text-center mb-12">
        <h2 class="text-4xl font-extrabold text-teal-600">Bienvenue, <?php echo htmlspecialchars($user->getName()); ?> !</h2>
        <p class="text-gray-600 text-lg mt-2">Voici un aperçu des statistiques clés et des outils de gestion.</p>
    </div>

  

    <sec class="p-6">
    <h3 class="text-2xl font-bold text-gray-700 mb-6">Détails des Projets</h3>
    
    <!-- Utilisation de flex pour mettre les divs côte à côte -->
    <div class="flex flex-wrap gap-6">
        <!-- Projets par statut -->
        <div class="bg-white shadow-md rounded-lg p-6 flex-1 min-w-[820px]">
            <h4 class="stat-title mb-4">Projets par Statut</h4>
            <ul class="space-y-3">
                <?php foreach ($projectsByStatus as $project): ?>
                    <li class="flex justify-between text-gray-600">
                        <span><?php echo htmlspecialchars($project['status']); ?> :</span>
                        <span class="font-bold"><?php echo $project['count']; ?></span>
                    </li>
                    <div class="border-b border-gray-200 my-2"></div>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="stat-card bg-gradient-to-r from-pink-400 to-red-400 text-white p-6 mb-6 rounded-lg shadow-lg ">
        <h4 class="text-3xl font-extrabold mb-2"><?php echo round($averageProjectDuration ?? 0, 2); ?></h4>

            <p class="text-lg">Moyenne de jours pour effectuer un projet</p>
        </div>
    </div>
                </br>
</section>
    <!-- Détails des Tâches -->
    <section>
        <h3 class="text-2xl font-bold text-gray-700 mb-6">Détails des Tâches</h3>
        <div class="tools-grid">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h4 class="stat-title mb-4">Tâches par Statut</h4>
            <ul class="space-y-3">
                <?php foreach ($tasksByStatus as $status): ?>
                    <li class="flex justify-between text-gray-600">
                        <span><?php echo htmlspecialchars($status['status']); ?> :</span>
                        <span class="font-bold"><?php echo $status['count']; ?></span>
                    </li>
                    <div class="divider"></div> 
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h4 class="stat-title mb-4">Tâches par Priorité</h4>
            <ul class="space-y-3">
                <?php foreach ($tasksByPriority as $priority): ?>
                    <li class="flex justify-between text-gray-600">
                        <span><?php echo htmlspecialchars($priority['priority']); ?> :</span>
                        <span class="font-bold"><?php echo $priority['count']; ?></span>
                    </li>
                    <div class="divider"></div>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="stat-card bg-gradient-to-r from-pink-400 to-red-400 text-white p-6 mb-6 rounded-lg shadow-lg">
        <h4 class="text-3xl font-extrabold mb-2"><?php echo round($averageCompletionTime ?? 0); ?></h4>    
        <p class="text-lg">Moyenne de jours Pour Effectuer une Tache</p>
            </div>
        </div>
    </section>

    <!-- Outils de Gestion -->
    <section>
        <h3 class="text-2xl font-semibold text-center text-gray-700 mb-6">Outils de Gestion</h3>
        <div class="tools-grid" style="margin-left: 400px;">
            <!-- Gestion des Utilisateurs -->
            <div class="bg-white text-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transform hover:scale-105 transition-transform ">
                <h4 class="text-lg font-semibold mb-4">Utilisateurs</h4>
                <a href="index.php?action=addUser" class="text-indigo-600 font-medium">Créer</a> |
                <a href="index.php?action=listUsers" class="text-teal-600 font-medium">Voir</a>
            </div>

            <!-- Gestion des Projets -->
            <div class="bg-white text-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transform hover:scale-105 transition-transform">
                <h4 class="text-lg font-semibold mb-4">Projets</h4>
                <a href="index.php?action=createProject" class="text-indigo-600 font-medium">Créer</a> |
                <a href="index.php?action=listProjects" class="text-teal-600 font-medium">Voir</a>
            </div>

           
        </div>
    </section>

</main>




</body>
<?php 
include 'footer.php';
?>
</html>

<?php endif; ?>
