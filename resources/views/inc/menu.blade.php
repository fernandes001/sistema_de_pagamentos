<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/registros') }}">
        <div class="sidebar-brand-text mx-3">
            <img class="logo" src="{{ asset('assets/img/logo-dsy-paga.png') }}"/>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Modulos
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuModuloUsuarios" aria-expanded="true" aria-controls="menuModuloUsuarios">
            <i class="fas fa-fw fa-folder"></i>
            <span>Usuários</span>
        </a>

        <div id="menuModuloUsuarios" class="collapse" aria-labelledby="headingMenuModuloUsuarios" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/usuarios') }}">Listagem</a>
                <a class="collapse-item" href="{{ url('/usuarios/create') }}">Adicionar</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/registros') }}">
        <i class="fas fa-fw fa-folder"></i>
        <span>Registros</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/logs') }}">
        <i class="fas fa-fw fa-folder"></i>
        <span>Logs</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/auditoria') }}">
        <i class="fas fa-fw fa-folder"></i>
        <span>Auditoria</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuModuloClientes" aria-expanded="true" aria-controls="menuModuloClientes">
            <i class="fas fa-fw fa-folder"></i>
            <span>Clientes</span>
        </a>

        <div id="menuModuloClientes" class="collapse" aria-labelledby="headingMenuModuloClientes" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/clientes') }}">Listagem</a>
                <a class="collapse-item" href="{{ url('/clientes/create') }}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuModuloBancos" aria-expanded="true" aria-controls="menuModuloBancos">
            <i class="fas fa-fw fa-folder"></i>
            <span>Bancos</span>
        </a>

        <div id="menuModuloBancos" class="collapse" aria-labelledby="headingMenuModuloBancos" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/bancos') }}">Listagem</a>
                <a class="collapse-item" href="{{ url('/bancos/create') }}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuModuloCitarDys" aria-expanded="true" aria-controls="menuModuloCitarDys">
            <i class="fas fa-fw fa-folder"></i>
            <span>Citar - DYS</span>
        </a>

        <div id="menuModuloCitarDys" class="collapse" aria-labelledby="headingMenuModuloCitarDys" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/citardys') }}">Listagem</a>
                <a class="collapse-item" href="{{ url('/citardys/create') }}">Adicionar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuModuloBoletos" aria-expanded="true" aria-controls="menuModuloBoletos">
            <i class="fas fa-fw fa-folder"></i>
            <span>Boletos</span>
        </a>

        <div id="menuModuloBoletos" class="collapse" aria-labelledby="headingMenuModuloBoletos" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('/boletos') }}">Listagem</a>
                <a class="collapse-item" href="{{ url('/boletos/create') }}">Adicionar</a>
            </div>
        </div>
    </li>
   
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->