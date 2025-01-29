@props([
    'question',
])

<div class="rounded dark:bg-gray-800/50 shadow shadow-blue-500/50 p-3 dark:text-gray-200">
    <span>{{ $question->question }}</span>

    <div>
        <x-form :action="route('questions.like', $question)" id="form-like-{{ $question->id }}">
            <button class="flex items-center space-x-1 text-green-600" type="submit" form="form-like-{{ $question->id }}">
                <x-icons.thumbs-up class="w-5 h-5 text-green-600 hover:text-green-900 cursor-pointer"/>
                <span>{{ $question->count_likes }}</span>
            </button>
        </x-form>

        <x-form :action="route('questions.unlike', $question)" id="form-unlike-{{ $question->id }}">
            <button class="flex items-center space-x-1 text-red-600" type="submit" form="form-unlike-{{ $question->id }}">
                <x-icons.thumbs-down class="w-5 h-5 text-red-600 hover:text-red-900 cursor-pointer"/>
                <span>{{ $question->count_unlikes }}</span>
            </button>
        </x-form>
    </div>
</div>
