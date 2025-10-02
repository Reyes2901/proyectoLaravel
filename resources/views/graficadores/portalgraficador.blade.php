<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Graficador UML Clases (mxGraph + Socket.IO)') }}
        </h2>
    </x-slot>

    <style>
        #uml-container {
            display: flex;
            height: 90vh;
        }

        #blocks {
            width: 250px;
            background: #14552d;
            color: white;
            padding: 10px;
            overflow-y: auto;
        }

        #blocks h4 {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .block-item {
            background: #1f7a47;
            margin: 8px 0;
            padding: 8px;
            border-radius: 4px;
            cursor: grab;
            user-select: none;
        }

        #graphContainer {
            flex: 1;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        #exportBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        #exportBtn:hover {
            background-color: #218838;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">PORTAL UML CLASES (mxGraph + Socket.IO)</h3>
                    <div id="uml-container">
                        <!-- Panel lateral -->
                        <div id="blocks">
                            <h4>Bloques UML</h4>
                            <div class="block-item" draggable="true" data-type="class">Clase</div>
                            <div class="block-item" draggable="true" data-type="abstract">Clase Abstracta</div>
                            <div class="block-item" draggable="true" data-type="interface">Interface</div>
                        </div>
                        <!-- Lienzo -->
                        <div id="graphContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón exportar -->
    <button id="exportBtn">Exportar a JSON</button>

    <!-- Modal para mostrar JSON -->
    <div id="jsonModal" class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-2/3 max-h-[80vh] overflow-y-auto p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">Código JSON Exportado</h2>
        <pre id="jsonOutput" class="bg-gray-100 dark:bg-gray-900 text-sm p-4 rounded-md overflow-x-auto whitespace-pre-wrap"></pre>

        <div class="flex justify-end mt-4 gap-3">
          <button id="downloadBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Descargar</button>
          <button id="closeModal" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Cerrar</button>
        </div>
      </div>
    </div>

    <!-- Cargar mxClient.js -->
    <script type="text/javascript">
        mxBasePath = 'https://jgraph.github.io/mxgraph/javascript/src';
    </script>
    <script src="https://jgraph.github.io/mxgraph/javascript/mxClient.js"></script>

    <!-- Socket.io -->
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("graphContainer");

        // Inicializar grafo
        const graph = new mxGraph(container);
        graph.setPanning(true);
        graph.setConnectable(true);
        graph.setMultigraph(false);
        graph.setHtmlLabels(true);

        // Estilos UML básicos
        const style = graph.getStylesheet().getDefaultVertexStyle();
        style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE;
        style[mxConstants.STYLE_FONTSIZE] = 12;
        style[mxConstants.STYLE_VERTICAL_ALIGN] = mxConstants.ALIGN_TOP;
        style[mxConstants.STYLE_SPACING_TOP] = 20;
        style[mxConstants.STYLE_SPACING] = 8;

        // Crear nodos UML
        function createUML(type, x, y) {
            let label = "";
            if (type === "class") {
                label = "<b>ClaseEjemplo</b><hr/>+ atributo1: String<hr/>+ metodo1()";
            } else if (type === "abstract") {
                label = "<i>ClaseAbstracta</i><hr/># atrAbs: Int<hr/>+ metodoAbs()";
            } else if (type === "interface") {
                label = "<u>IMiInterface</u><hr/>+ metodoInterface()";
            }

            const parent = graph.getDefaultParent();
            graph.getModel().beginUpdate();
            try {
                graph.insertVertex(parent, null, label, x, y, 160, 100,
                    "shape=rectangle;whiteSpace=wrap;html=1;");
            } finally {
                graph.getModel().endUpdate();
            }
        }

        // Drag & drop desde panel
        const blocks = document.querySelectorAll(".block-item");
        blocks.forEach(block => {
            block.addEventListener("dragstart", (e) => {
                e.dataTransfer.setData("type", block.dataset.type);
            });
        });

        container.addEventListener("dragover", (e) => e.preventDefault());
        container.addEventListener("drop", (e) => {
            e.preventDefault();
            const type = e.dataTransfer.getData("type");
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            createUML(type, x, y);

            // Emitir cambios
            emitGraph();
        });

        // Serializar a XML
        function getGraphXml() {
            const encoder = new mxCodec();
            const node = encoder.encode(graph.getModel());
            return mxUtils.getXml(node);
        }

        // Cargar XML
        function loadGraphXml(xml) {
            const doc = mxUtils.parseXml(xml);
            const decoder = new mxCodec(doc);
            decoder.decode(doc.documentElement, graph.getModel());
        }

        // Exportar a JSON y mostrar en modal
        function exportToJSON() {
            const xml = getGraphXml();

            // Convertir XML a JSON simple
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xml, "text/xml");

            function xmlToJson(xml) {
                const obj = {};
                if (xml.nodeType === 1) {
                    if (xml.attributes.length > 0) {
                        obj["@attributes"] = {};
                        for (let j = 0; j < xml.attributes.length; j++) {
                            const attribute = xml.attributes.item(j);
                            obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
                        }
                    }
                } else if (xml.nodeType === 3) {
                    if (xml.nodeValue.trim() !== "") {
                        return xml.nodeValue.trim();
                    }
                }
                if (xml.hasChildNodes()) {
                    for (let i = 0; i < xml.childNodes.length; i++) {
                        const item = xml.childNodes.item(i);
                        const nodeName = item.nodeName;
                        if (typeof (obj[nodeName]) === "undefined") {
                            obj[nodeName] = xmlToJson(item);
                        } else {
                            if (!Array.isArray(obj[nodeName])) {
                                obj[nodeName] = [obj[nodeName]];
                            }
                            obj[nodeName].push(xmlToJson(item));
                        }
                    }
                }
                return obj;
            }

            const json = xmlToJson(xmlDoc.documentElement);
            const jsonStr = JSON.stringify(json, null, 2);

            // Mostrar en ventana modal
            document.getElementById("jsonOutput").textContent = jsonStr;
            document.getElementById("jsonModal").classList.remove("hidden");

            // Preparar descarga
            document.getElementById("downloadBtn").onclick = () => {
                const blob = new Blob([jsonStr], { type: "application/json" });
                const url = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = "uml-diagram.json";
                a.click();
                URL.revokeObjectURL(url);
            };
        }

        // Eventos del modal
        document.getElementById("exportBtn").addEventListener("click", exportToJSON);
        document.getElementById("closeModal").addEventListener("click", () => {
            document.getElementById("jsonModal").classList.add("hidden");
        });

        // ---------------- SOCKET.IO ----------------
        const socket = io("http://localhost:3000", { transports: ["websocket"] });
        let isUpdating = false;

        function emitGraph() {
            if (!isUpdating) {
                socket.emit("graph-update", { data: getGraphXml() });
            }
        }

        // Emitir en cada cambio
        graph.getModel().addListener(mxEvent.CHANGE, () => {
            emitGraph();
        });

        // Recibir actualizaciones
        socket.on("graph-update", (data) => {
            isUpdating = true;
            loadGraphXml(data.data);
            isUpdating = false;
        });
    });
    </script>
</x-app-layout>
