<header class="bg-gradient-to-r from-green-400 to-teal-500 py-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Titre avec effet de brillance -->
        <h1 class="text-3xl font-bold tracking-wider text-white">
            Gestion de Projet - <?php if($_SESSION['user']->getRole()== 'Responsable de projet') {echo 'Responsable';}
            elseif($_SESSION['user']->getRole()== 'Membre de l\'équipe'){echo 'Membre';}
            ?>
            
</h1>
        
        <div class="flex items-center space-x-8">
            <!-- Bouton Notifications -->
            <div class="relative group">
                <button id="notificationButton" onclick="toggleNotificationModal()" 
                        class="relative bg-transparent p-4 rounded-full border-2 border-white/50 transition-all duration-300
                               hover:border-white hover:shadow-[0_0_15px_rgba(255,255,255,0.5)] 
                               group-hover:bg-blue-700 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" 
                                class="group-hover:animate-[spin_3s_linear_infinite]"/>
                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              d="M12 4 C15 4 17 7 17 10 C17 15 19 16 19 16 L5 16 C5 16 7 15 7 10 C7 7 9 4 12 4"
                              class="origin-top group-hover:animate-[swing_1s_ease-in-out_infinite]"/>
                        <circle cx="12" cy="3" r="1.5" fill="currentColor" 
                                class="group-hover:animate-[bounce_1s_ease-in-out_infinite]"/>
                    </svg>
                    
                </button>
                <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="bg-white text-blue-700 px-4 py-2 rounded-lg shadow-lg font-semibold whitespace-nowrap text-sm relative">
                        Notifications
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 border-8 border-transparent border-b-white"></div>
                    </div>
                    <span id="notificationCount" 
                          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-6 h-6
                                 rounded-full flex items-center justify-center animate-bounce">
                        
                    </span>
                </div>
            </div>

            <!-- Bouton Déconnexion -->
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


<style>
@keyframes shine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes swing {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(15deg); }
}

@keyframes fadeOut {
    to { opacity: 0; }
}

@keyframes slideDown {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.animate-shine {
    animation: shine 2s infinite;
}
</style>
