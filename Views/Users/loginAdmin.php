<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100">

<div class="min-h-screen flex flex-col justify-center items-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row w-full max-w-5xl overflow-hidden">
        
        <!-- Section Gauche - Image -->
        <div class="md:w-1/2 bg-gradient-to-tr from-blue-700 to-blue-500 p-8 flex flex-col justify-center items-center text-white">
            <div class="relative w-full h-full flex justify-center items-center">
                <img src="/gestionProjet/images/gestion-de-projets.jpg" alt="Gestion de Projets" 
                     class="w-3/4 h-auto rounded-lg shadow-lg border-4 border-blue-400 transition-transform duration-300 transform hover:scale-105">
            </div>
            <h2 class="text-4xl font-bold mt-6 mb-4">Bienvenue</h2>
            <p class="text-center text-blue-100 text-lg">
                Accédez à votre espace  pour gérer vos projets efficacement
            </p>
        </div>

        <!-- Section Droite - Formulaire -->
        <div class="md:w-1/2 p-10">
            <div class="flex justify-center mb-8">
                <img src="/gestionProjet/images/gestion-de-projets.jpg" alt="Logo" class="w-20 h-20 md:hidden">
            </div>

            <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-8">Connexion</h1>

            <?php if (isset($_SESSION['status'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded animate-shake">
                    <?php echo $_SESSION['status']; ?>
                </div>
                <?php unset($_SESSION['status']); ?>
            <?php endif; ?>

            <form action="index.php?action=login" method="POST" class="space-y-6">
                <div class="group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" required
                               class="pl-10 w-full rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 py-2.5">
                    </div>
                </div>

                <div class="group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de Passe</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required
                               class="pl-10 w-full rounded-lg border-2 border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 py-2.5">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Se Connecter
                </button>
            </form>

            <p class="mt-8 text-center text-gray-600 text-sm">
                Vous n'avez pas accès ? 
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition duration-300">
                    Contactez le Superadmin
                </a>
            </p>
        </div>
    </div>
</div>










</body>
<?php 
include 'footer.php';
?>
</html>
