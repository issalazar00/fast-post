<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">


	<!-- Scripts -->
	<script src="{{ asset('js/shortcut.js')}} "></script>
	<script src="{{ asset('js/app.js') }}" defer></script>
	

</head>

<body>
	<div id="app">
		<div id="wrapper">
			<!-- Sidebar -->
			@component('components.navigation-bar')
			@endcomponent
			<main id="content-wrapper" class="d-flex flex-column">
				<button  type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#selected_box_user">
				
				</button>
				<div class="modal" id="selected_box_user" tabindex="-1" aria-hidden="true" data-backdrop="static">
					<div class="modal-dialog">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Seleccionar Caja</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetBox">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<select name="selected_box_user" id="" class="form-control" v-model="box">
									<option value="" disabled>Seleccione una caja</option>
									<option v-for="item in listBoxes" :value="item.id" :key="item.id">
										@{{ item.name+' '+item.prefix }}
									</option>
							</select>	
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetBox">Cancelar</button>
							<button type="button" class="btn btn-primary" @click="saveBox">Guardar</button>
						</div>
						</div>
					</div>
				</div>
				<div class="justify-content-center">
					<router-view />
				</div>
				@component('components.footer')
				@endcomponent
			</main>
		</div>
	</div>

</body>

</html>