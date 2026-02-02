 @props([
     'avatar' => null,
     'author' => null,
 ])

 @if ($avatar)
     <img src="{{ $avatar }}" alt="{{ $author }}"
         class="
        w-10 h-10 rounded-full
        object-cover
        ring-1 ring-gray-200 dark:ring-gray-700
        shadow-sm
        shrink-0
    ">
 @endif
