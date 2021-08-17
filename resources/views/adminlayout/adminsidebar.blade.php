<!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a style="height:50px !important;" class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin/dashboard">
          <div class="sidebar-brand-icon">
            <img style="height: 37px;" src="{{asset('assets/admin/img/unud.png')}}">
          </div>
          <div style="font-size: 20px" class="sidebar-brand-text mx-3">SIM Dosen</div>
        </a>
        
        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <div class="sidebar-brand d-flex align-items-center justify-content-right" href="/admin">
          <div style="margin-left: 10px;" class="sidebar-brand-icon">
            <img class="border" style="height:60px;width:50px;" src="{{asset('assets/admin/img/4x6.jpg')}}">
          </div>
          <div style="font-size: 10px !important;margin-left:10px;" class="sidebar-brand-text my-3">
            @if(is_null($profiledata->gelar_belakang))
              {{$profiledata->gelar_depan}}. {{$profiledata->nama}}
            @elseif(is_null($profiledata->gelar_depan))
              {{$profiledata->nama}}, {{$profiledata->gelar_belakang}}
            @else
              {{$profiledata->gelar_depan}}. {{$profiledata->nama}}, {{$profiledata->gelar_belakang}}
            @endif
            <br>
            @if($profiledata->role != '[]')
              @foreach($role as $rs)
                  @if($rs->id == $profiledata->role->last()->id_role)
                      <!-- <td></td> -->
                      <span class="badge badge-pill badge-success">{{$rs->role}}</span> 
                      @break
                  @endif
              @endforeach
            @else
              <span class="badge badge-secondary">No Role</span></h1>
            @endif
          </div>
        </div>
        <!-- Divider -->
        <hr style="margin-top: 20px" class="sidebar-divider my-0">
        
        <li class="nav-item {{ Request::is('admin/masterdata*') ? 'active' : '' }}">
          <a class="nav-link {{ Request::is('admin/masterdata*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-house-user"></i>
            <span>Master Data</span>
          </a>
          <div id="collapseMaster" class="collapse {{ Request::is('admin/masterdata*') ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item {{ Request::is('admin/masterdata/fakultas*') ? 'active' : '' }}" href="{{route('masterdata-fakultas-index')}}">Fakultas</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/jabatanfungsional*') ? 'active' : '' }}" href="{{route('masterdata-jabatanfungsional-index')}}">Jabatan Fungsional</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/kategoripenelitian*') ? 'active' : '' }}" href="{{route('masterdata-kategoripenelitian-index')}}">Kategori Penelitian</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/kategoripengabdian*') ? 'active' : '' }}" href="{{route('masterdata-kategoripengabdian-index')}}">Kategori Pengabdian</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/pangkatpns*') ? 'active' : '' }}" href="{{route('masterdata-pangkatpns-index')}}">Pangkat PNS</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/prodi*') ? 'active' : '' }}" href="{{route('masterdata-prodi-index')}}">Prodi</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/statusdosen*') ? 'active' : '' }}" href="{{route('masterdata-statusdosen-index')}}">Status Dosen</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/statuskeaktifan*') ? 'active' : '' }}" href="{{route('masterdata-statuskeaktifan-index')}}">Status Keaktifan</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/statuskepegawaian*') ? 'active' : '' }}" href="{{route('masterdata-statuskepegawaian-index')}}">Status Kepegawaian</a>
              <a class="collapse-item {{ Request::is('admin/masterdata/tahunajaran*') ? 'active' : '' }}" href="{{route('masterdata-tahunajaran-index')}}">Tahun Ajaran</a>
            </div>
          </div>
        </li>

        <li class="nav-item {{ Request::is('admin/datauser*') ? 'active' : '' }}">
          <a class="nav-link {{ Request::is('admin/datauser*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseDataUser" aria-expanded="{{ Request::is('admin/datauser*') ? 'true' : 'false' }}" aria-controls="collapseDataUser">
            <i class="fas fa-fw fa-user"></i>
            <span>Management Data User</span>
          </a>
          <div id="collapseDataUser" class="collapse {{ Request::is('admin/datauser*') ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item {{ Request::is('admin/datauser/dosen*') ? 'active' : '' }}" href="/admin/datauser/dosen/listdosen">Data Dosen</a>
              <a class="collapse-item" href="utilities-border.html">Data Pegawai</a>
            </div>
          </div>
        </li>

        {{-- <!-- Nav Item - Dashboard -->
        <li class="nav-item">
          <a class="nav-link" href="/admin">
            <i class="fas fa-fw fa-user"></i>
            <span>Management Data Dosen</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/admin">
            <i class="fas fa-fw fa-user"></i>
            <span>Management Data Pegawai</span></a>
        </li> --}}
        <!-- Divider -->
        <!--<hr class="sidebar-divider">-->
  
        <!-- Heading -->
        <!--<div class="sidebar-heading">
          Interface
        </div>-->
  
        <!-- Nav Item - Pages Collapse Menu 
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Riwayat</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Custom Components:</h6>
              <a class="collapse-item" href="buttons.html">Buttons</a>
              <a class="collapse-item" href="cards.html">Cards</a>
            </div>
          </div>
        </li> -->
  
        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item {{ Request::is('admin/penelitian*') ? 'active' : '' }}">
          <a class="nav-link {{ Request::is('admin/penelitian*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="{{ Request::is('admin/penelitian') ? 'true' : 'false' }}" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Data Karya Ilmiah</span>
          </a>
          <div id="collapseUtilities" class="collapse {{ Request::is('admin/penelitian*') ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item {{ Request::is('admin/penelitian*') ? 'active' : '' }}" href="{{route('penelitian-list')}}">Penelitian</a>
              <a class="collapse-item" href="utilities-border.html">Pengabdian</a>
            </div>
          </div>
        </li>
  
        <!-- Divider -->
        <!--<hr class="sidebar-divider">-->
  
        <!-- Heading -->
        <!--<div class="sidebar-heading">
          Addons
        </div>-->
  
        <!-- Nav Item - Pages Collapse Menu -->
        <!-- <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRemu" aria-expanded="true" aria-controls="collapseRemu">
            <i class="fas fa-fw fa-money-check"></i>
            <span>Pengabdian Masyarakat</span>
          </a>
          <div id="collapseRemu" class="collapse" aria-labelledby="headingRemu" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Login Screens:</h6>
              <a class="collapse-item" href="login.html">Login</a>
              <a class="collapse-item" href="register.html">Register</a>
              <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
              <div class="collapse-divider"></div>
              <h6 class="collapse-header">Other Pages:</h6>
              <a class="collapse-item" href="404.html">404 Page</a>
              <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
          </div>
        </li> -->
  
        <!-- Nav Item - Charts -->
        <!--<li class="nav-item">
          <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
        </li>-->
  
        <!-- Nav Item - Tables -->
        <!--<li class="nav-item">
          <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
        </li>-->
        <!--<li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSkp" aria-expanded="true" aria-controls="collapseSkp">
            <i class="fas fa-fw fa-folder"></i>
            <span>SKP</span>
          </a>
          <div id="collapseSkp" class="collapse" aria-labelledby="headingSkp" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Custom Utilities:</h6>
              <a class="collapse-item" href="utilities-color.html">Colors</a>
              <a class="collapse-item" href="utilities-border.html">Borders</a>
              <a class="collapse-item" href="utilities-animation.html">Animations</a>
              <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
          </div>
        </li>-->
        <li class="nav-item">
          <a class="nav-link" href="{{Route('kompetensi-list')}}">
            <i class="fas fa-fw fa-list"></i>
            <span>Kompetensi</span></a>
        </li>
        <li class="nav-item {{ Request::is('admin/botsetting*') ? 'active' : '' }}">
          <a class="nav-link" href="{{route('botsetting-index')}}">
            <i class="fas fa-fw fa-robot"></i>
            <span>Bot Setting</span></a>
        </li>
        <li class="nav-item {{ Request::is('admin/role*') ? 'active' : '' }}">
          <a class="nav-link" href="{{route('roleset-index')}}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Role Setting</span></a>
        </li>
        <li class="nav-item {{ Request::is('admin/report*') ? 'active' : '' }}">
          <a class="nav-link" href="{{route('report-index')}}">
            <i class="fas fa-fw fa-file"></i>
            <span>Report</span></a>
        </li>
        {{-- <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseKI" aria-expanded="true" aria-controls="collapseKI">
            <i class="fas fa-fw fa-list"></i>
            <span>Kompetensi</span>
          </a>
          <div id="collapseKI" class="collapse" aria-labelledby="headingKI" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Custom Utilities:</h6>
              <a class="collapse-item" href="utilities-color.html">Colors</a>
              <a class="collapse-item" href="utilities-border.html">Borders</a>
              <a class="collapse-item" href="utilities-animation.html">Animations</a>
              <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
          </div>
        </li> --}}
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
  
        <!-- Sidebar Toggler (Sidebar) -->
        <!--<div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>-->
  
      </ul>
      <!-- End of Sidebar -->