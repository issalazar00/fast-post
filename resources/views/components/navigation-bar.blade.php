<nav v-if="token && user" class="navbar navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{-- {{ config('app.name', 'Laravel') }} --}}
            <img src="{{ asset('images/logo.jpeg') }}" alt="" srcset="" width="200">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link " active-class="active" to="/orders">Ordenes</router-link>
                </li>
                {{-- <li class="nav-item" v-if="validatePermission('product.index')">
                    <router-link class="nav-link " to="/products">Productos</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('category.index')">
                    <router-link class="nav-link " to="/categories">Categorias</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('brand.index')">
                    <router-link class="nav-link " to="/brands">Marcas</router-link>
                </li> --}}

                <li class="nav-item" v-if="validatePermission('tax.index')">
                    <router-link class="nav-link" to="/taxes">Iva</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('client.index')">
                    <router-link class="nav-link" to="/clients">Clientes</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('supplier.index')">
                    <router-link class="nav-link" to="/suppliers">Proveedores</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('rol.index')">
                    <router-link class="nav-link" to="/roles">Roles</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/checker">Verificador</router-link>
                </li>
                <li class="nav-item" v-if="validatePermission('user.index')">
                    <router-link class="nav-link" to="/users">Usuarios</router-link>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Configuraciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <router-link v-if="validatePermission('user.store')" class="dropdown-item" to="/configuration">
                            Configuración general</router-link>
                        <router-link class="dropdown-item " to="/products">Productos</router-link>
                        {{-- <a class="dropdown-item" href="#">Another action</a> --}}
                        {{-- <div class="dropdown-divider"></div> --}}
                        {{-- <a class="dropdown-item" href="#">Something else here</a> --}}
                        <router-link class="dropdown-item " to="/categories">Categorias</router-link>
                        <router-link class="dropdown-item " to="/brands">Marcas</router-link>
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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