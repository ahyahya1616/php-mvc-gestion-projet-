<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 to-indigo-300 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-xl rounded-lg max-w-3xl w-full p-8 border border-indigo-200">
        <!-- Titre principal -->
        <h1 class="text-3xl md:text-4xl font-extrabold text-center text-indigo-800 mb-8">
            Modifier Utilisateur
        </h1>

        <!-- Formulaire de modification -->
        <form action="index.php?action=updateUser" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">

            <!-- Champ de nom -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>"
                       class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez le nom" required>
            </div>

            <!-- Champ d'email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>"
                       class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez l'email" required>
            </div>

            <div>
    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Rôle</label>
    <?php if ($user->getRole() === 'Administrateur'): ?>
        <input type="text" id="role" value="Administrateur" 
               class="w-full p-4 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed" 
               readonly>
        <!-- Champ caché pour envoyer la valeur du rôle -->
        <input type="hidden" name="role" value="Administrateur">
    <?php else: ?>
        <select id="role" name="role" 
                class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out">
            <option value="Responsable de projet" <?php echo $user->getRole() == 'Responsable de projet' ? 'selected' : ''; ?>>Responsable de projet</option>
            <option value="Membre de l'équipe" <?php echo $user->getRole() == 'Membre de l\'équipe' ? 'selected' : ''; ?>>Membre de l'équipe</option>
        </select>
    <?php endif; ?>
</div>

            

            <!-- Boutons -->
            <div class="flex justify-between items-center">
                <!-- Bouton de soumission -->
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none">
                    Enregistrer
                </button>

                <!-- Bouton retour -->
                <a href="index.php?action=listUsers" 
                   class="text-indigo-600 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">
                    Retour
                </a>
            </div>
        </form>
    </div>

</body>

</html>
