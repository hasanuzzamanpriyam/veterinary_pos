<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-t from-green-400 to-blue-500 dark:bg-gray-900" style=" background: linear-gradient(to bottom, #0099ff 0%, #99ff33 99%);">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg" style="background: linear-gradient(to bottom, #0099ff 0%, #ccccff 99%);"> 
        {{ $slot }}
    </div>
</div>
