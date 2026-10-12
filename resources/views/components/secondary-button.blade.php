<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white border-2 border-gray-900 rounded-sm font-bold text-xs text-gray-900 uppercase tracking-widest shadow-[4px_4px_0_#000] hover:bg-gray-100 hover:shadow-[2px_2px_0_#000] hover:translate-x-0.5 hover:translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
