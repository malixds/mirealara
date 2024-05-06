@extends('layouts.default')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

    </section>

    @if($user)
    @if($user->roles()->first()->slug !== 'worker')
    <div class="mx-auto max-w-3xl text-center">
        <h1>Вы не можете выполнять задания для этого заполните анкету</h1>
        <a href="{{route('user.profile-form', $user->id)}}"
            class="mt-5 inline-block rounded-xl bg-black px-8 py-4 font-semibold text-white [box-shadow:rgb(255,_255,_255)_6px_6px]">
            Заполнить анкету
            </a>
    </div>
    @else
    <h1>Вы можете выполнять задания</h1>
    <a href="{{route('user.profile-form', $user->id)}}"
        class="mt-5 inline-block rounded-xl bg-black px-8 py-4 font-semibold text-white [box-shadow:rgb(255,_255,_255)_6px_6px]">
        Смотреть анкету
        </a>
    @endif
    @endif

    <header class="w-full">
        <!-- Container -->
        <div class="px-5 py-16 md:px-10 max-w-7xl md:py-16 lg:py-24 mx-auto" data-aos="fade-up" data-aos-duration="900">
            <!-- Component -->
            <div
                class="w-full mx-auto max-w-7xl rounded-[48px] bg-cover bg-center bg-no-repeat py-20 px-5 text-white [box-shadow:rgb(106,_218,_255)_9px_9px] bg-[url('https://firebasestorage.googleapis.com/v0/b/flowspark-1f3e0.appspot.com/o/6391a6daa19785eb51dd3666_CTA%20(2)%20(1).svg?alt=media&token=b877deae-f9fb-4f75-ad69-cbc62e593e50')]">
                <div class="mx-auto max-w-3xl text-center">
                    <!-- Heading Div -->
                    <div class="mb-6 max-w-[720px] lg:mb-12">
                        <h2 class="mb-4 text-3xl font-semibold md:text-5xl">
                            <span
                                class="bg-cover bg-center px-4 text-white bg-[ background-image: url('https://assets.website-files.com/63904f663019b0d8edf8d57c/6391a5b04f2836ad87dcc3bc_Rectangle%20773.svg')]">Lightning
                                Fast</span>&nbsp;Webflow Dev Made Easy
                        </h2>
                        <div class="mx-auto max-w-[630px]">
                            <p class="text-[#e0e0e0]">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit ut
                                aliquam, purus sit amet luctus venenatis, lectus magna
                                fringilla urna
                            </p>
                        </div>
                    </div>
                    <!-- CTA -->
                    <a href="#"
                        class="inline-block rounded-xl bg-black px-8 py-4 font-semibold text-white [box-shadow:rgb(255,_255,_255)_6px_6px]">Get
                        Started</a>
                </div>
            </div>
        </div>
    </header>
@endsection
