<section>
    <header>
        <h2 class="text-xl font-semibold text-[#A66E38]">
            Perbarui Password
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-gray-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" 
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#A66E38] focus:ring focus:ring-[#A66E38] focus:ring-opacity-50" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-gray-700" />
            <x-text-input id="update_password_password" name="password" type="password" 
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#A66E38] focus:ring focus:ring-[#A66E38] focus:ring-opacity-50" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#A66E38] focus:ring focus:ring-[#A66E38] focus:ring-opacity-50" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#A66E38] border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-[#8B5A2B] focus:bg-[#8B5A2B] active:bg-[#8B5A2B] focus:outline-none focus:ring-2 focus:ring-[#A66E38] focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Perbarui Password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >Password diperbarui.</p>
            @endif
        </div>
    </form>
</section>
