<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary flex items-center justify-center text-sm']) }}>
    {{ $slot }}
</button>
