<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Graficadores') }}
        </h2>
    </x-slot>
    <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    
    #editorjs {
      padding: 20px;
      height: 100vh;
      background: #fff; /* Fondo blanco */
      border: 1px solid #ddd; /* Borde gris */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
      border-radius: 8px;
    }

    .cdx-input {
      font-size: 16px;
      line-height: 1.5;
      color: #333; /* Color de texto oscuro */
    }

    .cdx-toolbar__button {
      background-color: #007bff; /* Color de fondo de botones */
      color: white; /* Color del texto de los botones */
    }

    .cdx-toolbar__button:hover {
      background-color: #0056b3; /* Color de fondo al pasar el ratón */
    }
    
    button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    
    button:hover {
      background-color: #218838;
    }

    </style>
     <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="    height: 1500px;" >
                    <h3 class="text-xl font-semibold mb-4" >PORTAL GRAFICADOR </h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div id="blocks" style=" top:0; left:0;  background:#f5f5f5; overflow:auto;"></div>
                            <div class="panel__top" style="margin-bottom: 10px;"></div> <!-- 👈 AQUI -->
                            <div id="gjs" style="height:100vh; border:1px solid #ccc;"></div>
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

<!-- Cargar la librería cliente de socket.io -->
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

<script src="https://unpkg.com/grapesjs"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic"></script>

<script>

const editor = grapesjs.init({
    container: '#gjs',
    fromElement: true,
    height: '100vh',
    width: 'auto',
    storageManager: false, // Para que no guarde en localstorage automáticamente

    // Activamos los paneles básicos
    panels: {
      defaults: [
        {
          id: 'panel-top',
          el: '.panel__top',
          buttons: [
            {
              id: 'visibility',
              active: true, // Hacerlo activo por defecto
              label: '<u>Mostrar</u>',
              command: 'sw-visibility', // Mostrar/ocultar componentes
            },
            {
              id: 'export',
              className: 'btn-open-export',
              label: 'Exportar',
              command: 'export-template',
              context: 'export-template',
            },
            {
              id: 'undo',
              className: 'btn-undo',
              label: 'Deshacer',
              command: 'core:undo',
            },
            {
              id: 'redo',
              className: 'btn-redo',
              label: 'Rehacer',
              command: 'core:redo',
            },
          ],
        },
      ],
    },

    blockManager: {
  appendTo: '#blocks', // a dónde mandar los bloques
  blocks: [
    {
      id: 'section', // id único
      label: '<b>Sección</b>',
      attributes: { class: 'gjs-block-section' },
      content: `<section><h1>Hola Mundo</h1><p>Texto de prueba.</p></section>`,
    },
    {
      id: 'text',
      label: 'Texto',
      content: '<div data-gjs-type="text">Insertar texto aquí</div>',
    },
    {
      id: 'image',
      label: 'Imagen',
      select: true,
      content: { type: 'image' },
      activate: true,
    },
    // Añadir más bloques aquí
    {
      id: 'button', // Nuevo bloque de botón
      label: 'Botón',
      content: `<button class="btn btn-primary">Haz clic aquí</button>`,
    },
    {
      id: 'card', // Nuevo bloque de tarjeta
      label: 'Tarjeta',
      content: `
        <div class="card" style="width: 18rem;">
          <img src="https://via.placeholder.com/150" class="card-img-top" alt="Imagen de tarjeta">
          <div class="card-body">
            <h5 class="card-title">Título de tarjeta</h5>
            <p class="card-text">Texto de ejemplo para la tarjeta.</p>
            <a href="#" class="btn btn-primary">Ir a algún lado</a>
          </div>
        </div>
      `,
    },
    {
      id: 'video', // Nuevo bloque de video
      label: 'Video',
      content: `<div class="embed-responsive embed-responsive-16by9">
                  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>
                </div>`,
    },
    {
      id: 'form', // Nuevo bloque de formulario
      label: 'Formulario',
      content: `<form>
                  <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" placeholder="Tu nombre">
                  </div>
                  <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="Tu correo">
                  </div>
                  <button type="submit" class="btn btn-primary">Enviar</button>
                </form>`,
    },
  ],
}


   
  });


  // Activar el evento del botón de exportar
editor.Panels.addButton('options', [{
  id: 'export',
  className: 'btn-open-export',
  label: 'Exportar',
  command: 'export-template',
  context: 'export-template',
}]);



    // Comando para exportar los archivos de Angular
  





   // Conectarse al servidor WebSocket
   const socket = io('http://localhost:3000', {
        transports: ['websocket'],
    });

    socket.on('connect', () => {
        console.log('Conectado al servidor WebSocket');
    });

    // Escuchar mensajes desde el servidor
    socket.on('mensaje', (data) => {
        console.log('Mensaje del servidor:', data);

    });
    let isUpdating = false;

// Emite un cambio cada vez que se agrega un nuevo componente
editor.on('component:add', (component) => {
  if (!isUpdating) {
    console.log('Nuevo componente agregado:', component);
    socket.emit('graph-update', { data: component.toJSON() });  // Enviar datos del componente
  }
});

// Emite un cambio cada vez que se agrega un nuevo componente
editor.on('component:add', (component) => {
  console.log('Nuevo componente agregado:', component);
  
  // Obtener toda la estructura del gráfico
  
  if (!isUpdating) {
    console.log('Nuevo componente agregado:', component);
    const fullData = editor.getComponents();  // Obtener todos los componentes del editor

// Emitir el gráfico completo al servidor para que se envíe a otros clientes
socket.emit('graph-update', { data: fullData });
  }

});



// Emite un cambio cada vez que se actualiza un componente
editor.on('component:update', (model) => {
  if (!isUpdating) {
    const fullData = editor.getComponents();  // Obtener todos los componentes del editor

// Emitir el gráfico completo al servidor para que se envíe a otros clientes
socket.emit('graph-update', { data: fullData });
  }
});

// Si el diseño del gráfico cambia, también puedes emitir un evento
editor.on('storage:update', (data) => {
  if (!isUpdating) {
    console.log('Diseño actualizado:', data);
    socket.emit('graph-update', { data: data });  // Enviar datos del gráfico completo
  }
});

// Escuchar las actualizaciones del gráfico desde el servidor WebSocket
socket.on('graph-update', (data) => {
  console.log('Recibiendo actualización del gráfico de otro cliente:', data);

  // Evitar que se emita un cambio cuando estamos recibiendo una actualización de otro cliente
  isUpdating = true;
  editor.setComponents(data.data);  // Ajusta esto si tu estructura de datos es diferente
  isUpdating = false;  // Restablecer la bandera después de actualizar
});

   

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.8.0/jszip.min.js"></script>

    <!-- En tu archivo Blade -->
<script src="{{ asset('exportcomponenteangular.js') }}"></script>


</x-app-layout>
