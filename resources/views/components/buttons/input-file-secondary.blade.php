@props(['id'])

<div class="p-0 m-0">
    <label for="{{$id}}" class=" hover:border-gray-400 hover:text-gray-500
    inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700
    active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
    dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
        {{$slot}}
    </label>
    <input
        id="{{$id}}"
        name="{{$id}}"
        type="file"
        accept="image/*"
        form="form-create-post"
        class="hidden"/>
</div>
