<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action = "{{ route('questions.store') }}" method = "post">
                @csrf

                <div class="mb-4">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Your message
                    </label>

                        <textarea name="question" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50
                        rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700
                        dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                        dark:focus:border-blue-500" placeholder="Leave your question here...">{{ old('question') }}
                        </textarea>
                    @error('question')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror

                </div>
                    <button type="submit" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium
                        text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500
                        group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none
                        focus:ring-purple-200 dark:focus:ring-purple-800">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        Save
                        </span>
                    </button>

                    <button type="reset" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium
                        text-gray-900 rounded-lg group bg-gradient-to-br from-purple-500 to-pink-500
                        group-hover:from-purple-500 group-hover:to-pink-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none
                        focus:ring-purple-200 dark:focus:ring-purple-800">
                        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        Cancel
                        </span>
                    </button>
            </form>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
