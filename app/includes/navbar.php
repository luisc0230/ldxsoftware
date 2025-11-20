<div class="grid items-center justify-center md:justify-normal w-full grid-cols-[auto_1fr] mx-auto text-white gap-x-10 md:flex max-w-screen-full py-4">
                <!-- Logo Section -->
                <div class="md:flex-grow md:basis-0 flex justify-start">
                    <a href="#hero" class="ml-4 flex items-center gap-2.5 font-bold transition-transform duration-300 hover:scale-110" title="Ir a la página principal" aria-label="Ir a la página principal">
                        <img src="assets/images/logo.png" alt="LDX Software" class="h-8 w-auto">
                    </a>
                </div>

                <!-- Navigation -->
                <nav aria-label="Navegación principal" id="header-navbar" class="col-span-full overflow-x-auto row-[2/3] grid grid-rows-[0fr] transition-[grid-template-rows] data-[open]:grid-rows-[1fr] md:justify-center md:flex group/nav">
                    <ul data-header-navbar="" class="flex flex-col items-center overflow-x-hidden overflow-y-hidden md:flex-row gap-x-2">
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#hero" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                Inicio
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#servicios" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-cogs" aria-hidden="true"></i>
                                Servicios
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#trabajos" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                                Trabajos
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#suscripciones" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-credit-card" aria-hidden="true"></i>
                                Suscripciones
                            </a>
                        </li>
                        <li class="flex justify-center w-full first:mt-5 md:first:mt-0 md:block md:w-auto">
                            <a href="#contacto" class="flex items-center md:w-auto justify-center gap-2 md:px-4 md:py-2 hover:bg-white/5 md:rounded-2xl border border-transparent hover:border-white/10 transition-all min-h-[50px] md:text-base px-5 py-4 text-xl duration-300 w-full">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                Contacto
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- CTA and Mobile Menu -->
                <div class="flex md:flex-grow md:basis-0 items-center gap-4 mr-4 ml-auto md:ml-0 justify-end">
                                            <!-- User Profile Dropdown -->
                        <div class="relative" id="userDropdown">
                            <button onclick="toggleUserMenu()" class="flex items-center gap-2 hover:bg-white/5 rounded-2xl px-3 py-2 transition-all">
                                                                    <img src="https://lh3.googleusercontent.com/a/ACg8ocJCgv7sjQOioAN5dypRCJEoZf0QJW7BbKiXD5YVPegacux8OM-P=s96-c" alt="Luis Carbajal" class="w-8 h-8 rounded-full border-2 border-blue-500">
                                                                <span class="text-white hidden md:block">Luis</span>
                                <i class="fas fa-chevron-down text-white text-xs" aria-hidden="true"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-64 bg-gray-900 border border-gray-700 rounded-xl shadow-2xl z-50">
                                <div class="p-4 border-b border-gray-700">
                                    <p class="text-white font-semibold">Luis Carbajal</p>
                                    <p class="text-gray-400 text-sm">luisc023030@gmail.com</p>
                                </div>
                                <div class="p-2">
                                    <a href="https://ldxsoftware.com.pe/mis-suscripciones" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-credit-card" aria-hidden="true"></i>
                                        <span>Mis Suscripciones</span>
                                    </a>
                                    <a href="https://ldxsoftware.com.pe/perfil" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span>Mi Perfil</span>
                                    </a>
                                    <hr class="my-2 border-gray-700">
                                    <a href="https://ldxsoftware.com.pe/auth/logout" class="flex items-center gap-3 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                        <span>Cerrar Sesión</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                                        
                    <!-- Mobile Menu Toggle -->
                    <button class="flex items-center justify-center py-2 md:hidden group" id="header-navbar-toggle" aria-controls="header-navbar" title="Mostrar Menú" aria-label="Mostrar menú" aria-expanded="false">
                        <div class="flex items-center justify-center p-2 cursor-pointer">
                            <div class="flex flex-col gap-2">
                                <span class="hamburger-line block h-0.5 w-8 origin-center rounded-full bg-white/80 transition-transform ease-in-out duration-300"></span>
                                <span class="hamburger-line block h-0.5 w-8 origin-center rounded-full bg-white/80 transition-transform ease-in-out duration-300"></span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
