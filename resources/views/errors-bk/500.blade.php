<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Interruption</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        /* Original light blue background tone and gradient */
        .portal-bg {
            background-color: #eaf4fa;
            background: linear-gradient(90deg, rgba(9, 90, 139, 0.5), rgba(103, 183, 229, 0.2), rgba(103, 183, 229, 0.5));
        }
        /* Original action button color */
        .portal-btn-blue {
            background-color: #0066ff;
        }
    </style>
</head>
<body class="portal-bg min-h-screen flex flex-col justify-between px-6 py-12 md:py-24 antialiased selection:bg-blue-500 selection:text-white">

    <div class="max-w-3xl mx-auto w-full flex-grow flex flex-col justify-center items-center text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight mb-10">
            Oops! Something went wrong
        </h1>

        @if(!empty($errorMessage))
            <div class="w-full text-left bg-white/50 border border-red-200 rounded-2xl p-6 backdrop-blur-md mb-8 shadow-sm">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-300/40">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                        Exception Details
                    </span>
                </div>

                <div class="font-mono text-sm space-y-3 text-slate-800">
                    <div class="flex items-start">
                        <span class="text-slate-400 font-semibold w-24 shrink-0">Message:</span>
                        <span class="font-bold text-red-600 break-words">{{ $errorMessage }}</span>
                    </div>

                    <div class="flex items-start pt-2 border-t border-slate-300/20">
                        <span class="text-slate-400 font-semibold w-24 shrink-0">Time:</span>
                        <span class="text-slate-500 text-xs font-medium select-all">{{ now()->toDateTimeString() }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-300/20">
                        <button type="button"
                                onclick="
                                    document.getElementById('tech-details').classList.toggle('hidden');
                                    document.getElementById('chevron-icon').classList.toggle('rotate-180');
                                "
                                class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline cursor-pointer focus:outline-none py-0.5">
                            <span>View Technical Details</span>
                            <svg id="chevron-icon" class="h-3.5 w-3.5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>

                    <div id="tech-details" class="hidden space-y-3 pt-2 border-t border-dashed border-slate-300/40">
                        @if(!empty($errorFile))
                            <div class="flex items-start">
                                <span class="text-slate-400 font-semibold w-24 shrink-0">File:</span>
                                <span class="text-slate-600 text-xs break-all select-all leading-relaxed">{{ $errorFile }}</span>
                            </div>
                        @endif

                        @if(!empty($errorLine))
                            <div class="flex items-start">
                                <span class="text-slate-400 font-semibold w-24 shrink-0">Line No:</span>
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-bold border border-red-200/40">{{ $errorLine }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-sky-100 text-sky-800 mb-6 border border-sky-200">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() ?? url('/') }}" class="portal-btn-blue inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold rounded-lg text-white hover:opacity-90 shadow-md shadow-blue-300/40 transition-colors">
               Go Back
            </a>
        </div>
    </div>

</body>
</html>