<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Workspace</title>
    <!-- Xterm.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/xterm@5.3.0/css/xterm.min.css" />
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #1e1e1e;
            color: white;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #333;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
        }
        .main-content {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #252526;
            border-right: 1px solid #444;
            padding: 10px;
            overflow-y: auto;
        }
        .editor-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        #editor {
            flex: 1;
            border-bottom: 1px solid #444;
        }
        .terminal-container {
            height: 300px;
            background-color: #000;
            padding: 10px;
            box-sizing: border-box;
        }
        iframe {
            flex: 1;
            border: none;
            background: white;
            display: none;
        }
        .controls {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 5px 15px;
            background-color: #0e639c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        button:hover {
            background-color: #1177bb;
        }
        .loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(30,30,30,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            font-size: 20px;
        }
    </style>
</head>
<body>

    <div id="loading" class="loading-overlay">
        Booting WebContainer Environment...
    </div>

    <header>
        <div>
            <strong>LMS Cloud Workspace</strong>
        </div>
        <div class="controls">
            <button id="run-btn">Run Node Server</button>
            <button id="preview-btn">Toggle Preview</button>
        </div>
    </header>

    <div class="main-content">
        <div class="sidebar">
            <h4>Files</h4>
            <ul id="file-list" style="list-style: none; padding: 0; margin: 0;">
                <li style="padding: 5px; cursor: pointer; color: #ccc;">index.js</li>
                <li style="padding: 5px; cursor: pointer; color: #ccc;">package.json</li>
            </ul>
        </div>
        <div class="editor-container">
            <div id="editor"></div>
            <iframe id="preview-frame" src=""></iframe>
            <div class="terminal-container" id="terminal"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/xterm@5.3.0/lib/xterm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xterm-addon-fit@0.8.0/lib/xterm-addon-fit.min.js"></script>
    <!-- Load Monaco Editor via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>

    <script type="module">
        import { WebContainer, auth } from 'https://esm.sh/@webcontainer/api';

        const apiKey = "{{ env('VITE_WEBCONTAINER_API_KEY') }}";
        
        // Initialize WebContainer Authentication
        auth.init({
            clientId: apiKey,
            scope: '',
        });

        // Initialize Monaco Editor
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
        
        let editor;
        let webcontainerInstance;

        const initialFiles = {
            'index.js': {
                file: {
                    contents: `import express from 'express';\nconst app = express();\nconst port = 3111;\n\napp.get('/', (req, res) => {\n  res.send('Welcome to a WebContainer in Laravel!');\n});\n\napp.listen(port, () => {\n  console.log(\`App is live at http://localhost:\${port}\`);\n});`,
                },
            },
            'package.json': {
                file: {
                    contents: `{\n  "name": "example-app",\n  "type": "module",\n  "dependencies": {\n    "express": "latest",\n    "nodemon": "latest"\n  },\n  "scripts": {\n    "start": "nodemon index.js"\n  }\n}`,
                },
            },
        };

        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: initialFiles['index.js'].file.contents,
                language: 'javascript',
                theme: 'vs-dark',
                automaticLayout: true
            });

            // Update file on change
            editor.onDidChangeModelContent(async () => {
                if (webcontainerInstance) {
                    await webcontainerInstance.fs.writeFile('/index.js', editor.getValue());
                }
            });
        });

        // Initialize Terminal
        const term = new Terminal({
            convertEol: true,
            theme: { background: '#000000' }
        });
        const fitAddon = new FitAddon.FitAddon();
        term.loadAddon(fitAddon);
        term.open(document.getElementById('terminal'));
        fitAddon.fit();

        // Boot WebContainer
        window.addEventListener('load', async () => {
            try {
                // You might not strictly need to pass the API key inside the Boot call 
                // if it's already verified, but this is how you initialize it.
                webcontainerInstance = await WebContainer.boot();
                
                // Mount files
                await webcontainerInstance.mount(initialFiles);
                
                document.getElementById('loading').style.display = 'none';
                term.writeln('\x1b[32mWebContainer Booted successfully!\x1b[0m');
                term.writeln('Running npm install...');

                // Install dependencies
                const installProcess = await webcontainerInstance.spawn('npm', ['install']);
                
                installProcess.output.pipeTo(new WritableStream({
                    write(data) {
                        term.write(data);
                    }
                }));

                await installProcess.exit;
                term.writeln('\x1b[32mDependencies installed. Click "Run Node Server" to start.\x1b[0m');

                // Listen for server-ready event to open preview
                webcontainerInstance.on('server-ready', (port, url) => {
                    term.writeln(`\x1b[32mServer is ready at ${url}\x1b[0m`);
                    const iframe = document.getElementById('preview-frame');
                    iframe.src = url;
                });

            } catch (error) {
                document.getElementById('loading').innerHTML = `Failed to boot WebContainer.<br>${error.message}`;
                console.error(error);
            }
        });

        // Run Server Button
        document.getElementById('run-btn').addEventListener('click', async () => {
            if (!webcontainerInstance) return;
            const startProcess = await webcontainerInstance.spawn('npm', ['run', 'start']);
            
            startProcess.output.pipeTo(new WritableStream({
                write(data) {
                    term.write(data);
                }
            }));
        });

        // Toggle Preview Button
        document.getElementById('preview-btn').addEventListener('click', () => {
            const editorDiv = document.getElementById('editor');
            const iframe = document.getElementById('preview-frame');
            if (iframe.style.display === 'block') {
                iframe.style.display = 'none';
                editorDiv.style.display = 'block';
            } else {
                iframe.style.display = 'block';
                editorDiv.style.display = 'none';
            }
        });

    </script>
</body>
</html>
