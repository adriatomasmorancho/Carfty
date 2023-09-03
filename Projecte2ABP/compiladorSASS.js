const sass = require('node-sass');
const fs = require('fs');
const chokidar = require('chokidar');
const inputPath = '/sass/main.scss'; 
const outputPath = '/resources/css/style.css'; 

const watcher = chokidar.watch('/sass'); 
watcher.on('change', (path) => { 
    console.log(`El archivo ${path} ha sido modificado`);
    sass.render({
        file: inputPath
    }, function (error, result) {
        if (error) {
            console.error(error);
        } else {
            fs.writeFile(outputPath, result.css, function (err) {
                if (err) {
                    console.error(err);
                } else {
                    console.log(`Archivo ${outputPath} creado exitosamente!`);
                }
            });
        }
    });
});