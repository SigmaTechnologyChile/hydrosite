<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} - {{ config('app.name', 'HydroSite') }}</title>

    <!-- Favicons -->
    <link href="{{asset('theme/common/img/favicon.png')}}" rel="icon">
    <link href="{{asset('theme/common/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('theme/common/img/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('theme/common/img/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('theme/common/img/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('theme/common/img/site.webmanifest')}}">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('theme/nice/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('theme/nice/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('theme/nice/assets/css/style.css')}}" rel="stylesheet">

</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{route('account.dashboard')}}" class="logo d-flex align-items-center">
        <img src="{{asset('theme/common/img/hydrosite_favicon.png')}}" alt="">
        <span class="d-none d-lg-block">HydroSite</span>
      </a>

      <i class="bi bi-list toggle-sidebar-btn"></i>

    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Busqueda general" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="{{asset('theme/nice/assets/img/messages-1.jpg')}}" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="{{asset('theme/nice/assets/img/messages-2.jpg')}}" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="{{asset('theme/nice/assets/img/messages-3.jpg')}}" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{asset('theme/nice/assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
                <h6>{{ Auth::user()->name }}</h6>
                <span>
                    @if(Auth::user()->is_admin)
                        <span class="badge bg-primary">Administrador</span>
                    @elseif(Auth::user()->is_manager)
                        <span class="badge bg-warning">Director</span>
                    @else
                        <span class="badge bg-warning">Usuario</span>
                    @endif
                </span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('orgs.index')}}">
                <i class="bi bi-person"></i>
                <span>Mis Organizaciones</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('account.profile')}}">
                <i class="bi bi-person"></i>
                <span>Mi Perfil</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('login.logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar Sesión</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>


    <!-- End Header -->
    <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

        @if(Auth::user()->is_admin)
            <li class="nav-item">
                @if($active == 'dashboard')
                <a class="nav-link" href="{{route('account.dashboard')}}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('account.dashboard')}}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard </span>
                </a>
                @endif
            </li>

            @if($active == 'orgs' OR $active == 'orgs-create')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#orgs-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-building-4-line"></i><span>Organizaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="orgs-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.create')}}"@if($active == 'orgs-create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Agregar Organización</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}"@if($active == 'orgs') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Lista Organizaciones</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#orgs-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-building-4-line"></i><span>Organizaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="orgs-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.create')}}">
                      <i class="bi bi-circle"></i><span>Agregar Organización</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}">
                      <i class="bi bi-circle"></i><span>Lista Organizaciones</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @endif

            @if($active == 'billings' OR $active == 'billings-create')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#billings-nav" data-bs-toggle="collapse" href="#">
                  <i class="bx bxs-file-doc"></i><span>Facturaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="billings-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('billings.folios.create')}}"@if($active == 'billings-create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Agregar Folios</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}"@if($active == 'billings') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Lista Folios</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#billings-nav" data-bs-toggle="collapse" href="#">
                  <i class="bx bxs-file-doc"></i><span>Facturaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="billings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('billings.folios.create')}}">
                      <i class="bi bi-circle"></i><span>Agregar Folios</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}">
                      <i class="bi bi-circle"></i><span>Lista Folios</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @endif

            @if($active == 'payments' OR $active == 'payments-create')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#payments-nav" data-bs-toggle="collapse" href="#">
                  <i class="bx bx-dollar"></i><span>Pagos</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="payments-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.create')}}"@if($active == 'payments-create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Metodos de Pago</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}"@if($active == 'payments') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Lista Ordenes</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#payments-nav" data-bs-toggle="collapse" href="#">
                  <i class="bx bx-dollar"></i><span>Pagos</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="payments-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.create')}}">
                      <i class="bi bi-circle"></i><span>Metodos de Pago</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.index')}}">
                      <i class="bi bi-circle"></i><span>Lista Ordenes</span>
                    </a>
                  </li>
                  <!-- Organizaciones -->
                </ul>
            </li>
            @endif
        @endif

        @if(Request::is('org/*'))
        <!-- Menus de Organización -->
            <li class="nav-heading">Accesos Directos</li>
            <li class="nav-item">
                @if($active == 'orgs.dashboard')
                <a class="nav-link" href="{{route('orgs.dashboard',$org->id)}}">
                  <i class="bi bi-speedometer2"></i>
                  <span>Dashboard </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.dashboard',$org->id)}}">
                  <i class="bi bi-speedometer2"></i>
                  <span>Dashboard </span>
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if($active == 'orgs.members.create')
                <a class="nav-link" href="{{route('orgs.members.create',$org->id)}}">
                 <i class="bi bi-people"></i>
                  <span>Agregar Cliente </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.members.create',$org->id)}}">
                  <i class="bi bi-people"></i>
                  <span>Agregar Cliente </span>
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if($active == 'orgs.readings.index')
                <a class="nav-link" href="{{route('orgs.readings.index',$org->id)}}">
                  <i class="bi bi-clipboard-plus"></i>
                  <span>Ingreso de Lecturas </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.readings.index',$org->id)}}">
                  <i class="bi bi-clipboard-plus"></i>
                  <span>Ingreso de Lecturas </span>
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if($active == 'orgs.payments.index')
                <a class="nav-link" href="{{route('orgs.payments.index',$org->id)}}">
                  <i class="bi bi-credit-card"></i>
                  <span>Realizar un pago </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.payments.index',$org->id)}}">
                  <i class="bi bi-credit-card"></i>
                  <span>Realizar un pago </span>
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if($active == 'orgs.notifications.index')
                <a class="nav-link" href="{{route('orgs.notifications.index',$org->id)}}">
                  <i class="bi bi-bell"></i>
                  <span>Notificaciones </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.notifications.index',$org->id)}}">
                  <i class="bi bi-bell"></i>
                  <span>Notificaciones </span>
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if($active == 'orgs.folios.create')
                <a class="nav-link" href="{{route('orgs.folios.create',$org->id)}}">
                  <i class="bi bi-receipt"></i>
                  <span>Generar Boletas</span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.folios.create',$org->id)}}">
                  <i class="bi bi-receipt"></i>
                  <span>Generar Boletas</span>
                </a>
                @endif
            </li>


            <li class="nav-heading">Módulos</li>
            @if($active == 'orgs.members.index' OR $active == 'orgs.members.create' OR $active == 'orgs.readings.index' OR $active == 'orgs.readings.histories' OR $active == 'orgs.services.index')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#module-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-folder-user-line"></i><span>Control de Clientes</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="module-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.members.create',$org->id)}}"@if($active == 'orgs.members.create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Agregar cliente</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.members.index',$org->id)}}"@if($active == 'orgs.members.index') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Lista de clientes</span>
                    </a>
                  </li>
                 <li>
                    <a href="{{route('orgs.services.index', $org->id)}}" @if($active == 'orgs.services.index') class="active" @endif>
                        <i class="bi bi-circle"></i><span>Lista de Servicios</span>
                    </a>
                </li>

                  <li class="nav-sub-heading">Gestión Medidores</li>
                  <li>
                    <a href="{{route('orgs.readings.index',$org->id)}}"@if($active == 'orgs.readings.index') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Ingreso de Lecturas</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.readings.history',$org->id)}}"@if($active == 'orgs.readings.histories') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Historial de Lecturas y Consumo</span>
                    </a>
                  </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#module-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-building-4-line"></i><span>Control de Clientes</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="module-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.members.create',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Agregar cliente</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.members.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Lista de clientes</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.services.index', $org->id)}}">
                        <i class="bi bi-circle"></i><span>Lista de Servicios</span>
                    </a>
                </li>
                  <li class="nav-sub-heading">Gestión Medidores</li>
                  <li>
                    <a href="{{route('orgs.readings.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Ingreso de lecturas</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.readings.history',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Historial de Lectura y Consumo</span>
                    </a>
                  </li>
                </ul>
            </li>
            @endif

            @if($active == 'orgs.sections.index' OR $active == 'orgs.sections.create' OR $active == 'orgs.folios.index' OR $active == 'orgs.folios.create' OR $active == 'orgs.payments.index' OR $active == 'orgs.folios.histories' OR $active == 'orgs.payments.histories')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#invoces-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-bill-line"></i><span>Facturación</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="invoces-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li class="nav-sub-heading">Tramos y Valores estandar</li>
                    <li>
                        <a href="{{route('orgs.sections.index',$org->id)}}"@if($active == 'orgs.sections.index' OR $active == 'orgs.sections.create') class="active" @endif>
                          <i class="bi bi-circle"></i><span>Cálculo de tarifas</span>
                        </a>
                    </li>
                  <li class="nav-sub-heading">DTE / Boleta Electrónica</li>
                  <li>
                    <a href="{{route('orgs.folios.create',$org->id)}}"@if($active == 'orgs.folios.index' OR $active == 'orgs.folios.create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Generar boletas</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.folios.index',$org->id)}}"@if($active == 'orgs.folios.histories') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Historial de boletas</span>
                    </a>
                  </li>
                  <li class="nav-sub-heading">Pagos</li>
                  <li>
                    <a href="{{route('orgs.payments.index',$org->id)}}"@if($active == 'orgs.payments.index') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Realizar pagos</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.payments.history',$org->id)}}"@if($active == 'orgs.payments.histories') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Historial de Pagos</span>
                    </a>
                  </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#invoces-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-file-text-line"></i><span>Facturación</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="invoces-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-sub-heading">Tramos y Valores estandar</li>
                    <li>
                        <a href="{{route('orgs.sections.index',$org->id)}}">
                          <i class="bi bi-circle"></i><span>Cálculo de tarifas</span>
                        </a>
                    </li>
                  <li class="nav-sub-heading">DTE / Boleta Electrónica</li>
                  <li>
                    <a href="{{route('orgs.folios.create',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Generar boletas</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.folios.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Historial de boletas</span>
                    </a>
                  </li>
                  <li class="nav-sub-heading">Pagos</li>
                  <li>
                    <a href="{{route('orgs.payments.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Realizar pagos</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.payments.history',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Historial de Pagos</span>
                    </a>
                  </li>
                </ul>
            </li>
            @endif


            @if($active == 'orgs.inventories.index' OR $active == 'orgs.inventories.create' OR $active == 'orgs.categories.index' OR $active == 'orgs.categories.create' OR $active == 'orgs.categories.edit')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#inventories-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-archive-line"></i><span>Inventario</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="inventories-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.inventories.create',$org->id)}}"@if($active == 'orgs.inventories.create') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Registro de inventario</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.inventories.index',$org->id)}}"@if($active == 'orgs.inventories.index') class="active" @endif>
                      <i class="bi bi-circle"></i><span>Historial de Inventario</span>
                    </a>
                  </li>
                  <li class="nav-sub-heading">Categorias</li>
                  <li>
                      <a href="{{ route('orgs.categories.create', $org->id) }}" @if($active == 'orgs.categories.create') class="active" @endif>
                        <i class="bi bi-circle"></i><span>Crear Categoría</span>
                     </a>
                  </li>
                  <li>
                     <a href="{{route('orgs.categories.index',$org->id)}}" @if($active == 'orgs.categories.index') class="active" @endif>
                        <i class="bi bi-circle"></i><span>Listado de Categorías</span>
                     </a>
                  </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#inventories-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-archive-line"></i><span>Inventario</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="inventories-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.inventories.create',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Registro de inventario</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.inventories.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Historial de Inventario</span>
                    </a>
                  </li>
                  <li class="nav-sub-heading">Categorias</li>
                 <li>
                    <a href="{{ route('orgs.categories.create', $org->id) }}" @if($active == 'orgs.categories.create') class="active" @endif>
                        <i class="bi bi-circle"></i><span>Crear Categoría</span>
                    </a>
                </li>
                  <li>
                     <a href="{{route('orgs.categories.index',$org->id)}}" @if($active == 'orgs.categories.index') class="active" @endif>
                        <i class="bi bi-circle"></i><span>Listado de Categorías</span>
                     </a>
                  </li>
                </ul>
            </li>
            @endif

            @if($active == 'orgs.locations.index' OR $active == 'orgs.locations.create')
            <li class="nav-item">
                <a class="nav-link" data-bs-target="#module-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-map-pin-line"></i><span>Ubicaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="module-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{route('orgs.locations.create',$org->id)}}"@if($active == 'orgs.locations.create') class="active" @endif>
                          <i class="bi bi-circle"></i><span>Agregar sector</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('orgs.locations.index',$org->id)}}"@if($active == 'orgs.locations.index') class="active" @endif>
                          <i class="bi bi-circle"></i><span>Lista de sectores</span>
                        </a>
                  </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#locations-nav" data-bs-toggle="collapse" href="#">
                  <i class="ri-map-pin-line"></i><span>Ubicaciones</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="locations-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                    <a href="{{route('orgs.locations.create',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Agregar sector</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{route('orgs.locations.index',$org->id)}}">
                      <i class="bi bi-circle"></i><span>Lista de sectores</span>
                    </a>
                  </li>
                </ul>
            </li>
            @endif
        @else
            <li class="nav-item">
                @if($active == 'orgs')
                <a class="nav-link" href="{{route('orgs.index')}}">
                  <i class="bi bi-grid"></i>
                  <span>Mis Organizaciones </span>
                </a>
                @else
                <a class="nav-link collapsed" href="{{route('orgs.index')}}">
                  <i class="bi bi-grid"></i>
                  <span>Mis Organizaciones </span>
                </a>
                @endif
            </li>
        @endif
        </ul>
    </aside>
    <!-- End Sidebar-->

    <main id="main" class="main">
        @if (\Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-1"></i> {!! \Session::get('success') !!}</div>
        @endif
        @if (Session::has('message'))
           <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-info-circle me-1"></i> {{ Session::get('message') }}</div>
        @endif
        @if (Session::has('warning'))
           <div class="alert alert-warning alert-dismissible fade show"><i class="bi bi-exclamation-octagon me-1"></i> {{ Session::get('warning') }}</div>
        @endif
        @if (Session::has('danger'))
           <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-octagon me-1"></i> {{ Session::get('danger') }}</div>
        @endif
        @yield('content')
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
          &copy; Copyright <strong><span>HydroSite®</span></strong>. Todos los derechos reservados
        </div>
        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Desarrollado por <strong >Sigma Technology SpA</strong>
        </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{asset('theme/common/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('theme/common/js/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/quill/quill.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('theme/nice/assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('theme/nice/assets/js/main.js')}}"></script>
    <script src="{{asset('theme/common/js/jquery.Rut.js')}}"></script>
    @yield('js')
</body>

</html>
