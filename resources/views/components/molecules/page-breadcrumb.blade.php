@props(['title'])

{{-- Gerold breadcrumb hero: background image + dark overlay, centered title and trail --}}
<section>
    <div class="relative z-[1] bg-cover bg-center bg-no-repeat pt-[150px] pb-[50px] after:absolute after:top-0 after:left-0 after:-z-[1] after:h-full after:w-full after:bg-[#140c1c] after:opacity-70 md:pt-40 md:pb-[60px] lg:pt-[200px] lg:pb-[100px]"
         style="background-image: url('{{ asset('vendor/gerold/img/breadcrumb/breadcrumb-bg.jpg') }}')">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col items-center">
                <h1 class="mb-[15px] text-center text-[35px] font-bold text-white md:text-[40px] lg:text-[50px]">
                    {{ $title }}
                </h1>

                <ul class="flex items-center gap-x-[10px]">
                    <li class="group relative">
                        <a href="{{ route('home') }}"
                           class="relative z-0 font-medium capitalize text-white after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-white after:transition-all after:duration-500 group-hover:after:w-full">
                            {{ __('nav.home') }}
                        </a>
                    </li>
                    <li>
                        <p class="flex items-center gap-[10px] font-medium capitalize text-white">
                            <span aria-hidden="true">&rarr;</span> {{ $title }}
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
