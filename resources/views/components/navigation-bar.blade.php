<nav v-if="token && user" class="navbar navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link " to="/orders">Tickets</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link " to="/products">Productos</router-link>
                </li>
                <li class="nav-item" v-if= "searchPermission('category.index')">
                    <router-link class="nav-link " to="/categories">Categorias</router-link>
                </li>

                <li class="nav-item" v-if= "searchPermission('tax.index')">
                    <router-link class="nav-link" to="/impuestos">Taxes</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/clients">Clientes</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/suppliers">Proveedores</router-link>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
 
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @{{ user.name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" @click="logout">
                            Cerrar Sesión 
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>