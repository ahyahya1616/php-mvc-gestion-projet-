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
            Ajouter Un Utilisateur
        </h1>

        <!-- Formulaire de modification -->
        <form action="index.php?action=addUser" method="POST" class="space-y-6">
            
            <!-- Champ de nom -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" id="name" name="name"   class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez le nom" required>
            </div>

            <!-- Champ d'email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email"  class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
                       placeholder="Entrez l'email" required>
            </div>


            <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out" 
            placeholder="Entrez le mot de passe" required>    </div>

            <div>


     <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Rôle</label>
            <select id="role" name="role" 
                class="w-full p-4 border border-indigo-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 ease-in-out">
                <option value="Responsable de projet">Responsable de projet</option>
                        <option value="Administrateur">Administrateur</option>
                        <option value="Membre de l'équipe">Membre de l'équipe</option>
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
                <a href="index.php?action=dashboardAdmin" 
                   class="text-indigo-600 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">
                    Retour
                </a>
            </div>
        </form>
    </div>

</body>


</html>



