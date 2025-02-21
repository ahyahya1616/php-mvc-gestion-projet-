
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Suppression</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 to-indigo-300 min-h-screen p-10">

    <div class="container mx-auto max-w-2xl">
        <!-- Titre de confirmation -->
        <h1 class="text-3xl font-extrabold text-center text-indigo-800 mb-10">
        Êtes-vous sûr de vouloir supprimer ce projet?
        </h1>

        <div class="bg-white shadow-lg rounded-lg p-8 border border-indigo-200 space-y-6">
            <p class="text-lg font-medium text-gray-700">Projet : <?php echo htmlspecialchars($project->getName()); ?></p>
            <p class="text-sm text-gray-600">Status : <?php echo htmlspecialchars($project->getStatus()); ?></p>
            
            <div class="mt-6 flex justify-around">
                <!-- Bouton pour confirmer la suppression -->
                <form action="index.php?action=deleteProject&id=<?php echo $project->getId(); ?>" method="POST">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none">
                        Oui, supprimer
                    </button>
                </form>

                <!-- Bouton pour annuler -->
                <a href="index.php?action=listUsers" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg focus:outline-none">
                    Annuler
                </a>
            </div>
        </div>
    </div>

</body>


</html>
