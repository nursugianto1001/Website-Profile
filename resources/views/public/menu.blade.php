@extends('layouts.public')

@section('title', 'Menu')

@section('content')
<!-- Full-screen Parallax Background -->
<div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('{{ Vite::asset('resources/images/copicop.jpg') }}');">
    <!-- Semi-transparent overlay for better text readability -->
    <div class="absolute inset-0 bg-black opacity-40"></div>
</div>

<!-- Main Content - With proper spacing for the fixed header -->
<div class="relative z-10 pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-8 text-center text-white">Our Menu</h1>

        <div class="mb-8" data-aos="fade-up" data-aos-duration="800">
            <p class="text-center text-white max-w-3xl mx-auto">
                Explore our extensive menu featuring handcrafted coffee beverages, delicious pastries, and savory meals.
                All of our items are made with quality ingredients and prepared with care.
            </p>
        </div>

        <!-- Category Filter Buttons -->
        <div class="mb-8 bg-white bg-opacity-80 p-4 rounded-lg" data-aos="fade-up">
            <div class="flex flex-wrap justify-center gap-4">
                <button class="filter-btn active px-4 py-2 rounded-full bg-amber-700 text-white" data-filter="all">
                    All Categories
                </button>
                @foreach($categories as $cat)
                <button class="filter-btn px-4 py-2 rounded-full bg-gray-200 hover:bg-amber-700 hover:text-white transition"
                    data-filter="category-{{ $cat->id }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- No pagination elements since we don't want to modify the controller -->

        @foreach($categories as $index => $category)
        @if($category->menus->count() > 0)
        <div class="mb-16 category-section" id="category-{{ $category->id }}" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ min($index * 100, 300) }}">
            <!-- Alternating section styles for visual interest -->
            <div class="{{ $index % 2 == 0 ? 'bg-white bg-opacity-80' : 'bg-black bg-opacity-70' }} backdrop-blur-md p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-6 {{ $index % 2 == 0 ? 'text-gray-900' : 'text-white' }}">{{ $category->name }}</h2>
                <div class="w-20 h-1 {{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-white' }} mb-6"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($category->menus->take(6) as $menuIndex => $menu)
                    <div class="menu-item bg-white shadow-md rounded-md overflow-hidden transition transform hover:scale-105 text-sm">
                        <img src="{{ asset('storage/' . $menu->image_path) }}"
                            alt="{{ $menu->name }}"
                            class="w-full h-48 object-cover"
                            loading="lazy">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-1">{{ $menu->name }}</h3>
                            <p class="text-gray-600 text-xs mb-2">{{ $menu->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-900">Rp{{ number_format($menu->price, 2) }}</span>
                            </div>
                        </div>
                    </div>@endforeach
                </div>

                <!-- If more than 6 items exist, add load more functionality -->
                @if($category->menus->count() > 6)
                <div class="hidden-menu-items" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                        @foreach($category->menus->skip(6) as $menu)
                        <div class="menu-item bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105">
                            <img src="{{ asset('storage/' . $menu->image_path) }}"
                                alt="{{ $menu->name }}"
                                class="w-full h-64 object-cover"
                                loading="lazy">
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">{{ $menu->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ $menu->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Rp{{ number_format($menu->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-center mt-6">
                    <button class="load-more-btn {{ $index % 2 == 0 ? 'bg-amber-700 text-white' : 'bg-white text-gray-900' }} px-6 py-2 rounded-full hover:bg-amber-800 hover:text-white transition"
                        data-category="{{ $category->id }}">
                        Show More Items
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif
        @endforeach

        <!-- Table of Contents for quick navigation -->
        <div class="fixed bottom-8 right-8">
            <button id="show-toc" class="bg-amber-700 text-white p-4 rounded-full shadow-lg hover:bg-amber-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div id="menu-toc" class="hidden bg-white p-4 rounded-lg shadow-lg max-h-96 overflow-y-auto" style="min-width: 200px;">
                <h3 class="font-bold text-lg mb-2">Category Jump</h3>
                <ul>
                    @foreach($categories as $category)
                    @if($category->menus->count() > 0)
                    <li class="mb-2">
                        <a href="#category-{{ $category->id }}" class="text-amber-700 hover:text-amber-900">
                            {{ $category->name }}
                            <span class="text-gray-500 text-sm">({{ $category->menus->count() }})</span>
                        </a>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- AOS Library Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS with reduced animation frequency
        AOS.init({
            once: true,
            offset: 120,
            duration: 800,
            disable: window.innerWidth < 768 // Disable on mobile for better performance
        });

        // Load more functionality
        const loadMoreButtons = document.querySelectorAll('.load-more-btn');
        loadMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category');
                const hiddenItems = this.closest('.category-section').querySelector('.hidden-menu-items');

                if (hiddenItems) {
                    hiddenItems.style.display = 'block';
                    this.style.display = 'none';
                }
            });
        });

        // Category filtering functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const categorySections = document.querySelectorAll('.category-section');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-amber-700', 'text-white');
                    btn.classList.add('bg-gray-200');
                });
                this.classList.add('active', 'bg-amber-700', 'text-white');
                this.classList.remove('bg-gray-200');

                // Show/hide categories
                if (filter === 'all') {
                    categorySections.forEach(section => section.style.display = 'block');
                } else {
                    categorySections.forEach(section => {
                        if (section.id === filter) {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                }

                // Refresh AOS animations
                AOS.refresh();
            });
        });

        // Table of Contents toggle
        const showTocButton = document.getElementById('show-toc');
        const menuToc = document.getElementById('menu-toc');

        showTocButton.addEventListener('click', function() {
            menuToc.classList.toggle('hidden');
        });

        // Hide TOC when clicking a category link
        const categoryLinks = document.querySelectorAll('#menu-toc a');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function() {
                menuToc.classList.add('hidden');

                // Smooth scroll to the category
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    setTimeout(() => {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }, 100);
                }
            });
        });

        // Virtual pagination for large data
        if (categorySections.length > 8) {
            // Initial visibility - show only first 8 categories
            categorySections.forEach((section, index) => {
                if (index >= 8) {
                    section.style.display = 'none';
                    section.classList.add('paginated-hidden');
                }
            });

            // Add load more categories button
            const paginationContainer = document.createElement('div');
            paginationContainer.className = 'text-center mb-16';

            const loadMoreCategoriesBtn = document.createElement('button');
            loadMoreCategoriesBtn.className = 'bg-amber-700 text-white px-8 py-3 rounded-full hover:bg-amber-800 transition';
            loadMoreCategoriesBtn.textContent = 'Load More Categories';
            loadMoreCategoriesBtn.addEventListener('click', function() {
                const hiddenCategories = document.querySelectorAll('.paginated-hidden');
                hiddenCategories.forEach((section, index) => {
                    if (index < 4) { // Load next 4 categories
                        section.style.display = 'block';
                        section.classList.remove('paginated-hidden');
                    }
                });

                // Hide button if no more hidden categories
                if (document.querySelectorAll('.paginated-hidden').length === 0) {
                    this.style.display = 'none';
                }

                // Refresh AOS
                AOS.refresh();
            });

            paginationContainer.appendChild(loadMoreCategoriesBtn);

            // Add after the 8th category
            if (categorySections[7]) {
                categorySections[7].parentNode.insertBefore(paginationContainer, categorySections[7].nextSibling);
            }
        }

        // Lazy load images implementation
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');

            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src || img.src;

                        if (src) {
                            img.src = src;
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            lazyImages.forEach(img => {
                imageObserver.observe(img);
            });
        }
    });
</script>
@endsection
