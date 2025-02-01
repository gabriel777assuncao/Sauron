<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __('Vote for a question here!') }}
        </x-header>
    </x-slot>

    <x-container>
        <div class="dark: text-gray-400 uppercase font-bold mb-1">Questions list</div>

        <div class="dark:text-gray-400 space-y-4">
            @foreach($questions as $item)
                <x-question :question="$item"/>
            @endforeach
        </div>
    </x-container>
</x-app-layout>
