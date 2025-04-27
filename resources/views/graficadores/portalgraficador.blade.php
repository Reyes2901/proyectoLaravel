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
   
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" style="    height: 1500px;" >
                    <h3 class="text-xl font-semibold mb-4" >PORTAL GRAFICADOR </h3>


                       <!-- Contenedor para el Editor.js -->
<div id="editorjs"></div>

<!-- Botón para guardar el contenido -->
<button onclick="saveData()">Guardar contenido</button>


<button id="btnTransmit">Transmitir</button>

<h3>Log de WebSocket</h3>
<textarea id="log" readonly></textarea>
<!-- Cargar Editor.js -->

                   
                </div>
            </div>
        </div>
    </div>
<!-- Instalación rápida en tu web -->

<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

<script src="https://cdn.socket.io/4.0.1/socket.io.min.js"></script>


<!-- Plugins de Editor.js -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>

<script>
  // Inicializar Editor.js

  const editor = new EditorJS({
    /** ID del contenedor */
    holder: 'editorjs',
    /** Herramientas disponibles */
    tools: {
      header: {
        class: Header,
        inlineToolbar: true
      },
      
      paragraph: {
        class: Paragraph,
        inlineToolbar: true
      }
    },
    /** Datos iniciales */
    data: {
      blocks: [
        {
          type: "header",
          data: {
            text: "Bienvenido a Editor.js",
            level: 2
          }
        },
        {
          type: "paragraph",
          data: {
            text: "Este es un ejemplo sencillo para probar Editor.js"
          }
        }
      ]
    }
  });

  /** Función para guardar el contenido */
  function saveData() {
    editor.save().then((outputData) => {
      console.log("Contenido guardado: ", outputData);
      alert("¡Contenido guardado en consola!");
      // Aquí puedes procesar el JSON y convertirlo a HTML
      exportToHTML(outputData);
     // socket.send(JSON.stringify({ type: 'editor-update', blocks: outputData.blocks }));
      
      
    }).catch((error) => {
      console.log('Error al guardar: ', error);
    });
  }

  // Función para exportar JSON a HTML
  function exportToHTML(data) {
    let htmlContent = '';
    data.blocks.forEach(block => {
      if (block.type === 'header') {
        htmlContent += `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
      } else if (block.type === 'paragraph') {
        htmlContent += `<p>${block.data.text}</p>`;
      } else if (block.type === 'list') {
        htmlContent += `<ul>`;
        block.data.items.forEach(item => {
          htmlContent += `<li>${item}</li>`;
        });
        htmlContent += `</ul>`;
      }
    });
    // Aquí se muestra el HTML generado (puedes enviarlo a algún lugar o procesarlo más)
    console.log("HTML Exportado: ", htmlContent);
  }

     // Conectar con el servidor WebSocket
  //const socket = io('http://localhost:8091');
  //const socket = new WebSocket('wss://localhost:8091');

  // Conéctate a tu propio servidor en ws://
  //const socket = new WebSocket('ws://localhost:8091');
   // 2) Conecta al servidor WebSocket Node
   const WS_URL = 'ws://localhost:8091'; // ajusta al host/puerto de tu servidor
    const socket = new WebSocket(WS_URL);

    const log = document.getElementById('log');
    function writeLog(msg){
      log.value += msg + "\n";
      log.scrollTop = log.scrollHeight;
    }

    socket.onopen = () => writeLog(`✔️ Conectado a ${WS_URL}`);
    socket.onerror = e => writeLog(`⚠️ Error WS: ${e.message || e}`);
    socket.onclose = () => writeLog('❌ Desconectado del servidor');

    // 3) Al recibir mensaje (asumimos JSON con { blocks: [...] })
    socket.onmessage = async (ev) => {
      writeLog('📨 Recibido del servidor: ' + ev.data);
      console.log("recvibido", ev.data)
      try {
        //const msg = JSON.parse(ev.data);
   /*     if (msg.blocks) {
          await editor.isReady;
          editor.blocks.clear();
          editor.blocks.render(msg.blocks);
        }*/
      } catch (err) {
        console.error('No es JSON válido:', err);
      }
    };

    // 4) Transmitir al servidor
    document.getElementById('btnTransmit').onclick = async () => {
      const out = await editor.save();
      const payload = JSON.stringify({ blocks: out.blocks });
      socket.send("mensja e para todos ");
      writeLog('📤 Enviado al servidor: ' + payload);
    };

    </script>

</x-app-layout>
