@extends('client.layout.master')

@section('content')
    <!-- Hero Section -->
    <div class="container container-post" id="hero">
        <div class="row justify-content-end">
            <div class="col-lg-6 hero-img-container">
                <div class="hero-img">
                    <img src="{{ asset(getPathImage(!empty($post->post_image) ? $post->post_image : '')) }}"
                        alt="{{ !empty($post->post_title) ? $post->post_title : __('common.lbl_default_alt') }}">
                </div>
            </div>

            <div class="col-lg-9">
                <div class="hero-title">
                    <h1>{{ !empty($post->post_title) ? $post->post_title : '' }}</h1>
                </div>

            </div>
            <!-- hero meta -->
            <div class="col-lg-6">
                <div class="hero-meta">
                    <div class="author">
                        <div class="author-img"><img src="{{ asset('client/img/author-img.png') }}"
                                alt="{{ __('common.lbl_default_alt') }}"></div>
                        <div class="author-meta">
                            <span class="author-name">{{ !empty($post->user->name) ? $post->user->name : '' }}</span>
                            <span class="author-tag">{{ !empty($post->user->email) ? $post->user->email : '' }}</span>
                        </div>
                    </div>
                    <span
                        class="date mt-2">{{ !empty($post->updated_at) ? formatDate($post->updated_at, 'jS F Y') : '' }}</span>

                    <div class="tags mt-2">
                        <a class="tag">{{ !empty($post->category->name) ? $post->category->name : '' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mt-5 container-post" id="content">
        <div class="row justify-content-center">
            <!-- Share buttons -->
            <div class="col-lg-1 text-left mb-3 fixed" id="social-share">
                <a class="btn  btn-light m-2" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="btn  btn-light m-2" href="#"><i class="fab fa-google"></i></a>
                <a class="btn  btn-light m-2" href="#"><i class="fab fa-twitter"></i></a>
            </div>

            <!-- the content -->
            <div class="col-xl-7 col-lg-10 col-md-12">
                {!! !empty($post->post_description) ? $post->post_description : '' !!}
            </div>

            <div class="col-lg-10 mt-3">
                <hr>
            </div>
        </div>
    </div>

    <div class="container mt-3 mb-5" id="article-grid">
        <div class="row">
            @if ($postRelation->count() > 0)
                @foreach ($postRelation as $post)
                    <div class="col-xl-6 col-lg-12 text-center">
                        <a href="{{ route('post-detail', ['slug' => $post->post_slug]) }}">
                            <div class="article-card">
                                <div class="article-img">
                                    <img src="{{ asset(getPathImage(!empty($post->post_image) ? $post->post_image : '')) }}"
                                        alt="{{ !empty($post->post_title) ? $post->post_title : __('common.lbl_default_alt') }}">
                                </div>
                                <div class="article-meta text-left">
                                    <p style="margin: 0;">
                                        <i>{{ !empty($post->updated_at) ? formatDate($post->updated_at, 'jS F Y') : '' }}</i>
                                    </p>
                                    <h2>{{ !empty($post->post_title) ? $post->post_title : '' }}</h2>
                                    <div class="tags">
                                        <a class="tag">
                                            {{ !empty($post->category->name) ? $post->category->name : '' }}</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-xl-6 col-lg-12 text-center">
                    <center><span>{{ __('post.lbl_not_data_available') }}</span></center>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('style-extend')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.css">
    <link rel="stylesheet" href="{{ asset('common/style/style.css') }}">
    <style>
        .container-post img {
            max-width: 100% !important;
            max-height: 550px !important;
        }

        pre {
            background-color: #1d1f21;
            color: #c5c8c6;
        }

        code {
            color: #c5c8c6;
        }

        .language-php .token.string {
            color: #b5cea8;
        }

        .language-php .token.comment,
        .language-php .token.prolog,
        .language-php .token.doctype,
        .language-php .token.cdata {
            color: #8e908c;
        }
    </style>
@endsection

@section('script-extend')
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Prism.highlightAll();
        });
    </script>
@endsection
