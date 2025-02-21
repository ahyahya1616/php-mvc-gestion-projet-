<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 to-indigo-300 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-xl rounded-lg max-w-3xl w-full p-8 border border-indigo-200">
        <!-- Titre principal -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-center text-indigo-800 mb-8">
            Ajouter Un Projet
        </h1>


        <form action="index.php?action=createProject" method="POST" class="space-y-6">
            
    
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom du Projet</label>
                <input type="text" id="name" name="name" 
                       class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez le nom du projet" required>
            </div>


            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4" 
                          class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                          placeholder="Entrez la description du projet" required></textarea>
            </div>

    
            <div>
                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Date de Début</label>
                <input type="date" id="start_date" name="start_date" 
                       class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       required>
            </div>

           
            <div>
                <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">Date de Fin</label>
                <input type="date" id="end_date" name="end_date" 
                       class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       required>
            </div>

            <!-- Champ du statut -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Statut</label>
                <select id="status" name="status" 
                        class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out">
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                    <option value="Suspendu">Suspendu</option>
                    <option value="Annulé">Annulé</option>
                </select>
            </div>

            <!-- Champ du responsable du projet -->
            <div>
                <label for="manager_id" class="block text-sm font-semibold text-gray-700 mb-1">Responsable du Projet</label>
                <select id="manager_id" name="manager_id" 
                        class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" required>
                    <?php foreach ($managers as $manager): ?>
                        <option value="<?php echo $manager->getId(); ?>">
                            <?php echo htmlspecialchars($manager->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Boutons -->
            <div class="flex justify-between items-center">
                <!-- Bouton de soumission -->
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none">
                    Ajouter
                </button>

                <!-- Bouton retour -->
                <a href="index.php?action=listProjects" 
                   class="text-indigo-600 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">
                    Retour
                </a>
            </div>
        </form>
    </div>

</body>


</html>
