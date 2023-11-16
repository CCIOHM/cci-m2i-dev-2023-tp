const express = require('express');
const multer = require('multer');
const fs = require('fs');

const app = express();
const port = process.env.APP_PORT || 3000;

// Configuration de Multer pour l'upload de fichiers
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, 'storage/');
    },
    filename: (req, file, cb) => {
        cb(null, file.originalname);
    }
});

const upload = multer({ storage: storage });

// Route pour servir la page web avec le formulaire d'upload et la liste des fichiers
app.get('/', (req, res) => {
    let filesList = fs.readdirSync('storage').map(filename => {
        return `<a href="storage/${filename}" target="_blank">${filename}</a>`;
    }).join('<br>');

    res.send(`
        <h1>Upload de Fichiers</h1>
        <form action="/upload" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <button type="submit">Upload</button>
        </form>
        <h2>Fichiers Uploadés</h2>
        ${filesList}
    `);
});

// Route pour gérer l'upload des fichiers
app.post('/upload', upload.single('file'), (req, res) => {
    res.redirect('/');
});

// Route pour servir les fichiers du dossier 'storage'
app.use('/storage', express.static('storage'));

app.listen(port, () => {
    console.log(`Server Start on http://localhost:${port}`);
});
