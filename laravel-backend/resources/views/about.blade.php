<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('À propos') }}
        </h2>
    </x-slot>

    <div class="py-12 m-">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4">
            <div class="bg-gradient-to-br from-gray-900 to-blue-900 overflow-hidden shadow-lg sm:rounded-lg text-white">
                <div class="p-8 futuristic-container">
                    <h3 class="text-lg mb-6 fade-in mt-4 text-center">{{ __("Merci pour votre travail acharné et votre dévouement.") }}</h3>
                    <br>

                    <h4 class="text-2xl font-semibold mb-4 glow-text">{{ __("Développeurs :") }}</h4>
                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 m-4">
                        @foreach([
                            ['name' => 'OUGDA Ibrahim Dayende', 'email' => 'o.ibrahimdayende@gmail.com'],
                            ['name' => 'DIALLO Djeneba', 'email' => 'djeneba.diallo@example.com'],
                            ['name' => 'BATIONO Jonathan', 'email' => 'jonathan.bationo@example.com'],
                            ['name' => 'GUISSOU Ali', 'email' => 'ali.guissou@example.com'],
                            ['name' => 'OUEDRAOGO Moumouni', 'email' => 'moumouniouedraogotech@gmail.com']
                        ] as $developer)
                            <li class="bg-blue-800 bg-opacity-50 p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <p class="font-semibold">{{ $developer['name'] }}</p>
                                <p class="text-sm text-blue-200"><a href="mailto:{{ $developer['email'] }}?subject=Your Library Management Projet CS26">{{ $developer['email'] }}</a></p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <style>
        .futuristic-container {
            background: linear-gradient(45deg, rgba(59, 179, 209, 0.7) 0%, rgba(0,0,128,0.7) 100%);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        .glow-text {
            text-shadow: 0 0 10px rgba(255,255,255,0.7);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px rgba(255,255,255,0.7);
            }
            to {
                text-shadow: 0 0 20px rgba(255,255,255,0.7), 0 0 30px rgba(255,255,255,0.7);
            }
        }

        .fade-in {
            animation: fadeIn 1.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        ul li {
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
            background-color: rgba(23, 67, 67, 0.263);
        }

        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        ul li:nth-child(1) { animation-delay: 0.1s; }
        ul li:nth-child(2) { animation-delay: 0.2s; }
        ul li:nth-child(3) { animation-delay: 0.3s; }
        ul li:nth-child(4) { animation-delay: 0.4s; }
        ul li:nth-child(5) { animation-delay: 0.5s; }
    </style>
</x-app-layout>