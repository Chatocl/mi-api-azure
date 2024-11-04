const express = require('express');
const bodyParser = require('body-parser');
const sql = require('mssql');

// Configuración de la conexión a la base de datos SQL Server en Azure
const dbConfig = {
    user: 'virtualizacion',
    password: 'U5uw8UV@FSm9tr5',
    server: 'virtualizacion.database.windows.net',
    database: 'BaseVirtualizacion',
    options: {
        encrypt: true, // Necesario para conexiones a Azure
        enableArithAbort: true
    }
};

// Crear una instancia de Express
const app = express();
app.use(bodyParser.json());

// Conectar a la base de datos
sql.connect(dbConfig).catch(err => console.log("Error de conexión: " + err));

// Rutas de la API

// Insertar una nota
app.post('/insertar', async (req, res) => {
    const { nombre, apellido, carnet, curso, nota } = req.body;
    try {
        const result = await sql.query`INSERT INTO estudiantes (nombre, apellido, carnet, curso, nota) VALUES (${nombre}, ${apellido}, ${carnet}, ${curso}, ${nota})`;
        res.json({ success: true, message: "Nota insertada exitosamente" });
    } catch (err) {
        res.json({ success: false, message: "Error: " + err.message });
    }
});

// Eliminar una nota
app.post('/eliminar', async (req, res) => {
    const { id } = req.body;
    try {
        const result = await sql.query`DELETE FROM estudiantes WHERE id = ${id}`;
        res.json({ success: true, message: "Nota eliminada exitosamente" });
    } catch (err) {
        res.json({ success: false, message: "Error: " + err.message });
    }
});

// Editar una nota
app.post('/editar', async (req, res) => {
    const { id, nombre, apellido, carnet, curso, nota } = req.body;
    try {
        const result = await sql.query`UPDATE estudiantes SET nombre = ${nombre}, apellido = ${apellido}, carnet = ${carnet}, curso = ${curso}, nota = ${nota} WHERE id = ${id}`;
        res.json({ success: true, message: "Nota actualizada exitosamente" });
    } catch (err) {
        res.json({ success: false, message: "Error: " + err.message });
    }
});

// Obtener todos los estudiantes
app.get('/obtener', async (req, res) => {
    try {
        const result = await sql.query`SELECT * FROM estudiantes`;
        res.json(result.recordset);
    } catch (err) {
        res.json({ success: false, message: "Error: " + err.message });
    }
});

// Iniciar el servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en el puerto ${PORT}`);
});
