<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Projet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 to-indigo-300 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-xl rounded-xl max-w-4xl w-full p-8 border border-indigo-200">
        <!-- Titre principal -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-center text-indigo-800 mb-8">
            Modifier Projet
        </h1>

        <!-- Formulaire de modification -->
        <form action="index.php?action=updateProject" method="POST" class="space-y-6">

            <input type="hidden" name="id" value="<?php echo $project->getId(); ?>">

            <!-- Nom du projet -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom du Projet</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($project->getName()); ?>"
                       class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out"
                       placeholder="Entrez le nom du projet" required>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out"
                          placeholder="Entrez la description du projet" required><?php echo htmlspecialchars($project->getDescription()); ?></textarea>
            </div>

            <!-- Dates (mise côte à côte) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Date de début -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de Début</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($project->getStartDate()); ?>"
                           class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out" required>
                </div>

                <!-- Date de fin -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">Date de Fin</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($project->getEndDate()); ?>"
                           class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out" required>
                </div>
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                <select id="status" name="status"
                        class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out">
                    <option value="En cours" <?php echo $project->getStatus() == 'En cours' ? 'selected' : ''; ?>>En cours</option>
                    <option value="Terminé" <?php echo $project->getStatus() == 'Terminé' ? 'selected' : ''; ?>>Terminé</option>
                    <option value="Suspendu" <?php echo $project->getStatus() == 'Suspendu' ? 'selected' : ''; ?>>Suspendu</option>
                    <option value="Annulé" <?php echo $project->getStatus() == 'Annulé' ? 'selected' : ''; ?>>Annulé</option>
                </select>
            </div>

            <?php if ($_SESSION['role'] == 'Administrateur') : ?>
                <!-- Responsable du projet -->
                <div>
                    <label for="manager_id" class="block text-sm font-semibold text-gray-700 mb-2">Responsable du Projet</label>
                    <select id="manager_id" name="manager_id"
                            class="w-full p-4 border border-indigo-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all ease-in-out" required>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?php echo $manager->getId(); ?>" <?php echo $project->getManagerId() == $manager->getId() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($manager->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php elseif ($_SESSION['role'] == 'Responsable de projet') : ?>
                <input type="hidden" name="manager_id" value="<?php echo $project->getManagerId(); ?>">
            <?php endif; ?>

            <!-- Boutons -->
            <div class="flex justify-between items-center mt-6">
                <!-- Bouton de soumission -->
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition-all ease-in-out transform hover:scale-105 focus:outline-none">
                    Enregistrer
                </button>

                <!-- Bouton retour -->
                <?php if ($_SESSION['role'] == 'Administrateur') : ?>
                    <a href="index.php?action=listProjects"
                       class="text-indigo-600 hover:text-indigo-700 font-medium transition-all ease-in-out">
                        Retour
                    </a>
                <?php elseif ($_SESSION['role'] == 'Responsable de projet') : ?>
                    <a href="index.php?action=dashboardResponsable"
                       class="text-indigo-600 hover:text-indigo-700 font-medium transition-all ease-in-out">
                        Retour
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

</body>

</html>
