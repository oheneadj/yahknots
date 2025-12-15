<div class="min-h-screen flex items-center justify-center py-6 sm:px-4 lg:px-8">
    <div class="max-w-md w-full bg-white/95 backdrop-blur-sm rounded-2xl shadow-xs border border-orange-100 overflow-hidden"
    @if($clientReference && $paymentStatus !== 'success') wire:poll.3s="checkTransactionStatus" @endif>
        @if($paymentStatus === 'success')
        <!-- Success State -->
        <div class="px-6 py-8 flex flex-col items-center justify-center text-center">
            <div class="h-24 w-24 bg-green-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
                <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 font-serif mb-3 tracking-wide">
                Thank You!
            </h2>
            <p class="text-lg text-gray-600 font-medium mb-8">
                Your contribution has been received with love.
            </p>
            <button type="button" wire:click="resetForm" class="text-sm text-[#d9aa6c] hover:text-[#c4965b] font-bold uppercase tracking-widest underline decoration-2 underline-offset-4 transition-colors">
                Send another gift
            </button>
        </div>
        @elseif($paymentStatus === 'processing')
        <!-- Processing State -->
        <div class="px-8 py-12 flex flex-col items-center justify-center text-center">
            <div class="relative w-24 h-24 mb-6">
                <!-- Outer Ring -->
                <div class="absolute inset-0 border-4 border-orange-100 rounded-full"></div>
                <!-- Spinning Ring -->
                <div class="absolute inset-0 border-4 border-[#FFDAB9] rounded-full border-t-transparent animate-spin"></div>
                <!-- Icon -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#d9aa6c] animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 font-serif mb-2 tracking-wide">
                Processing Payment
            </h2>
            <p class="text-gray-500 font-medium animate-pulse">
                Please wait while we confirm your transaction...
            </p>
            <p class="text-sm text-gray-400 mt-4 max-w-xs mx-auto">
                Check your phone for a prompt to authorize the payment.
            </p>
        </div>
        @else
        <!-- Header -->
        <div class="px-8 py-6 border-b border-orange-100 bg-orange-50/50">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-[#FFDAB9] rounded-full flex items-center justify-center shrink-0 shadow-sm border border-orange-200">
                    <svg class="h-5 w-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 font-serif tracking-wide">
                        Wedding Contribution
                    </h2>
                    <p class="text-sm text-gray-500 font-medium">
                        Thank you for celebrating with us.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if($paymentStatus === 'error')
            <div class="mb-6 bg-red-50 border border-red-100 rounded-xl p-4 animate-pulse">
                <div class="flex items-start gap-3">
                     <div class="shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                     </div>
                     <div class="flex-1">
                        <p class="text-sm text-red-800 font-bold">{{ $errorMessage }}</p>
                        <p class="text-xs text-red-600 font-mono break-all bg-red-100/50 p-2 rounded border border-red-200">
                        </p>
                        @if(!app()->environment('production') && $detailedError)
                            <div class="mt-2 text-xs text-red-600 font-mono break-all bg-red-100/50 p-2 rounded border border-red-200">
                                {{ $detailedError }}
                            </div>
                        @endif
                     </div>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="processPayment" class="space-y-6">
                
                <!-- Network Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 tracking-wide">
                        Select Network
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            'mtn-gh' => '/myn momo.jpeg', 
                            'vodafone-gh' => '/telecel.jpg', 
                            'tigo-gh' => '/airteltigo money.jpeg'
                        ] as $value => $image)
                            <button type="button" 
                                wire:click="$set('network', '{{ $value }}')"
                                class="relative flex flex-col items-center justify-center p-2 rounded-xl border-2 transition-all duration-200 focus:outline-none aspect-[4/3] overflow-hidden {{ $network === $value ? 'border-[#FFDAB9] ring-2 ring-[#FFDAB9]/50 shadow-md transform scale-[1.02]' : 'border-gray-200 hover:border-orange-200 opacity-80 hover:opacity-100 bg-white' }}">
                                
                                <img src="{{ $image }}" alt="{{ $value }}" class="w-full h-full object-contain object-center rounded-lg">
                                
                                @if($network === $value)
                                    <div class="absolute inset-0 bg-black/10 flex items-center justify-center rounded-lg">
                                        <div class="bg-white rounded-full p-1 shadow-sm">
                                            <svg class="w-4 h-4 text-[#d9aa6c] font-bold" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @error('network') 
                        <p class="mt-2 text-xs text-red-600 animate-pulse font-medium">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Personal Info Group -->
                <div class="space-y-5">
                    <!-- Name Field -->
                    <div class="group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5 tracking-wide">
                            Full Name
                        </label>
                        <input wire:model="name" type="text" id="name" 
                            class="block w-full px-4 py-3.5 rounded-xl border-gray-200 focus:border-[#FFDAB9] focus:ring-[#FFDAB9] bg-orange-50/30 sm:text-sm transition-all duration-200 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror placeholder-gray-400 shadow-xs" 
                            placeholder="e.g. Ama Badu">
                        @error('name') 
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Phone Number Field -->
                    <div>
                        <label for="number" class="block text-sm font-semibold text-gray-700 mb-1.5 tracking-wide">
                            Phone Number
                        </label>
                        <input wire:model.debounce.500ms="number" type="tel" id="number" 
                            inputmode="numeric" pattern="[0-9]*"
                            class="block w-full px-4 py-3.5 rounded-xl border-gray-200 focus:border-[#FFDAB9] focus:ring-[#FFDAB9] bg-orange-50/30 sm:text-sm transition-all duration-200 @error('number') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror placeholder-gray-400 shadow-xs" 
                            placeholder="e.g. 054 123 4567"
                            onkeypress="return /[0-9]/.test(String.fromCharCode(event.which))">
                        @error('number') 
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Amount Field -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1.5 tracking-wide">
                            Amount (GHS)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-lg font-serif">₵</span>
                            </div>
                            <input wire:model.debounce.500ms="amount" type="text" id="amount" 
                                inputmode="numeric" pattern="[0-9]*"
                                class="block w-full pl-9 pr-4 py-3.5 rounded-xl border-gray-200 focus:border-[#FFDAB9] focus:ring-[#FFDAB9] bg-orange-50/30 sm:text-lg font-medium transition-all duration-200 @error('amount') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror placeholder-gray-400 shadow-xs" 
                                placeholder="0.00" 
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                        </div>
                        @error('amount') 
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-1.5 tracking-wide">
                            Message for the Couple
                        </label>
                        <textarea wire:model="message" id="message" rows="3"
                            class="block w-full px-4 py-3.5 rounded-xl border-gray-200 focus:border-[#FFDAB9] focus:ring-[#FFDAB9] bg-orange-50/30 sm:text-sm transition-all duration-200 @error('message') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror placeholder-gray-400 shadow-xs" 
                            placeholder="Write a wish for the couple..."></textarea>
                        @error('message') 
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="button" wire:click="processPayment" 
                        class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-gray-900 bg-[#FFDAB9] hover:bg-[#ffcfb0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FFDAB9] transition-all duration-200 transform active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed tracking-wide uppercase">
                        
                        <span wire:loading.remove wire:target="processPayment">
                            SEND GIFT
                        </span>
                        
                        <span wire:loading wire:target="processPayment" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                          <span>PROCESSING...</span>
                        </span>
                    </button>
                    
                    <p class="mt-4 text-center text-xs text-gray-400">
                        <svg class="inline w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        Secure payment processing
                    </p>
                  
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
