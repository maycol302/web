<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fernagym - Gimnasio Profesional</title>
    <script>
    // Dropdown for desktop
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('dropdownNavbarLink');
        const dropdownMenu = document.getElementById('dropdownNavbar');
        if(dropdownBtn && dropdownMenu) {
            let hideTimeout;
            const showMenu = () => {
                clearTimeout(hideTimeout);
                dropdownMenu.classList.remove('hidden');
            };
            const hideMenu = () => {
                hideTimeout = setTimeout(() => {
                    dropdownMenu.classList.add('hidden');
                }, 500);
            };
            dropdownBtn.addEventListener('mouseenter', showMenu);
            dropdownBtn.addEventListener('mouseleave', hideMenu);
            dropdownMenu.addEventListener('mouseenter', showMenu);
            dropdownMenu.addEventListener('mouseleave', hideMenu);
        }
        // Mobile menu
        const mobileBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if(mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #000;
            color: #fff;
        }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('https://placehold.co/1920x1080') no-repeat center center;
            background-size: cover;
            height: 80vh;
        }
        .btn-primary {
            background-color: #FFD700;
            color: #000;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.7);
        }
        .plan-card {
            border: 2px solid #FFD700;
            transition: all 0.3s;
        }
        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
        }
        .feature-icon {
            color: #FFD700;
            font-size: 2.5rem;
        }
        .navbar {
            background: linear-gradient(90deg, #000000ff 0%, #ffd9006b 300%);
            box-shadow: 0 4px 24px rgba(64, 0, 255, 0.76), 0 1.5px 0 #FFD700;
            border-bottom: 2px solid #FFD700;
            transition: background 0.5s cubic-bezier(.4,2,.3,1), box-shadow 0.5s cubic-bezier(.4,2,.3,1), border-bottom 0.5s;
        }
        .navbar.scrolled {
            background: rgba(0,0,0,0.98);
            box-shadow: 0 8px 32px 0 rgba(255,215,0,0.25), 0 1.5px 0 #FFD700;
            border-bottom: 3px solid #FFD700;
            animation: navbarScrollAnim 0.6s cubic-bezier(.4,2,.3,1);
        @keyframes navbarScrollAnim {
            0% {
                transform: translateY(-40px);
                opacity: 0.5;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        }
        .section-title {
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50%;
            height: 3px;
            background-color: #FFD700;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed w-full z-50 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="assets/img/logo.jpg" alt="Logo Fernagym - Gimnasio profesional con diseño moderno en amarillo y negro" width="150" height="50" style="width:150px;height:50px;object-fit:contain;">
            </div>
            <!-- Dropdown Navigation -->
            <div class="hidden md:flex items-center space-x-4">
                <div class="relative group">
                    <button id="dropdownNavbarLink" class="flex items-center text-white hover:text-yellow-400 font-semibold focus:outline-none">
                        Menú
                        <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="dropdownNavbar" class="absolute left-0 z-10 hidden group-hover:block bg-black border border-yellow-400 rounded-lg shadow-lg mt-2 min-w-[180px]">
                        <div class="animate-slide-fade-bounce">
                            <a href="#inicio" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Inicio</a>
                            <a href="#nosotros" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Nosotros</a>
                            <a href="#servicios" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Servicios</a>
                            <a href="#horarios" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Horarios</a>
                            <a href="#planes" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Planes</a>
                            <a href="#inscripcion" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Inscripción</a>
                            <a href="#contacto" class="block px-4 py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Contacto</a>
                        </div>
                    </div>
                </div>
                <a href="login.php" class="text-white hover:text-yellow-400 font-semibold ml-4">Iniciar Sesión</a>
                <a href="registro.php" class="btn-primary px-4 py-2 rounded-full text-sm font-semibold ml-2">Registrarse</a>
            </div>
            <!-- Mobile Hamburger -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Dropdown -->
        <div id="mobile-menu" class="md:hidden hidden bg-black border-t border-yellow-400 px-4 pb-4">
            <a href="#inicio" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Inicio</a>
            <a href="#nosotros" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Nosotros</a>
            <a href="#servicios" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Servicios</a>
            <a href="#horarios" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Horarios</a>
            <a href="#planes" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Planes</a>
            <a href="#inscripcion" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Inscripción</a>
            <a href="#contacto" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Contacto</a>
            <a href="login.php" class="block py-2 text-white hover:bg-yellow-400 hover:text-black font-semibold">Iniciar Sesión</a>
            <a href="registro.php" class="block py-2 btn-primary rounded-full text-black font-semibold">Registrarse</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero flex items-center justify-center text-center pt-20">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-yellow-400">TRANSFORMA TU CUERPO, TRANSFORMA TU VIDA</h1>
            <p class="text-xl md:text-2xl mb-8">El gimnasio más completo de la ciudad con los mejores entrenadores</p>
            <a href="#inscripcion" class="btn-primary py-3 px-8 rounded-full text-lg font-semibold inline-block">Quiero inscribirme</a>
        </div>
    </section>

    <!-- Nosotros Section -->
    <section id="nosotros" class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400">SOBRE NOSOTROS</h2>
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                    <img src="assets/img/gym.jpg" alt="Interior moderno del gimnasio Fernagym con equipos de última generación y buen diseño" class="rounded-lg shadow-xl">
                </div>
                <div class="md:w-1/2">
                    <h3 class="text-2xl font-bold mb-4">Fernagym - Más que un gimnasio</h3>
                    <p class="mb-4">Fundado en 2018, Fernagym se ha convertido en el referente de fitness y salud en la región. Nuestro objetivo es ayudarte a alcanzar tus metas físicas en un ambiente motivador y profesional.</p>
                    <p class="mb-6">Contamos con un equipo de entrenadores certificados que te guiarán en cada paso de tu transformación física.</p>
                    <div class="flex space-x-4">
                        <div class="text-center">
                            <div class="feature-icon mb-2">🏋️</div>
                            <p class="font-semibold">+5 Años</p>
                            <p class="text-sm">de experiencia</p>
                        </div>
                        <div class="text-center">
                            <div class="feature-icon mb-2">👥</div>
                            <p class="font-semibold">+2000</p>
                            <p class="text-sm">clientes satisfechos</p>
                        </div>
                        <div class="text-center">
                            <div class="feature-icon mb-2">💪</div>
                            <p class="font-semibold">15</p>
                            <p class="text-sm">entrenadores</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400 text-center">NUESTROS SERVICIOS</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Entrenamiento Funcional</h3>
                    </div>
                    <p>Ejercicios que mejoran tu capacidad para realizar actividades diarias con mayor eficiencia y menor riesgo de lesiones.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Sala de Pesas</h3>
                    </div>
                    <p>Equipos modernos y pesas libres para desarrollar fuerza muscular y tonificar tu cuerpo.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Cardio</h3>
                    </div>
                    <p>Máquinas de última generación para mejorar tu resistencia cardiovascular y quemar calorías.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Clases Grupales</h3>
                    </div>
                    <p>Diversas disciplinas como spinning, zumba, crossfit y más, para entrenar en grupo con energía.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Entrenamiento Personalizado</h3>
                    </div>
                    <p>Programas diseñados exclusivamente para ti, con seguimiento constante de un entrenador especializado.</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-400 p-3 rounded-full mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">Nutrición</h3>
                    </div>
                    <p>Asesoramiento nutricional personalizado para complementar tu entrenamiento y alcanzar tus objetivos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Horarios Section -->
    <section id="horarios" class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400 text-center">HORARIOS</h2>
            <div class="max-w-4xl mx-auto bg-gray-900 rounded-lg shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-6 text-yellow-400">Horario Semanal</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="font-semibold">Lunes a Viernes:</span>
                                <span>07:00 - 23:00</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="font-semibold">Sábados:</span>
                                <span>07:00 - 20:00</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-700 pb-2">
                                <span class="font-semibold">Domingos:</span>
                                <span>Cerrado</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-gray-800">
                        <h3 class="text-2xl font-bold mb-6 text-white">Clases Especiales</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between border-b border-gray-600 pb-2">
                                <span class="font-semibold">Spinning:</span>
                                <span>L-J 09:00 y 18:30</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-600 pb-2">
                                <span class="font-semibold">Zumba:</span>
                                <span>M-V 10:00 y 19:00</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-600 pb-2">
                                <span class="font-semibold">Crossfit:</span>
                                <span>L-M-J-V 07:00 y 20:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Planes Section -->
    <section id="planes" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400 text-center">NUESTROS PLANES</h2>
            <!-- Swiper Carousel -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <!-- Plan Básico -->
                    <div class="swiper-slide">
                        <div class="plan-card bg-black p-8 rounded-lg text-center">
                            <h3 class="text-2xl font-bold mb-4 text-yellow-400">BÁSICO</h3>
                            <p class="text-4xl font-bold mb-6">$15.000<sub class="text-sm">/mes</sub></p>
                            <ul class="mb-8 space-y-3 text-left">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso ilimitado a sala de pesas</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso a máquinas de cardio</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Duchas y locker</li>
                            </ul>
                            <a href="#inscripcion" class="btn-primary py-3 px-6 rounded-full font-semibold inline-block">Inscribirme</a>
                        </div>
                    </div>
                    <!-- Plan Premium -->
                    <div class="swiper-slide">
                        <div class="plan-card bg-black p-8 rounded-lg text-center border-4 border-yellow-400 transform scale-105 relative">
                            <div class="absolute top-0 right-0 bg-yellow-400 text-black px-3 py-1 font-bold rounded-bl-lg">POPULAR</div>
                            <h3 class="text-2xl font-bold mb-4 text-yellow-400">PREMIUM</h3>
                            <p class="text-4xl font-bold mb-6">$30.000<sub class="text-sm">/trimestre</sub></p>
                            <ul class="mb-8 space-y-3 text-left">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Todos los beneficios del plan Básico</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso a clases grupales</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Evaluación física mensual</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Asesoría nutricional básica</li>
                            </ul>
                            <a href="#inscripcion" class="btn-primary py-3 px-6 rounded-full font-semibold inline-block">Inscribirme</a>
                        </div>
                    </div>
                    <!-- Plan VIP -->
                    <div class="swiper-slide">
                        <div class="plan-card bg-black p-8 rounded-lg text-center">
                            <h3 class="text-2xl font-bold mb-4 text-yellow-400">VIP</h3>
                            <p class="text-4xl font-bold mb-6">$100.000<sub class="text-sm">/anual</sub></p>
                            <ul class="mb-8 space-y-3 text-left">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Todos los beneficios del plan Premium</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso ilimitado a todas las clases</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Entrenamiento personalizado (4 sesiones/mes)</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Nutrición avanzada</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Descuentos en productos</li>
                            </ul>
                            <a href="#inscripcion" class="btn-primary py-3 px-6 rounded-full font-semibold inline-block">Inscribirme</a>
                        </div>
                    </div>
                    <!-- Plan Estudiante -->
                    <div class="swiper-slide">
                        <div class="plan-card bg-black p-8 rounded-lg text-center">
                            <h3 class="text-2xl font-bold mb-4 text-yellow-400">ESTUDIANTE</h3>
                            <p class="text-4xl font-bold mb-6">$10.000<sub class="text-sm">/mes</sub></p>
                            <ul class="mb-8 space-y-3 text-left">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso a sala de pesas y cardio</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Clases grupales seleccionadas</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Locker</li>
                            </ul>
                            <a href="#inscripcion" class="btn-primary py-3 px-6 rounded-full font-semibold inline-block">Inscribirme</a>
                        </div>
                    </div>
                    <!-- Plan Diario -->
                    <div class="swiper-slide">
                        <div class="plan-card bg-black p-8 rounded-lg text-center">
                            <h3 class="text-2xl font-bold mb-4 text-yellow-400">ACCESO DIARIO</h3>
                            <p class="text-4xl font-bold mb-6">$2.000<sub class="text-sm">/día</sub></p>
                            <ul class="mb-8 space-y-3 text-left">
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Acceso a sala de pesas y cardio por 1 día</li>
                                <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Locker</li>
                            </ul>
                            <a href="#inscripcion" class="btn-primary py-3 px-6 rounded-full font-semibold inline-block">Inscribirme</a>
                        </div>
                    </div>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination mt-6"></div>
                <!-- Add Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                new Swiper('.mySwiper', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    autoplay: {
                        delay: 2000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 }
                    }
                });
            </script>
        </div>
    </section>


    <!-- Galería Section -->
    <section id="galeria" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400 text-center">GALERÍA</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Sala de pesas moderna con equipos profesionales y buena iluminación" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Clase de spinning con participantes motivados y entrenador liderando" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Zona de cardio con cintas modernas y bicicletas frente a ventanales" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Entrenador personal ayudando a cliente con técnica correcta en ejercicio" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Clase grupal de crossfit en área especialmente equipada" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Área de peso libre con variedad de mancuernas y barras ordenadas" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Piscina temperada para rehabilitación y clases acuáticas" class="w-full h-48 object-cover">
                </div>
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="https://placehold.co/800x600" alt="Sala de entrenamiento funcional con equipos versátiles" class="w-full h-48 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto" class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-3xl md:text-4xl font-bold mb-12 text-yellow-400 text-center">CONTACTO</h2>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="md:w-1/2">
                    <div class="bg-gray-900 p-8 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-6 text-yellow-400">Información de Contacto</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold">Dirección:</p>
                                    <p>Calle Principal 1234, Comuna, Región</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold">Teléfono:</p>
                                    <p>+56 9 1234 5678</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold">Email:</p>
                                    <p>contacto@fernagym.cl</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold">Horario Atención:</p>
                                    <p>Lunes a Viernes: 09:00 - 21:00</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <h4 class="font-bold mb-4">Síguenos en redes sociales:</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="bg-gray-800 p-3 rounded-full hover:bg-yellow-400 hover:text-black transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                                    </svg>
                                </a>
                                <a href="#" class="bg-gray-800 p-3 rounded-full hover:bg-yellow-400 hover:text-black transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"></path>
                                    </svg>
                                </a>
                                <a href="#" class="bg-gray-800 p-3 rounded-full hover:bg-yellow-400 hover:text-black transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                    </svg>
                                </a>
                                <a href="#" class="bg-gray-800 p-3 rounded-full hover:bg-yellow-400 hover:text-black transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="bg-gray-900 p-8 rounded-lg shadow-lg h-full">
                        <h3 class="text-2xl font-bold mb-6 text-yellow-400">Ubicación</h3>
                        <div class="overflow-hidden rounded-lg h-96">
                            <iframe src="https://www.google.com/maps?q=Infante+876,+Constituci%C3%B3n,+Maule,+Chile&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="mt-6">
                            <a href="https://wa.me/56912345678" class="btn-primary py-3 px-6 rounded-full font-semibold inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.199.05-.371-.025-.52-.075-.15-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-12.244 5.69c-.346 0-.683-.045-1.012-.136a.635.635 0 0 1-.373-.25.662.662 0 0 1-.1-.524c.072-.32.22-1.401.288-1.711a7.577 7.577 0 0 1-.526-2.8c0-4.085 3.364-7.45 7.5-7.45 1.998 0 3.873.775 5.282 2.183a7.297 7.297 0 0 1 2.218 5.267c0 4.085-3.365 7.45-7.5 7.45H12c-1.233.008-2.455-.322-3.524-.97l-.445-.292-3.702.967.947-3.62-.242-.38a7.419 7.419 0 0 1-1.133-3.904zm8.772-12.572c-3.304 0-6 2.697-6 6 0 1.162.338 2.27.975 3.217l-.326 1.164 1.208-.398a5.998 5.998 0 0 0 4.778.397A5.968 5.968 0 0 0 18 13.5c0-3.303-2.696-6-6-6z"></path>
                                </svg>
                                Escríbenos por WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-gray-800 py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <img src="assets/img/logo.jpg" alt="Logo Fernagym - Gimnasio profesional en versión footer" width="150" height="50" style="width:150px;height:50px;object-fit:contain;">
                    <p class="mt-4 text-gray-400">Transforma tu cuerpo, transforma tu vida.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-yellow-400">Enlaces</h4>
                        <ul class="space-y-2">
                            <li><a href="#inicio" class="text-gray-400 hover:text-yellow-400">Inicio</a></li>
                            <li><a href="#nosotros" class="text-gray-400 hover:text-yellow-400">Nosotros</a></li>
                            <li><a href="#servicios" class="text-gray-400 hover:text-yellow-400">Servicios</a></li>
                            <li><a href="#planes" class="text-gray-400 hover:text-yellow-400">Planes</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-yellow-400">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-yellow-400">Términos y Condiciones</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-yellow-400">Política de Privacidad</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-yellow-400">Política de Cookies</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-yellow-400">Contacto</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-400">contacto@fernagym.cl</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-400">+56 9 1234 5678</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm mb-4 md:mb-0">© 2023 Fernagym. Todos los derechos reservados.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/animations.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                if (!navbar.classList.contains('scrolled')) {
                    navbar.classList.add('scrolled');
                    navbar.style.animation = 'navbarScrollAnim 0.6s cubic-bezier(.4,2,.3,1)';
                }
            } else {
                navbar.classList.remove('scrolled');
                navbar.style.animation = '';
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // e.preventDefault(); // Ahora el formulario se enviará realmente si pasa las validaciones
                // Validaciones completas
                const nombre = document.getElementById('nombre').value.trim();
                const rut = document.getElementById('rut').value.trim();
                const email = document.getElementById('email').value.trim();
                const telefono = document.getElementById('telefono').value.trim();
                const plan = document.getElementById('plan').value;
                const comprobante = document.getElementById('comprobante').files[0];
                const terminos = form.querySelector('input[type="checkbox"]:required');

                // Validación de campos vacíos
                if (!nombre || !rut || !email || !telefono || !plan || !comprobante) {
                    alert('Por favor completa todos los campos requeridos.');
                    return;
                }

                // Validación de nombre (solo letras y espacios)
                if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,}$/.test(nombre)) {
                    alert('El nombre debe contener solo letras y al menos 3 caracteres.');
                    return;
                }

                // Validación de RUT chileno
                function validarRut(rut) {
                    rut = rut.replace(/\./g, '').replace(/-/g, '');
                    if (rut.length < 8 || rut.length > 9) return false;
                    let cuerpo = rut.slice(0, -1);
                    let dv = rut.slice(-1).toUpperCase();
                    let suma = 0, multiplo = 2;
                    for (let i = cuerpo.length - 1; i >= 0; i--) {
                        suma += parseInt(cuerpo[i]) * multiplo;
                        multiplo = multiplo < 7 ? multiplo + 1 : 2;
                    }
                    let dvEsperado = 11 - (suma % 11);
                    dvEsperado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();
                    return dv === dvEsperado;
                }
                if (!validarRut(rut)) {
                    alert('El RUT ingresado no es válido.');
                    return;
                }

                // Validación de email
                const emailRegex = /^[\w-.]+@[\w-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(email)) {
                    alert('Por favor ingresa un correo electrónico válido.');
                    return;
                }

                // Validación de teléfono (solo números, mínimo 8 dígitos)
                if (!/^\d{8,15}$/.test(telefono)) {
                    alert('El teléfono debe contener solo números y al menos 8 dígitos.');
                    return;
                }

                // Validación de plan
                if (!plan) {
                    alert('Por favor selecciona un plan.');
                    return;
                }

                // Validación de comprobante (archivo PDF/JPG/PNG, máx 5MB)
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
                if (!allowedTypes.includes(comprobante.type)) {
                    alert('El comprobante debe ser un archivo PDF, JPG o PNG.');
                    return;
                }
                if (comprobante.size > 5 * 1024 * 1024) {
                    alert('El comprobante no debe superar los 5MB.');
                    return;
                }

                // Validación de términos y condiciones
                if (!terminos.checked) {
                    alert('Debes aceptar los términos y condiciones.');
                    return;
                }

                // Si todo es válido, se puede enviar el formulario (aquí puedes hacer submit real o AJAX)
                // alert('¡Formulario enviado correctamente!');
                // El formulario se enviará realmente
            }
        }
    </script>
</body>
</html>
