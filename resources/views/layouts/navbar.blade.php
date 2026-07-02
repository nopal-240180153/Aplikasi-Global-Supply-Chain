<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">

    <div class="container-fluid">

        <button class="btn btn-outline-primary d-lg-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMobile">

            <i class="bi bi-list"></i>

        </button>

        <div>

            <h5 class="mb-0 fw-bold">
                Sistem Monitoring Risiko Rantai Pasok Global
            </h5>

            <small class="text-muted">
                Dashboard Monitoring Global Supply Chain
            </small>

        </div>

        <div class="ms-auto d-flex align-items-center">

            <div class="dropdown">

                <button class="btn btn-light border dropdown-toggle"
                        data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle"></i>

                    {{ Auth::user()->name }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>

                        <a class="dropdown-item"
                           href="{{ route('profile.edit') }}">

                            <i class="bi bi-person"></i>

                            Profil

                        </a>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <form method="POST"
                              action="{{ route('logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item">

                                <i class="bi bi-box-arrow-right"></i>

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>