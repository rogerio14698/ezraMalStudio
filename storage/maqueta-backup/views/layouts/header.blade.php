<header class="navbar" id="navbar">
    <div class="container navbar__inner">
        <a href="#inicio" class="navbar__brand">
            <span class="navbar__brand-dot"></span>
            <span>ATELIER // LIBRE</span>
        </a>

        <button class="navbar__toggle" id="navToggle" aria-label="Abrir menú" aria-expanded="false">
            <span></span>
            <span></span>
        </button>
        @if (Route::has('login'))
            <nav class="navbar__menu" id="navMenu">
                @auth
                    <!--Cambiar rutas a las que correspondan -->
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
</header>
