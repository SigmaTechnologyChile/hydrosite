@extends('layouts.app', ['active'=>'faq','title'=>'Preguntas Frecuentes'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Preguntas Frecuentes</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Preguntas Frecuentes</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    
    <!-- Faq Section -->
    <section id="faq" class="faq section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item">
                <h3>Non consectetur a erat nam at lectus urna duis?</h3>
                <div class="faq-content">
                  <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Feugiat scelerisque varius morbi enim nunc faucibus?</h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
                <div class="faq-content">
                  <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">

            <div class="faq-container">

              <div class="faq-item">
                <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Tempus quam pellentesque nec nam aliquam sem et tortor?</h3>
                <div class="faq-content">
                  <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Perspiciatis quod quo quos nulla quo illum ullam?</h3>
                <div class="faq-content">
                  <p>Enim ea facilis quaerat voluptas quidem et dolorem. Quis et consequatur non sed in suscipit sequi. Distinctio ipsam dolore et.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section><!-- /Faq Section -->

@endsection