<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div
            class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
            <div x-data="{
                current: 0,
                images: [
                    '{{ asset('PEL_9485.webp') }}',
                    '{{ asset('PEL_9490.webp') }}',
                    '{{ asset('PEL_9493.webp') }}',
                    '{{ asset('PEL_9553.webp') }}',
                    '{{ asset('PEL_9593.webp') }}',
                    '{{ asset('PEL_9609.webp') }}',
                    '{{ asset('PEL_9628.webp') }}',
                    '{{ asset('PEL_9645.webp') }}',
                    '{{ asset('PEL_9652.webp') }}',
                    '{{ asset('PEL_9663.webp') }}',
                    '{{ asset('PEL_9679.webp') }}',
                    '{{ asset('PEL_9712.webp') }}',
                    '{{ asset('PEL_9731.webp') }}',
                    '{{ asset('PEL_9747.webp') }}',
                    '{{ asset('PEL_9848.webp') }}',
                ],
                init() {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.images.length;
                    }, 20000);
                }
            }" class="absolute inset-0 overflow-hidden">
                <template x-for="(image, index) in images" :key="index">
                    <div x-show="current === index" x-transition:enter="transition ease-in duration-1000"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-out duration-1000" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="absolute inset-0 bg-cover bg-center"
                        :style="`background-image: url('${image}');`">
                        <div class="absolute inset-0 bg-black/60"></div>
                    </div>
                </template>
            </div>
            <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                <span class="flex h-10 w-10 items-center justify-center rounded-md">
                    <x-app-logo-icon class="me-2 h-7 fill-current text-black" />
                </span>
                {{ config('app.name', 'Laravel') }}
            </a>

            @php
                $quotes = [
                    'Love is composed of a single soul inhabiting two bodies. - Aristotle',
                    'Being deeply loved by someone gives you strength, while loving someone deeply gives you courage. - Lao Tzu',
                    'The best thing to hold onto in life is each other. - Audrey Hepburn',
                    'You contain moments of my life that I will never forget. - Unknown',
                    'I love you not only for what you are, but for what I am when I am with you. - Elizabeth Barrett Browning',
                    'To love and be loved is to feel the sun from both sides. - David Viscott',
                    'Love recognizes no barriers. It jumps hurdles, leaps fences, penetrates walls to arrive at its destination full of hope. - Maya Angelou',
                    'There is no remedy for love but to love more. - Henry David Thoreau',
                    'Love is not about how many days, months, or years you have been together. Love is about how much you love each other every single day. - Unknown',
                    'I swear I couldn’t love you more than I do right now, and yet I know I will tomorrow. - Leo Christopher',
                    'Love is patient, love is kind. It does not envy, it does not boast, it is not proud. - 1 Corinthians 13:4',
                    'Above all, love each other deeply, because love covers over a multitude of sins. - 1 Peter 4:8',
                    'Let all that you do be done in love. - 1 Corinthians 16:14',
                    'We love because he first loved us. - 1 John 4:19',
                    'Be completely humble and gentle; be patient, bearing with one another in love. - Ephesians 4:2',
                    'And now these three remain: faith, hope and love. But the greatest of these is love. - 1 Corinthians 13:13',
                    'Let love and faithfulness never leave you; bind them around your neck, write them on the tablet of your heart. - Proverbs 3:3',
                    'I have found the one whom my soul loves. - Song of Solomon 3:4',
                    'Two are better than one, because they have a good return for their labor. - Ecclesiastes 4:9',
                    'For where you go I will go, and where you stay I will stay. Your people will be my people and your God my God. - Ruth 1:16',
                ];
                [$message, $author] = str(collect($quotes)->random())->explode('-');
            @endphp

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <flux:heading size="lg" class="text-white">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                    <footer>
                        <flux:heading class="text-white">{{ trim($author) }}</flux:heading>
                    </footer>
                </blockquote>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>