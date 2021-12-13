<nav class="navbar navbar-expand-lg navbar-dark bg-property">
    <a class="navbar-brand" href="#">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @can('accessAdminpanel')
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/dashboard">Dashboard</a>
                </li>
                {{--<li class="nav-item {{ Request::is('home') ? 'active' : '' }}">--}}
                    {{--<a class="nav-link" href="/home">Agent Dashboard</a>--}}
                {{--</li>--}}
                <li class="nav-item {{ Request::is('admin/agents') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/agents">Agents</a>
                </li>
                <li class="nav-item {{ Request::is('admin/transactions') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/transactions">Transactions</a>
                </li>
                <li class="nav-item {{ Request::is('admin/pipeline') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/pipeline">Pipeline</a>
                </li>
                <li class="nav-item {{ Request::is('admin/assistants') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/assistants">Assistants</a>
                </li>
                <li class="nav-item {{ Request::is('admin/emails') ? 'active' : '' }}">
                    <a class="nav-link" href="/admin/emails">Emails</a>
                </li>
            </ul>
        @endcan
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Request::is('admin/agents') ? 'active' : '' }}">

            </li>
            <li class="nav-item {{ Request::is('admin/assistants') ? 'active' : '' }}">

            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                {{-- <a class="nav-link" href="{{ url('/login') }}">Login</a> --}}
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>