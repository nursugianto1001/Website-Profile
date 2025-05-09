<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-badminton-blue inline-flex items-center px-4 py-2 rounded-full border border-transparent text-sm text-white font-medium tracking-widest hover:bg-badminton-blue/90 focus:outline-none focus:ring-2 focus:ring-badminton-blue focus:ring-offset-2 transition ease-in-out duration-150 transform hover:-translate-y-0.5 hover:shadow-md']) }}>
    {{ $slot }}
</button>
